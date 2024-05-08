<script>
    loading.close();
    @if ($message = Session::get('success'))
        Swal.fire({
            title: "Success!",
            text: "{{ $message }}",
            icon: "success",
            didOpen: () => {
                Swal.hideLoading();
            }
        });
    @endif

    @if ($message = Session::get('error'))
        Swal.fire({
            title: "Error!",
            text: "{{ $message }}",
            icon: "error",
            didOpen: () => {
                Swal.hideLoading();
            }
        });
    @endif
</script>
