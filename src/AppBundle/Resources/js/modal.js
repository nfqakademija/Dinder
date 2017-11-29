"use strict";

import React from 'react';
import Item from './item';
import ReactDOM from "react-dom";

$(function() {
    $('.link-exchange').click(function() {
        const $me = $(this);
        itemId = $me.data('id');

        $.ajax({
            url: $me.attr('href'),
            success: function(data) {
                const modal = $('#exchangeModal')
                const body = modal.find('.modal-body').get(0);

                for(let i = 0; i < data.items.length; i++) {
                    ReactDOM.render(
                        <Item {...data.items[i]}/>,
                        body
                    );
                }

                modal.modal('show');
            }
        });

        return false;
    });
});