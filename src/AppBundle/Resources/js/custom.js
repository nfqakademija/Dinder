$(function() {
    if($('#appbundle_item_value').length) {
        calculateItemValueMargins();
    }

    $('#appbundle_item_value').keyup(function() {
        calculateItemValueMargins();
    });
});

function calculateItemValueMargins() {
    const value = $('#appbundle_item_value').val();
    const margin = $('#appbundle_item_value').data('margin');

    const marginMin = value * (1 - margin / 100);
    const marginMax = value * (1 + margin / 100);

    $('#appbundle_item_value_margins').text(marginMin.toFixed(2) + '€ - ' + marginMax.toFixed(2) + '€');
}
