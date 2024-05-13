<script>
    function confirmationRenewal() {
        swal.fire({
            title: "Are you sure info\n is accurate?",
            text: "You have selected " + selected_items.length + " permits of " + table.rows().count()+" \nin the previous application.",
            icon: 'question',
            showConfirmButton: true,
            showCancelButton: true,
            cancelButtonText: "No! Go Back",
            confirmButtonText: "Yes! I am sure",
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('submit-btn').click();
            }
        }).catch((error) => {
            alert(error);
        })
    }
</script>
