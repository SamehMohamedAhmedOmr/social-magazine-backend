<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Purchase Order</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
          integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <style>

        body {
            font-family: DejaVu Sans, serif;
            font-weight: lighter !important;
        }

        .d-flex {
            display: flex;
        }

        .justify-content-end {
            justify-content: flex-end;
        }

        .gray-color {
            color: #696969;
        }

        hr {
            border-width: 2px;
        }

        .tab {
            border-collapse: collapse;
            min-width: 100% !important;
        }

        .tab * {
            border: none !important;
        }

        .tab .second{
            text-align: right !important;
        }

        p.small-text {
            font-size: 10px !important;
        }


    </style>

</head>


<body class="container">
<section aria-label="Header">
    <div style="text-align: right;">
        <h4 class="gray-color font-weight-lighter">PURCHASE ORDER</h4>
        <h6 class="gray-color">PO-{{$purchase_order->id}}</h6>
    </div>
    <hr class="gray-color">
</section>


<section aria-label="related-data">
    @if(isset($purchase_order->company))
        @if(count($purchase_order->company->language) > 0)
            <h6>Company:</h6>
            <p class="small-text">{{ $purchase_order->company->language[0]->pivot->name }}</p>
        @endif
    @endif
    @if(isset($purchase_order->warehouse))
        @if(count($purchase_order->warehouse->language) > 0)
            <h6>Warehouse:</h6>
            <p class="small-text">{{ $purchase_order->warehouse->language[0]->pivot->name }}</p>
        @endif
    @endif

    <h6>Date:</h6>
    <p class="small-text">{{ $purchase_order->created_at->todatestring() }}</p>

    <h6>Required by date:</h6>
    <p class="small-text">{{ $purchase_order->delivery_date }}</p>

    <table class="table table-bordered table-sm" style="font-size: 10px;">
        <thead>
            <tr>
                <th style="font-weight: bold !important;">SKU</th>
                <th style="font-weight: bold !important;">Product name</th>
                <th style="font-weight: bold !important;">Quantity</th>
                @if($price)
                    <th style="font-weight: bold !important;">Price</th>
                    <th style="font-weight: bold !important;">Amount</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($purchase_order->products as $product)
                <tr>
                    <th>{{ $product->sku }}</th>
                    <td>{{ $product->languages->where('language_id',$lang)->first()->name }}</td>
                    <td>{{ $product->pivot->quantity }}</td>
                    @if($price)
                        <th style="font-weight: bold !important;">{{ $product->pivot->price }}</th>
                        <th style="font-weight: bold !important;">{{ $product->pivot->total_amount }}</th>
                    @endif
            </tr>
            @endforeach
        </tbody>
    </table>

        @if($price)
            <table class="tab" style="font-size: 10px !important;">
                <tr>
                    <td class="first">Total Money Before Taxes and shipping and discounts</td>
                    <td class="second">{{ $total_price_before_extra_fees }}</td>
                </tr>
                <tr>
                    <td class="first">Discount price</td>
                    <td class="second">{{ $discount_price_rate }}</td>
                </tr>
                <tr>
                    <td class="first">Shipping Rate</td>
                    <td class="second">{{ $shipping_price }}</td>
                </tr>
                <tr>
                    <td class="first">Taxes and Charges</td>
                    <td class="second">{{ $tax_price }}</td>
                </tr>
                <tr>
                    <td class="first">Grand Total After Taxes, Shipping And Discounts</td>
                    <td class="second">{{ $total_price }}</td>
                </tr>
            </table>​​​

        @endif

</section>

</body>
</html>
