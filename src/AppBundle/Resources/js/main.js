/**
 * Sample usage:
 *
 * <a href="/blog/48/delete" data-method="DELETE" data-token="...">delete this post</a>
 */
$(document).ready(function () {

    // Every link with an attribute data-method
    $("a[data-method]").click(function (event) {
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

                    $(_target).slideUp(300).promise().done(function () {
                        $(_target).remove();
                        if(_targetParent.children().length === 0) {
                            _targetParent.closest('.offers-block').remove();

                            if($('.offers-block').length === 0) {
                                $('#no-items-left').removeClass('hidden');
                            }
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
});
