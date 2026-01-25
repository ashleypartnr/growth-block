module.exports = {
	extends: [ 'plugin:@wordpress/eslint-plugin/recommended' ],
	env: {
		browser: true,
		es2021: true,
	},
	globals: {
		wp: 'readonly',
	},
	overrides: [
		{
			files: [
				'jest.config.js',
				'postcss.config.js',
				'tailwind.config.js',
				'webpack.config.js',
			],
			env: {
				node: true,
			},
		},
		{
			files: [
				'**/__tests__/**/*.js',
				'**/?(*.)+(spec|test).js',
				'tests/**/*.js',
			],
			env: {
				jest: true,
				browser: true,
			},
			globals: {
				wp: 'readonly',
			},
		},
	],
};
