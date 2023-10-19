$(document).ready(function() {
    $('.confirm-success').confirm({
        title: 'Confirmation Alert!',
        content: 'Are You Sure?',
        type: 'green',
        icon: 'fa fa-warning',
    });
    $('.confirm-danger').confirm({
        title: 'Confirmation Alert!',
        content: 'Are You Sure?',
        type: 'red',
        icon: 'fa fa-warning',
    });

    $('.confirm-form-success').on('click', function(e) {
        e.preventDefault(); // Prevent the default form submission

        var $form = $(this).closest('form'); // Find the parent form of the clicked button

        $.confirm({
            title: 'Confirmation Alert!',
            content: 'Are You Sure Submit The Request?',
            type: 'green',
            icon: 'fa fa-warning',
            buttons: {
                confirm: {
                    text: 'Yes',
                    action: function() {
                        // Proceed with the form submission
                        $form.submit();
                    }
                },
                cancel: {
                    text: 'No'
                }
            }
        });
    });

    $('.confirm-form-danger').on('click', function(e) {
        e.preventDefault(); // Prevent the default form submission

        var $form = $(this).closest('form'); // Find the parent form of the clicked button

        $.confirm({
            title: 'Confirmation Alert!',
            content: 'Are You Sure Submit The Request?',
            type: 'red',
            icon: 'fa fa-warning',
            buttons: {
                confirm: {
                    text: 'Yes',
                    action: function() {
                        // Proceed with the form submission
                        $form.submit();
                    }
                },
                cancel: {
                    text: 'No'
                }
            }
        });
    });
});