"use strict";

import React from 'react';
import Swinger from "./Swinger";

export default class AppContainer extends React.Component {
    constructor(props) {
        super(props);

        this.updateCards = this.updateCards.bind(this);

        this.state = {
            cards: []
        };
    }

    updateCards(cards) {
        this.setState({
            cards: cards
        });
    }

    render() {
        return (
            <Swinger cards={this.state.cards} updateCards={this.updateCards} />
        )
    }
}