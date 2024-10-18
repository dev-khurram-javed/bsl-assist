module.exports = {
    mode: 'production',
    devtool: 'source-map',
    output: {
        filename: '[name].js',
    },
    stats: {
        colors: true,
        hash: false,
        version: false,
        timings: false,
        assets: false,
        chunks: false,
        modules: false,
        reasons: false,
        children: false,
        source: false,
        warnings: false,
        publicPath: false,
    },
};