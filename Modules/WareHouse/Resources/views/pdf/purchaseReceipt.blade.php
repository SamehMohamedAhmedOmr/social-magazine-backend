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

        .tab .second {
            text-align: right !important;
        }

        p.small-text {
            font-size: 10px !important;
        }

        .bold-text{
            font-weight: bold !important;
        }


    </style>

</head>


<body class="container">
<section aria-label="Header">
    <div style="text-align: right;">
        <h4 class="gray-color font-weight-lighter">PURCHASE RECEIPT</h4>
        <h6 class="gray-color">PR-{{$purchase_receipt->id}}</h6>
    </div>
    <hr class="gray-color">
</section>


<section aria-label="related-data">
    @if(isset($purchase_receipt->company))
        @if(count($purchase_receipt->company->language) > 0)
            <h6>Company:</h6>
            <p class="small-text">{{ $purchase_receipt->company->language[0]->pivot->name }}</p>
        @endif
    @endif

    <h6>Purchase Order:</h6>
    <p class="small-text">PO-{{ $purchase_order->id }}</p>

    <h6>Date:</h6>
    <p class="small-text">{{ $purchase_receipt->created_at->todatestring() }}</p>

    <h6>Required by date:</h6>
    <p class="small-text">{{ $purchase_order->delivery_date }}</p>

     <!--  TODO => make this table dynamic  -->
    <table class="table table-bordered table-sm" style="font-size: 10px;">
        <thead>
        <tr>
            <th class="bold-text">SKU</th>
            <th class="bold-text">Product name</th>
            <th class="bold-text">Accepted Quantity</th>
            <th class="bold-text">Price</th>
            <th class="bold-text">Amount</th>
        </tr>
        </thead>
        <tbody>
        @foreach($products as $product)
            <tr>
                <th>{{ $product['sku'] }}</th>
                <td>{{ $product['name'] }}</td>
                <td>{{ $product['accepted_quantity'] }}</td>
                <th style="font-weight: bold !important;">{{ $product['price'] }}</th>
                <th style="font-weight: bold !important;">{{ $product['quantity'] }}</th>
            </tr>
        @endforeach
        </tbody>
    </table>

    <table class="tab" style="font-size: 10px !important;">
        <tr>
            <th class="bold-text">Shipping Rate</th>
            <th class="bold-text">Taxes</th>
            <th class="bold-text">Total</th>
        </tr>
        <tr>
            <td>{{ $shipping_price }}</td>
            <td>{{ $tax_price }}</td>
            <td>{{ $total_price }}</td>
        </tr>
    </table>
    ​​​


</section>

</body>
</html>
