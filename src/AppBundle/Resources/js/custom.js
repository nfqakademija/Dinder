$(function() {
    $('select').each(function() {
        const max = $(this).data('max');

        const params = {
            placeholder: "Select an option",
            allowClear: true,
            width: '100%'
        };

        if (typeof max !== 'undefined') {
            params.maximumSelectionLength = 3;
        }

        $(this).select2(params);
    });
});
