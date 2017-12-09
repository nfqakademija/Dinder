import React from 'react';
import ReactDOM from 'react-dom';
import Swing, {Stack, Card, Direction} from 'react-swing';
import ItemCard from './ItemCard';

export default class Swinger extends React.Component {
    config = {
        allowedDirections: [Swing.DIRECTION.LEFT, Swing.DIRECTION.RIGHT]
    };

    state = {
        stack: null,
        more: true,
        loading: true
    };

    componentWillMount() {
        fetch(fetchUrl, {
            credentials: 'same-origin'
        })
        .then(res => res.json())
        .then((json) => {
            this.props.updateCards(json);
            this.setState({loading: false})
        })
    }

    componentWillReceiveProps(nextProps) {
        if (this.state.more && nextProps.cards.length <= 3) {
            fetch(fetchUrl, {
                credentials: 'same-origin'
            })
            .then(res => res.json())
            .then(json => {
                this.setState({loading: false});

                if(json.length) {
                    this.props.updateCards(_.union(json, nextProps.cards));
                } else {
                    this.setState({more: false});
                }
            })
        }
    }

    getLastCard = () => {
        const cards = this.refs.stack.refs;
        const target = cards[Object.keys(cards)[Object.keys(cards).length - 1]];
        const el = ReactDOM.findDOMNode(target);
        const card = this.state.stack.getCard(el);

        return card;
    };

    acceptCard = () => {
        const card = this.getLastCard();

        card.throwOut(300, 0);
    };

    rejectCard = () => {
        const card = this.getLastCard();

        card.throwOut(-300, 0);
    };

    throwOut = (e) => {
        const el = ReactDOM.findDOMNode(e.target);
        const card = this.state.stack.getCard(el);
        const newSet = this.props.cards;

        if(card) {
            newSet.pop();

            card.destroy();

            const params = {
                'item': itemId,
                'respondent': e.target.dataset.id,
                'status': Swing.DIRECTION.RIGHT === e.throwDirection ? 1 : 0
            };

            const urlParams = new URLSearchParams(Object.entries(params));

            fetch(matchUrl + '?' + urlParams, {
                credentials: 'same-origin'
            }).then(
                this.props.updateCards(newSet)
            );
        }
    };

    render() {
        return (
            <div>
                <div id="viewport">
                    { this.state.loading ?
                        <h4>Loading...</h4>
                        :
                        <Swing
                            config={this.config}
                            className="stack"
                            tagName="div"
                            setStack={(stack) => this.setState({stack: stack})}
                            ref="stack"
                            throwout={(e) => this.throwOut(e)}
                        >
                            {this.props.cards.map((c, i) => {
                                return <ItemCard key={i} index={i} onThrow={(e) => console.log(e)} card={c} />
                            })}
                        </Swing>
                    }
                </div>
                <div className="control">
                    <button type="button" onClick={this.rejectCard}>Reject item</button>
                    <button type="button" onClick={this.acceptCard}>Offer match</button>
                </div>
            </div>
        )
    }
}