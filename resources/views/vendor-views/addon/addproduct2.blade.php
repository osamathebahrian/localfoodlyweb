@extends('layouts.vendor.app')

@section('title', __('Add Product'))

@push('css_or_js')
    <style>
        .selectMultiple {
            width: 400px;
            position: relative;
        }

        .selectMultiple select {
            display: none;
        }

        .selectMultiple>div {
            position: relative;
            z-index: 2;
            padding: 8px 12px 2px 12px;
            border-radius: 8px;
            background: #fff;
            font-size: 14px;
            min-height: 44px;
            box-shadow: 0 4px 16px 0 rgba(22, 42, 90, 0.12);
            transition: box-shadow 0.3s ease;
        }

        .selectMultiple>div:hover {
            box-shadow: 0 4px 24px -1px rgba(22, 42, 90, 0.16);
        }

        .selectMultiple>div .arrow {
            right: 1px;
            top: 0;
            bottom: 0;
            cursor: pointer;
            width: 28px;
            position: absolute;
        }

        .selectMultiple>div .arrow:before,
        .selectMultiple>div .arrow:after {
            content: "";
            position: absolute;
            display: block;
            width: 2px;
            height: 8px;
            border-bottom: 8px solid #99A3BA;
            top: 43%;
            transition: all 0.3s ease;
        }

        .selectMultiple>div .arrow:before {
            right: 12px;
            transform: rotate(-130deg);
        }

        .selectMultiple>div .arrow:after {
            left: 9px;
            transform: rotate(130deg);
        }

        .selectMultiple>div span {
            color: #99A3BA;
            display: block;
            position: absolute;
            left: 12px;
            cursor: pointer;
            top: 8px;
            line-height: 28px;
            transition: all 0.3s ease;
        }

        .selectMultiple>div span.hide {
            opacity: 0;
            visibility: hidden;
            transform: translate(-4px, 0);
        }

        .selectMultiple>div a {
            position: relative;
            padding: 0 24px 6px 8px;
            line-height: 28px;
            color: #1E2330;
            display: inline-block;
            vertical-align: top;
            margin: 0 6px 0 0;
        }

        .selectMultiple>div a em {
            font-style: normal;
            display: block;
            white-space: nowrap;
        }

        .selectMultiple>div a:before {
            content: "";
            left: 0;
            top: 0;
            bottom: 6px;
            width: 100%;
            position: absolute;
            display: block;
            background: rgba(228, 236, 250, 0.7);
            z-index: -1;
            border-radius: 4px;
        }

        .selectMultiple>div a i {
            cursor: pointer;
            position: absolute;
            top: 0;
            right: 0;
            width: 24px;
            height: 28px;
            display: block;
        }

        .selectMultiple>div a i:before,
        .selectMultiple>div a i:after {
            content: "";
            display: block;
            width: 2px;
            height: 10px;
            position: absolute;
            left: 50%;
            top: 50%;
            background: #006aff;
            border-radius: 1px;
        }

        .selectMultiple>div a i:before {
            transform: translate(-50%, -50%) rotate(45deg);
        }

        .selectMultiple>div a i:after {
            transform: translate(-50%, -50%) rotate(-45deg);
        }

        .selectMultiple>div a.notShown {
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .selectMultiple>div a.notShown:before {
            width: 28px;
            transition: width 0.45s cubic-bezier(0.87, -0.41, 0.19, 1.44) 0.2s;
        }

        .selectMultiple>div a.notShown i {
            opacity: 0;
            transition: all 0.3s ease 0.3s;
        }

        .selectMultiple>div a.notShown em {
            opacity: 0;
            transform: translate(-6px, 0);
            transition: all 0.4s ease 0.3s;
        }

        .selectMultiple>div a.notShown.shown {
            opacity: 1;
        }

        .selectMultiple>div a.notShown.shown:before {
            width: 100%;
        }

        .selectMultiple>div a.notShown.shown i {
            opacity: 1;
        }

        .selectMultiple>div a.notShown.shown em {
            opacity: 1;
            transform: translate(0, 0);
        }

        .selectMultiple>div a.remove:before {
            width: 28px;
            transition: width 0.4s cubic-bezier(0.87, -0.41, 0.19, 1.44) 0s;
        }

        .selectMultiple>div a.remove i {
            opacity: 0;
            transition: all 0.3s ease 0s;
        }

        .selectMultiple>div a.remove em {
            opacity: 0;
            transform: translate(-12px, 0);
            transition: all 0.4s ease 0s;
        }

        .selectMultiple>div a.remove.disappear {
            opacity: 0;
            transition: opacity 0.5s ease 0s;
        }

        .selectMultiple>ul {
            margin: 0;
            padding: 0;
            list-style: none;
            font-size: 16px;
            z-index: 99;
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            visibility: hidden;
            opacity: 0;
            border-radius: 8px;
            transform: translate(0, 20px) scale(0.8);
            transform-origin: 0 0;
            filter: drop-shadow(0 12px 20px rgba(22, 42, 90, 0.08));
            transition: all 0.4s ease, transform 0.4s cubic-bezier(0.87, -0.41, 0.19, 1.44), filter 0.3s ease 0.2s;
        }

        .selectMultiple>ul li {
            color: #1E2330;
            background: #fff;
            padding: 12px 16px;
            cursor: pointer;
            overflow: hidden;
            position: relative;
            transition: background 0.3s ease, color 0.3s ease, transform 0.3s ease 0.3s, opacity 0.5s ease 0.3s, border-radius 0.3s ease 0.3s;
        }

        .selectMultiple>ul li:first-child {
            border-radius: 8px 8px 0 0;
        }

        .selectMultiple>ul li:first-child:last-child {
            border-radius: 8px;
        }

        .selectMultiple>ul li:last-child {
            border-radius: 0 0 8px 8px;
        }

        .selectMultiple>ul li:last-child:first-child {
            border-radius: 8px;
        }

        .selectMultiple>ul li:hover {
            background: #006aff;
            color: #fff;
        }

        .selectMultiple>ul li:after {
            content: "";
            position: absolute;
            top: 50%;
            left: 50%;
            width: 6px;
            height: 6px;
            background: rgba(0, 0, 0, 0.4);
            opacity: 0;
            border-radius: 100%;
            transform: scale(1, 1) translate(-50%, -50%);
            transform-origin: 50% 50%;
        }

        .selectMultiple>ul li.beforeRemove {
            border-radius: 0 0 8px 8px;
        }

        .selectMultiple>ul li.beforeRemove:first-child {
            border-radius: 8px;
        }

        .selectMultiple>ul li.afterRemove {
            border-radius: 8px 8px 0 0;
        }

        .selectMultiple>ul li.afterRemove:last-child {
            border-radius: 8px;
        }

        .selectMultiple>ul li.remove {
            transform: scale(0);
            opacity: 0;
        }

        .selectMultiple>ul li.remove:after {
            -webkit-animation: ripple 0.4s ease-out;
            animation: ripple 0.4s ease-out;
        }

        .selectMultiple>ul li.notShown {
            display: none;
            transform: scale(0);
            opacity: 0;
            transition: transform 0.35s ease, opacity 0.4s ease;
        }

        .selectMultiple>ul li.notShown.show {
            transform: scale(1);
            opacity: 1;
        }

        .selectMultiple.open>div {
            box-shadow: 0 4px 20px -1px rgba(22, 42, 90, 0.12);
        }

        .selectMultiple.open>div .arrow:before {
            transform: rotate(-50deg);
        }

        .selectMultiple.open>div .arrow:after {
            transform: rotate(50deg);
        }

        .selectMultiple.open>ul {
            transform: translate(0, 12px) scale(1);
            opacity: 1;
            visibility: visible;
            filter: drop-shadow(0 16px 24px rgba(22, 42, 90, 0.16));
        }

        @-webkit-keyframes ripple {
            0% {
                transform: scale(0, 0);
                opacity: 1;
            }

            25% {
                transform: scale(30, 30);
                opacity: 1;
            }

            100% {
                opacity: 0;
                transform: scale(50, 50);
            }
        }

        @keyframes ripple {
            0% {
                transform: scale(0, 0);
                opacity: 1;
            }

            25% {
                transform: scale(30, 30);
                opacity: 1;
            }

            100% {
                opacity: 0;
                transform: scale(50, 50);
            }
        }
    </style>
@endpush

<body class="">
    @section('content')
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col-sm">
                        <h1 class="page-header-title">
                            Add Product (Pre-Order)
                        </h1>
                    </div>
                </div>
            </div>
            <!-- End Page Header -->

            <div class="product-form">

                <div class="row">
                    <div class="col-lg-7">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-head">
                                    <a href="#"><i class="icon-arrow-left"></i></a>
                                    <div>
                                        <a href="#"><i class="icon-trash"></i>Delete</a>
                                        <a href="#"><i class="icon-document"></i>Duplicate</a>
                                    </div>
                                </div>
                                <form action="{{ route('vendor.addon.create_product') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <!-- Form Group -->
                                    <div class=" form-group">
                                        <label class="input-label" for="">Product Name *</label>
                                        <input type="text" class="form-control form-control-lg" name="name"
                                            placeholder="Name">
                                    </div>
                                    <div class=" form-group" hidden>
                                        <label class="input-label" for="">Product Type *</label>
                                        <input type="text" class="form-control form-control-lg" name="product_type"
                                            placeholder="" value="preorder">

                                        {{-- <select name="product_type" id="label" class="custom-select custom-select-lg">
                                        <option value="">Please select a product type</option>
                                        <option value="Normal">Normal</option>
                                        <option value="Meal Kits">Meal Kits</option>
                                    </select> --}}
                                    </div>
                                    <div class=" form-group">
                                        <label class="input-label" for="">Description *</label>
                                        <textarea name="description" id="description" cols="" rows="4" class="form-control form-control-lg"></textarea>
                                    </div>

                                    <div class=" form-group">
                                        <label class="input-label" for="">Category</label>
                                        <select name="category_id" id="category_id" class="custom-select custom-select-lg">
                                            <option value="">Please select category</option>
                                            @foreach ($category as $value)
                                                <option value="{{ $value->id }}">{{ $value->name }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    {{-- PreOrders --}}
                                    <h2 class="mb-4">Fulfillment Date</h2>
                                    <div class="form-row">
                                        <div class="col-lg-6">
                                            <div class=" form-group">
                                                <label class="input-label" for="">Date</label>
                                                <input type="date" class="form-control form-control-lg"
                                                    name="fulfillment_date">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class=" form-group">
                                                <label class="input-label" for="">Time</label>
                                                <input type="time" class="form-control form-control-lg"
                                                    name="fulfillment_time">
                                            </div>
                                        </div>
                                    </div>
                                    <h2 class="mb-4">Pre-Order End Date</h2>
                                    <div class="form-row">
                                        <div class="col-lg-6">
                                            <div class=" form-group">
                                                <label class="input-label" for="">Date</label>
                                                <input type="date" class="form-control form-control-lg"
                                                    name="pre_order_end_date">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class=" form-group">
                                                <label class="input-label" for="">Time</label>
                                                <input type="time" class="form-control form-control-lg"
                                                    name="pre_order_end_time">
                                            </div>
                                        </div>
                                    </div>
                                    <h2 class="mb-4">Fulfillment Type</h2>
                                    <div class=" form-group">
                                        <select name="fulfillment_type" id=""
                                            class="custom-select custom-select-lg">
                                            <option value="">Please select a delivery option</option>
                                            <option value="delivery">Delivery</option>
                                            <option value="pickup">Pickup</option>
                                        </select>
                                    </div>
                                    <div class=" form-group">
                                        <label class="input-label" for="">Pre-Order Quantity Limit</label>
                                        <input type="number" class="form-control form-control-lg"
                                            name="pre_order_quantity_limit" placeholder="10 Purchases">
                                    </div>
                                    {{-- PreOrders --}}

                                    <div class=" form-group">
                                        <label class="input-label" for="">Add Ingredients</label>
                                        <div class="search-form">
                                            <div class="input-group input-group-merge input-group-flush">
                                                <div class="input-group-prepend">
                                                    <button type="submit" class="">
                                                        <div class="input-group-text">
                                                            <i class="icon-Path-11"></i>
                                                        </div>
                                                    </button>
                                                </div>
                                                <input id="ingredients" type="text" name="ingredient"
                                                    class="form-control form-control-lg" placeholder="Enter Ingredients"
                                                    aria-label="Search by ingredients">

                                            </div>
                                        </div>
                                        <div class="tags ingredients">

                                        </div>
                                    </div>
                                    {{-- <div class=" form-group">
                                    <label class="input-label" for="">Add Ingredients</label>
                                    <div class="search-form">
                                        <!-- Search -->
                                        <div class="input-group input-group-merge input-group-flush">
                                            <div class="input-group-prepend">
                                                <button type="submit" class="">
                                                    <div class="input-group-text">
                                                        <i class="icon-Path-11"></i>
                                                    </div>
                                                </button>
                                            </div>
                                            <input id="" type="search" name="ingredients" class="form-control form-control-lg" placeholder="Search here" aria-label="Search by related things">

                                        </div>
                                        <!-- End Search -->
                                    </div>
                                    <div class="tags">
                                        <div class="alert alert-primary fade show" role="alert">
                                            One Tag
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="alert alert-primary fade show" role="alert">
                                            One Tag
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="alert alert-primary fade show" role="alert">
                                            One Tag
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    </div>
                                </div> --}}

                                    <div class=" form-group">
                                        <label class="input-label" for="">Add Allergens</label>
                                        <div class="search-form">
                                            <div class="input-group input-group-merge input-group-flush">
                                                <div class="input-group-prepend">
                                                    <button type="submit" class="">
                                                        <div class="input-group-text">
                                                            <i class="icon-Path-11"></i>
                                                        </div>
                                                    </button>
                                                </div>
                                                <input id="allergens" type="text" name="allergen"
                                                    class="form-control form-control-lg" placeholder="Enter Allergens"
                                                    aria-label="Search by allergens">

                                            </div>
                                        </div>
                                        <div class="tags allergens">

                                        </div>
                                    </div>
                                    {{-- <div class=" form-group">
                                    <label class="input-label" for="">Add Allergens</label>
                                    <div class="search-form">
                                        <!-- Search -->
                                        <div class="input-group input-group-merge input-group-flush">
                                            <div class="input-group-prepend">
                                                <button type="submit" class="">
                                                    <div class="input-group-text">
                                                        <i class="icon-Path-11"></i>
                                                    </div>
                                                </button>
                                            </div>
                                            <input id="" type="search" name="allergens" class="form-control form-control-lg" placeholder="Search here" aria-label="Search by related things">

                                        </div>
                                        <!-- End Search -->
                                    </div>
                                    <div class="tags">
                                        <div class="alert alert-primary fade show" role="alert">
                                            One Tag
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="alert alert-primary fade show" role="alert">
                                            One Tag
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="alert alert-primary fade show" role="alert">
                                            One Tag
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    </div>
                                </div> --}}

                                     <div class=" form-group">
                                        <label class="input-label" for="addons">Unit Serve</label>
                                        <select name="unit_serve" class="custom-select custom-select-lg" data-placeholder="Select Unit Serve">
                                            <option value="KG">KG</option>
                                            <option value="DAZAN">DAZAN</option>
                                            <option value="LENGTH">LENGTH</option>
                                          </select>
                                        <label class="input-label" for="">Unit</label>
                                        <input type="number" class="form-control form-control-lg" name="unit" placeholder="Please enter the quantity or size">
                                    </div>
                                    <div class=" form-group">
                                        <label class="input-label" for="addons">Add-On</label>
                                        <select name="add_ons[]" class="custom-select custom-select-lg" multiple
                                            data-placeholder="Select Add-On">
                                            {{-- <option>Italian Sausage</option>
                                            <option>Meatballs</option>
                                            <option>Nuggets</option> --}}
                                            @foreach ($addon as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class=" form-group">
                                        <a href="{{ route('vendor.addon.add-new') }}">Create More Add-On</a>
                                    </div>
                                    <div class=" form-group">
                                        <label class="input-label" for="addons">Badge</label>
                                        <select name="Badge[]" class="custom-select custom-select-lg" multiple
                                            data-placeholder="Select Badge">
                                            {{-- <option>Italian Sausage</option>
                                            <option>Meatballs</option>
                                            <option>Nuggets</option> --}}
                                            @foreach ($badges as $badge)
                                                <option value="{{ $badge->id }}">{{ $badge->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class=" form-group">
                                        <a href="{{ route('vendor.badge.add-new') }}">Create More Badge</a>
                                    </div>
                                    
                                    <div class=" form-group">
                                        <label class="input-label" for="">Serves</label>
                                        <select name="serves" id="serves" class="custom-select custom-select-lg">
                                            @for ($i = 1; $i <= 16; $i++)
                                                <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>


                                    {{-- <div class=" form-group">
                                    <label class="input-label" for="">Restaurant</label>
                                    <select name="restaurant_id" id="restaurant_id"
                                        class="custom-select custom-select-lg">
                                        <option value="">Please select Restaurant</option>
                                        <option value="1">Item 1</option>
                                        <option value="2">Item 2</option>
                                        <option value="3">Item 3</option>
                                    </select>
                                </div> --}}

                                    {{-- <input type="hidden" name="product_type" value="3" /> --}}


                                    <div class=" form-group">
                                        <label class="input-label" for="">Add Labels</label>
                                        <select name="labels" id="label" class="custom-select custom-select-lg">
                                            <option value="">Please select one or multiple labels from dropdown
                                            </option>
                                            <option value="1">Item 1</option>
                                            <option value="2">Item 2</option>
                                            <option value="3">Item 3</option>
                                        </select>
                                    </div>
                                    
                                    
                                    <div class="form-group">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="card-title">
                                                    <span class="card-header-icon">
                                                        <i class="tio-canvas-text"></i>
                                                    </span>
                                                    <span>{{ translate('messages.food_variations') }}</span>
                                                </h5>
                                            </div>
                                            <div class="card-body pb-0">
                                                <div class="row g-2">
                                                    <div class="col-md-12">
                                                        <div id="add_new_option">
                                                        </div>
                                                        <br>
                                                        <div class="mt-2">
                                                            <a class="btn btn-outline-success"
                                                                id="add_new_option_button">{{ translate('add_new_variation') }}</a>
                                                        </div> <br><br>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="customer_choice_options" id="customer_choice_options">

                                        </div>
                                    </div>

                                    <div class=" form-group">
                                        <label class="input-label" for="">Add Related Tags</label>
                                        <div class="search-form">
                                            <div class="input-group input-group-merge input-group-flush">
                                                <div class="input-group-prepend">
                                                    <button type="submit" class="">
                                                        <div class="input-group-text">
                                                            <i class="icon-Path-11"></i>
                                                        </div>
                                                    </button>
                                                </div>
                                                <input id="related_tags" type="text" name="related_tag"
                                                    class="form-control form-control-lg" placeholder="Search here"
                                                    aria-label="Search by related things">

                                            </div>
                                        </div>
                                        <div class="tags related_tags">

                                        </div>
                                    </div>


                                    <div class=" form-group">
                                        <label class="input-label" for="">Default Prize</label>
                                        <div class="search-form">
                                            <!-- Search -->
                                            <div class="input-group input-group-merge input-group-flush">
                                                <div class="input-group-prepend">
                                                    <button type="submit" class="">
                                                        <div class="input-group-text">
                                                            <i class="icon-banking_currency_dollar_icon"></i>
                                                        </div>
                                                    </button>
                                                </div>
                                                <input id="price" type="number" name="price" class="form-control"
                                                    placeholder="67.00">
                                            </div>
                                            <!-- End Search -->
                                        </div>
                                    </div>

                                    {{-- <div class=" form-group">
                                    <label class="input-label" for="">Discount</label>
                                    <div class="search-form">
                                        <!-- Search -->
                                        <div class="input-group input-group-merge input-group-flush">
                                            <div class="input-group-prepend">
                                                <button type="submit" class="">
                                                    <div class="input-group-text">
                                                        <i class="icon-banking_currency_dollar_icon"></i>
                                                    </div>
                                                </button>
                                            </div>
                                            <input id="discount" type="number" name="discount"
                                                class="form-control" placeholder="4.00">
                                        </div>
                                        <!-- End Search -->
                                    </div>
                                </div>

                                <div class=" form-group">
                                    <label class="input-label" for="">Tax</label>
                                    <div class="search-form">
                                        <!-- Search -->
                                        <div class="input-group input-group-merge input-group-flush">
                                            <div class="input-group-prepend">
                                                <button type="submit" class="">
                                                    <div class="input-group-text">
                                                        <i class="icon-banking_currency_dollar_icon"></i>
                                                    </div>
                                                </button>
                                            </div>
                                            <input id="tax" type="number" name="tax" class="form-control"
                                                placeholder="0.00">
                                        </div>
                                        <!-- End Search -->
                                    </div>
                                </div> --}}

                                    {{-- <div class=" form-group">
                                    <label class="input-label" for="">Available Time Starts</label>
                                    <input id="available_time_starts" type="time" name="available_time_starts"
                                        class="form-control" />
                                </div>

                                <div class=" form-group">
                                    <label class="input-label" for="">Available End Starts</label>
                                    <input id="available_time_ends" type="time" name="available_time_ends"
                                        class="form-control" />
                                </div> --}}

                                    <div class=" form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input name="in_stock" type="checkbox" class="custom-control-input"
                                                id="customControlInline" value="0">
                                            <label class="custom-control-label" for="customControlInline">Product Is Out
                                                Of Stock</label>
                                        </div>
                                    </div>

                                    <Button type="submit" class="btn btn-primary btn-lg">Save Now</Button>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="col-lg-5">
                        <div class="uploadimgs">
                            <div class="card">
                                <div class="card-body">
                                    <div class="inputgroup">
                                        <div class="input-group-prepend">
                                            <i class="icon-plus"><i class="path1"></i><i class="path2"></i><i
                                                    class="path3"></i></i>
                                        </div>
                                        <div class="custom-file">
                                            <input type="file" name="images[]" id="inputGroupFile01" multiple />
                                            <label class="custom-file-label" for="inputGroupFile01">Upload
                                                Images/Video <p>Select file</p></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    <div class="col-lg-5">
                        <div class="uploadimgs insert-img">
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-row images-preview-div">

                                        {{-- <div class="img">
                                            <img src="{{ asset('assets/admin/img/Group-173.jpg') }}" alt="">
                                            <div class="action-btn">
                                                <button><i class="icon-Pin"></i></button>
                                                <button><i class="icon-trash"></i></button>
                                            </div>
                                        </div> --}}

                                        {{-- <div class="img">
                                            <img src="{{ asset('assets/admin/img/Group-174.jpg') }}" alt="">
                                            <div class="action-btn">
                                                <button class="pinned"><i class="icon-Pin"></i></button>
                                            </div>
                                        </div>

                                        <div class="img">
                                            <img src="{{ asset('assets/admin/img/Group-176.jpg') }}" alt="">

                                        </div>
                                        <div class="img">
                                            <img src="{{ asset('assets/admin/img/Group-177.jpg') }}" alt="">
                                            <div class="action-btn">
                                                <button class="pinned"><i class="icon-Pin"></i></button>
                                            </div>
                                        </div> --}}


                                    </div>
                                    <div class="inputgroup">
                                        <div class="input-group-prepend">
                                            <i class="icon-plus_round_icon"></i>
                                        </div>
                                        <div class="custom-file">
                                            <input type="file" name="images[]" class="custom-file-input"
                                                id="images" multiple>
                                            <label class="custom-file-label" for="inputGroupFile01">Upload Images/Video
                                                <p>Select file</p>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="uploadimgs insert-img">
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-row featureImage-preview-div">
                                    </div>
                                    <div class="inputgroup">
                                        <div class="input-group-prepend">
                                            <i class="icon-plus_round_icon"></i>
                                        </div>
                                        <div class="custom-file">
                                            <input type="file" name="featureImage" class="custom-file-input"
                                                id="featureImage">
                                            <label class="custom-file-label" for="inputGroupFile01">Upload Feature Images
                                                Optional
                                                <p>Select file</p>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <!--<div class="uploadimgs insert-img">-->
                        <!--    <div class="card">-->
                        <!--        <div class="card-body">-->
                        <!--            <div class="form-row featureVideo-preview-div">-->

                        <!--            </div>-->
                        <!--            <div class="inputgroup">-->
                        <!--                <div class="input-group-prepend">-->
                        <!--                    <i class="icon-plus_round_icon"></i>-->
                        <!--                </div>-->
                        <!--                <div class="custom-file">-->
                        <!--                    <input type="file" name="featureVideo" class="custom-file-input"-->
                        <!--                        id="file-input-video">-->
                        <!--                    <label class="custom-file-label" for="inputGroupFile01">Upload Feature Video-->
                        <!--                        Optional-->
                        <!--                        <p>Select file</p>-->
                        <!--                    </label>-->
                        <!--                </div>-->
                        <!--            </div>-->
                        <!--        </div>-->
                        <!--    </div>-->
                        <!--</div>-->
                    </div>
                </div>
                </form>
            </div>
        </div>
    @endsection
</body>

@push('custom_js')
    <script>
        $(function() {
            // Multiple images preview with JavaScript
            var previewImages = function(input, imgPreviewPlaceholder) {
                if (input.files) {
                    var filesAmount = input.files.length;
                    for (i = 0; i < filesAmount; i++) {
                        var reader = new FileReader();
                        reader.onload = function(event) {
                            $($.parseHTML('<img>')).attr('src', event.target.result).appendTo(
                                imgPreviewPlaceholder);
                        }
                        reader.readAsDataURL(input.files[i]);
                    }
                }
            };
            // $('#images').on('change', function() {
            // previewImages(this, 'div.images-preview-div');
            // });
        });

        $('#images').on('change', function() {
            render_img(this);
            // $('.images-preview-div').append(render_img(this));
            // (this).value= "";
        })

        function render_img(input) {
            if (input.files) {
                var filesAmount = input.files.length;
                for (i = 0; i < filesAmount; i++) {
                    var reader = new FileReader();
                    reader.onload = function(event) {
                        $('.images-preview-div').append(`
            <div class="img">
                <img src="${event.target.result}" alt="">
                <div class="action-btn">
                    <button><i class="icon-trash"></i></button>
                </div>
            </div>
        `);
                    }
                    reader.readAsDataURL(input.files[i]);
                }
            }

            // var tag = `
        //         <div class="img images-preview-div">
        //             <img src="${target}" alt="">
        //             <div class="action-btn">
        //                 <button><i class="icon-Pin"></i></button>
        //                 <button><i class="icon-trash"></i></button>
        //             </div>
        //         </div>
        //     `;
            // return tag;
        }
         $('#featureImage').on('change', function() {
            render_featureImage(this);
        });

        function render_featureImage(input) {
            if (input.files) {
                var filesAmount = input.files.length;
                for (i = 0; i < filesAmount; i++) {
                    var reader = new FileReader();
                    reader.onload = function(event) {
                        $('.featureImage-preview-div').append(`
                            <div class="img">
                                <img src="${event.target.result}" alt="">
                                <div class="action-btn">
                                    <button><i class="icon-trash"></i></button>
                                </div>
                            </div>
                        `);
                    }
                    reader.readAsDataURL(input.files[i]);
                }
            }

            // var tag = `
        //     <div class="img images-preview-div">
        //         <img src="${target}" alt="">
        //         <div class="action-btn">
        //             <button><i class="icon-Pin"></i></button>
        //             <button><i class="icon-trash"></i></button>
        //         </div>
        //     </div>
        // `;
            // return tag;
        }

        const input = document.getElementById('file-input-video');
        const video = document.getElementById('feature-video');
        const videoSource = document.createElement('source');

        input.addEventListener('change', function() {
            const files = this.files || [];

            if (!files.length) return;

            const reader = new FileReader();

            reader.onload = function(e) {
                $('.featureVideo-preview-div').append(`
                    <div class="img">
                        <video src="${event.target.result}" id="feature-video" width="300" height="300" controls></video>
                        <div class="action-btn">
                            <button><i class="icon-trash"></i></button>
                        </div>
                    </div>
                `);
                // videoSource.setAttribute('src', e.target.result);
                // video.appendChild(videoSource);
                video.load();
                video.play();
            };

            reader.onprogress = function(e) {
                console.log('progress: ', Math.round((e.loaded * 100) / e.total));
            };

            reader.readAsDataURL(files[0]);
        });
    </script>
    <script>
        $(document).ready(function() {

            var select = $('select[multiple]');
            var options = select.find('option');

            var div = $('<div />').addClass('selectMultiple');
            var active = $('<div />');
            var list = $('<ul />');
            var placeholder = select.data('placeholder');

            var span = $('<span />').text(placeholder).appendTo(active);

            options.each(function() {
                var text = $(this).text();
                if ($(this).is(':selected')) {
                    active.append($('<a />').html('<em>' + text + '</em><i></i>'));
                    span.addClass('hide');
                } else {
                    list.append($('<li />').html(text));
                }
            });

            active.append($('<div />').addClass('arrow'));
            div.append(active).append(list);

            select.wrap(div);

            $(document).on('click', '.selectMultiple ul li', function(e) {
                var select = $(this).parent().parent();
                var li = $(this);
                if (!select.hasClass('clicked')) {
                    select.addClass('clicked');
                    li.prev().addClass('beforeRemove');
                    li.next().addClass('afterRemove');
                    li.addClass('remove');
                    var a = $('<a />').addClass('notShown').html('<em>' + li.text() + '</em><i></i>').hide()
                        .appendTo(select.children('div'));
                    a.slideDown(400, function() {
                        setTimeout(function() {
                            a.addClass('shown');
                            select.children('div').children('span').addClass('hide');
                            select.find('option:contains(' + li.text() + ')').prop(
                                'selected', true);
                        }, 500);
                    });
                    setTimeout(function() {
                        if (li.prev().is(':last-child')) {
                            li.prev().removeClass('beforeRemove');
                        }
                        if (li.next().is(':first-child')) {
                            li.next().removeClass('afterRemove');
                        }
                        setTimeout(function() {
                            li.prev().removeClass('beforeRemove');
                            li.next().removeClass('afterRemove');
                        }, 200);

                        li.slideUp(400, function() {
                            li.remove();
                            select.removeClass('clicked');
                        });
                    }, 600);
                }
            });

            $(document).on('click', '.selectMultiple > div a', function(e) {
                var select = $(this).parent().parent();
                var self = $(this);
                self.removeClass().addClass('remove');
                select.addClass('open');
                setTimeout(function() {
                    self.addClass('disappear');
                    setTimeout(function() {
                        self.animate({
                            width: 0,
                            height: 0,
                            padding: 0,
                            margin: 0
                        }, 300, function() {
                            var li = $('<li />').text(self.children('em').text())
                                .addClass('notShown').appendTo(select.find('ul'));
                            li.slideDown(400, function() {
                                li.addClass('show');
                                setTimeout(function() {
                                    select.find('option:contains(' +
                                        self.children('em')
                                        .text() + ')').prop(
                                        'selected', false);
                                    if (!select.find(
                                            'option:selected')
                                        .length) {
                                        select.children('div')
                                            .children('span')
                                            .removeClass('hide');
                                    }
                                    li.removeClass();
                                }, 400);
                            });
                            self.remove();
                        })
                    }, 300);
                }, 400);
            });

            $(document).on('click', '.selectMultiple > div .arrow, .selectMultiple > div span', function(e) {
                $(this).parent().parent().toggleClass('open');
            });

        });

        $('#related_tags, #ingredients, #allergens').on('change', function() {
            $('.' + $(this).attr('id')).append(render_tag((this).value, (this).id));
            (this).value = "";
        })

        function render_tag(value, id) {
            var tag = `
            <div class="alert alert-primary fade show" role="alert">
                ${value}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <input type="hidden" name="${id}[]" value="${value}">
            </div>
        `;
            return tag;
        }
    </script>
    <script>
        var count = 0;
        // var countRow=0;
        $(document).ready(function() {
            $("#add_new_option_button").click(function(e) {
                count++;
                var add_option_view = `
        <div class="card view_new_option mb-2" >
            <div class="card-header">
                <label for="" id=new_option_name_` + count + `> {{ translate('add_new') }}</label>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-lg-3 col-md-6">
                        <label for="">{{ translate('name') }}</label>
                        <input required name=options[` + count +
                    `][name] class="form-control" type="text" onkeyup="new_option_name(this.value,` +
                    count + `)">
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="form-group">
                            <label class="input-label text-capitalize d-flex alig-items-center"><span class="line--limit-1">{{ translate('messages.selcetion_type') }} </span>
                            </label>
                            <div class="resturant-type-group border">
                                <label class="form-check form--check mr-2 mr-md-4">
                                    <input class="form-check-input" type="radio" value="multi"
                                    name="options[` + count + `][type]" id="type` + count +
                    `" checked onchange="show_min_max(` + count + `)"
                                    >
                                    <span class="form-check-label">
                                        {{ translate('Multiple') }}
                                    </span>
                                </label>

                                <label class="form-check form--check mr-2 mr-md-4">
                                    <input class="form-check-input" type="radio" value="single"
                                    name="options[` + count + `][type]" id="type` + count +
                    `" onchange="hide_min_max(` + count + `)"
                                    >
                                    <span class="form-check-label">
                                        {{ translate('Single') }}
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="row g-2">
                            <div class="col-sm-6 col-md-4">
                                <label for="">{{ translate('Min') }}</label>
                                <input id="min_max1_` + count + `" required  name="options[` + count + `][min]" class="form-control" type="number" min="1">
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <label for="">{{ translate('Max') }}</label>
                                <input id="min_max2_` + count + `"   required name="options[` + count + `][max]" class="form-control" type="number" min="1">
                            </div>

                            <div class="col-md-4">
                                <label class="d-md-block d-none">&nbsp;</label>
                                    <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <input id="options[` + count + `][required]" name="options[` + count + `][required]" type="checkbox">
                                        <label for="options[` + count + `][required]" class="m-0">{{ translate('Required') }}</label>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-danger btn-sm delete_input_button" onclick="removeOption(this)"
                                            title="{{ translate('Delete') }}">
                                            <i class="tio-add-to-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="option_price_` + count + `" >
                    <div class="border rounded p-3 pb-0 mt-3">
                        <div  id="option_price_view_` + count + `">
                            <div class="row g-3 add_new_view_row_class mb-3">
                                <div class="col-md-4 col-sm-6">
                                    <label for="">{{ translate('Option_name') }}</label>
                                    <input class="form-control" required type="text" name="options[` + count +
                    `][values][0][label]" id="">
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <label for="">{{ translate('Additional_price') }}</label>
                                    <input class="form-control" required type="number" min="0" step="0.01" name="options[` + count + `][values][0][optionPrice]" id="">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3 p-3 mr-1 d-flex "  id="add_new_button_` + count + `">
                            <button type="button" class="btn btn-outline-primary" onclick="add_new_row_button(` +
                    count + `)" >{{ translate('Add_New_Option') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;

                $("#add_new_option").append(add_option_view);
            });
        });
        $('#choice_attributes').on('change', function() {
            $('#customer_choice_options').html(null);
            $.each($("#choice_attributes option:selected"), function() {
                if ($(this).val().length > 50) {
                    toastr.error(
                        '{{ translate('validation.max.string', ['attribute' => translate('messages.variation'), 'max' => '50']) }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    return false;
                }
                add_more_customer_choice_option($(this).val(), $(this).text());
            });
        });

        function add_more_customer_choice_option(i, name) {
            let n = name;
            $('#customer_choice_options').append(
                '<div class="row"><div class="col-md-3"><input type="hidden" name="choice_no[]" value="' + i +
                '"><input type="text" class="form-control" name="choice[]" value="' + n +
                '" placeholder="{{ translate('messages.choice_title') }}" readonly></div><div class="col-md-9"><input type="text" class="form-control" name="choice_options_' +
                i +
                '[]" placeholder="{{ translate('messages.enter_choice_values') }}" data-role="tagsinput" onchange="combination_update()"></div></div>'
            );
            $("input[data-role=tagsinput], select[multiple][data-role=tagsinput]").tagsinput();
        }

        function combination_update() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: '{{ route('admin.food.variant-combination') }}',
                data: $('#food_form').serialize(),
                beforeSend: function() {
                    $('#loading').show();
                },
                success: function(data) {
                    $('#loading').hide();
                    $('#variant_combination').html(data.view);
                    if (data.length > 1) {
                        $('#quantity').hide();
                    } else {
                        $('#quantity').show();
                    }
                }
            });
        }

        function add_new_row_button(data) {
            count = data;
            countRow = 1 + $('#option_price_view_' + data).children('.add_new_view_row_class').length;
         var add_new_row_view = `
                <div class="row add_new_view_row_class mb-3 position-relative pt-3 pt-sm-0">
                    <div class="col-md-4 col-sm-5">
                            <label for="">{{ translate('Option_name') }}</label>
                            <input class="form-control" required type="text" name="options[` + count + `][values][` +
                        countRow + `][label]" id="">
                        </div>
                        <div class="col-md-4 col-sm-5">
                            <label for="">{{ translate('Additional_price') }}</label>
                            <input class="form-control"  required type="number" min="0" step="0.01" name="options[` + count +
                        `][values][` + countRow + `][optionPrice]" id="">
                        </div>
                        <div class="col-sm-2 max-sm-absolute">
                            <label class="d-none d-sm-block">&nbsp;</label>
                            <div class="mt-1">
                                <button type="button" class="btn btn-danger btn-sm" onclick="deleteRow(this)"
                                    title="{{ translate('Delete') }}">
                                    <i class="tio-add-to-trash"></i>
                                </button>
                            </div>
                    </div>
                </div>`;
            $('#option_price_view_' + data).append(add_new_row_view);

        }

        function deleteRow(e) {
            element = $(e);
            element.parents('.add_new_view_row_class').remove();
        }

        function removeOption(e) {
            element = $(e);
            element.parents('.view_new_option').remove();
        }
    </script>
@endpush
