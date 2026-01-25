/**
 * Jest configuration for GreenGrowth Impact Showcase.
 */

module.exports = {
	testEnvironment: 'jsdom',
	roots: [ '<rootDir>/src' ],
	testMatch: [ '**/__tests__/**/*.js', '**/?(*.)+(spec|test).js' ],
	moduleNameMapper: {
		'\\.(css|scss)$': '<rootDir>/tests/mocks/styleMock.js',
	},
	transform: {
		'^.+\\.jsx?$': 'babel-jest',
	},
	collectCoverageFrom: [
		'src/**/*.js',
		'!src/index.js',
		'!src/**/*.test.js',
		'!src/**/__tests__/**',
	],
	coverageThreshold: {
		global: {
			branches: 70,
			functions: 70,
			lines: 70,
			statements: 70,
		},
	},
	setupFilesAfterEnv: [ '<rootDir>/tests/setup.js' ],
	globals: {
		wp: {
			i18n: {
				__: ( text ) => text,
				_x: ( text ) => text,
				sprintf: ( format, ...args ) => {
					if ( args.length === 0 ) {
						return format;
					}

					return format.replace( /%s/g, () =>
						String( args.shift() ?? '' )
					);
				},
			},
		},
	},
};
