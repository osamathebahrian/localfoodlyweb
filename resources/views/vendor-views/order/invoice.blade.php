@extends('layouts.vendor.app')

@section('title', '')

@push('css_or_js')
    <style>
        #printableArea *{
            color: black !important;
        }
        @media print {
            .non-printable {
                display: none;
            }

            .printable {
                display: block;
                font-family: emoji !important;
            }

            body {
                -webkit-print-color-adjust: exact !important;
                /* Chrome, Safari */
                color-adjust: exact !important;
                font-family: emoji !important;
            }
        }

    </style>

    <style type="text/css" media="print">
        @page {
            size: auto;
            /* auto is the initial value */
            margin: 2px;
            /* this affects the margin in the printer settings */
            font-family: emoji !important;
        }

    </style>
@endpush
<body class="">
@section('content')

    <div class="content container-fluid">
    <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm">
                    <h1 class="page-header-title">
                        Invoice
                    </h1>
                </div>
            </div>
        </div>
        <div class="row" style="font-family: emoji;">
            <div class="col-md-12 page-header">
                <center>
                    <input type="button" class="btn btn-primary non-printable" onclick="printDiv('printableArea')"
                        value="Proceed, If thermal printer is ready." />
                    <a href="{{ url()->previous() }}" class="btn btn-danger non-printable">Back</a>
                </center>
                <hr class="non-printable">
            </div>
            <div class="col-5" id="printableArea">
                <div class="text-center pt-4 mb-3">
                    <h2 style="line-height: 1">{{ $order->restaurant->name }}</h2>
                    <h5 class="text-break" style="font-size: 20px;font-weight: lighter;line-height: 1">
                        {{ $order->restaurant->address }}
                    </h5>
                    <h5 style="font-size: 16px;font-weight: lighter;line-height: 1">
                        {{ translate('phone') }} : {{ $order->restaurant->phone }}
                    </h5>
                    @if ($order->restaurant->gst_status)
                        <h5 style="font-size: 12px;font-weight: lighter;line-height: 1">
                            {{ translate('Gst No') }} : {{ $order->restaurant->gst_code }}
                        </h5>
                    @endif
                </div>

                <span>---------------------------------------------------------------------------------</span>
                <div class="row mt-3">
                    <div class="col-6">
                        <h5>{{ translate('Order ID') }} : {{ $order['id'] }}</h5>
                    </div>
                    <div class="col-6">
                        <h5 style="font-weight: lighter">
                            {{ date('d/M/Y ' . config('timeformat'), strtotime($order['created_at'])) }}
                        </h5>
                    </div>
                    <div class="col-12">
                        <h5>
                            {{ translate('Customer Name') }} :

                            {{-- isset($order->delivery_address) ? json_decode($order->delivery_address, true)['contact_person_name'] : '' --}}
                        </h5>
                        <h5>
                            {{ translate('messages.phone') }} :
                            {{-- isset($order->delivery_address) ? json_decode($order->delivery_address, true)['contact_person_number'] : '' --}}
                        </h5>
                        <h5 class="text-break">
                            {{ translate('messages.address') }} : 
                            {{ isset($order->delivery_address) ? $order->delivery_address : ''}}

                            {{-- isset($order->delivery_address) ? json_decode($order->delivery_address, true)['address'] : '' --}}
                        </h5>
                    </div>
                </div>
                <h5 class="text-uppercase"></h5>
                <span>---------------------------------------------------------------------------------</span>
                <table class="table table-bordered mt-3" style="width: 98%">
                    <thead>
                        <tr>
                            <th style="">{{ translate('messages.qty') }}</th>
                            <th class="">{{ translate('DESC') }}</th>
                            <th class="">{{ translate('messages.price') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @php($sub_total = 0)
                        @php($total_tax = 0)
                        @php($total_dis_on_pro = 0)
                        @php($add_ons_cost = 0)
                        @foreach ($order->details as $detail)
                            @if ($detail->food_id)
                                <tr>
                                    <td class="">
                                        {{ $detail['quantity'] }}
                                    </td>
                                    <td class="text-break">
                                    {{ json_decode($detail->food_details, true)['name'] }} <br>
                                        @if (false)
                                            <strong><u>Variation : </u></strong>
                                            @foreach (json_decode($detail['variation'], true)[0] as $key1 => $variation)
                                                <div class="font-size-sm text-body">
                                                    <span>{{ $key1 }} : </span>
                                                    <span
                                                        class="font-weight-bold">{{ $key1 == 'price' ? \App\CentralLogics\Helpers::format_currency($variation) : $variation }}</span>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="font-size-sm text-body">
                                                <span>{{ 'Price' }} : </span>
                                                <span
                                                    class="font-weight-bold">{{ \App\CentralLogics\Helpers::format_currency($detail->price) }}</span>
                                            </div>
                                        @endif

                                        {{--@foreach (json_decode($detail['add_ons'], true) as $key2 => $addon)
                                            @if ($key2 == 0)
                                                <strong><u>Addons : </u></strong>
                                            @endif
                                            <div class="font-size-sm text-body">
                                                <span class="text-break">{{ $addon['name'] }} : </span>
                                                <span class="font-weight-bold">
                                                    {{ $addon['quantity'] }} x
                                                    {{ \App\CentralLogics\Helpers::format_currency($addon['price']) }}
                                                </span>
                                            </div>
                                            @php($add_ons_cost += $addon['price'] * $addon['quantity'])
                                        @endforeach--}}
                                    </td>
                                    <td style="width: 28%">
                                        @php($amount = $detail['price'] * $detail['quantity'])
                                        {{ \App\CentralLogics\Helpers::format_currency($amount) }}
                                    </td>
                                </tr>
                                @php($sub_total += $amount)
                                @php($total_tax += $detail['tax_amount'] * $detail['quantity'])
                            @elseif($detail->campaign)
                                <tr>
                                    <td class="">
                                        {{ $detail['quantity'] }}
                                    </td>
                                    <td class="">
                                        {{ $detail->campaign['title'] }} <br>
                                        @if (count(json_decode($detail['variation'], true)) > 0)
                                            <strong><u>{{ translate('messages.variation') }} : </u></strong>
                                            @foreach (json_decode($detail['variation'], true)[0] as $key1 => $variation)
                                                <div class="font-size-sm text-body">
                                                    <span>{{ $key1 }} : </span>
                                                    <span
                                                        class="font-weight-bold">{{ $key1 == 'price' ? \App\CentralLogics\Helpers::format_currency($variation) : $variation }}</span>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="font-size-sm text-body">
                                                <span>{{ translate('messages.price') }} : </span>
                                                <span
                                                    class="font-weight-bold">{{ \App\CentralLogics\Helpers::format_currency($detail->price) }}</span>
                                            </div>
                                        @endif

                                        @foreach (json_decode($detail['add_ons'], true) as $key2 => $addon)
                                            @if ($key2 == 0)
                                                <strong><u>{{ translate('messages.addons') }} : </u></strong>
                                            @endif
                                            <div class="font-size-sm text-body">
                                                <span>{{ $addon['name'] }} : </span>
                                                <span class="font-weight-bold">
                                                    {{ $addon['quantity'] }} x
                                                    {{ \App\CentralLogics\Helpers::format_currency($addon['price']) }}
                                                </span>
                                            </div>
                                            @php($add_ons_cost += $addon['price'] * $addon['quantity'])
                                        @endforeach
                                    </td>
                                    <td style="width: 28%">
                                        @php($amount = $detail['price'] * $detail['quantity'])
                                        {{ \App\CentralLogics\Helpers::format_currency($amount) }}
                                    </td>
                                </tr>
                                @php($sub_total += $amount)
                                @php($total_tax += $detail['tax_amount'] * $detail['quantity'])
                            @endif
                        @endforeach
                    </tbody>
                </table>
                <span>---------------------------------------------------------------------------------</span>
                <div class="row justify-content-md-end mb-3" style="width: 97%">
                    <div class="col-md-7 col-lg-7">
                        <dl class="row text-right">
                            <dt class="col-6">{{ translate('Items Price') }}:</dt>
                            <dd class="col-6">{{ \App\CentralLogics\Helpers::format_currency($sub_total) }}</dd>
                            <dt class="col-6">{{ translate('Addon Cost') }}:</dt>
                            <dd class="col-6">
                                {{ \App\CentralLogics\Helpers::format_currency($add_ons_cost) }}
                                <hr>
                            </dd>
                            <dt class="col-6">{{ translate('messages.subtotal') }}:</dt>
                            <dd class="col-6">
                                {{ \App\CentralLogics\Helpers::format_currency($sub_total + $add_ons_cost) }}</dd>
                            <dt class="col-6">{{ translate('messages.discount') }}:</dt>
                            <dd class="col-6">
                                - {{ \App\CentralLogics\Helpers::format_currency($order['restaurant_discount_amount']) }}
                            </dd>
                            <dt class="col-6">{{ translate('messages.coupon_discount') }}:</dt>
                            <dd class="col-6">
                                - {{ \App\CentralLogics\Helpers::format_currency($order['coupon_discount_amount']) }}</dd>
                            <dt class="col-6">{{ translate('messages.vat/tax') }}:</dt>
                            <dd class="col-6">+
                                {{ \App\CentralLogics\Helpers::format_currency($order['total_tax_amount']) }}</dd>
                            <dt class="col-6">{{ translate('messages.delivery_man_tips') }}:</dt>
                            <dd class="col-6">
                                @php($dm_tips = $order['dm_tips'])
                                {{ \App\CentralLogics\Helpers::format_currency($dm_tips) }}
                            </dd>
                            <dt class="col-6">{{ translate('messages.delivery_charge') }}:</dt>
                            <dd class="col-6">
                                @php($del_c = $order['delivery_charge'])
                                {{ \App\CentralLogics\Helpers::format_currency($del_c) }}
                                <hr>
                            </dd>

                            <dt class="col-6" style="font-size: 20px">{{ translate('messages.total') }}:</dt>
                            <dd class="col-6" style="font-size: 20px">
                                {{ \App\CentralLogics\Helpers::format_currency($sub_total + $del_c + $dm_tips + $order['total_tax_amount'] + $add_ons_cost - $order['coupon_discount_amount'] - $order['restaurant_discount_amount']) }}
                            </dd>
                        </dl>
                    </div>
                </div>

                <div class="d-flex flex-row justify-content-between border-top">
                    <span>{{ translate('Paid by') }}: {{ str_replace('_', ' ', $order['payment_method']) }}</span>
                    <span>{{ translate('messages.amount') }}: {{ $order->order_amount + $order->adjusment }}</span>
                    <span>{{ translate('messages.change') }}: {{ $order->adjusment }}</span>
                </div>
                <span>---------------------------------------------------------------------------------</span>
                <h5 class="text-center pt-3">
                    """{{ translate('THANK YOU') }}"""
                </h5>
                <span>---------------------------------------------------------------------------------</span>
            </div>
        </div>
    </div>

@endsection
    </body>
@push('script')
    <script>
        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>
@endpush
