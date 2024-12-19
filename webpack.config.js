const path = require('path')
const MiniCssExtractPlugin = require('mini-css-extract-plugin')
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin')
const { WebpackManifestPlugin } = require('webpack-manifest-plugin')
const  { CleanWebpackPlugin }  =  require ( 'clean-webpack-plugin' )
const ImageMinimizerPlugin = require("image-minimizer-webpack-plugin");
const TerserPlugin = require("terser-webpack-plugin");
const autoprefixer = require('autoprefixer');

module.exports = {
    entry: {
      app: ['./assets/css/index.scss', './assets/index.js']
      // admin: ['./assets/css/admin.scss', './assets/admin.js']
    },
    watch: true,
    output: {
        path: path.resolve(__dirname, 'public/dist'), 
        filename: '[name].js',
    },
    module: {
        rules: [
            {
                test: /\.(?:js|mjs|cjs)$/,
                exclude: /node_modules/,
                use: {
                loader: 'babel-loader',
                options: {
                    presets: [
                    ['@babel/preset-env', { targets: "defaults" }]
                    ]
                }
                }
            },
            {
                test: /\.css$/i,
                use: [MiniCssExtractPlugin.loader, "css-loader"],
            },
            {
                test: /\.s[ac]ss$/i,
                use: [
                {loader: MiniCssExtractPlugin.loader},
                {loader: "css-loader"},
                {
                    loader: "postcss-loader",
                    options: {
                    postcssOptions: {
                        plugins: [
                        autoprefixer
                        ]
                    }
                    }
                },
                {loader: "sass-loader"}
                ]
            },
            {
                test: /\.(jpe?g|png|gif|svg)$/i,
                type: "asset/resource",
            },
            {
                test: /\.(woff|woff2|eot|ttf|otf)$/i,
                type: "asset/inline"
            }
        ], 
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: '[name].css',
        }),
        new WebpackManifestPlugin(),
        new CleanWebpackPlugin({
            dry: false,
            verbose: true,
        }),
    ],
    optimization: {
        minimize: true,
        minimizer: [
            new CssMinimizerPlugin(),
            new TerserPlugin({
            terserOptions: {
                ecma: 5,
            }
            }),
            new ImageMinimizerPlugin({
                minimizer: {
                    implementation: ImageMinimizerPlugin.sharpMinify,
                    options: {
                        encodeOptions: {
                        jpeg: {
                            // https://sharp.pixelplumbing.com/api-output#jpeg
                            quality: 100,
                        },
                        webp: {
                            // https://sharp.pixelplumbing.com/api-output#webp
                            lossless: true,
                        },
                        avif: {
                            // https://sharp.pixelplumbing.com/api-output#avif
                            lossless: true,
                        },

                        // png by default sets the quality to 100%, which is same as lossless
                        // https://sharp.pixelplumbing.com/api-output#png
                        png: {},

                        // gif does not support lossless compression at all
                        // https://sharp.pixelplumbing.com/api-output#gif
                        gif: {},
                        },
                    },
                },
            }),
        ],
    }
}