<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Models\Zone;
use App\Models\Vendor;
use App\Models\Restaurant;
use App\Models\PhoneVerification;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Models\BusinessSetting;
use App\Models\SubscriptionPackage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use App\CentralLogics\SMS_module;
use Illuminate\Support\Facades\DB;

class VendorLoginController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
            'password' => 'required|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $data = [
            'phone' => $request->phone,
            'password' => $request->password
        ];

        if (auth('vendor')->attempt($data)) {
            $token = $this->genarate_token($request['phone']);
            $vendor = Vendor::where(['phone' => $request['phone']])->first();
            $vendor->auth_token = $token;
            $vendor->save();
            return response()->json(['status' => true, 'phone' => $request['phone'], 'token' => $token], 200);
        } else {
            $errors = [];
            array_push($errors, ['code' => 'auth-001', 'message' => 'Unauthorized.']);
            return response()->json([
                'errors' => $errors
            ], 401);
        }
    }

    private function genarate_token($phone)
    {
        $token = Str::random(120);
        $is_available = Vendor::where('auth_token', $token)->where('phone', '!=', $phone)->count();
        if ($is_available) {
            $this->genarate_token($phone);
        }
        return $token;
    }

    public function register(Request $request)
    {
        $status = BusinessSetting::where('key', 'toggle_restaurant_registration')->first();
        if (!isset($status) || $status->value == '0') {
            return response()->json(['errors' => Helpers::error_formater('self-registration', translate('messages.restaurant_self_registration_disabled'))]);
        }

        $validator = Validator::make($request->all(), [
            // 'store_name' => 'required',
            // 'restaurant_name' => 'required',
            // 'restaurant_address' => 'required',
            // 'lat' => 'required|numeric|min:-90|max:90',
            // 'lng' => 'required|numeric|min:-180|max:180',
            // 'email' => 'required|email|unique:vendors',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|unique:vendors',
            // 'min_delivery_time' => 'required|regex:/^([0-9]{2})$/|min:2|max:2',
            // 'max_delivery_time' => 'required|regex:/^([0-9]{2})$/|min:2|max:2',
            'password' => 'required|min:6',
            // 'zone_id' => 'required',
            // 'logo' => 'required',
            // 'vat' => 'required',
        ]);

        // if($request->zone_id)
        // {
        //     $point = new Point($request->lat, $request->lng);
        //     $zone = Zone::contains('coordinates', $point)->where('id', $request->zone_id)->first();
        //     if(!$zone){
        //         $validator->getMessageBag()->add('latitude', translate('messages.coordinates_out_of_zone'));
        //     }
        // }

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $vendor = new Vendor();
        // $vendor->store_name = $request->store_name;
        // $vendor->l_name = $request->lName;
        // $vendor->email = $request->email;
        $vendor->phone = $request->phone;
        $vendor->password = bcrypt($request->password);
        $vendor->status = null;
        $vendor->save();

        // $restaurant = new Restaurant;
        // $restaurant->name = $request->restaurant_name;
        // $restaurant->phone = $request->phone;
        // $restaurant->email = $request->email;
        // $restaurant->logo = Helpers::upload('restaurant/', 'png', $request->file('logo'));
        // $restaurant->cover_photo = Helpers::upload('restaurant/cover/', 'png', $request->file('cover_photo'));
        // $restaurant->address = $request->restaurant_address;
        // $restaurant->latitude = $request->lat;
        // $restaurant->longitude = $request->lng;
        // $restaurant->vendor_id = $vendor->id;
        // $restaurant->zone_id = $request->zone_id;
        // $restaurant->tax = $request->vat;
        // $restaurant->delivery_time = $request->min_delivery_time .'-'. $request->max_delivery_time;
        // $restaurant->status = 0;
        // $restaurant->restaurant_model = 'none';
        // $restaurant->save();

        // try {
        //     if (config('mail.status')) {
        //         Mail::to($request['email'])->send(new \App\Mail\SelfRegistration('pending', $vendor->f_name . ' ' . $vendor->l_name));
        //     }
        // } catch (\Exception $ex) {
        //     info($ex);
        // }

        return response()->json([
            'status' => true,
            'message' => 'Registered Successfully'
        ], 200);
    }

    public function registerAndVerify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|min:11|max:14',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $user = PhoneVerification::where('phone', $request->phone)->first();

        if (!empty($user)) {
            return response()->json([
                "code" => 0,
                'message' => "Phone Number already exist"
            ], 200);
        } else {
            $otp = rand(1000, 9999);
            $response = SMS_module::send($request->phone, $otp);
            if ($response != 'success') {
                $errors = [];
                array_push($errors, ['code' => 'otp', 'message' => translate('messages.failed_to_send_sms')]);
                return response()->json([
                    'errors' => $errors
                ], 405);
            }

            $check = DB::insert("INSERT INTO phone_verifications(phone, token) values('" . $request->phone . "', $otp) ");

            if ($check) {
                return response()->json([
                    "code" => 1,
                    'message' => "Code Send to your mobile number"
                ], 200);
            } else {
                return response()->json([
                    "code" => 0,
                    'message' => "Error"
                ], 200);
            }
        }
    }

    public function verify_phone(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|min:11|max:14',
            'otp' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $otp = DB::select("SELECT * from phone_verifications where phone = '" . $request->phone . "' AND token = '" . $request->otp . "' ");
        // $user = PhoneVerification::where('phone', $request->phone)->get();
        // dd($user);


        if (!empty($otp)) {


            // $update = DB::update("UPDATE phone_verifications SET verify = '1' where id = '" . $user[0]->id . "' ");

            // if ($update) {
                // DB::delete("Delete from phone_verifications where phone = '" . $request->phone . "' AND token = '" . $request->token . "' ");
                return response()->json([
                    "code" => 1,
                    'message' => "Phone number verified successfully",
                    'status' => true
                ], 200);
            } 
            else {
                return response()->json([
                    "code" => 0,
                    'message' => "Phone number and OTP not matched",
                    'status' => false
                ], 400);
            }
        
    }

    public function package_view()
    {
        $packages = SubscriptionPackage::where('status', 1)->get();
        return response()->json(['packages' => $packages], 200);
    }

    public function business_plan(Request $request)
    {
        $restaurant = Restaurant::findOrFail($request->restaurant_id);

        if ($request->business_plan == 'subscription' && $request->package_id != null) {
            $restaurant_id = $restaurant->id;
            $package_id = $request->package_id;
            $payment_method = $request->payment_method ?? 'free_trial';
            $reference = $request->reference ?? null;
            $discount = $request->discount ?? 0;
            $restaurant = Restaurant::findOrFail($restaurant_id);
            $type = $request->type ?? 'new_join';
            if ($request->payment == 'free_trial') {
                Helpers::subscription_plan_chosen($restaurant_id, $package_id, $payment_method, $reference, $discount, $type);
            } elseif ($request->payment == 'paying_now') {
                // dd('paying_now');
                Helpers::subscription_plan_chosen($restaurant_id, $package_id, $payment_method, $reference, $discount, $type);
            }
            $data = [
                'restaurant_model' => 'subscription',
                'logo' => $restaurant->logo,
                'message' => translate('messages.application_placed_successfully')
            ];
            return response()->json($data, 200);
        } elseif ($request->business_plan == 'commission') {
            $restaurant->restaurant_model = 'commission';
            $restaurant->save();

            $data = [
                'restaurant_model' => 'commission',
                'logo' => $restaurant->logo,
                'message' => translate('messages.application_placed_successfully')
            ];
            return response()->json($data, 200);
        }
    }
}
