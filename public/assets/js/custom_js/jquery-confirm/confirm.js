/**
 * jquery-confirm v3.2.3 (http://craftpip.github.io/jquery-confirm/)
 */

$(document).ready(function() {
    $('.confirm-success').confirm({
        title: 'Confirmation Alert!',
        content: 'Are You Sure?',
        type: 'green', // More types: red, green, orange, blue, purple, dark
        icon: 'fa fa-warning',
    });
    $('.confirm-danger').confirm({
        title: 'Confirmation Alert!',
        content: 'Are You Sure?',
        type: 'red', // More types: red, green, orange, blue, purple, dark
        icon: 'fa fa-warning',
    });
    $('.confirm-warning').confirm({
        title: 'Confirmation Alert!',
        content: 'Are You Sure?',
        type: 'orange', // More types: red, green, orange, blue, purple, dark
        icon: 'fa fa-warning',
    });
    $('.confirm-info').confirm({
        title: 'Confirmation Alert!',
        content: 'Are You Sure?',
        type: 'blue', // More types: red, green, orange, blue, purple, dark
        icon: 'fa fa-warning',
    });
    $('.confirm-primary').confirm({
        title: 'Confirmation Alert!',
        content: 'Are You Sure?',
        type: 'purple', // More types: red, green, orange, blue, purple, dark
        icon: 'fa fa-warning',
    });
    $('.confirm-dark').confirm({
        title: 'Confirmation Alert!',
        content: 'Are You Sure?',
        type: 'dark', // More types: red, green, orange, blue, purple, dark
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