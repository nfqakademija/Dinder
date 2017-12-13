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
            this.setState({loading: false});
        })
    }

    componentWillReceiveProps(nextProps) {
        if (!this.state.loading && this.state.more && nextProps.cards.length <= 3) {

            this.setState({loading: true});

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
                { this.props.cards.length ?
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
                                {this.props.cards.map((c, i) => {
                                    return <ItemCard key={i} index={i} onThrow={(e) => console.log(e)} card={c} />
                                })}
                            </Swing>
                        </div>
                        <div className="row">
                            <div className="col-xs-6">
                                <button type="button" className="btn btn-primary btn-block" onClick={this.rejectCard}>
                                    <i className="fa fa-thumbs-o-down" aria-hidden="true"></i> Reject item
                                </button>
                            </div>
                            <div className="col-xs-6">
                                <button type="button" className="btn btn-default btn-block" onClick={this.acceptCard}>
                                    <i className="fa fa-thumbs-o-up" aria-hidden="true"></i> Offer match
                                </button>
                            </div>
                        </div>
                    </div>
                    :
                    <div className="alert alert-info">
                        <strong>Sorry, there are no items to show.</strong><br/>
                        You may tray changing the categories or search later?
                    </div>
                }
            </div>
        )
    }
}
