# Modules Directory

This directory contains modular feature components for the application.

## Structure

Each module should be organized in its own folder with related files:

```
modules/
├── users/
│   ├── User.php          # User model/class
│   ├── user_list.php     # List view
│   └── user_form.php     # Form view
├── posts/
│   ├── Post.php
│   └── ...
└── ...
```

## Creating a Module

1. Create a new folder for your module
2. Create a PHP class file for business logic
3. Create view files as needed
4. Follow the existing User module as an example

## Best Practices

- Keep related functionality together
- Use classes for models and business logic
- Separate views from logic
- Use prepared statements for database queries
- Validate and sanitize all inputs
