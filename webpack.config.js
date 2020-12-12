// Load the default @wordpress/scripts config object
const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );

// Use the defaultConfig but replace the entry and output properties
module.exports = {
	...defaultConfig,
	entry: {
		admin: './assets/src/admin/index.js'
	},
	output: {
		filename: '[name].js',
		path: __dirname + '/assets/build',
	},
};
