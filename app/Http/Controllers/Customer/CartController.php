<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\{Food, Order, OrderTransaction};
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Stripe\StripeClient;

class CartController extends Controller
{
    public function addToCart($id, Request $request)
    {
        // dd($request->all());
        $addMoreQuantity = '';
        if (!empty($request->addMoreQuantity)) {
            $addMoreQuantity = $request->addMoreQuantity;
        } else {
            $addMoreQuantity = "1";
        }

        $food = Food::find($id);
        if (!$food) {
            abort(404);
        }

        $cart = session()->get('cart');

        if (!$cart) {

            $cart = [
                $id => [
                    "name" => $food->name,
                    "restaurant_id" => $food->restaurant_id,
                    "quantity" => $addMoreQuantity,
                    "price" => $food->price,
                    "photo" => $food->image
                ]
            ];

            session()->put('cart', $cart);

            $htmlCart = view('user-views.layouts.app')->render();

            return response()->json(['msg' => 'Food added to cart successfully!', 'data' => $htmlCart , 'countlist' => count((array) session('cart'))]);

            //return redirect()->back()->with('success', 'Product added to cart successfully!');
        }

        if (isset($cart[$id])) {

            $cart[$id]['quantity']++;

            session()->put('cart', $cart);

            $htmlCart = view('user-views.layouts.app')->render();

            return response()->json(['msg' => 'Product added to cart successfully!', 'data' => $htmlCart , 'countlist' => count((array) session('cart'))]);

            //return redirect()->back()->with('success', 'Product added to cart successfully!');

        }

        $cart[$id] = [
            "name" => $food->name,
            "restaurant_id" => $food->restaurant_id,
            "quantity" => $addMoreQuantity,
            "price" => $food->price,
            "photo" => $food->image
        ];

        session()->put('cart', $cart);

        $htmlCart = view('user-views.layouts.app')->render();

        return response()->json(['msg' => 'Product added to cart successfully!', 'data' => $htmlCart , 'countlist' => count((array) session('cart'))]);
    }

    public function cart()
    {
        return view('user-views.products.cart');
    }

    public function update(Request $request)
    {
        if ($request->id and $request->quantity) {
            $cart = session()->get('cart');

            $cart[$request->id]["quantity"] = $request->quantity;

            session()->put('cart', $cart);

            $subTotal = $cart[$request->id]['quantity'] * $cart[$request->id]['price'];

            $total = $this->getCartTotal();

            $htmlCart = view('user-views.layouts.app')->render();

            return response()->json(['msg' => 'Cart updated successfully', 'data' => $htmlCart,  'total' => $total, 'subTotal' => $subTotal , 'countlist' => count((array) session('cart'))]);

            //session()->flash('success', 'Cart updated successfully');
        }
    }

    public function remove(Request $request)
    {
        if ($request->id) {

            $cart = session()->get('cart');

            if (isset($cart[$request->id])) {

                unset($cart[$request->id]);

                session()->put('cart', $cart);
            }

            $total = $this->getCartTotal();

            $htmlCart = view('user-views.layouts.app')->render();

            return response()->json(['msg' => 'food product removed successfully', 'data' => $htmlCart, 'total' => $total , 'countlist' => count((array) session('cart'))]);

            //session()->flash('success', 'Product removed successfully');
        }
    }


    /**
     * getCartTotal
     *
     *
     * @return float|int
     */
    private function getCartTotal()
    {
        $total = 0;

        $cart = session()->get('cart');

        foreach ($cart as $id => $details) {
            $total += $details['price'] * $details['quantity'];
        }

        return number_format($total, 2);
    }

    public function addOrder(Request $request)
    {
        // dd($request->all());
       try {


            DB::beginTransaction();
            // dd(Auth::guard('customer')->user()->id);

            $stripe = new StripeClient(env('STRIPE_SECRET'));
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            $geenerateStrpieToken = $stripe->tokens->create([
                'card' => [
                    'number' => $request->cardNumber,
                    'exp_month' => $request->month,
                    'exp_year' => $request->year,
                    'cvc' => $request->cardCVC,
                ],
            ]);

            $intent = \Stripe\Charge::create([
                'amount' => "5000",
                'currency' => 'usd',
                'description' => 'Wallet',
                'source' => $geenerateStrpieToken->id,
                'statement_descriptor' => 'Wallet',
            ]);
            // dd($intent->toArray());
            $order = new Order();
            $order->user_id = Auth::user()->id;
            $order->order_amount = $request->totalPrice;
            // $order->payment_status = "unpaid";
            $order->payment_status = "paid";
            $order->order_status = "pending";
            // if ($request->optradio == "on") {
            //     $order->payment_method = "Cash ON Delivery";
            // } else {
                $order->payment_method = $request->optradio;
            // }
            $order->order_type = "delivery";
            $food = Food::find($request->food_id[0]);
            $order->restaurant_id = $food->restaurant_id;
            $order->delivery_address = $request->apartment . " " . $request->street_address . " " . $request->city . " " . $request->zipCode . " " . $request->country;
            // dd($order->save());
            if ($order->save()) {
                foreach ($request->food_id as $index => $foodID) {
                    $food = Food::find($foodID);
                    // dd($food);
                    $orderDetail = new OrderDetail();
                    $orderDetail->food_id = $foodID;
                    $orderDetail->order_id = $order->id;
                    $orderDetail->price = $food->price;
                    $orderDetail->restaurant_id = $food->restaurant_id;
                    $orderDetail->quantity = $request->quantity[$index];
                    $orderDetail->tax_amount = "0";
                    $orderDetail->save();
                    // dd($orderDetail);
                }

                $orderTransactions = new OrderTransaction();
                $orderTransactions->order_id = $order->id;
                $orderTransactions->status = "paid";
                $orderTransactions->order_amount = $request->totalPrice;
                $orderTransactions->transaction = $intent->id;
                $orderTransactions->receipt_url = $intent->receipt_url;
                $orderTransactions->save();
            }
            $request->session()->forget('cart');
            return redirect()->route('thank.order')->with('success', 'Order Complete successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        } finally {
            DB::commit();
        }
    }

    public function ThankOrder()
    {
        return view('user-views.products.thank');
    }
}
