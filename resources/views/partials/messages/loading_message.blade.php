<script>
    function showLoading(element) {
        element.setAttribute('disabled', 'true');
        element.form.submit();
        Swal.fire({
            icon: "info",
            title: "Application is Processing",
            text: "This should only take a minute...",
            didOpen: () => {
                Swal.showLoading();
            }
        })
    }
</script>