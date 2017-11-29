import React from 'react';

export default class Item extends React.Component {
    render() {
        return (
            <div className="category-grid-box" data-id={this.props.id}>
                <div className="category-grid-img">
                    <img src={this.props.image} alt={this.props.title} />
                    <p className="item-description">{this.props.description}</p>
                </div>
                <div className="short-description">
                    <div className="category-title">{this.props.category}</div>
                    <h3>{this.props.title}</h3>
                    <div className="price">{this.props.value}€</div>
                </div>
            </div>
        )
    }
}
