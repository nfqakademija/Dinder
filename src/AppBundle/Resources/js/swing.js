import React from 'react';
import ReactDOM from 'react-dom';
import Swing, {Stack, Card, Direction} from 'react-swing';
import MyCard from './card';

export default class Swinger extends React.Component {
    config = {
        allowedDirections: [Swing.DIRECTION.LEFT, Swing.DIRECTION.RIGHT]
    };

    state = {
        stack: null,
        cards: []
    };

    componentWillMount() {
        fetch(fetchUrl, {
            credentials: 'same-origin'
        })
            .then(res => res.json())
            .then((json) => this.setState({cards: json}))
    }

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
        const newSet = this.state.cards;

        newSet.unshift();

        this.setState({cards: newSet});

        card.destroy();
        el.parentNode.removeChild(el);

        $.ajax({
            url: matchUrl,
            data: {
                'item': itemId,
                'respondent': e.target.dataset.id,
                'status': Swing.DIRECTION.RIGHT === e.throwDirection ? 1 : 0
            },
            success: function (data) {
                console.log(data);
            }
        });

        this.setState({stack: stack.destroyCard(card)});

        if (this.state.cards.length <= 3) {
            fetch(fetchUrl, {
                credentials: 'same-origin'
            })
                .then(res => res.json())
                .then(json => this.setState({cards: [...this.state.cards, ...json]}))
        }
    }

    render() {
        return (
            <div>
                <div id="viewport">
                    <Swing
                        config={this.config}
                        className="stack"
                        tagName="div"
                        setStack={(stack) => this.setState({stack: stack})}
                        ref="stack"
                        throwout={(e) => this.throwOut(e)}
                    >
                        {this.state.cards.map((c, i) => {
                            return <MyCard index={i} onThrow={(e) => console.log(e)} card={c}/>
                        })}
                    </Swing>
                </div>
                <div className="control">
                    <button type="button" onClick={this.throwCard}>throw Card</button>
                </div>
            </div>
        )
    }
}