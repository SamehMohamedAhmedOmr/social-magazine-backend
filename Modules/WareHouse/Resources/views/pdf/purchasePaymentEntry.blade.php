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
        <h4 class="gray-color font-weight-lighter">PAYMENT ENTRY</h4>
        <h6 class="gray-color">PE-{{$payment_entry->id}}</h6>
    </div>
    <hr class="gray-color">
</section>


<section aria-label="related-data">

    <h6>Purchase Order:</h6>
    <p class="small-text">PO-{{ $payment_entry->PurchaseInvoice->purchaseReceipt->PurchaseOrder->id }}</p>

    <h6>Date:</h6>
    <p class="small-text">{{ $payment_entry->created_at->todatestring() }}</p>

    <h6>Payment Entry Amount</h6>
    <p class="small-text">{{ $payment_entry->payment_price }}</p>

    <h6>Payment Entry Type</h6>
    <p class="small-text">{{ $payment_entry->PaymentEntryType->name }}</p>

    @if(isset($payment_entry->payment_reference))
        <h6>Payment Entry Reference</h6>
        <p class="small-text">{{ $payment_entry->payment_reference }}</p>
    @endif

</section>

</body>
</html>
