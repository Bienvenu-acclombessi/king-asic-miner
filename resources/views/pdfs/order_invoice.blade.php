<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Facture</title>
    {{-- <link rel="stylesheet" href="http://127.0.0.1:8000/assets/css/demo_1/style.css" /> --}}
    <style>
        .ronde {
            height: 200px;
            width: 200px;
            border-radius: 50%;
            position: absolute;
            top: 0%;
            left: 0%;
            overflow: visible;
        }

        .container-fluid {
            margin-bottom: 10px;
        }

        .company_name {
            margin-left: 100px;
            margin-top: 50px;
            z-index: 9999;
            white-space: nowrap;
        }

        .border-end {
            border-right: 10px solid #F57C00;
        }

        .border-bottom {
            border-bottom: 10px solid #F57C00;

        }

        .row {
            display: flex;
            -ms-flex-wrap: nowrap;
            flex-wrap: nowrap;
        }

        .row .col-12 {
            width: 100%;
        }

        .col-6 {
            width: 50%;
        }

        .justify-content-center {
            justify-content: center;
        }

        .bg-primary {
            background-color: #F57C00 !important;
        }

        .text-right {
            text-align: right !important;
        }

        .position-relative {
            position: relative;
        }

        .d-flex {
            display: flex;
        }

        .justify-content-between {
            justify-content: space-between;
        }

        .text-center {
            text-align: center
        }

        .text-danger {
            color: red;
        }

        .text-white {
            color: white;
        }

        .w-100 {
            width: 100%;
        }

        .table-responsive {
            display: block;
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table,
        .jsgrid .jsgrid-table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
        }

        .table th,
        .jsgrid .jsgrid-table th,
        .table td,
        .jsgrid .jsgrid-table td {
            padding: 0.9375rem;
            vertical-align: top;
            /* border-top: 1px solid #ebedf2; */
        }

        .table thead th,
        .jsgrid .jsgrid-table thead th {
            vertical-align: bottom;
            /* border-bottom: 2px solid #ebedf2; */
        }

        .table tbody+tbody,
        .jsgrid .jsgrid-table tbody+tbody {
            border-top: 2px solid #ebedf2;
        }

        .transaction_info {
            font-size: 15px;
            line-height: 20px
        }

        .transaction_info .h4,
        .transaction_info span {
            white-space: nowrap;
            margin-bottom: 0%;
        }

        .text-primary {
            color: #F57C00
        }
    </style>
</head>

<body>


    <div class="row">
        <div class="col-12">
            <hr />
            <div class="container-fluid position-relative" style="min-height: 200px">
                {{-- balons --}}

                <table class="table">
                    <tr>
                        <td class="col-6">
                            <div class="ronde bg-primary">
                                <div class="  company_name">
                                    <h2 class="display-1 text-white">Facture</h2>
                                    <h1 class="h1">{{ config('app.name') }}</h1>
                                </div>
                            </div>

                        </td>
                        <td class="col-6">
                            <table class="table transaction_info border-end">
                                <tr>
                                    <td class="col-6">
                                        <div class="item text-right mb-2">
                                            <h4 class="h4 font-bold">Numéro de Reçu</h4>
                                            <span>{{ $order->order_number }}</span>
                                        </div>
                                        <div class="item text-right mb-2">
                                            <h4 class="h4">Méthode de paiement</h4>
                                            <span>{{ $order->payment->payment_method }}</span>
                                        </div>


                                    </td>
                                    <td class="col-6">
                                        {{-- <div class="item text-right mb-2">
                                <h4 class="h4">Télephone de paiement</h4>
                                <span>121233445</span>
                            </div> --}}
                                        <div class="item text-right mb-2">
                                            <h4 class="h4">Date de paiement</h4>
                                            <span>{{ $order->payment->created_at }}</span>
                                        </div>
                                        <div class="item text-right mb-2">
                                            <h4 class="h4">Réference de transaction</h4>
                                            <span>{{ $order->payment->transaction_id }}</span>
                                        </div>
                                        {{-- <div class="item text-right mb-2">
                                            <h4 class="h4">Réference</h4>
                                            <span>2323</span>
                            </div> --}}
                                        {{-- <div class="item text-right mb-2">
                                            <h4 class="h4">via</h4>
                                            <span>Fedapay</span>
                                          </div> --}}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

            </div>
            <div class="container-fluid d-flex justify-content-between">
                <table class="table">
                    <tr>
                        <td>
                            <div class="col-lg-3 pl-0">
                                <p class="mt-5 mb-2 h2"><b>Payé par </b></p>
                                <p> {{ $order->order_billing_adress->first_name }}
                                    {{ $order->order_billing_adress->last_name }} ,<br>{{ $order->email }}
                                    <br>{{ $order->telephone }} <br>{{ $order->order_billing_adress->address }},
                                    <br>{{ $order->order_billing_adress->postal_code }}, {{ $order->order_billing_adress->city }}, {{ $order->order_billing_adress->country }}</p>
                            </div>

                        </td>
                        {{-- <td>
                            <div class="col-lg-3 pr-0">
                                <p class="mt-5 mb-2 text-right h2"><b>Payé par </b></p>
                                <p class="text-right"><span class="text-primary">Modernet soft</span>,<br>RB/DGO/22A2525<br> +22966821796/+22994513830<br> contact@modernetsoft.com.</p>
                            </div>
                        </td> --}}
                    </tr>
                </table>


            </div>
            <div class="container-fluid d-flex justify-content-between">
                <div class="col-lg-3 pl-0">
                    <p class="mb-0 mt-5">Date : {{ $order->payment->created_at }}</p>
                    {{-- <p>Date d'échéance : 25 janvier 2017</p> --}}
                </div>
            </div>
            <div class="container-fluid mt-5 d-flex justify-content-center w-100">
                <div class="table-responsive w-100">
                    <table class="table">
                        <thead>
                            <tr class="bg-primary text-white">
                                <th>#</th>
                                <th>Description</th>
                                <th class="text-right">Quantité</th>
                                <th class="text-right">P.U (Sans options)</th>
                                <th class="text-right">P.U (Avec options)</th>
                                <th class="text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 1;
                                $totalItem=0.0;
                            @endphp
                            @foreach ($order->details()->get() as $orderdetail)
                                <tr class="text-right">
                                    <td class="text-left">{{ $i }}</td>
                                    <td class="text-left">{{ $orderdetail->product->name }}</td>
                                    <td>{{ $orderdetail->quantity }}</td>
                                    <td>{{ round($orderdetail->unit_price,2) }}</td>
                                    @php

                                        $unit = 0;
                                        foreach ($orderdetail->specifications()->get() as $spec) {
                                            $unit += $spec->specification_price;
                                        }
                                        $unit += $orderdetail->unit_price;

                                    @endphp
                                    <td>{{ round($unit,2) }}</td>
                                    <td>{{ round($unit*$orderdetail->quantity,2)  }}</td>
                                    @php
                                        $totalItem+=$unit*$orderdetail->quantity;
                                    @endphp
                                </tr>

                                @php
                                    $i++;
                                @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="container-fluid mt-5 w-100">
                <p class="text-right mb-2">Sous-total: {{ round($totalItem,2) }}€</p>
                <p class="text-right mb-2">Livraison: {{ round($shipping,2) }}€</p>
                @php
                $totalWithDiscount=0.0;
                $remise=0.0;
                if ($order->coupon_order_id!=null)
                {
                    $totalWithDiscount=$order->order_coupon->reduction_value;
                    $remise=$totalItem-$totalWithDiscount;
                }

            @endphp
                <p class="text-right mb-2">Remise: -{{ round($remise,2) }}€</p>

                {{-- <p class="text-right">vat (10%) : $138</p> --}}
                <h4 class="text-right mb-5">Total : {{ round(($totalItem-$remise+$shipping),2) }}€</h4>

            </div>
            <hr>
            <div class="container-fluid w-100">
                <div class="mt-5">
                    <p class="text-danger text-center">Pour toute reclamation ou suggestion,veuillez contacter <span
                            class="h6 text-primary">{{ config('app.name') }}</span> au +22994513830 ou par email au
                        contact@modernetsoft.com </p>
                </div>
            </div>

        </div>
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->

    <!-- End custom js for this page -->
</body>

</html>
