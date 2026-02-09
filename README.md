# PHP_LARAVEL12_QUERY_BUILDER
```php
Laravel 12 based Query Builder CRUD Web Application built using clean MVC architecture and Blade UI.
```
# Key Features
```php
- Web Based Student Management CRUD
- Query Builder Database Operations
- Blade + Bootstrap UI
- MVC Architecture Implementation
- Laravel 12 Compatible
- Beginner Friendly Project Structure
```
# Step 1: Install Fresh Laravel 12 Application

Open Terminal / Command Prompt and run:
```php
composer create-project laravel/laravel:^12.0 PHP_Laravel12_Query_Builder
```
Move into project directory:
```php
cd PHP_Laravel12_Query_Builder
```
Generate application key:
```php
php artisan key:generate
```
# Explanation
```php
- Installs fresh Laravel 12 project
- Application key is required for encryption and security
```
# Step 2: Configure Environment & Database
Open .env file and update database configuration:
```php
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3307
DB_DATABASE=php_laravel12_query_builder
DB_USERNAME=root
DB_PASSWORD=
```
Save the file.
Run default migrations:
```php
php artisan migrate
```
# Explanation
```php
- .env manages environment configuration
- Migrations create default Laravel tables
```
# Step 3: Create Students Database Table
Create migration:
```php
php artisan make:migration create_students_table
```
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('students', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('email')->unique();
        $table->integer('age');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
```
Run migration:
```php
php artisan migrate
```
# Explanation
```php
- Creates students table
- Stores student data for CRUD operations
```
# Step 4: Create Student Controller
Create controller:
```php
php artisan make:controller StudentController
```
# Explanation
```php
- Controller handles business logic
- Query Builder queries are written inside controller
```
# Step 5: Configure Web Routes
Open routes/web.php
```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/insert', [StudentController::class, 'insert']);
// Route::get('/students', [StudentController::class, 'index']);
// Route::get('/student/{id}', [StudentController::class, 'single']);
// Route::get('/update/{id}', [StudentController::class, 'update']);
// Route::get('/delete/{id}', [StudentController::class, 'delete']);

Route::get('/students', [StudentController::class, 'index']);
Route::get('/students/create', [StudentController::class, 'create']);
Route::post('/students/store', [StudentController::class, 'store']);
Route::get('/students/edit/{id}', [StudentController::class, 'edit']);
Route::post('/students/update/{id}', [StudentController::class, 'update']);
Route::get('/students/delete/{id}', [StudentController::class, 'delete']);
```
# Explanation
```php
- Defines all CRUD web routes
- Handles form submission and navigation
```
# Step 6: Blade UI Structure
Views Folder Structure:
```php
resources/views/
   layouts/app.blade.php
   students/index.blade.php
   students/create.blade.php
   students/edit.blade.php
```
# Explanation
```php
- Layout = Common UI structure
- Index = Student list page
- Create = Add student form
- Edit = Update student form
```
# Step 7: Run Laravel Project
Start Laravel development server:
```php
php artisan serve
```
Open Browser:
```php
http://127.0.0.1:8000/students
```
<img width="1295" height="526" alt="image" src="https://github.com/user-attachments/assets/3eb3fa2d-83fb-4911-b04c-0f26a209208b" />

Create Student Page:
```php
http://127.0.0.1:8000/students/create
```
<img width="1308" height="529" alt="image" src="https://github.com/user-attachments/assets/6942e314-5478-45fc-8b72-f60d5f92f633" />

Edit Student Page:
```php
http://127.0.0.1:8000/students/edit/1
```
<img width="1283" height="492" alt="image" src="https://github.com/user-attachments/assets/14266593-adff-4624-b68c-b3af1356d5e5" />

# Explanation
```php
- Runs Laravel locally
- Opens full web CRUD application
```
# Project Folder Structure
```php
PHP_LARAVEL12_QUERY_BUILDER
├── app/
│   └── Http/
│       └── Controllers/
│           └── StudentController.php
│
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php
│       └── students/
│           ├── index.blade.php
│           ├── create.blade.php
│           └── edit.blade.php
│
├── routes/
│   └── web.php
│
├── database/
│   └── migrations/
│
├── .env
├── artisan
└── composer.json
```
