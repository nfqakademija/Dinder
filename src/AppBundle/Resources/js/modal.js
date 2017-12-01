"use strict";

import React from 'react';
import ReactDOM from 'react-dom';
import Swinger from "./swing";

$(function() {
    $('.link-exchange').click(function() {
        itemId = $(this).data('id');
        fetchUrl = $(this).attr('href');

        $('#exchangeModal').modal('show');

        ReactDOM.render(
            <Swinger />,
            document.getElementById('swing')
        );

        return false;
    });
});