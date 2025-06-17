<p align="center"><h1 align="center">LARAVEL API RESPONSES</h1></p>
<p align="center">
	<em><code>composer require harrisonratcliffe/laravel-api-responses</code></em>
</p>
<p align="center">
	<img src="https://img.shields.io/github/license/harrisonratcliffe/laravel-api-responses?style=default&logo=opensourceinitiative&logoColor=white&color=0080ff" alt="license">
	<img src="https://img.shields.io/github/last-commit/harrisonratcliffe/laravel-api-responses?style=default&logo=git&logoColor=white&color=0080ff" alt="last-commit">
	<img src="https://img.shields.io/github/languages/top/harrisonratcliffe/laravel-api-responses?style=default&color=0080ff" alt="repo-top-language">
	<img src="https://img.shields.io/github/languages/count/harrisonratcliffe/laravel-api-responses?style=default&color=0080ff" alt="repo-language-count">
</p>
<p align="center">
</p>
<p align="center">
</p>
<br>

## 🔗 Table of Contents

- 📍 [Overview](#-overview)
- 👾 [Features](#-features)
- 🚀 [Getting Started](#-getting-started)
    - ☑️ [Prerequisites](#-prerequisites)
    - ⚙ [Installation](#-installation)
    - 🤖 [Usage](#-usage)
- 🧪 [Testing](#-testing)
- 🔰 [Contributing](#-contributing)
- 🎗 [License](#-license)

[//]: # (- [🙌 Acknowledgments]&#40;#-acknowledgments&#41;)

---

## 📍 Overview

A Laravel package to easily handle API responses and exceptions.

---

## 👾 Features

- 🌟 Return clean, consistent API responses
- 🛡️ Handle API exceptions with standardized error responses
- 🚀 Easy integration with Laravel 7 to 12

---
## 🚀 Getting Started

### ☑️ Prerequisites

Before getting started with Laravel API Responses, ensure your runtime environment meets the following requirements:

- **Programming Language:** PHP
- **Package Manager:** Composer
- **Laravel Version:** 8.0 or later


## ⚙️ Installation

Install laravel-api-responses using the following method:

1. Install the package via Composer:
```sh
composer require harrisonratcliffe/laravel-api-responses
```

2. Publish the config with:
```sh
php artisan vendor:publish --tag="apiresponses-config"
```

### API Exception Handler

*Note: if you use a different API route path than /api you should adjust the below if statement.*

#### Laravel 11-12

To configure the API Exception Handler on Laravel 11-12, add the following configuration to your `boostrap/app.php` file:
```php
use Harrisonratcliffe\LaravelApiResponses\ApiExceptionHandler;

->withExceptions(function (Exceptions $exceptions) {
        // your other code here
       $exceptions->render(function (Throwable $exception, $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return app(ApiExceptionHandler::class)->renderApiException($exception);
            }
        });
        // your other code here
    })
```

#### Laravel 7-10

To configure the API Exception Handler on Laravel 7-10, add the following configuration inside your render method of your `app/Exceptions/Handler.php` file:
```php
use Harrisonratcliffe\LaravelApiResponses\ApiExceptionHandler;

public function render($request, Throwable $exception)
    {
    // your other code here
        if ($request->expectsJson() || $request->is('api/*')) {
            return app(ApiExceptionHandler::class)->renderApiException($exception);
        }
    // your other code here
    }
```

Here's the modified README to reflect the new option for handling internal server error messages:

## 🔧 Configuration Options

The Laravel API Handler package provides a flexible configuration file that allows you to customize default response messages and behaviors. Below is a table of all available configuration options:

| Key                  | Default Value                                                                 | Description                                                                                       |
|----------------------|-------------------------------------------------------------------------------|---------------------------------------------------------------------------------------------------|
| debug_mode           | env('APP_DEBUG', false)                                                       | Controls whether detailed error information is exposed. Only enable in development environments.   |
| show_500_error_message | true                                                                        | Show actual error message for 500 errors. If false, uses `unknown_error` message.                 |
| success_response     | 'API request processed successfully.'                                         | Default message for successful API requests.                                                      |
| success_status_code  | 200                                                                           | Default HTTP status code for success responses.                                                   |
| http_not_found       | 'The requested resource or endpoint could not be located.'                    | Message for 404 Not Found errors.                                                                 |
| unauthenticated      | 'You must be logged in to access this resource. Please provide valid credentials.' | Message for 401 Unauthenticated errors.                                                      |
| not_authorized       | 'You are not authorized to access this resource.'                             | Message for 403 Forbidden errors.                                                                 |
| validation           | 'There has been one or more validation error with your request.'              | Message for 422 Validation errors.                                                                |
| model_not_found      | 'The requested resource could not be found. This resource doesn\'t exist.'    | Message for missing database records.                                                             |
| rate_limit           | 'You have exceeded the API request limit. Please try again later.'            | Message for 429 Too Many Requests errors.                                                         |
| unknown_error        | 'An unexpected error has occurred. Please try again later or contact support if the issue persists.' | Fallback message for unexpected errors. |
| custom_exceptions    | []                                                                            | Map your own exception classes to custom messages and status codes.                                |

### Example: Custom Exception Mapping

You can map your own exception classes to custom messages and status codes in `config/api-responses.php`:

```php
'custom_exceptions' => [
    App\Exceptions\CustomException::class => [
        'message' => 'A custom error occurred.',
        'status' => 422,
    ],
],
```

### 🛠️ Customization Tips
- Modify the config file located at `config/api-responses.php`
- Tailor messages to match your application's voice
- Keep error messages informative but not overly technical
- Ensure messages are user-friendly and provide clear guidance

### 🤖 Usage

### Success Responses

```php
// In your controller
use Harrisonratcliffe\LaravelApiResponses\Facades\ApiResponses;

public function index()
{
    $data = User::all();
    return ApiResponses::success(
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
use Harrisonratcliffe\LaravelApiResponses\Facades\ApiResponses;

public function store()
{
    return ApiResponses::error(
        'This virtual machine does not exist',
        404,
        [
            'vm_id' => 12345
        ],
        'https://docs.yourapi.com/errors/some-error'
    );
}
```

#### Example Error Response
```json
{
    "status": "error",
    "message": "This virtual machine does not exist",
    "details": {
        "vm_id": 12345
    },
    "documentation": "https://docs.yourapi.com/errors/some-error"
}
```

### Custom Exception Usage Example

```php
// In your exception handler or controller
throw new \App\Exceptions\CustomException('Something custom happened!');
// This will return a response with your custom message and status code as defined in config.
```

## 🧪 Testing

Run the test suite using the following command:

```sh
vendor/bin/pest
```

The test suite covers:
- All response types (success, error, validation, etc.)
- Config-driven behaviors (e.g., toggling debug, 500 error message)
- Custom exception mapping
- Facade and service usage

## 🏆 Best Practices

This package follows modern Laravel and PHP best practices:
- Uses strict types throughout the codebase
- Type-hinted methods and properties
- Robust configuration and extensibility
- Well-documented PHPDoc blocks for IDE support
- Fully tested with Pest and Testbench

## 🔰 Contributing

Contributions are welcome!

- **🐛 [Report Issues](https://github.com/harrisonratcliffe/laravel-api-responses/issues)**: Submit bugs found or log feature requests for the `laravel-api-responses` project.
- **💡 [Submit Pull Requests](https://github.com/harrisonratcliffe/laravel-api-responses/pulls)**: Review open PRs, and submit your own PRs.

<details closed>
<summary>Contributing Guidelines</summary>

1. **Fork the Repository**: Start by forking the project repository to your github account.
2. **Clone Locally**: Clone the forked repository to your local machine using a git client.
   ```sh
   git clone https://github.com/harrisonratcliffe/laravel-api-responses
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
   <a href="https://github.com{/harrisonratcliffe/laravel-api-responses/}graphs/contributors">
      <img src="https://contrib.rocks/image?repo=harrisonratcliffe/laravel-api-responses">
   </a>
</p>
</details>

---

## 🎗 License

This project is protected under the [MIT](https://choosealicense.com/licenses/mit) License. For more details, refer to
the [LICENSE](https://github.com/letrixlabs/nodehut-cli/blob/main/LICENSE) file.

[//]: # (---)

[//]: # (## 🙌 Acknowledgments)

[//]: # ()
[//]: # (- List any resources, contributors, inspiration, etc. here.)

[//]: # ()
[//]: # (---)
