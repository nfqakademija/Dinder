const webpack = require('webpack');
const path = require('path');
const ExtractTextPlugin = require("extract-text-webpack-plugin");

const extractSass = new ExtractTextPlugin({
    filename: "css/style.css"
});

module.exports = {
    entry: {
        app: [
            path.resolve(__dirname, './src/AppBundle/Resources/scss/main.scss'),
            path.resolve(__dirname, './src/AppBundle/Resources/js/index.js'),
            path.resolve(__dirname, './src/AppBundle/Resources/js/item.js'),
            path.resolve(__dirname, './src/AppBundle/Resources/js/modal.js'),
            path.resolve(__dirname, './src/AppBundle/Resources/js/main.js'),
        ],
        vendor: [
            'react',
            'react-dom',
            path.resolve(__dirname, './src/AppBundle/Resources/js/custom.js')
        ]
    },
    output: {
        path: path.resolve('./web/build'),
        filename: 'js/bundle.js'
    },
    module: {
        rules: [
            {
                test: /\.jsx?$/,
                loader: 'babel-loader',
                exclude: /node_modules/,
                options: {
                    presets: ["es2015", "stage-0", "react"]
                }
            },
            {
                test: /\.scss$/,
                use: extractSass.extract({
                    use: [{
                        loader: 'css-loader', options: { url: false }
                    }, {
                        loader: "sass-loader"
                    }],
                    // use style-loader in development
                    fallback: "style-loader"
                })
            },
            {
                test: /\.css$/,
                use: extractSass.extract({
                    use: [{
                        loader: "css-loader"
                    }],
                    fallback: "style-loader"
                })
            },
            {
                test: /\.(png|woff|woff2|eot|ttf|svg)$/,
                loader: 'url-loader?limit=100000'
            }
        ]
    },
    plugins: [
        new webpack.optimize.CommonsChunkPlugin({
            name: "vendor",
            filename: "js/vendor.js",
            minChunks: Infinity
        }),
        extractSass,
    ]
};
