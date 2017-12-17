import slick from 'slick-carousel';

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

    if($('#appbundle_item_value').length) {
        calculateItemValueMargins();
    }

    $('#appbundle_item_value').keyup(function() {
        calculateItemValueMargins();
    });


    $('.items-slider').slick({
        infinite: true,
        slidesToShow: 4,
        responsive: [{
            breakpoint: 992,
            settings: {
                slidesToShow: 3,
            }
        }, {
            breakpoint: 756,
            settings: {
                slidesToShow: 2,
            }
        }, {
            breakpoint: 480,
            settings: {
                slidesToShow: 1,
            }
        }],
        prevArrow: '<i class="slick-prev fa fa-chevron-left" aria-hidden="true"></i>',
        nextArrow: '<i class="slick-next fa fa-chevron-right" aria-hidden="true"></i>'
    });
});

function calculateItemValueMargins() {
    const value = $('#appbundle_item_value').val();
    const margin = $('#appbundle_item_value').data('margin');

    const marginMin = value * (1 - margin / 100);
    const marginMax = value * (1 + margin / 100);

    $('#appbundle_item_value_margins').text(marginMin.toFixed(2) + '€ - ' + marginMax.toFixed(2) + '€');
}
