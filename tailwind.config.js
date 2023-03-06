/** @type {import('tailwindcss').Config} */
module.exports = {
	content: ["./templates/**/*.html.twig", "./public/js/**/*.js"],
	theme: {
		extend: {
			colors: {
				luna: {
					darker: "#021046",
					dark: "#03217D",
					DEFAULT: "#0838CF",
					light: "#0C82F2",
					white: "#FFF4DF",
					accent: "#FAA816",
				},
			},
		},
	},
	plugins: [],
}
