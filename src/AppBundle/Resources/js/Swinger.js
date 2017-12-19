import React from 'react';
import ReactDOM from 'react-dom';
import Swing, {Stack, Card, Direction} from 'react-swing';
import vendorPrefix from 'vendor-prefix';
import ItemCard from './ItemCard';

export default class Swinger extends React.Component {
    config = {
        allowedDirections: [Swing.DIRECTION.LEFT, Swing.DIRECTION.RIGHT],
        throwOutConfidence: (xOffset, yOffset, element) => {
            const xConfidence = Math.min(Math.abs(xOffset) / element.offsetWidth * 2.5, 1);
            const yConfidence = Math.min(Math.abs(yOffset) / element.offsetHeight * 2.5, 1);

            return Math.max(xConfidence, yConfidence);
        },
        transform: (element, coordinateX) => {
            element.style[vendorPrefix('transform')] = 'translate3d(0, 0, 0) translate(' + coordinateX + 'px, 0px) rotateY(' + (coordinateX * -0.2) + 'deg)';
        }
    };

    state = {
        stack: null,
        more: true,
        loading: true,
        initialized: false,
        holding: false,
        throwing: false
    };

    componentWillMount() {
        fetch(fetchUrl, {
            credentials: 'same-origin'
        })
            .then(res => res.json())
            .then((json) => {
                this.props.updateCards(json);
                this.setState({loading: false, initialized: true});
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

                    if (!this.state.holding) {
                        if (_.size(json) && _.differenceBy(nextProps.cards, json, 'id').length) {
                            // const unionCards = _.unionBy(nextProps.cards, json, 'id');
                            const unionCards = _.union(_.differenceBy(json, nextProps.cards, 'id'), nextProps.cards);
                            this.props.updateCards(unionCards);
                        } else {
                            this.setState({more: false});
                        }
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
        // explanation: Swing throwout binds events with the dom,
        // if react re-renders, events stay on the dom with the old id bind\ed, but binds a second one with the new id.
        // hence when you throwout, you trigger every throwout that was bind'ed to the container.
        // No good way to handle this without touching the Swing code itself. (shitty port to react, sorry swing)
        // a hack would be to throttle the throwout event.
        // hoping the user will not spam this every 0.5s or less
        // A good approach would be to fork the swing component and fix the issue with the library,
        // a.k.a. binding the throwout to react, not directly to the Stack.
        // there should be no need to do newSet.pop(); card.destroy(); / only card.destroy();
        if (!this.state.throwing) {
            this.setState({throwing: true});
            const el = ReactDOM.findDOMNode(e.target);
            const card = this.state.stack.getCard(el);
            const newSet = [...this.props.cards];

            this.setState({holding: false});

            if (card) {
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
                });

                setTimeout(() => this.setState({throwing: false}), 250);
                this.props.updateCards(newSet);
            }
        }
    };

    render() {

        if (!this.state.initialized) return <span/>;

        return (
            <div>
                {this.props.cards.length ?
                    <div>
                        <div id="viewport">
                            <Swing
                                config={this.config}
                                className="stack"
                                tagName="div"
                                setStack={(stack) => this.setState({stack: stack})}
                                ref="stack"
                                throwout={(e) => this.throwOut(e)}
                                dragstart={(e) => {
                                    this.setState({holding: true});
                                    this.forceUpdate();
                                    e.target.classList.add('moving');
                                }
                                }
                                dragend={(e) => {
                                    e.target.classList.remove('moving');
                                }
                                }
                                dragmove={(e) => {
                                    if (e.throwDirection === Swing.DIRECTION.RIGHT) {
                                        e.target.getElementsByClassName('overlay-reject')[0].style.opacity = 0;
                                        e.target.getElementsByClassName('overlay-match')[0].style.opacity = e.throwOutConfidence;
                                    } else {
                                        e.target.getElementsByClassName('overlay-match')[0].style.opacity = 0;
                                        e.target.getElementsByClassName('overlay-reject')[0].style.opacity = e.throwOutConfidence;
                                    }

                                    if (e.throwOutConfidence >= 1) {
                                        e.target.classList.add('moving-edge');
                                    } else {
                                        e.target.classList.remove('moving-edge');
                                    }
                                }}
                            >
                                {this.props.cards.map((c, i) => {
                                    return <ItemCard key={i} index={i} onThrow={(e) => console.log(e)} card={c}/>
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
                        <strong>Sorry, there are no more items to show.</strong><br/>
                        You may try changing the categories or search later?
                    </div>
                }
            </div>
        )
    }
}
