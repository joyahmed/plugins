import Dashboard from './components/Dashboard';
import Navbar from './components/Navbar';
import Reports from './components/Reports';
import Settings from './components/Settings';

const App = () => (
	<Router>
		{/* <Navbar /> */}
		<Routes>
			<Route path='/' element={<Dashboard />} />
			<Route path='/reports' element={<Reports />} />
			<Route path='/settings' element={<Settings />} />
			<Route path='*' element={<Navigate to='/' />} />
		</Routes>
	</Router>
);

export default App;
