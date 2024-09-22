## Features

- Create products from the web and CLI
- List and filter products by price and category

## Installation

1. Clone the Repository:
   ```bash
   git clone https://github.com/yayassssine/codingChallenge.git
   cd codingChallenge
2. Install Dependencies:
   ```bash
    composer install

3. Set Up Environment:
   ```bash
    cp .env.example .env
    php artisan key:generate

4. Run Migrations:
   ```bash
    php artisan migrate

5. Start the server:
   ```bash
    php artisan serve
   
## CLI Command

-
   ```bash 
   php artisan product:create "Product Name" 99.99 1 "Product Description"

## PSR-12

-   ```bash 
    vendor/bin/php-cs-fixer fix
