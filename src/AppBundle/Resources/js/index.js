"use strict";

import React from 'react';
import ReactDOM from 'react-dom';
import Swinger from "./Swing";

$(function() {
    $('.link-exchange').click(function() {
        itemId = $(this).data('id');
        fetchUrl = $(this).attr('href');

        $('#exchangeModal').modal('show');

        return false;
    });

    $('#exchangeModal').on('show.bs.modal', function (e) {
        ReactDOM.render(
            <Swinger />,
            document.getElementById('swing')
        );
    }).on('hide.bs.modal', function() {
        ReactDOM.unmountComponentAtNode(document.getElementById('swing'));
    });
});