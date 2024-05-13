<script>
    function removeEntry(path, entry_id) {
        swal.fire({
            title: 'What is the reason you are\n deleting this entry?',
            text: 'Reason will be recorded.',
            icon: 'question',
            input: "textarea",
            inputAttributes: {
                required: true
            },
            showConfirmButton: true,
            showCancelButton: true,
            confirmButtonText: "Delete Entry",
            cancelButtonText: "Cancel"
        }).then((result) => {
            if (result.isConfirmed) {
                swal.fire({
                    title: 'Are you sure you want to delete \n this entry?',
                    text: 'Ensure correct entry was selected.',
                    icon: 'info',
                    showCancelButton: true,
                    showCancelButton: true
                }).then((result2) => {
                    if (result2.isConfirmed) {
                        $.post({!! json_encode(url('/')) !!} + path + "/delete/" + entry_id, {
                            _method: "DELETE",
                            data: {
                                reason: result.value
                            },
                            _token: "{{ csrf_token() }}"
                        }).then(function(data) {
                            console.log(data);
                            if (data == "success") {
                                swal.fire(
                                    "Done!",
                                    "Download was successfully deleted!.",
                                    "success").then(esc => {
                                    if (esc) {
                                        location.reload();
                                    }
                                });
                            } else {
                                swal.fire(
                                    "Oops! Something went wrong.",
                                    data,
                                    "error");
                            }
                        });
                    }
                })
            }
        })
    }
</script>
