<script>
    function showLoading(element) {
        element.setAttribute('disabled', 'true');
        element.form.submit();
        Swal.fire({
            title: "Application is processing",
            didOpen: () => {
                Swal.showLoading();
            }
        })
    }
</script>