"use strict";

import React from 'react';
import ReactDOM from 'react-dom';
import AppContainer from './AppContainer';
import { modalResize } from './helper';

$(function() {
    $('.link-exchange').click(function() {
        itemId = $(this).data('id');
        fetchUrl = $(this).attr('href');

        $('#exchangeModal').modal('show');

        return false;
    });

    $('#exchangeModal').on('show.bs.modal', function () {
        ReactDOM.render(
            <AppContainer />,
            document.getElementById('swing')
        );
    }).on('hide.bs.modal', function() {
        ReactDOM.unmountComponentAtNode(document.getElementById('swing'));
    });

    $(window).resize(function() {
        clearTimeout(window.resizedFinished);

        window.resizedFinished = setTimeout(function(){
            modalResize();
        }, 250);
    });
});
