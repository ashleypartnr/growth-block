/** @type {import('tailwindcss').Config} */
module.exports = {
	content: [ './src/blocks/impact-showcase/**/*.{js,jsx,php}' ],
	theme: {
		extend: {
			fontFamily: {
				title: [ 'Cormorant Garamond', 'Georgia', 'serif' ],
				body: [ 'Montserrat', 'system-ui', 'sans-serif' ],
			},
		},
	},
	plugins: [],
};
