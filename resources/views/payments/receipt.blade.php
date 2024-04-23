@extends('partials.layouts.layout')

@section('title', 'Print Receipt')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">
            @if ($message = Session::get('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <p class="text-success"><strong>{{ $message }}</strong></p>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if ($message = Session::get('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <p class="text-danger font-weight-bold">{{ $message }}</p>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="card">
                <div class="card-body">
                    <h2 class="text-muted">Applicant Receipt</h2>
                    <hr>
                    @if (!empty($receipt_info['application_no']))
                        <h5 class="mt-3">Application No : {{ $receipt_info['application_no'] }}</h5>
                    @endif
                    @if (!empty($receipt_info['app_type']))
                        <h5 class="mt-3">Application Type: {{ $receipt_info['app_type'] }}</h5>
                    @endif
                    @if (!empty($receipt_info['permit_category']))
                        <h5 class="mt-3">Permit Category : {{ $receipt_info['permit_category'] }}</h5>
                    @endif
                    @if (!empty($receipt_info['receipt_no']))
                        <h5 class="mt-3">Receipt Number : {{ $receipt_info['receipt_no'] }}</h5>
                    @endif
                    @if (!empty($receipt_info['applicant_name']))
                        <h5 class="mt-3">Name : {{ $receipt_info['applicant_name'] }}</h5>
                    @endif
                    @if (!empty($receipt_info['no_of_employees']))
                        <h5 class="mt-3">No of Employees {{ $receipt_info['no_of_employees'] }}</h5>
                    @endif
                    @if (!empty($receipt_info['payment_date_time']))
                        <h5 class="mt-3">
                            Payment Date : {{ $receipt_info['payment_date_time'] }}
                        </h5>
                    @endif
                    @if (!empty($receipt_info['appointment_date']))
                        <h5 class="mt-3">Appointment Date : {{ $receipt_info['appointment_date'] }}</h5>
                    @endif
                    @if (!empty($receipt_info['exam_site']))
                        <h5 class="mt-3">
                            Appointment Location : {{ $receipt_info['exam_site'] }}
                        </h5>
                    @endif
                    @if (!empty($receipt_info['total_cost']))
                        <h5 class="mt-3">
                            Total Cost : ${{ $receipt_info['total_cost'] }}
                        </h5>
                    @endif
                    @if (!empty($receipt_info['amount_paid']))
                        <h5 class="mt-3">
                            Amount Paid : ${{ $receipt_info['amount_paid'] }}
                        </h5>
                    @endif
                    @if (!empty($receipt_info['change_amt']))
                        <h5 class="mt-3">
                            Change : ${{ $receipt_info['change_amt'] }}
                        </h5>
                    @endif
                    @if (!empty($receipt_info['cashier']))
                        <h5 class="mt-3">
                            Cashier : {{ $receipt_info['cashier'] }}
                        </h5>
                    @endif
                    <button class="btn btn-success mt-4" onClick="printReceipt({{ json_encode($receipt_info) }})">
                        Print Receipt
                    </button>
                    <a href="/payments/applications/filter/0" class="btn btn-danger mt-4">Back to Dashboard</a>
                </div>
            </div>
        </div>
    </div>
    <script>
        const hamBurger = document.querySelector(".toggle-btn");

        hamBurger.addEventListener("click", function() {
            document.querySelector("#sidebar").classList.toggle("expand");
        });
    </script>
    <script>
        function printReceipt(receipt_info) {
            console.log(receipt_info);
            var mywindow = window.open('', 'PRINT', 'height=600,width=600');

            var serha = `<h2>SOUTH EAST REGIONAL HEALTH AUTHORITY</h2>`;

            mywindow.document.write(serha + "<br>");

            mywindow.document.write('Application No.     : ' + receipt_info['application_no'] + "<br>");
            mywindow.document.write('Item     : ' + receipt_info['app_type'] + "<br>");
            if (typeof receipt_info['permit_category'] != 'undefined') {
                mywindow.document.write('Permit Category      : ' + receipt_info['permit_category'] + "<br>");
            }

            mywindow.document.write('Receipt No.     : ' + receipt_info['receipt_no'] + "<br>");


            mywindow.document.write('Name     : ' + receipt_info['applicant_name'] + "<br>");
            if (typeof receipt_info['no_of_employees'] != 'undefined') {
                mywindow.document.write('No. of Empoloyees      : ' + receipt_info['no_of_employees'] + "<br>");
            }

            mywindow.document.write('Payment Date     : ' + receipt_info['payment_date_time'] + "<br>");
            // mywindow.document.write('Appointment Date     : ' + receipt_info['appointment_date'] + "<br>");

            if (typeof receipt_info['appointment_date'] != 'undefined') {
                mywindow.document.write('Appointment Date      : ' + receipt_info['appointment_date'] + "<br>");
            }
            if (typeof receipt_info['exam_site'] != 'undefined') {
                mywindow.document.write('Appointment Location      : ' + receipt_info['exam_site'] + "<br>");
            }

            mywindow.document.write('Total Cost     : ' + "$" + receipt_info['total_cost'] + "<br>");
            mywindow.document.write('Amount Paid      : ' + "$" + receipt_info['amount_paid'] + "<br>");
            mywindow.document.write('Change     : ' + "$" + receipt_info['change_amt'] + "<br><br>");
            mywindow.document.write('Cashier    : ' + receipt_info['cashier'] + "<br><br>");

            mywindow.document.write('REMINDER: TAKE ALONG THIS RECEIPT' + "<br>" + 'WITH YOU ON THE APPOINTMENT DATE.' +
                "<br>" + '***DO NOT LOSE THIS RECEIPT!***');

            // mywindow.document.write('</head><body >');
            // mywindow.document.write('<h1>' + document.title  + '</h1>');
            // mywindow.document.write(document.getElementById(elem).innerHTML);
            // mywindow.document.write('</body></html>');

            mywindow.document.close(); // necessary for IE >= 10
            mywindow.focus(); // necessary for IE >= 10*/

            mywindow.print();
            mywindow.close();

            return true;
        }
    </script>
@endsection
