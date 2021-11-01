const path = require( 'path' );
const MiniCssExtractPlugin = require( 'mini-css-extract-plugin' );
const CssMinimizerPlugin = require( 'css-minimizer-webpack-plugin' );
const { CleanWebpackPlugin } = require( 'clean-webpack-plugin' );
const UglifyJsPlugin = require( 'uglifyjs-webpack-plugin' );

/**
 * Paths
 */


const assetsFolder = 'assets';

const mainAssets = path.resolve(
	__dirname,
	`${ assetsFolder }`
);
const entry = {
    'test': [__dirname + `/${ assetsFolder }/src/test.js`],
    'main': [__dirname + `/${ assetsFolder }/src/index.js`],
    'survey': [__dirname + `/${ assetsFolder }/src/survey.js`],
    'multistep-survey': [__dirname + `/${ assetsFolder }/src/multistep-survey.js`],
}
const mainDistentation = path.resolve(
	__dirname,
	`${ assetsFolder }/dist`
);
const output = {
	filename: '[name].js',
	path: mainDistentation,
};

const rules = [
	{
		test: /\.(png|jpe?g|gif|ico)$/i,
		exclude: /node_modules/,
		use: [
			{
				loader: 'file-loader',
			},
		],
	},
	{
		test: /\.(woff2?|ttf|otf|eot|svg)$/,
		exclude: /node_modules/,
		loader: 'file-loader',
		options: {
			name: '[path][name].[ext]',
		},
	},
	{
		test: /\.scss$/,
		exclude: /node_modules/,
		use: [
			MiniCssExtractPlugin.loader,
			'css-loader',
			'resolve-url-loader',
			'sass-loader',
		],
	},
	{
		test: /\.js$/,
		exclude: /node_modules/,
		use: {
			loader: 'babel-loader',
			options: {
				presets: [ [ '@babel/preset-env', { targets: 'defaults' } ] ],
			},
		},
	},
];

const minimizing = [
	new CssMinimizerPlugin( {
		parallel: true,
		minimizerOptions: {
			preset: [
				'default',
				{
					discardComments: { removeAll: true },
				},
			],
		},
	} ),

	new UglifyJsPlugin( {
		extractComments: true,
		cache: false,
		parallel: true,
		sourceMap: false,
	} ),
];

/**
 * Note: argv.mode will return 'development' or 'production'.
 *
 * @param argv
 */
const plugins = ( argv ) => [
	new CleanWebpackPlugin( {
		cleanStaleWebpackAssets: 'production' === argv.mode, // Automatically remove all unused webpack assets on rebuild, when set to true in production. ( https://www.npmjs.com/package/clean-webpack-plugin#options-and-defaults-optional )
	} ),

	new MiniCssExtractPlugin(),
];

/**
 * Module Exports
 *
 * @param env
 * @param argv
 */
module.exports = ( env, argv ) => ( {
	mode: 'development',
	context: mainAssets,
    entry,
	output,
	devtool: 'source-map',
	optimization: {
		minimize: true,
		minimizer: minimizing,
	},
	plugins: plugins( argv ),
	module: {
		rules,
	},
} );
