import slick from 'slick-carousel';

/**
 * Sample usage:
 *
 * <a href="/blog/48/delete" data-method="DELETE" data-token="...">delete this post</a>
 */
$(document).ready(function () {

    // Every link with an attribute data-method
    $('body').on('click', 'a[data-method]', function(event) {
        event.preventDefault();

        const target = $(event.currentTarget);
        const action = target.attr('href');
        const _method = target.data('method');
        const _token = target.data('token');
        const _target = $('#' + target.data('target'));

        if (typeof _target !== 'undefined') {

            $.ajax({
                'url': action,
                'method': _method,
                'data': {
                    '_token': _token
                },
                success: function() {
                    const _targetParent = _target.parent();

                    _target.slideUp(300).promise().done(function () {
                        _target.remove();

                        if(_targetParent.hasClass('list-group')) {
                            _targetParent.parent().find('form').removeClass('hidden');
                        }
                    });
                }
            });
        } else {

            // Create a form on click
            let form = $('<form/>', {
                style:  "display:none;",
                action: action,
                method: 'POST'
            });

            form.append($('<input/>', {
                type:'hidden',
                name:'_method',
                value: _method
            }));

            form.append($('<input/>', {
                type:'hidden',
                name:'_token',
                value: _token
            }));

            form.appendTo(target);

            // Submit the form
            form.submit();
        }
    });

    $('.item-categories-form').submit(function(event) {
        event.preventDefault();
        const $me = $(this);

        $.ajax({
            'url': $me.attr('action'),
            'method': $me.attr('method'),
            'data': $me.serialize(),
            success: function(data) {
                $me.parent().find('.list-group').html(data.template);
                $me.find('select option:selected').remove();

                if($me.parent().find('.list-group .list-group-item').length > 2) {
                    $me.addClass('hidden');
                }
            },
            error: function() {
                window.location.reload();
            }
        });
    });

    $('body').on('click', '.link-activate', function (event) {
        event.preventDefault();
        const $me = $(this);

        $.ajax({
            'url': $me.attr('href'),
            'method': 'PUT',
            'data': {
                '_token': $me.data('token')
            },
            success: function() {
                const badge = $me.closest('.tab-content').prev().find('.active .badge');
                badge.text(parseInt(badge.text()) - 1);

                const badgeActive = $me.closest('.tab-content').prev().find('.active').prev().find('.badge');
                badgeActive.text(parseInt(badgeActive.text()) + 1);

                if($me.closest('.slick-slider').find('.slick-slide').length === 1) {
                    $me.closest('.slick-slider').next().removeClass('hidden');
                }

                $me.closest('.slick-slider').slick('slickRemove', $me.closest('.slick-slide').attr('data-slick-index')).slick('refresh');
            }
        });
    });

    let itemOwner = false;
    let itemOwnerId = false;
    let itemRespondent = false;
    let itemRespondentId = false;

    $('body').on('click', '.item-owner', function(event) {
        event.preventDefault();

        const $me = $(this);
        itemOwner = $me.closest('.slick-slide');
        itemOwnerId = $me.data('id');

        $.ajax({
            'url': $me.attr('href'),
            'method': 'GET',
            success: function(data) {
                $('#matches-container').html(data.template).show();
            }
        });
    });

    $('body').on('click', '.item-respondent', function(event) {
        event.preventDefault();

        const $me = $(this);
        itemRespondent = $me.closest('.slick-slide');
        itemRespondentId = $me.data('id');

        $.ajax({
            'url': $me.attr('href'),
            'method': 'GET',
            success: function(data) {
                $('#matches-container').html(data.template).show();
            }
        });
    });

    $('body').on('click', '.item-match-delete', function(event) {
        event.preventDefault();

        const $me = $(this);

        $.ajax({
            'url': $me.attr('href'),
            'method': 'DELETE',
            'data': {
                'owner': itemOwnerId,
                '_token': $me.data('token')
            },
            success: function(data) {
                if($(data.template).find('.item-card').length === 0) {
                    const badge = itemOwner.closest('.tab-content').prev().find('.active .badge');
                    badge.text(parseInt(badge.text()) - 1);

                    if(itemOwner.closest('.slick-slider').find('.slick-slide').length === 1) {
                        itemOwner.closest('.slick-slider').next().removeClass('hidden');
                    }

                    itemOwner.closest('.slick-slider').slick('slickRemove', itemOwner.closest('.slick-slide').attr('data-slick-index')).slick('refresh');
                    $('#matches-container').empty().hide();
                } else {
                    $('#matches-container').html(data.template).show();
                }
            }
        });
    });

    $('body').on('click', '.item-match-decline', function(event) {
        event.preventDefault();

        const $me = $(this);

        $.ajax({
            'url': $me.attr('href'),
            'method': 'PUT',
            'data': {
                'respondent': itemRespondentId,
                '_token': $me.data('token')
            },
            success: function(data) {
                if($(data.template).find('.item-card').length === 0) {
                    const badge = itemRespondent.closest('.tab-content').prev().find('.active .badge');
                    badge.text(parseInt(badge.text()) - 1);

                    if(itemRespondent.closest('.slick-slider').find('.slick-slide').length === 1) {
                        itemRespondent.closest('.slick-slider').next().removeClass('hidden');
                    }

                    itemRespondent.closest('.slick-slider').slick('slickRemove', itemRespondent.closest('.slick-slide').attr('data-slick-index')).slick('refresh');
                    $('#matches-container').empty().hide();
                } else {
                    $('#matches-container').html(data.template).show();
                }
            }
        });
    });

    $('body').on('click', '.item-match-trade', function(event) {
        event.preventDefault();

        const $me = $(this);

        $.ajax({
            'url': $me.attr('href'),
            'method': 'PUT',
            'data': {
                'respondent': itemRespondentId,
                '_token': $me.data('token')
            },
            success: function() {
                const badge = itemRespondent.closest('.tab-content').prev().find('.active .badge');
                badge.text(parseInt(badge.text()) - 1);

                if(itemRespondent.closest('.slick-slider').find('.slick-slide').length === 1) {
                    itemRespondent.closest('.slick-slider').next().removeClass('hidden');
                }

                $('#matches-container').empty().hide();
                itemRespondent.closest('.slick-slider').slick('slickRemove', itemRespondent.closest('.slick-slide').attr('data-slick-index')).slick('refresh');

                $('#menu-items-count').text(parseInt($('#menu-items-count').text()) + 1).removeClass('hidden');
            }
        });
    });

    if($('.nav-tabs-load').length) {
        var $tab = $('.nav-tabs-load .active a');
        loadTabTemplate($tab);
    }

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        $('.tab-content .tab-pane').empty();
        $('#matches-container').empty().hide();
        loadTabTemplate($(this));
    });
});

function loadTabTemplate($tab) {
    $.ajax({
        url: $tab.data('load'),
        method: 'GET',
        beforeSend: function() {
            $($tab.attr('href')).empty();
        },
        success: function(data) {
            $($tab.attr('href')).html(data.template);
            initializeSlider();
        }
    });
}

function initializeSlider() {
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
}