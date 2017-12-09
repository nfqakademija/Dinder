/**
 * Sample usage:
 *
 * <a href="/blog/48/delete" data-method="DELETE">delete this post</a>
 */
$(document).ready(function () {

    // Every link with an attribute data-method
    $("a[data-method]").click(function (event) {
        event.preventDefault();

        var target = $(event.currentTarget);
        var action = target.attr('href');
        var _method = target.attr('data-method');

        // Create a form on click
        var form = $('<form/>', {
            style:  "display:none;",
            action: action,
            method: 'POST'
        });

        form.append('<input type="hidden" name="_method" value="'+ _method +'">');

        form.appendTo(target);

        // Submit the form
        form.submit();
    });
});
