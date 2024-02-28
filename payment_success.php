<?php
session_start();
include('dbconnection.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .invoice-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
        h2 {
            text-align: center;
            text-decoration: underline;
            margin-bottom: 50px;
        }

        .total {
            margin-top: 20px;
            text-align: right;
        }
        .invoice-inner-container {
            display: grid;
            grid-template-columns: 1fr 1fr; /* Two columns with equal width */
            gap: 20px; /* Adjust the gap between columns */
        }

        .order-details {
            text-align: right;
        }

        .company-address {
            grid-column: 1 / 2; /* Span the first column */
        }

        .gst-bill {
            grid-column: 2 / 3; /* Span the second column */
        }
        /* Apply styles for printing */
        @media print {
            body {
                font-family: Arial, sans-serif;
            }

            .invoice-container {
                max-width: 100%; /* Use full width when printing */
                margin-top: 50px;
                padding: 20px;
                border: 1px solid #ccc;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }

            th, td {
                border: 1px solid #ddd;
                padding: 8px;
                text-align: left;
            }

            th {
                background-color: #f2f2f2;
            }
            h2 {
                text-align: center;
                text-decoration: underline;
                margin-bottom: 50px;
            }

            .total {
                margin-top: 20px;
                text-align: right;
            }
            .invoice-inner-container {
                display: grid;
                grid-template-columns: 1fr 1fr; /* Two columns with equal width */
                gap: 20px; /* Adjust the gap between columns */
            }

            .order-details {
                text-align: right;
            }

            .company-address {
                grid-column: 1 / 2; /* Span the first column */
            }

            .gst-bill {
                grid-column: 2 / 3; /* Span the second column */
            }

            .print-button {
                display: none;
            }

            /* Set page size to A4 for printing */
            @page {
                size: A4;
                margin: 0; /* Remove default margins */
            }
        }
        
    </style>

</head>
<body>
    
<div class="invoice-container">

    <div class="print-button">
        <a class="btn btn-primary">Print Invoice</a>
    </div><br>
    <h2>Invoice</h2>


    <div class="invoice-inner-container">
        <!-- Company Address -->
        <div class="company-address">
            <p><strong>GST No:</strong> GST5473685734685638728799</p>
            <p><strong>Address:</strong></p>
            <p>Krishibhavan</p>
            <p>123 Main Street,</p>
            <p>Kerala, India</p>
        </div>

        <!-- GST Bill Number -->
        <div class="gst-bill">
            <p><strong>Invoice ID:</strong> INV{{ order.id }}26729479249</p>
            <p><strong>Order Date:</strong> {{ order.order_date }}</p>
            <p><strong>Order ID:</strong> {{ order.id }}</p>
            <p><strong>Customer e-mail:</strong> {{ order.customer.username }}</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ order.product.pro_category }}</td>
                <td>{{ order.product.price }}</td>
            </tr>
        </tbody>
    </table>
    
    <div class="total">
        <p>Total Amount: {{ order.product.price }} <p>Inclusive of all taxes</p></p>
    </div>

    <h3>Payment Details</h3>
    <p>Payment Status: {{ payment.payment_status }}</p>
    <p>Payment Amount: {{ payment.payment_amount }}</p>
    <p>Payment Date: {{ payment.payment_datetime }}</p>

</div>

</body>
</html>