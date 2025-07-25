## About KatiBayan Web Portal

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

## How to Setup 

git clone https://github.com/yourusername/katibayan.git
cd katibayan
composer install
copy .env.example .env

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=katibayandb
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=file

create a database named katibayandb in phpMyAdmin

php artisan key:generate
php artisan migrate
php artisan serve






