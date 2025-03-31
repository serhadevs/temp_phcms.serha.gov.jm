@extends('partials.layouts.layout')

@section('title', 'Create Payment')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">
            <div class="card shadow">
                <div class="card-header">
                    <h2 class="text-muted">Create New Payment</h2>
                </div>
                <div class="card-body">
                    @if ($message = Session::get('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <p class="text-danger font-weight-bold">{{ $message }}</p>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col">
                            <form action="{{ route('payments.create.store') }}" method="POST">
                                @csrf
                                @method('POST')
                                <div class="mt-3">
                                    <label for="" class="form-label">Application Type</label>
                                    <select name="price_id" class="form-select" id="prices" onchange="detPrice()">
                                        <option readonly disabled selected>Please select application type</option>
                                        @foreach ($prices as $price)
                                            <option value="{{ $price->id }}" data-price="{{ $price->price }}"
                                                {{ old('price_id')
                                                    ? (old('price_id') == $price->id
                                                        ? 'selected'
                                                        : '')
                                                    : (isset($price_id)
                                                        ? ($price_id == $price->id
                                                            ? 'selected'
                                                            : '')
                                                        : '') }}>
                                                {{ $price->app_type_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('price_id')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="mt-3">
                                    <label for="" class="form-label">Application Number</label>
                                    <input type="text" class="form-control" name="application_id" {{-- value="{{ $app_id_1 != '' ? $app_id_1 : (old('application_id') == '' ? '' : old('application_id')) }}" --}}
                                        value="{{ old('application_id') ? old('application_id') : (isset($app_id) ? $app_id : '') }}"
                                        id="application_id" />
                                    @error('application_id')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="mt-3">
                                    <label for="" class="form-label">Total Cost</label>
                                    <input type="number" class="form-control" id="total_cost" name="total_cost"
                                        value = "{{ old('total_cost') == '' ? '' : old('total_cost') }}" readonly>
                                    @error('total_cost')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="mt-3">
                                    <label for="" class="form-label">Amount Paid</label>
                                    <input type="number" class="form-control" id="amount_paid" name="amount_paid"
                                        value = "{{ old('amount_paid') == '' ? '' : old('amount_paid') }}"
                                        onkeyup="calcChange()">
                                    @error('amount_paid')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="mt-3">
                                    <label for="" class="form-label">Change</label>
                                    <input type="number" class="form-control" id= "change_amt" name="change_amt"
                                        value = "{{ old('change_amt') == '' ? '' : old('change_amt') }}" readonly>
                                    @error('change_amt')
                                        <p class="text-danger">Cannot be a negative number</p>
                                    @enderror
                                </div>
                                <div class="mt-3" {{ auth()->user()->facility_id == 3 ? '' : 'hidden' }}>
                                    <label for="" class="form-label">Payment Type</label>
                                    <select name="payment_type_id" id="" class="form-select">
                                        @foreach ($payment_types as $payment_type)
                                            <option value="{{ $payment_type->id }}"
                                                {{ old('payment_type_id') == $payment_type->id ? 'selected' : '' }}>
                                                {{ $payment_type->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('payment_type_id')
                                        <p class="text-danger">This is a required field</p>
                                    @enderror
                                </div>
                                <div class="mt-3">
                                    <div class="mt-3" style="display:none" id="backlog_1">
                                        <label for="" class="form-label">Receipt No of manual receipt</label>
                                        <input type="text" class="form-control" name="manual_receipt_no"
                                            value="{{ old('manual_receipt_no') }}">
                                        @error('manual_receipt_no')
                                            <p class="text-danger">This is required if this payment is a part of backlog.</p>
                                        @enderror
                                    </div>
                                    <div class="mt-3" style="display:none" id="backlog_2">
                                        <label for="" class="form-label">Manual Receipt Date</label>
                                        <input type="date" class="form-control" name="manual_receipt_date"
                                            value="{{ old('manual_receipt_date') }}">
                                        @error('manual_receipt_date')
                                            <p class="text-danger">This is required if this payment is a part of backlog.</p>
                                        @enderror
                                    </div>
                                    <div class="form-check form-switch mt-3">
                                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault"
                                            onchange="backlog(this.checked)" value="1" name="is_backlog"
                                            {{ old('is_backlog') == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexSwitchCheckDefault">Backlog
                                            Payment</label>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <button class="btn btn-success" type="button" onclick="showLoading(this)">Submit
                                        Payment</button>
                                    <a class="btn btn-danger" onclick="history.back()">Cancel</a>
                                </div>
                            </form>

                        </div>
                        <div class="col">
                            <div class="card">
                                <div class="card-body">
                                    <div id="result">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <script>
                            function backlog(checked_stat) {
                                if (checked_stat) {
                                    document.getElementById('backlog_1').style.display = "";
                                    document.getElementById('backlog_2').style.display = "";
                                    document.getElementById('total_cost').removeAttribute('readonly');
                                } else {
                                    document.getElementById('backlog_1').style.display = "none";
                                    document.getElementById('backlog_2').style.display = "none";
                                    document.getElementById('total_cost').setAttribute('readonly', true);
                                }
                            }

                            function calcChange() {
                                var total_cost = parseFloat(document.getElementById("total_cost").value);
                                var amount_paid = parseFloat(document.getElementById("amount_paid").value);

                                var change_amt = amount_paid - total_cost;
                                document.getElementById('change_amt').setAttribute('value', parseFloat(change_amt));
                            }
                        </script>
                        <script>
                            function detPrice() {
                                var element = document.getElementById('prices');
                                var tot_cost = element.options[element.selectedIndex].getAttribute("data-price");
                                var app_type_id = element.options[element.selectedIndex].value;
                                if (app_type_id == "3" || app_type_id == "4" || app_type_id == "6" || app_type_id == "5") {
                                    document.getElementById('total_cost').removeAttribute('readonly');
                                } else {
                                    document.getElementById('total_cost').setAttribute('readonly', true);
                                }
                                document.getElementById('total_cost').value = tot_cost;
                            }
                        </script>
                        <script>
                            window.onload = () => {
                                if (document.getElementById('prices').value != "") {
                                    detPrice();
                                }

                                if (document.getElementById('flexSwitchCheckDefault').checked) {
                                    document.getElementById('backlog_1').style.display = "";
                                    document.getElementById('backlog_2').style.display = "";
                                }

                                if (document.getElementById('application_id').value != "") {
                                    var app_id = $('#application_id').val();
                                    var price_id = $('#prices').val();
                                    // console.
                                    if (app_id != '') {
                                        $('#result').html('');
                                        $.ajax({
                                            url: "/payments/search/" + app_id + "/" + price_id + "/",
                                            method: "get",
                                            data: {
                                                search: app_id
                                            },
                                            dataType: "text",
                                            success: function(data) {
                                                $('#result').html(data);
                                            }
                                        })
                                    } else {
                                        $('#result').html(
                                            '<h4>Application Information</h4><p class="text-danger">No Application Found</p>'
                                        );
                                    }
                                }
                            }
                        </script>
                        <script>
                            $(document).ready(function() {
                                $('#application_id').keyup(function() {
                                    var txt = $(this).val();
                                    var app_id = $('#application_id').val();
                                    var price_id = $('#prices').val();
                                    if (txt != '') {
                                        $('#result').html('');
                                        $.ajax({
                                            url: "/payments/search/" + app_id + "/" + price_id + "/",
                                            method: "get",
                                            data: {
                                                search: txt
                                            },
                                            dataType: "text",
                                            success: function(data) {
                                                $('#result').html(data);
                                            }
                                        })
                                    } else {
                                        $('#result').html(
                                            '<h4>Application Information</h4><p class="text-danger">No Application Found</p>'
                                        );
                                    }
                                });
                            });
                        </script>
                    </div>
                </div>

            </div>
        </div>
        @include('partials.messages.loading_message')
    </div>
@endsection
