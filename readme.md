# To-Do List Plugin Documentation

## Overview

The **To-Do List Plugin** is a custom WordPress plugin that allows users to manage a to-do list directly from the WordPress admin dashboard. Users can add, edit, and delete tasks, with all data stored in the WordPress database.

---

## Features

- Add new tasks to the to-do list.
- Edit existing tasks.
- Delete tasks.
- Data is stored securely in the WordPress database.
- Clean and modern UI with responsive design.

---

## Installation

1. Download or clone the plugin code into your WordPress plugins directory:
   ```
   wp-content/plugins/todo-list/
   ```
2. Activate the plugin from the **WordPress Admin Dashboard** under **Plugins**.

---

## File Structure

```
todo-list/
│
├── includes/
│   └── class-todo-list.php       # Core logic for the To-Do List plugin.
├── assets/
│   ├── styles.css               # Styling for the plugin UI.
│   └── scripts.js               # JavaScript for AJAX functionality.
├── todo-list.php                # Main plugin file.
└── README.md                    # Documentation for the plugin.
```

---

## Usage

### **Access the To-Do List**

1. After activating the plugin, a new menu item **"To-Do List"** will appear in the WordPress admin dashboard.
2. Click on **To-Do List** to open the plugin interface.

### **Add a New Task**

1. Enter the task name in the input field at the top.
2. Click the **"Add Task"** button.

### **Edit a Task**

1. Click the **"Edit"** button next to the task you want to modify.
2. Update the task text in the input field and click **"Save"**.

### **Delete a Task**

1. Click the **"Delete"** button next to the task you want to remove.
2. The task will be removed from the list and the database.

---

## Code Walkthrough

### **Main Plugin File (todo-list.php)**

- Registers the plugin and initializes the core logic:

  ```php
  // Includes core class
  require_once TODO_PLUGIN_PATH . 'includes/class-todo-list.php';

  // Initialize the plugin
  function todo_list_init() {
      $plugin = new Todo_List();
      $plugin->run();
  }
  add_action('plugins_loaded', 'todo_list_init');
  ```

### **Core Class File (class-todo-list.php)**

- Handles database operations (add, edit, delete tasks).
- AJAX handlers for real-time task management:

  ```php
  public function add_todo_item() {
      // Adds a task to the database.
  }

  public function edit_todo_item() {
      // Edits a task in the database.
  }

  public function delete_todo_item() {
      // Deletes a task from the database.
  }
  ```

### **JavaScript File (scripts.js)**

- Adds interactivity with AJAX:

  ```javascript
  // Add Task
  $('#todo-form').on('submit', function (e) {
  	// Sends a POST request to add a new task.
  });

  // Edit Task
  $(document).on('click', '.edit-task', function () {
  	// Enables editing of a task in the list.
  });

  // Delete Task
  $(document).on('click', '.delete-task', function () {
  	// Sends a request to delete the selected task.
  });
  ```

### **CSS File (styles.css)**

- Provides modern and responsive styles for the UI:

  ```css
  #todo-form button {
  	background-color: #007bff;
  	color: #fff;
  }

  #todo-list li {
  	padding: 15px;
  	border: 1px solid #ddd;
  }
  ```

---

## AJAX Endpoints

### Add Task

- **Action:** `wp_ajax_add_todo_item`
- **Handler:** `Todo_List::add_todo_item`

### Edit Task

- **Action:** `wp_ajax_edit_todo_item`
- **Handler:** `Todo_List::edit_todo_item`

### Delete Task

- **Action:** `wp_ajax_delete_todo_item`
- **Handler:** `Todo_List::delete_todo_item`

---

## FAQ

### Why isn’t the table created automatically?

Make sure the `register_activation_hook` is properly configured in `todo-list.php`. This ensures that the table is created when the plugin is activated.

### How can I change the database table prefix?

Update the `$wpdb->prefix` variable in `class-todo-list.php` if needed.

### Can I extend the plugin?

Yes! You can extend the plugin by adding new methods in `class-todo-list.php` or new AJAX actions in `scripts.js`.

---

## Future Enhancements

- Add task priority levels (e.g., Low, Medium, High).
- Enable task due dates.
- Add filtering and sorting options for tasks.

---

## Support

I just created it for learning purpose 😊
If you encounter any issues or have suggestions, please feel free to reach out. Happy coding! 🎉
