$(document).ready(function() {
    $(".modal").on('shown', function() {
        $(this).find("[autofocus]:first").focus();
    });
});