# Laravel Installation Guide

This guide provides step-by-step instructions for installing a Laravel project using Laravel 9 and PHP 8.

## Prerequisites

Before proceeding with the installation, ensure that you have the following software installed on your system:

- PHP 8 or later
- Composer (Dependency Manager for PHP)
- Laravel Installer
- MySQL or any other compatible database server
- Web server (e.g., Apache, Nginx)

## Installation Steps

Follow these steps to install the Laravel project:

1. Clone the project repository from the source control system or download the project ZIP file.

2. Navigate to the project directory using the command line.

3. Install project dependencies using Composer:

```shell
composer install
```

4. Create a copy of the .env.example file and rename it to .env. Update the .env file with your database credentials and other configuration settings.

5. Generate an application key:
```shell
php artisan key:generate
```
6. Run database migrations to create the required tables:
```shell
Start the development server:
```
7. Start the development server:
```shell
php artisan serve
```
8. Open a web browser and navigate to http://localhost:8000 to access the Laravel application

