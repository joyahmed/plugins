const browserSync = require('browser-sync').create();
const { exec } = require('child_process');

// Paths to your plugin files
const pluginPath = './**/*'; // Monitor everything inside the plugin
const cssInputPath = './src/globals.css'; // Tailwind input file
const cssOutputPath = './css/tailwind.min.css'; // Tailwind output file
const cssWatchPath = './css/**/*.css'; // CSS output to watch
const tailwindBuildCommand = `pnpm tailwindcss -i ${cssInputPath} -o ${cssOutputPath} --minify`;

// Run the Tailwind build command initially
const runTailwindBuild = () => {
	exec(tailwindBuildCommand, (err, stdout, stderr) => {
		if (err) {
			console.error(`Tailwind build error: ${stderr}`);
		} else {
			console.log(`Tailwind built: ${stdout}`);
		}
	});
};

// Initialize Browsersync
browserSync.init({
	proxy: {
		target: 'http://plugin-dev.local/wp-admin', // Proxy the WordPress site
		middleware: (req, res, next) => {
			req.headers['accept-encoding'] = 'identity'; // Avoid compression issues
			next();
		}
	},
	serveStatic: ['./'], // Serve static files
	files: ['./**/*.php'], // Watch PHP files for changes
	open: true,
	notify: true,
	injectChanges: true, // Inject CSS changes without full reload
	logLevel: 'debug' // Detailed logging for troubleshooting
});

// Watch for changes in Tailwind input file and rebuild
browserSync.watch(cssInputPath).on('change', () => {
	console.log('Tailwind input file changed. Rebuilding...');
	runTailwindBuild();
});

// Watch the compiled CSS and inject changes
browserSync.watch(cssWatchPath).on('change', file => {
	console.log(`CSS changed: ${file}`);
	browserSync.reload('*.css'); // Inject CSS changes
});

// Watch other plugin changes (non-CSS files) and reload the browser
browserSync.watch(pluginPath).on('change', file => {
	if (!file.endsWith('.css')) {
		console.log(`File changed: ${file}`);
		browserSync.reload(); // Reload the entire page
	}
});

// Run Tailwind build initially
runTailwindBuild();
