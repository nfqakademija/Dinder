const path = require('path');

module.exports = {
    entry: './src/AppBundle/Resources/js/index.js',
    output: {
        path: path.resolve('./web/build'),
        filename: 'bundle.js'
    },
    module: {
        loaders: [
            {
                test: /\.jsx?$/,
                loader: 'babel-loader',
                exclude: /node_modules/,
                options: {
                    presets: ["es2015", "stage-0", "react"]
                }
            }
        ]
    }
};