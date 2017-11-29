import React from 'react';
import ReactDOM from 'react-dom';
import Swing, { Stack, Card, Direction } from 'react-swing';

export default class Swinger extends React.Component {
    config = {
        allowedDirections: [Swing.DIRECTION.LEFT, Swing.DIRECTION.RIGHT]
    };

    state = {
        stack: null
    };

    throwCard = () => {
        // Swing Card Directions
        console.log('Swing.DIRECTION', Swing.DIRECTION);

        // Swing Component Childrens refs
        const target = this.refs.stack.refs.card2;

        // get Target Dom Element
        const el = ReactDOM.findDOMNode(target);

        // stack.getCard
        const card = this.state.stack.getCard(el);

        // throwOut method call
        card.throwOut(100, 200, Swing.DIRECTION.RIGHT);
    };

    throwOut = (e) => {
        const el = ReactDOM.findDOMNode(e.target);
        const card = this.state.stack.getCard(el);
        card.destroy();
        el.parentNode.removeChild(el);

        $.ajax({
            url: matchUrl,
            data: {
                'item': itemId,
                'respondent': e.target.dataset.id,
                'status': Swing.DIRECTION.RIGHT === e.throwDirection ? 1 : 0
            },
            success: function(data) {
                console.log(data);
            }
        });
    }

    render() {
        return (
            <div>
                <div id="viewport">
                    <Swing
                        config={this.config}
                        className="stack"
                        tagName="div"
                        setStack={(stack) => this.setState({stack:stack})}
                        ref="stack"
                        throwout={(e) => this.throwOut(e)}
                    >
                        {/*
                            children elements is will be Card
                        */}
                        <div className="card clubs" data-id="1" ref="card1" throwout={(e)=>console.log('card throwout',e)}>♣</div>
                        <div className="card diamonds" data-id="2" ref="card2">♦</div>
                        <div className="card hearts" data-id="3" ref="card3">♥</div>
                        <div className="card spades" data-id="4" ref="card4">♠</div>
                    </Swing>
                </div>
                <div className="control">
                    <button type="button" onClick={this.throwCard}>throw Card</button>
                </div>
            </div>
        )
    }
}