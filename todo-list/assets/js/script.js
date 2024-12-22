jQuery(document).ready(function ($) {
	// Add a new task
	$('#todo-form').on('submit', function (e) {
		e.preventDefault();

		const task = $('#todo-task').val();

		if (!task.trim()) {
			alert('Please enter a task.');
			return;
		}

		$.ajax({
			url: todo_ajax.ajax_url,
			method: 'POST',
			data: {
				action: 'add_todo_item',
				task: task,
				nonce: todo_ajax.nonce
			},
			success: function (response) {
				if (response.success) {
					$('#todo-list').append(
						'<li data-id="' +
							response.data.id +
							'">' +
							'<span class="task-text">' +
							response.data.task +
							'</span> ' +
							'<button class="edit-task">Edit</button> ' +
							'<button class="delete-task">Delete</button></li>'
					);
					$('#todo-task').val('');
				} else {
					alert('Failed to add task.');
				}
			},
			error: function () {
				alert('An error occurred.');
			}
		});
	});

	// Edit a task
	$(document).on('click', '.edit-task', function () {
		const listItem = $(this).closest('li');
		const taskId = listItem.data('id');
		const taskText = listItem.find('.task-text').text();

		// Replace task text with an input field
		listItem.html(
			'<input type="text" class="edit-input" value="' +
				taskText +
				'">' +
				'<button class="save-task">Save</button>'
		);
	});

	// Save the edited task
	$(document).on('click', '.save-task', function () {
		const listItem = $(this).closest('li');
		const taskId = listItem.data('id');
		const newTask = listItem.find('.edit-input').val();

		if (!newTask.trim()) {
			alert('Task cannot be empty.');
			return;
		}

		$.ajax({
			url: todo_ajax.ajax_url,
			method: 'POST',
			data: {
				action: 'edit_todo_item',
				id: taskId,
				task: newTask,
				nonce: todo_ajax.nonce
			},
			success: function (response) {
				if (response.success) {
					listItem.html(
						'<span class="task-text">' +
							newTask +
							'</span>' +
							' <button class="edit-task">Edit</button>' +
							' <button class="delete-task">Delete</button>'
					);
				} else {
					alert('Failed to update task.');
				}
			},
			error: function () {
				alert('An error occurred.');
			}
		});
	});

	// Delete a task
	$(document).on('click', '.delete-task', function () {
		const listItem = $(this).closest('li');
		const taskId = listItem.data('id');

		if (!confirm('Are you sure you want to delete this task?')) {
			return;
		}

		$.ajax({
			url: todo_ajax.ajax_url,
			method: 'POST',
			data: {
				action: 'delete_todo_item',
				id: taskId,
				nonce: todo_ajax.nonce
			},
			success: function (response) {
				if (response.success) {
					listItem.remove();
				} else {
					alert(response.data.message || 'Failed to delete task.');
				}
			},
			error: function () {
				alert('An error occurred while deleting the task.');
			}
		});
	});
});
