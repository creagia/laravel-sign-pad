const path = require('path');

module.exports = {
    mode: 'development',
    entry: './resources/assets/sign-pad.js',
    output: {
        path: path.resolve(__dirname, 'resources/dist'),
        filename: 'sign-pad.min.js',
    },
    optimization: {
        minimize: true,
    }
};