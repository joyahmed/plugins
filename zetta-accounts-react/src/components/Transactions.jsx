import React, { useEffect, useState } from 'react';

const Transactions = () => {
	const [transactions, setTransactions] = useState([]);
	const [form, setForm] = useState({
		description: '',
		amount: '',
		type: 'income'
	});

	// Fetch transactions
	useEffect(() => {
		fetch('/wp-json/zetta-accounts/v1/transactions')
			.then(res => res.json())
			.then(data => setTransactions(data));
	}, []);

	// Add a transaction
	const addTransaction = e => {
		e.preventDefault();
		fetch('/wp-json/zetta-accounts/v1/transactions', {
			method: 'POST',
			headers: { 'Content-Type': 'application/json' },
			body: JSON.stringify(form)
		})
			.then(res => res.json())
			.then(data => {
				setTransactions([...transactions, { ...form, id: data.id }]);
				setForm({ description: '', amount: '', type: 'income' });
			});
	};

	// Delete a transaction
	const deleteTransaction = id => {
		fetch(`/wp-json/zetta-accounts/v1/transactions/${id}`, {
			method: 'DELETE'
		}).then(() => {
			setTransactions(transactions.filter(t => t.id !== id));
		});
	};

	return (
		<div className='flex flex-col items-center justify-center h-screen w-screen'>
			<h1>Transactions</h1>

			{/* Transaction Form */}
			<form onSubmit={addTransaction}>
				<input
					type='text'
					placeholder='Description'
					value={form.description}
					onChange={e =>
						setForm({ ...form, description: e.target.value })
					}
				/>
				<input
					type='number'
					placeholder='Amount'
					value={form.amount}
					onChange={e => setForm({ ...form, amount: e.target.value })}
				/>
				<select
					value={form.type}
					onChange={e => setForm({ ...form, type: e.target.value })}
				>
					<option value='income'>Income</option>
					<option value='expense'>Expense</option>
				</select>
				<button type='submit'>Add Transaction</button>
			</form>

			{/* Transactions List */}
			<ul>
				{transactions.map(transaction => (
					<li key={transaction.id}>
						{transaction.description} - ${transaction.amount} (
						{transaction.type})
						<button onClick={() => deleteTransaction(transaction.id)}>
							Delete
						</button>
					</li>
				))}
			</ul>
		</div>
	);
};

export default Transactions;
