# Project Setup Guide

This is a URL shortener prototype built with Laravel and Vue.js. The project allows users to submit URLs, generates short unique URLs, checks URLs using the Google Safe Browsing API, and redirects short URLs to their original URLs.

## Features

- Shorten URLs to 6 alphanumeric characters.
- Recognize duplicate URLs and return the same short URL if already shortened.
- Check URLs using the Google Safe Browsing API.
- Redirect short URLs to their original URLs.
- Work from a folder (e.g., `example.com/something/[hash]`).


## Prerequisites

Make sure you have the following installed on your machine:
- [Git](https://git-scm.com/)
- [PHP](https://www.php.net/)
- [Composer](https://getcomposer.org/)
- [Node.js & npm](https://nodejs.org/)
- [MySQL](https://www.mysql.com/) or [PostgreSQL](https://www.postgresql.org/) (depending on your database choice)

## Installation Steps
1. **Clone the repository:**
    ```sh
    git clone https://github.com/valtrinshala/url-shortener
    cd url-shortener
    ```

2. **Copy the `.env` file:**
    ```sh
    cp .env.example .env
    ```

3. **Create a new database:**

   Create a new database named `laravel` using your preferred database management tool (e.g., phpMyAdmin, MySQL Workbench, or command line).

4. **Update database credentials:**

   Open the `.env` file and update the database credentials to match your local setup:
    ```env
    DB_DATABASE=laravel
    DB_USERNAME=your-database-username
    DB_PASSWORD=your-database-password
    ```

5. **Generate the application key:**
    ```sh
    php artisan key:generate
    ```

6. **Install PHP dependencies:**
    ```sh
    composer install
    ```

7. **Run database migrations:**
    ```sh
    php artisan migrate
    ```

8. **Install Node.js dependencies:**
    ```sh
    npm install
    ```

9. **Compile assets:**
    ```sh
    npm run dev
    ```

10. **Start the development server:**
    ```sh
    php artisan serve
    ```

11. **Access the application:**
    Open your browser and go to [https://localhost:8000](https://localhost:8000)

## Additional Information

For any issues or further assistance, please refer to the [documentation](https://github.com/your-username/your-repository/wiki) or create an issue on GitHub.

Happy coding!
