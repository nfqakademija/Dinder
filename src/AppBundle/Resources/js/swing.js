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
        cards: [],
        more: true
    };

    componentWillMount() {
        fetch(fetchUrl, {
            credentials: 'same-origin'
        })
            .then(res => res.json())
            .then((json) => this.setState({cards: json}))
    }

    acceptCard = () => {
        const cards = this.refs.stack.refs;
        const target = cards[Object.keys(cards)[Object.keys(cards).length - 1]];
        const el = ReactDOM.findDOMNode(target);
        const card = this.state.stack.getCard(el);

        card.throwOut(300, 0);
    };

    rejectCard = () => {
        const cards = this.refs.stack.refs;
        const target = cards[Object.keys(cards)[Object.keys(cards).length - 1]];
        const el = ReactDOM.findDOMNode(target);
        const card = this.state.stack.getCard(el);

        card.throwOut(-300, 0);
    }

    throwOut = (e) => {
        const el = ReactDOM.findDOMNode(e.target);
        const card = this.state.stack.getCard(el);
        const newSet = this.state.cards;

        // throwOut is triggered twice and second time we don't have card element
        if(card) {
            newSet.pop();

            this.setState({cards: newSet});

            card.destroy();

            const params = {
                'item': itemId,
                'respondent': e.target.dataset.id,
                'status': Swing.DIRECTION.RIGHT === e.throwDirection ? 1 : 0
            };

            const urlParams = new URLSearchParams(Object.entries(params));

            fetch(matchUrl + '?' + urlParams, {
                credentials: 'same-origin'
            }).then(() => {
                if (this.state.more && this.state.cards.length <= 3) {
                    fetch(fetchUrl, {
                        credentials: 'same-origin'
                    })
                        .then(res => res.json())
                        .then(json => {
                            let more = false;

                            for(let i = 0; i < json.length; i++) {
                                let exist = false;

                                for(let j = 0; j < newSet.length; j++) {
                                    if(newSet[j].id === json[i].id) {
                                        exist = true;
                                    }
                                }

                                if(!exist) {
                                    newSet.unshift(json[i]);
                                    more = true;
                                }
                            }

                            this.setState({cards: newSet, more: more});
                        })
                    // .then(json => this.setState({cards: [...json, ...this.state.cards]}))
                }
            });

            // stack is undefined and destroyCard is event not method
            // don't know what this was supposed to do
            // this.setState({stack: stack.destroyCard(card)});

            // console.log(this.state.cards.length);
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
                            return <MyCard key={i} index={i} onThrow={(e) => console.log(e)} card={c}/>
                        })}
                    </Swing>
                </div>
                <div className="control">
                    <button type="button" onClick={this.rejectCard}>Reject item</button>
                    <button type="button" onClick={this.acceptCard}>Offer match</button>
                </div>
            </div>
        )
    }
}