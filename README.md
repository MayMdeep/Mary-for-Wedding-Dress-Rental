<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

Laravel is accessible, powerful, and provides tools required for large, robust applications.

# Wedding Dress Rental System

## Project Overview
This project is a web application for renting wedding dresses. It allows users to browse available dresses, view detailed information, make reservations, and manage their accounts. The application is built using Laravel and follows best practices for design patterns and code organization.

## Features
- **Homepage**: Display a list of available wedding dresses with images, names, and rental prices.
- **Dress Detail Page**: Show detailed information about the selected dress, including description, size availability, and rental price.
- **Reservation System**: Allow users to select a dress, choose a rental duration, and confirm the reservation. Users can view their reservation history on their account page.
- **User Authentication**: Implement user authentication using Laravel's built-in authentication system. Users can register, log in, and log out.
- **User Account Page**: Users can view and manage their reservations, edit profile information, and change passwords.
- **Dynamic Specifications**: Admins can add and manage dress specifications and options dynamically.

## Installation

### Prerequisites
- PHP 8.0 or higher
- Laravel 11
- Composer
- MySQL or any other supported database

### Steps
1. **Clone the repository**
   ```bash
   git clone https://github.com/maymdeep/wedding-dress-rental.git
   cd wedding-dress-rental

2. **Install dependencies**
    ```bash
    composer install

3. **Copy the .env file**
    ```bash
    cp .env.example .env


5. **Configure the .env file Update the database configuration in the .env file:**

        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1
        DB_PORT=3306
        DB_DATABASE=mary
        DB_USERNAME=your_database_username(root)
        DB_PASSWORD=your_database_password(usually empty)

6. **Run migrations and seeders**
   ```bash
     php artisan migrate --seed

7. **Serve the application**
    ```bash
    php artisan serve


### API Endpoints
- Authentication
- Register: POST /api/register
- Login: POST /api/login
- Logout: POST /api/logout (requires authentication)
**Dresses**
- List Dresses: GET /api/dresses
- View Dress Details: GET /api/dresses/{id}
- Create Dress: POST /api/dresses (requires authentication)
- Update Dress: PUT /api/dresses/{id} (requires authentication)
- Delete Dress: DELETE /api/dresses/{id} (requires authentication)
**Reservations**
- Create Reservation: POST /api/reservations (requires authentication)
- View Reservations: GET /api/reservations (requires authentication)
- Cancel Reservation: DELETE /api/reservations/{id} (requires authentication)


### Design Patterns

Action Design Pattern: Encapsulates specific tasks or actions within single classes, promoting modularity and maintainability.
Repository Design Pattern: Abstracts the data layer and provides a clean API for data access and manipulation.

