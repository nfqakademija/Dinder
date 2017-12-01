import React from 'react';

const Card = ({index, onThrow, card}) => (
    <div className="card" data-id={index} ref={`card${index}`} throwout={onThrow}>
        {console.log(card)}
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

export default Card;