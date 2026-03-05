@extends('partials.layouts.layout')

@section('title', 'Mailing List Settings')

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
                <div class="row card-header">
                    <div class="col">
                        <h3 class="text-muted">
                            Mailing List For Reports
                        </h3>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-primary" onclick = "add()">
                            Add Mailing Personnel
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @include('partials.tables.mailing_list_table')
                </div>
            </div>
        </div>
    </div>
    <script>
        function add() {
            swal.fire({
                title: "Add Mailing List Personnel",
                text: "Enter Personnel Email",
                icon: "question",
                input: "text",
                inputAttributes: {
                    required: true
                },
                showCancelButton: true,
                showConfirmButton: true
            }).then((result) => {
                swal.fire({
                    title: "Add Mailing List Personnel",
                    text: "Enter First Name",
                    icon: "question",
                    input: "text",
                    inputAttributes: {
                        required: true
                    },
                    showCancelButton: true,
                    showConfirmButton: true
                }).then((result2) => {
                    swal.fire({
                        title: "Add Mailing List Personnel",
                        text: "Enter Last Name",
                        icon: "question",
                        input: "text",
                        inputAttributes: {
                            required: true
                        },
                        showCancelButton: true,
                        showConfirmButton: true
                    }).then((result3) => {
                        if (result3.isConfirmed) {
                            swal.fire({
                                title: "Are you sure you want to add this email to mailing list",
                                text: "Ensure correct email was entered",
                                icon: "warning",
                                showCancelButton: true,
                                showConfirmButton: true
                            }).then((result4) => {
                                if (result4.isConfirmed) {
                                    $.post({!! json_encode(url('/mailing-list')) !!}, {
                                        data: {
                                            email: result.value,
                                            first_name: result2.value,
                                            last_name: result3.value
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
                })
            })
        }
    </script>
@endsection
