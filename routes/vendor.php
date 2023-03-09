<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Vendor\ProductController;
// Route::post('sortProducts', 'ProductController@sort');

Route::delete('category/{id}', 'CategoryController@deleteCategory')->name('category.destroy');
Route::group(['namespace' => 'Vendor', 'as' => 'vendor.'], function () {
    /*authentication*/
    Route::group(['namespace' => 'Auth', 'prefix' => 'auth', 'as' => 'auth.'], function () {
        Route::get('password', 'LoginController@password')->name('password');
        Route::get('signup', 'LoginController@signup')->name('signup');
        Route::post('signup', 'LoginController@registerAndVerify')->name('registerAndVerify');
        Route::post('verify-phone', 'LoginController@verify_phone')->name('verify_phone');
        Route::get('verify', 'LoginController@verify')->name('verify');
        Route::post('register-vendor', 'LoginController@registerVendor')->name('registerVendor');
        Route::get('login', 'LoginController@login')->name('login');
        Route::post('login', 'LoginController@submit');
        Route::get('logout', 'LoginController@logout')->name('logout');
        Route::post('contact-support', 'LoginController@contact_support')->name('contact_support');
        Route::group(['prefix' => 'employee', 'as' => 'employee.'], function () {
            Route::post('login', 'EmployeeLoginController@submit')->name('login');
            Route::get('logout', 'EmployeeLoginController@logout')->name('logout');
        });
    });
    /*authentication*/

    Route::group(['middleware' => ['vendor']], function () {
        Route::get('/', 'DashboardController@dashboard')->name('dashboard');
        Route::get('/get-restaurant-data', 'DashboardController@restaurant_data')->name('get-restaurant-data');
        Route::post('/sortProducts', 'ProductController@sort');
        

        Route::get('/reviews', 'ReviewController@index')->name('reviews')->middleware('module:reviews');
        

        Route::group(['prefix' => 'pos', 'as' => 'pos.'], function () {
            Route::post('variant_price', 'POSController@variant_price')->name('variant_price');
            Route::group(['middleware' => ['module:pos']], function () {
                Route::get('/', 'POSController@index')->name('index');
                Route::get('quick-view', 'POSController@quick_view')->name('quick-view');
                Route::get('quick-view-cart-item', 'POSController@quick_view_card_item')->name('quick-view-cart-item');
                Route::post('add-to-cart', 'POSController@addToCart')->name('add-to-cart');
                Route::post('remove-from-cart', 'POSController@removeFromCart')->name('remove-from-cart');
                Route::post('cart-items', 'POSController@cart_items')->name('cart_items');
                Route::post('update-quantity', 'POSController@updateQuantity')->name('updateQuantity');
                Route::post('empty-cart', 'POSController@emptyCart')->name('emptyCart');
                Route::post('tax', 'POSController@update_tax')->name('tax');
                Route::post('discount', 'POSController@update_discount')->name('discount');
                Route::get('customers', 'POSController@get_customers')->name('customers');
                Route::post('order', 'POSController@place_order')->name('order');
                Route::get('orders', 'POSController@order_list')->name('orders');
                Route::post('search', 'POSController@search')->name('search');
                Route::get('order-details/{id}', 'POSController@order_details')->name('order-details');
                Route::get('invoice/{id}', 'POSController@generate_invoice');
            });
        });

        Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.'], function () {
            Route::post('order-stats', 'DashboardController@order_stats')->name('order-stats');
        });

        Route::group(['prefix' => 'category', 'as' => 'category.', 'middleware' => ['module:food']], function () {
            Route::get('get-all', 'CategoryController@get_all')->name('get-all');
            Route::get('list', 'CategoryController@index')->name('add');
            Route::get('sub-category-list', 'CategoryController@sub_index')->name('add-sub-category');
            Route::post('search', 'CategoryController@search')->name('search');
            
            
        });

        Route::group(['prefix' => 'custom-role', 'as' => 'custom-role.', 'middleware' => ['module:custom_role']], function () {
            Route::get('create', 'CustomRoleController@create')->name('create');
            Route::post('create', 'CustomRoleController@store')->name('store');
            Route::get('edit/{id}', 'CustomRoleController@edit')->name('edit');
            Route::post('update/{id}', 'CustomRoleController@update')->name('update');
            Route::delete('delete/{id}', 'CustomRoleController@distroy')->name('delete');
            Route::post('search', 'CustomRoleController@search')->name('search');
        });

        Route::group(['prefix' => 'delivery-man', 'as' => 'delivery-man.', 'middleware' => ['module:deliveryman']], function () {
            Route::get('add', 'DeliveryManController@index')->name('add');
            Route::post('store', 'DeliveryManController@store')->name('store');
            Route::get('list', 'DeliveryManController@list')->name('list');
            Route::get('preview/{id}/{tab?}', 'DeliveryManController@preview')->name('preview');
            Route::get('status/{id}/{status}', 'DeliveryManController@status')->name('status');
            Route::get('earning/{id}/{status}', 'DeliveryManController@earning')->name('earning');
            Route::get('edit/{id}', 'DeliveryManController@edit')->name('edit');
            Route::post('update/{id}', 'DeliveryManController@update')->name('update');
            Route::delete('delete/{id}', 'DeliveryManController@delete')->name('delete');
            Route::post('search', 'DeliveryManController@search')->name('search');
            Route::get('get-deliverymen', 'DeliveryManController@get_deliverymen')->name('get-deliverymen');

            Route::group(['prefix' => 'reviews', 'as' => 'reviews.'], function () {
                Route::get('list', 'DeliveryManController@reviews_list')->name('list');
            });
        });

        Route::group(['prefix' => 'employee', 'as' => 'employee.', 'middleware' => ['module:employee']], function () {
            Route::get('add-new', 'EmployeeController@add_new')->name('add-new');
            Route::post('add-new', 'EmployeeController@store');
            Route::get('list', 'EmployeeController@list')->name('list');
            Route::get('edit/{id}', 'EmployeeController@edit')->name('edit');
            Route::post('update/{id}', 'EmployeeController@update')->name('update');
            Route::delete('delete/{id}', 'EmployeeController@distroy')->name('delete');
            Route::post('search', 'EmployeeController@search')->name('search');
        });

        Route::group(['prefix' => 'food', 'as' => 'food.', 'middleware' => ['module:food']], function () {
            Route::get('add-new', 'FoodController@index')->name('add-new');
            Route::post('variant-combination', 'FoodController@variant_combination')->name('variant-combination');
            Route::post('store', 'FoodController@store')->name('store');
            Route::get('edit/{id}', 'FoodController@edit')->name('edit');
            Route::post('update/{id}', 'FoodController@update')->name('update');
            Route::get('list', 'FoodController@list')->name('list');
            Route::delete('delete/{id}', 'FoodController@delete')->name('delete');
            Route::get('status/{id}/{status}', 'FoodController@status')->name('status');
            Route::post('search', 'FoodController@search')->name('search');
            Route::get('view/{id}', 'FoodController@view')->name('view');
            Route::get('get-categories', 'FoodController@get_categories')->name('get-categories');
            
            //Import and export
            Route::get('bulk-import', 'FoodController@bulk_import_index')->name('bulk-import');
            Route::post('bulk-import', 'FoodController@bulk_import_data');
            Route::get('bulk-export', 'FoodController@bulk_export_index')->name('bulk-export-index');
            Route::post('bulk-export', 'FoodController@bulk_export_data')->name('bulk-export');
        });

        Route::group(['prefix' => 'banner', 'as' => 'banner.', 'middleware' => ['module:banner']], function () {
            Route::get('list', 'BannerController@list')->name('list');
            Route::get('join_campaign/{id}/{status}', 'BannerController@status')->name('status');
        });

        Route::group(['prefix' => 'campaign', 'as' => 'campaign.', 'middleware' => ['module:campaign']], function () {
            Route::get('list', 'CampaignController@list')->name('list');
            Route::get('remove-restaurant/{campaign}/{restaurant}', 'CampaignController@remove_restaurant')->name('remove-restaurant');
            Route::get('add-restaurant/{campaign}/{restaurant}', 'CampaignController@addrestaurant')->name('addrestaurant');
            Route::post('search', 'CampaignController@search')->name('search');
        });

        Route::group(['prefix' => 'wallet', 'as' => 'wallet.', 'middleware' => ['module:wallet']], function () {
            Route::get('/', 'WalletController@index')->name('index');
            Route::post('request', 'WalletController@w_request')->name('withdraw-request');
            Route::delete('close/{id}', 'WalletController@close_request')->name('close-request');
        });


        // Route::group(['prefix' => 'coupon', 'as' => 'coupon.', 'middleware' => ['module:coupon']], function () {
        //     Route::get('add-new', 'CouponController@add_new')->name('add-new');
        //     Route::post('store', 'CouponController@store')->name('store');
        //     Route::get('update/{id}', 'CouponController@edit')->name('update');
        //     Route::post('update/{id}', 'CouponController@update');
        //     Route::get('status/{id}/{status}', 'CouponController@status')->name('status');
        //     Route::delete('delete/{id}', 'CouponController@delete')->name('delete');
        // });

        Route::group(['prefix' => 'addon', 'as' => 'addon.', 'middleware' => ['module:addon']], function () {
            Route::get('add-new', 'AddOnController@index')->name('add-new');
            Route::get('profile', 'AddOnController@profile')->name('profile');
            Route::get('products', 'ProductController@index')->name('products');
            
            Route::get('edit-product/{id}', 'ProductController@edit')->name('product.edit');
            Route::post('update-product/{id}', 'ProductController@update_product')->name('product.update');

            Route::get('upload-images', 'AddOnController@upload_images')->name('upload-images');
            
            Route::get('categories', 'AddOnController@categories')->name('categories');
            Route::get('addcategory', 'AddOnController@addcategory')->name('addcategory');
            Route::post('create_category', 'CategoryController@store')->name('create_category');
            Route::get('categories/edit', 'CategoryController@edit')->name('categories.edit');
            Route::post('categories/update', 'CategoryController@update')->name('categories.update');
            
            
            Route::get('addproduct/{type}', 'AddOnController@addproduct')->name('addproduct');
            Route::get('addproduct2', 'AddOnController@addproduct2')->name('addproduct2');
            Route::get('pizza-products', 'AddOnController@pizza_products')->name('pizza-products');
            Route::get('customer', 'AddOnController@customer')->name('customer');
            Route::get('order', 'OrderController@index')->name('order');
            Route::get('invoices', 'AddOnController@invoices')->name('invoices');
            Route::get('massage', 'AddOnController@massage')->name('massage');
            Route::get('review', 'AddOnController@review')->name('review');
            Route::get('coupon', 'AddOnController@coupon')->name('coupon');
            Route::get('product-detail/{id}', 'ProductController@show')->name('product-detail');
            Route::get('product-pre-order', 'AddOnController@product_pre_order')->name('product-pre-order');
            Route::get('product-subscription', 'AddOnController@product_subscription')->name('product-subscription');
            Route::get('single-customer', 'AddOnController@single_customer')->name('single-customer');
            Route::post('store', 'AddOnController@store')->name('store');
            Route::get('edit/{id}', 'AddOnController@edit')->name('edit');
            Route::post('update/{id}', 'AddOnController@update')->name('update');
            Route::delete('delete/{id}', 'AddOnController@delete')->name('delete');
            Route::post('create_product', 'ProductController@create_product')->name('create_product');
        });

        Route::get('get_all_products', [ProductController::class, "get_all_products"]);
        Route::get('edit_product/{product_id}', [ProductController::class, "get_product_for_edit"]);
        Route::post('update_product/{product_id}', [ProductController::class, "update_product"]);
        
        // Settings Routes
        Route::get('settings', 'SettingsController@index')->name('settings');
        Route::post('update-profile', 'SettingsController@UpdateProfile')->name('profile');
        Route::post('update-store', 'SettingsController@UpdateStore')->name('update.store');
        Route::post('create-store', 'SettingsController@CreateStore')->name('create.store');
        Route::post('kitchen-store-gallery', 'SettingsController@CreateStoreGallery')->name('kitchen.store.gallery');
        Route::post('change-password', 'SettingsController@ChangePassword')->name('change_password');
        Route::post('kitchen-store-gallery', 'SettingsController@CreateStoreGallery')->name('kitchen.store.gallery');


        // Settings Routes Ends

        Route::group(['prefix' => 'order', 'as' => 'order.' , 'middleware' => ['module:order']], function () {
            Route::get('list/{status}', 'OrderController@list')->name('list');
            Route::put('status-update/{id}', 'OrderController@status')->name('status-update');
            Route::post('search', 'OrderController@search')->name('search');
            Route::post('add-to-cart', 'OrderController@add_to_cart')->name('add-to-cart');
            Route::post('remove-from-cart', 'OrderController@remove_from_cart')->name('remove-from-cart');
            Route::get('update/{order}', 'OrderController@update')->name('update');
            Route::get('edit-order/{order}', 'OrderController@edit')->name('edit');
            Route::get('details/{id}', 'OrderController@orderdetails')->name('details');
            Route::get('detailsAjax/{id}', 'OrderController@detailsAjax')->name('detailsAjax');
            Route::get('status', 'OrderController@status')->name('status');
            Route::get('quick-view', 'OrderController@quick_view')->name('quick-view');
            Route::get('quick-view-cart-item', 'OrderController@quick_view_cart_item')->name('quick-view-cart-item');
            Route::get('generate-invoice/{id}', 'OrderController@generate_invoice')->name('generate-invoice');
            Route::post('add-payment-ref-code/{id}', 'OrderController@add_payment_ref_code')->name('add-payment-ref-code');
        });
        
        Route::group(['prefix' => 'badge', 'as' => 'badge.', 'middleware' => ['module:badge']], function () {
            Route::get('add-new', 'BadgeController@index')->name('add-new');
            Route::post('store', 'BadgeController@store')->name('store');
            Route::get('edit/{id}', 'BadgeController@edit')->name('edit');
            Route::post('update/{id}', 'BadgeController@update')->name('update');
            Route::delete('delete/{id}', 'BadgeController@delete')->name('delete');
        });

        Route::group(['prefix' => 'business-settings', 'as' => 'business-settings.', 'middleware' => ['module:restaurant_setup']], function () {
            Route::get('restaurant-setup', 'BusinessSettingsController@restaurant_index')->name('restaurant-setup');
            Route::post('add-schedule', 'BusinessSettingsController@add_schedule')->name('add-schedule');
            Route::get('remove-schedule/{restaurant_schedule}', 'BusinessSettingsController@remove_schedule')->name('remove-schedule');
            Route::get('update-active-status', 'BusinessSettingsController@active_status')->name('update-active-status');
            Route::post('update-setup/{restaurant}', 'BusinessSettingsController@restaurant_setup')->name('update-setup');
            Route::get('toggle-settings-status/{restaurant}/{status}/{menu}', 'BusinessSettingsController@restaurant_status')->name('toggle-settings');
        });

        Route::group(['prefix' => 'profile', 'as' => 'profile.', 'middleware' => ['module:bank_info']], function () {
            Route::get('view', 'ProfileController@view')->name('view');
            // Route::get('update', 'ProfileController@edit')->name('update');
            Route::post('update', 'ProfileController@update')->name('update');
            Route::post('settings-password', 'ProfileController@settings_password_update')->name('settings-password');
            Route::get('bank-view', 'ProfileController@bank_view')->name('bankView');
            Route::get('bank-edit', 'ProfileController@bank_edit')->name('bankInfo');
            Route::post('bank-update', 'ProfileController@bank_update')->name('bank_update');
        });

        Route::group(['prefix' => 'restaurant', 'as' => 'shop.', 'middleware' => ['module:my_shop']], function () {
            Route::get('view', 'RestaurantController@view')->name('view');
            Route::get('edit', 'RestaurantController@edit')->name('edit');
            Route::post('update', 'RestaurantController@update')->name('update');
        });

    });
});