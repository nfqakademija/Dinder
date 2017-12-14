import React from 'react';

export default class ItemCard extends React.Component {

    render() {
        return (
            <div className="card" data-id={this.props.card.id} ref={`card${this.props.index}`}>
                <div className="item-card">
                    <div className="item-header">
                        <div className="overlay-match"></div>
                        <div className="overlay-reject"></div>

                        <img className="img-responsive" src={this.props.card.image} alt={this.props.card.title} />

                        <div className="item-description">
                            {this.props.card.description}
                        </div>
                    </div>
                    <div className="item-content">
                        <div className="item-content-header">
                            <h3 className="item-title">{this.props.card.title}</h3>
                        </div>
                        <div className="row">
                            <div className="col-xs-12 col-md-4">
                                <div className="item-value">
                                    <span>{this.props.card.value}€</span>
                                </div>
                            </div>
                            <div className="col-xs-12 col-md-8">
                                <div className="item-category">
                                    <span>{this.props.card.category}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        )
    }
}
