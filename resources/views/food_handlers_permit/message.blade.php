
   

   


@extends('partials.layouts.layout')

@section('title', 'Food Handlers Permit')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">
            @include('partials.messages.messages')
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Resend Email</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('messages.send',['id' => request('id')]) }}" method="post">
                                @csrf
                                @method('post')


                                
                         <h5>Are you sure you want to resend the email?</h5>
                        </div>
                        <div class="modal-footer">
                            <a href="{{ route('permit.application.view',['id'=>request('id')]) }}" class="btn btn-secondary">Close</a>
                            <button type="submit" class="btn btn-success">Resend</button>
                    </form>
                    </div>
                </div>
            </div>
        </div>
       
    </div>
     <!-- Bootstrap JS and dependencies -->
     <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
     {{-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
     <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> --}}
     
     <script>
         $(document).ready(function () {
             $('#exampleModal').modal('show');
         });
     </script>
@endsection
