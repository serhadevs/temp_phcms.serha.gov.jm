<script>
    let loading = Swal.fire({
        icon: "info",
        title: "Populating Table Contents",
        text: "This should only take a minute...",
        didOpen: () => {
            Swal.showLoading();
        }
    });
</script>