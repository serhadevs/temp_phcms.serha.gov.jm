<script>
  
    @if ($message = Session::get('success'))
        Swal.fire({
            title: "Success!",
            text: "{{ $message }}",
            icon: "success",
        });
    @endif

    @if ($message = Session::get('error'))
        Swal.fire({
            title: "Error!",
            text: "{{ $message }}",
            icon: "error",
        });
    @endif
</script>
