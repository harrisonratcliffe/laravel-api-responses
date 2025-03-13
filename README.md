<p align="center"><h1 align="center">LARAVEL API HANDLER</h1></p>
<p align="center">
	<em><code>composer require harrisonratcliffe/laravel-api-handler</code></em>
</p>
<p align="center">
	<img src="https://img.shields.io/github/license/harrisonratcliffe/laravel-api-handler?style=default&logo=opensourceinitiative&logoColor=white&color=0080ff" alt="license">
	<img src="https://img.shields.io/github/last-commit/harrisonratcliffe/laravel-api-handler?style=default&logo=git&logoColor=white&color=0080ff" alt="last-commit">
	<img src="https://img.shields.io/github/languages/top/harrisonratcliffe/laravel-api-handler?style=default&color=0080ff" alt="repo-top-language">
	<img src="https://img.shields.io/github/languages/count/harrisonratcliffe/laravel-api-handler?style=default&color=0080ff" alt="repo-language-count">
</p>
<p align="center"><!-- default option, no dependency badges. -->
</p>
<p align="center">
	<!-- default option, no dependency badges. -->
</p>
<br>

## 🔗 Table of Contents

- [📍 Overview](#-overview)
- [👾 Features](#-features)
- [🚀 Getting Started](#-getting-started)
    - [☑️ Prerequisites](#-prerequisites)
    - [⚙️ Installation](#-installation)
    - [🤖 Usage](#🤖-usage)
- [🧪 Testing](#🧪-testing)
- [🔰 Contributing](#-contributing)
- [🎗 License](#-license)

[//]: # (- [🙌 Acknowledgments]&#40;#-acknowledgments&#41;)

---

## 📍 Overview

A Laravel package to easily handle API responses and exceptions.

---

## 👾 Features

- 🌟 Return clean, consistent API responses
- 🛡️ Handle API exceptions with standardized error responses
- 🚀 Easy integration with Laravel 10 and 11

---
## 🚀 Getting Started

### ☑️ Prerequisites

Before getting started with Laravel API Handler, ensure your runtime environment meets the following requirements:

- **Programming Language:** PHP
- **Package Manager:** Composer
- **Laravel Version:** 7 or later


### ⚙️ Installation

Install laravel-api-handler using one of the following methods:

**Build from source:**

1. Install the package via Composer:
```sh
composer require harrisonratcliffe/laravel-api-handler
```

2. Publish the config with:
```sh
php artisan vendor:publish --tag="laravelapihandler-config"
```

## 🔧 Configuration Options

The Laravel API Handler package provides a flexible configuration file that allows you to customize default response messages and behaviors. Let's break down each configuration option:

### 🐞 Debug Mode
```php
'debug_mode' => config('app.debug', false),
```
- **Purpose**: Controls whether detailed error information is exposed
- **Default**: Inherits from Laravel's `app.debug` configuration
- **Security Warning**: 🚨 **Only enable in development environments**
- **Behavior**:
    - When `true`: Potentially exposes sensitive error details
    - When `false`: Provides generic, safe error messages

### 📡 Default Success Response
```php
'success_response' => 'API request processed successfully',
'success_status_code' => 200,
```
- **Success Message**: Customizable default message for successful API requests
- **Status Code**: Standard HTTP 200 OK response
- **Customization**: Easily modify the default success message to match your application's tone

### 🚧 Default Error Messages
```php
'http_not_found' => 'The resource or endpoint cannot be found',
'unauthenticated' => 'You are not authenticated',
'model_not_found' => 'Unable to locate the %s you requested',
'unknown_error' => 'An unknown error occurred',
```

#### Error Message Breakdown
- **`http_not_found`**: Used when a requested endpoint doesn't exist
- **`unauthenticated`**: Triggered for unauthorized access attempts
- **`model_not_found`**: Dynamic message for missing database records
    - Uses a `%s` placeholder for the specific resource type
    - Example: "Unable to locate the User you requested"
- **`unknown_error`**: Fallback message for unexpected errors

### 🛠️ Customization Tips
- Modify the config file located at `config/laravel-api-handler.php`
- Tailor messages to match your application's voice
- Keep error messages informative but not overly technical
- Ensure messages are user-friendly and provide clear guidance

### 🤖 Usage

### Success Responses

```php
// In your controller
use Harrisonratcliffe\LaravelApiHandler\Facades\LaravelApiHandler;

public function index()
{
    $data = User::all();
    return LaravelApiHandler::successResponse(
        'Users retrieved successfully', 
        $data
    );
}
```

#### Example Success Response
```json
{
    "status": "success",
    "message": "Users retrieved successfully",
    "data": [
        {"id": 1, "name": "John Doe"},
        {"id": 2, "name": "Jane Smith"}
    ]
}
```

### Error Responses

```php
public function store()
{
    return LaravelApiHandler::errorResponse(
        'Resource creation failed', 
        422,
        'https://docs.yourapi.com/errors/resource-creation'
    );
}
```

#### Example Error Response
```json
{
    "status": "error",
    "error": {
        "code": 422,
        "message": "Resource creation failed",
        "documentation": "https://docs.yourapi.com/errors/resource-creation"
    }
}
```

### Method Signatures

### `successResponse()`
- `$message` (optional): Custom success message
- `$data` (optional): Response data
- `$statusCode` (optional): HTTP status code (default: 200)

### `errorResponse()`
- `$message` (required): Error description
- `$statusCode` (optional): HTTP error code (default: 400)
- `$documentation` (optional): Error documentation link
- `$debug` (optional): Additional debug information

### API Exception Handler

#### Laravel 11-12

To configure the API Exception Handler on Laravel 11-12, add the following configuration to your `boostrap/app.php` file:
```php
use Harrisonratcliffe\LaravelApiHandler\ApiExceptionHandler;

->withExceptions(function (Exceptions $exceptions) {
        // your other code here
        $exceptions->render(function (Throwable $exception, $request) {
            if ($request->is('api/*')) {
                return app(ApiExceptionHandler::class)->renderApiException($exception);
            }
        });
        // your other code here
    })
```

#### Laravel 7-10

To configure the API Exception Handler on Laravel 7-10, add the following configuration inside your render method of your `app/Exceptions/Handler.php` file:
```php
use Harrisonratcliffe\LaravelApiHandler\ApiExceptionHandler;

public function render($request, Throwable $exception)
    {
    // your other code here
        if ($request->is('api/*')) {
            return app(ApiExceptionHandler::class)->renderApiException($exception);
        }
    // your other code here
    }
```

## 🧪 Testing
Run the test suite using the following command:
**Using `composer`** &nbsp; [<img align="center" src="https://img.shields.io/badge/PHP-777BB4.svg?style={badge_style}&logo=php&logoColor=white" />](https://www.php.net/)

```sh
vendor/bin/pest
```


[//]: # (---)

[//]: # (## 📌 Project Roadmap)

[//]: # ()
[//]: # (- [X] **`Task 1`**: <strike>Implement feature one.</strike>)

[//]: # (- [ ] **`Task 2`**: Implement feature two.)

[//]: # (- [ ] **`Task 3`**: Implement feature three.)

[//]: # ()
[//]: # (---)

## 🔰 Contributing

Contributions are welcome!

- **💬 [Join the Discussions](https://github.com/harrisonratcliffe/laravel-api-handler/discussions)**: Share your insights, provide feedback, or ask questions.
- **🐛 [Report Issues](https://github.com/harrisonratcliffe/laravel-api-handler/issues)**: Submit bugs found or log feature requests for the `laravel-api-handler` project.
- **💡 [Submit Pull Requests](https://github.com/harrisonratcliffe/laravel-api-handler/blob/main/CONTRIBUTING.md)**: Review open PRs, and submit your own PRs.

<details closed>
<summary>Contributing Guidelines</summary>

1. **Fork the Repository**: Start by forking the project repository to your github account.
2. **Clone Locally**: Clone the forked repository to your local machine using a git client.
   ```sh
   git clone https://github.com/harrisonratcliffe/laravel-api-handler
   ```
3. **Create a New Branch**: Always work on a new branch, giving it a descriptive name.
   ```sh
   git checkout -b new-feature-x
   ```
4. **Make Your Changes**: Develop and test your changes locally.
5. **Commit Your Changes**: Commit with a clear message describing your updates.
   ```sh
   git commit -m 'Implemented new feature x.'
   ```
6. **Push to github**: Push the changes to your forked repository.
   ```sh
   git push origin new-feature-x
   ```
7. **Submit a Pull Request**: Create a PR against the original project repository. Clearly describe the changes and their motivations.
8. **Review**: Once your PR is reviewed and approved, it will be merged into the main branch. Congratulations on your contribution!
</details>

<details closed>
<summary>Contributor Graph</summary>
<br>
<p align="left">
   <a href="https://github.com{/harrisonratcliffe/laravel-api-handler/}graphs/contributors">
      <img src="https://contrib.rocks/image?repo=harrisonratcliffe/laravel-api-handler">
   </a>
</p>
</details>

---

## 🎗 License

This project is protected under the [MIT](https://choosealicense.com/licenses/mit) License. For more details, refer to the [LICENSE](https://github.com/harrisonratcliffe/laravel-api-handler/blob/main/LICENSE) file.

[//]: # (---)

[//]: # (## 🙌 Acknowledgments)

[//]: # ()
[//]: # (- List any resources, contributors, inspiration, etc. here.)

[//]: # ()
[//]: # (---)
