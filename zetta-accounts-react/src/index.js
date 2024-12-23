import { render } from '@wordpress/element';
// import App from './App';

// // Render the App component into the DOM
// render(<App />, document.getElementById('zetta-accounts-root'));

import React from 'react';
import ReactDOM from 'react-dom';
import Dashboard from './components/Dashboard';
import Reports from './components/Reports';
import Settings from './components/Settings';
import Transactions from './components/Transactions';

const root = document.getElementById('zetta-accounts-root');
const page = root ? root.getAttribute('data-page') : null;

if (root) {
	let Component;

	switch (page) {
		case 'dashboard':
			Component = Dashboard;
			break;
		case 'transactions':
			Component = Transactions;
			break;
		case 'reports':
			Component = Reports;
			break;
		case 'settings':
			Component = Settings;
			break;
		default:
			Component = () => <div>Page Not Found</div>;
	}

	render(<Component />, root);
}
