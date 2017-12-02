import React from 'react';

export default class Card extends React.Component {
    render() {
        return (
            <div className="card" data-id={this.props.card.id} ref={`card${this.props.index}`}>
                <div className="category-grid-box" data-id={this.props.card.id}>
                    <div className="category-grid-img">
                        <img src={this.props.card.image} alt={this.props.card.title} />
                        <p className="item-description">{this.props.card.description}</p>
                    </div>
                    <div className="short-description">
                        <div className="category-title">{this.props.card.category}</div>
                        <h3>{this.props.card.title}</h3>
                        <div className="price">{this.props.card.value}€</div>
                    </div>
                </div>
            </div>
        )
    }
}

/*
const Card = ({index, onThrow, card}) => (
    <div className="card" data-id={index} ref={`card${index}`} throwout={onThrow}>
        <div className="category-grid-box" data-id={card.id}>
            <div className="category-grid-img">
                <img src={card.image} alt={card.title} />
                <p className="item-description">{card.description}</p>
            </div>
            <div className="short-description">
                <div className="category-title">{card.category}</div>
                <h3>{card.title}</h3>
                <div className="price">{card.value}€</div>
            </div>
        </div>
    </div>
);

export default Card;*/
