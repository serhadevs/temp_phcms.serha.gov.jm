@extends('partials.layouts.layout')

@section('title', 'Establiushment Categories')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">
            <div class="card shadow">
                <div class="card-header">
                    <div class="row">
                        <div class="col">
                            <h3 class="text-muted fw-bold">
                                Establishment Categories
                            </h3>
                        </div>
                        <div class="col text-end">
                            <button class="btn btn-primary" onclick="add()">
                                Add Category
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @include('partials.tables.establishment_categories_table')
                </div>
                <div class="card-footer">
                    <button class="btn btn-danger">Back to Dashbord</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        function add() {
            swal.fire({
                title: "Add Establishment Category",
                text: "Enter Category Name",
                icon: "question",
                input: "text",
                inputAttributes: {
                    required: true
                },
                showCancelButton: true,
                showConfirmButton: true
            }).then((result) => {
                if (result.isConfirmed) {
                    swal.fire({
                        title: "What is the reason you are deleting this entry?",
                        text: "Reason will be recorded",
                        input: "textarea",
                        icon: "question",
                        showCancelButton: true,
                        showConfirmButton: true
                    }).then((result2) => {
                        if (result2.isConfirmed) {
                            swal.fire({
                                title: "Are you sure you want to add this category?",
                                text: "Ensure correct Name was entered",
                                icon: "warning",
                                showCancelButton: true,
                                showConfirmButton: true
                            }).then((result3) => {
                                if (result3.isConfirmed) {
                                    $.post({!! json_encode(url('/admin/establishment-categories/create')) !!}, {
                                        data: {
                                            est_cat_name: result.value,
                                            reason: result2.value
                                        },
                                        _method: "POST",
                                        _token: "{{ csrf_token() }}"
                                    }).then((data) => {
                                        if (data[0] == 'success') {
                                            swal.fire({
                                                title: "Done",
                                                text: data[1],
                                                icon: 'success'
                                            }).then((esc) => {
                                                if (esc) {
                                                    location.reload();
                                                }
                                            })
                                        } else {
                                            swal.fire({
                                                title: "Error",
                                                text: data,
                                                icon: 'error'
                                            })
                                        }
                                    })
                                }
                            })
                        }
                    })
                }
            })
        }
    </script>
@endsection
