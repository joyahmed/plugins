# Zetta Accounts Plugin Development Setup

## Overview

This guide outlines the steps we've taken to set up the **Zetta Accounts** WordPress plugin, including Composer configuration, environment setup, and additional tools like Node.js for auto-reloading.

---

## 1. Composer Setup

### **Installation Steps**

1. **Install PHP**

   - Ensure PHP is installed and added to the system PATH.
   - Verify installation with:
     ```bash
     php -v
     ```

2. **Install Composer**
   - Download the installer from [getcomposer.org](https://getcomposer.org/installer).
   - Run the installer with PHP:
     ```bash
     php composer-setup.php
     ```
   - Optionally move `composer.phar` to a global directory for system-wide access:
     ```bash
     move composer.phar C:\Windows\System32\composer
     ```
   - Verify installation:
     ```bash
     composer --version
     ```

---

## 2. Composer Configuration

### **`composer.json` Example**

```json
{
	"name": "zettabyte/zetta-accounts",
	"description": "A robust WordPress accounting plugin.",
	"type": "wordpress-plugin",
	"license": "GPL-2.0-or-later",
	"authors": [
		{
			"name": "Joy",
			"email": "joy@example.com"
		}
	],
	"minimum-stability": "stable",
	"require": {}
}
```

### Key Fields:

- **`name`**: `zettabyte/zetta-accounts`
- **`description`**: Brief overview of the plugin.
- **`type`**: Set to `wordpress-plugin` for WordPress compatibility.
- **`license`**: Use `GPL-2.0-or-later` to align with WordPress.
- **`minimum-stability`**: Default is `stable` for production-ready dependencies.

---

## 3. Node.js for Auto-Reloading

### **Setup Node.js**

1. **Install Node.js**

   - Download and install Node.js from [nodejs.org](https://nodejs.org/).
   - Verify installation:
     ```bash
     node -v
     npm -v
     ```

2. **Install `nodemon` for Auto-Restart**

   ```bash
   npm install -g nodemon
   ```

   Run `nodemon` to watch for changes and restart the PHP server:

   ```bash
   nodemon --exec "php -S localhost:8000" -e php,js,css,html
   ```

3. **Install `browser-sync` for Live Reload**
   ```bash
   npm install -g browser-sync
   ```
   Run `browser-sync` to refresh the browser on changes:
   ```bash
   browser-sync start --proxy "http://localhost" --files "wp-content/plugins/my-plugin/**/*"
   ```

---

## 4. Workflow Summary

### Development Tools:

- **Composer**: Manages PHP dependencies.
- **Node.js Tools**:
  - `nodemon`: Restarts the server automatically on file changes.
  - `browser-sync`: Refreshes the browser for live preview.

### Workflow Example:

1. Run `nodemon` to restart the PHP server:
   ```bash
   nodemon --exec "php -S localhost:8000" -e php,js,css,html
   ```
2. Run `browser-sync` to refresh the browser:
   ```bash
   browser-sync start --proxy "http://localhost:8000" --files "wp-content/plugins/my-plugin/**/*"
   ```
3. Start developing your plugin with auto-reloading enabled!

---

## Next Steps

- Add specific dependencies for the plugin using Composer:
  ```bash
  composer require <package-name>
  ```
- Begin coding the plugin's functionality and structure.
- Test changes in real-time with Node.js tools.

Let me know if you’d like to add or refine anything further!
