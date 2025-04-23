@extends('partials.layouts.layout')

@section('title', 'Coupons')

@section('content')
    @include('partials.sidebar._sidebar')
    @include('partials.messages.confirmmessage')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="text-muted mb-0">Coupons</h5>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCouponModal">Add
                        Coupon</button>
                </div>
                {{-- <h5 class="card-header text-muted">Coupons</h5> --}}

                <div class="card-body table-responsive">
                    @include('partials.tables.coupons')
                </div>
                <div class="card-footer">
                    <a href="{{ route('dashboard.dashboard') }}" class="btn btn-danger">Back to Dashboard</a>
                </div>
            </div>


        </div>

    </div>
@endsection

<div class="modal fade" id="addCouponModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('coupons.store') }}" method="post">
                    @csrf
                    @method('post')
                    <div class="mb-3">
                        <label for="coupon_name" class="form-label">Coupon Name</label>
                        <input type="text"
                            class="form-control @error('coupon_name')
                    is-invalid
                @enderror"
                            id="coupon_name" name = "coupon_name" placeholder="Coupon Name">
                        @error('coupon_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="coupon_name" class="form-label">Coupon Discount</label>
                        <select name="coupon_discount" id="coupon_discount"
                            class="form-select @error('coupon_discount')
                   is-invalid
               @enderror">
                            <option selected disabled>Select a coupon discount</option>
                            <option value="300">$300</option>
                            <option value="500">$500</option>
                        </select>
                        @error('coupon_discount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="coupon_validity" class="form-label">Coupon Validity</label>
                        <input type="date" name = "coupon_validity"
                            class="form-control @error('coupon_validity')
                    is-invalid
                @enderror"
                            id="coupon_validity" name="coupon_validity"
                            value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                            min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                        @error('coupon_validity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
            </form>
        </div>
    </div>
</div>


@if($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var myModal = new bootstrap.Modal(document.getElementById('addCouponModal'));
        myModal.show();
    });
</script>
@endif