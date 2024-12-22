const browserSync = require('browser-sync').create();

// Paths to your plugin files
const pluginPath = './**/*'; // Monitor everything inside the plugin
const cssPath = './**/*.css'; // Monitor CSS files inside the plugin

// Initialize Browsersync
browserSync.init({
	proxy: {
		target: 'http://plugin-dev.local/wp-admin', // Proxy the entire WordPress site
		middleware: (req, res, next) => {
			// if (req.url.includes('/wp-admin/')) {
			// }
			req.headers['accept-encoding'] = 'identity'; // Avoid compression issues
			next();
		}
	},
	serveStatic: ['./'], // Serve static files
	files: ['./assets/css/*.css', './**/*.php'], // Watch CSS and PHP files
	open: true,
	notify: true,
	injectChanges: true, // CSS injection without full reload
	logLevel: 'debug' // Enable detailed logging for troubleshooting
});

// Watch CSS changes and inject them
browserSync.watch(cssPath).on('change', file => {
	console.log(`CSS changed: ${file}`);
	browserSync.reload('*.css'); // Reload CSS
});

// Watch other plugin changes and reload the browser
browserSync.watch(pluginPath).on('change', file => {
	if (!file.endsWith('.css')) {
		console.log(`File changed: ${file}`);
		browserSync.reload(); // Reload the entire page
	}
});
