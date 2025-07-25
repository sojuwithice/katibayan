## About KatiBayan Web Portal

KatiBayan is an innovative system designed to assist SK officials in EM’s Barrio by digitizing and streamlining essential processes such as youth registration, event planning, attendance tracking, and report generation. By leveraging a centralized MySQL database along with real-time data validation and analytics, KatiBayan will enable data-driven decision-making and promote greater youth involvement in local governance. KatiBayan will function as a centralized digital platform through which SK officials can efficiently manage member information, disseminate announcements, organize programs, and monitor youth engagement. Ultimately, the project aims to enhance transparency, improve operational efficiency, and strengthen youth participation through the integration of modern digital tools in SK governance.

## How to Set Up the Project

Follow the steps below to get the project running on your local machine.

### 1. Clone the Repository

``` git clone https://github.com/yourusername/katibayan.git ``` <br>
`` cd katibayan ``

### 2. Install Dependecies 

```composer install ```

### 3. Set Up Environment File

copy .env.example .env

### 4. Configure the Database

Open the .env file and update your database configuration:


DB_CONNECTION=mysql <br>
DB_HOST=127.0.0.1 <br>
DB_PORT=3306 <br>
DB_DATABASE=katibayandb <br>
DB_USERNAME=root <br>
DB_PASSWORD= <br>

SESSION_DRIVER=file

### 4. Create a Database in phpmyadmin and named it:

``` katibayandb ```

### 5. Generate the Application Key

``` php artisan key:generate ```

### 6. Run Database Migrations

``` php artisan migrate ```

### 7. Serve the Application

``` php artisan serve ```






