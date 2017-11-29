"use strict";

$(function() {
    $('.link-exchange').click(function() {
        var $me = $(this);
        itemId = $me.data('id');

        $.ajax({
            url: $me.attr('href'),
            success: function() {
                $('#exchangeModal').modal('show');
            }
        });

        return false;
    });
});