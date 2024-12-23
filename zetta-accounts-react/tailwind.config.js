/** @type {import('tailwindcss').Config} */
module.exports = {
	content: [
		'./src/**/*.{js,jsx,ts,tsx}', // Include all JavaScript and TypeScript files in src
		'./templates/**/*.php', // Include all PHP files in templates
		'./*.php', // Include the main plugin PHP file
		'./build/**/*.js', // Include built JavaScript files
		'./css/**/*.css'
	],
	theme: {
		extend: {}
	},
	plugins: []
};
