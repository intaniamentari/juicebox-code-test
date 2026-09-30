# Laravel API

## Overview

This is a Laravel-based REST API application that provides the following features:
* Authentication (register, login, logout) using Laravel Sanctum
* Posts management (create, read, update, delete)
* User registration
* Weather data integration (fetches weather data from Open-Meteo API), no need to set up API key

## API Documentation

The API documentation is available at `Juicebox.postman_collection.json` in this root project.

## Setup

Configure the `.env` file with the required database credentials, then run:

```bash
php artisan key:generate
php artisan migrate:fresh --seed
```

> **Note:** `migrate:fresh` resets the database.

## Run
run this command to start the development server and queue worker:

```bash
php artisan dev
```

Starts the development server and queue worker.

## Test

```bash
php artisan test tests/Feature/WeatherTest.php
php artisan test tests/Feature/UserTest.php
php artisan test tests/Feature/PostTest.php
```
