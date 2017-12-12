import React from 'react';
import ReactDOM from "react-dom";

export default class ItemCard extends React.Component {

    imageLoaded({target:img}) {
        const viewport = document.getElementById('viewport');
        let height = 120;

        setTimeout(function () {
            if(img.closest('.card').offsetHeight > height) {
                height = img.closest('.card').offsetHeight;
            }

            viewport.style.height = (height + 20) + 'px';
        }, 100);
    }

    render() {
        return (
            <div className="card" data-id={this.props.card.id} ref={`card${this.props.index}`}>
                <div className="item-card">
                    <div className="item-header">
                        <img className="img-responsive" onLoad={this.imageLoaded} src={this.props.card.image} alt={this.props.card.title} />

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
