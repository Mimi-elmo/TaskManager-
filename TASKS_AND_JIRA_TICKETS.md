# Laravel Task Manager - Jira Tickets & Task Breakdown

## 📋 Table of Contents
1. [Setup & Planning](#setup--planning)
2. [Database & Models](#database--models)
3. [Authentication](#authentication)
4. [Task Management (CRUD)](#task-management-crud)
5. [Filtering & Advanced Features](#filtering--advanced-features)
6. [Quality & Debugging](#quality--debugging)

---

---

# PHASE 1: SETUP & PLANNING

---

## 📌 TASK: SETUP-001 - Project Initialization & Git Configuration

**Type:** Setup  
**Priority:** P0 (Critical)  
**Estimated Time:** 1 hour  
**Status:** Todo

### Description
Initialize Laravel project with all necessary tools, configurations, and Git setup for version control.

### Acceptance Criteria
- [ ] Fresh Laravel project created (`laravel new task-manager`)
- [ ] Git repository initialized with `.gitignore` properly configured
- [ ] Laravel Debugbar installed and active in development
- [ ] Laravel Telescope installed and running on `/telescope` route
- [ ] Database configured (MySQL/PostgreSQL)
- [ ] First commit made: "Initial Laravel setup with Debugbar and Telescope"

### Instructions

#### Step 1: Create Laravel Project
```bash
composer create-project laravel/laravel task-manager
cd task-manager
```

#### Step 2: Initialize Git
```bash
git init
git add .
git commit -m "Initial Laravel setup with Debugbar and Telescope"
git remote add origin <your-repo-url>
git branch -M main
git push -u origin main
```

#### Step 3: Install Debugging Tools

**Laravel Debugbar:**
```bash
composer require barryvdh/laravel-debugbar --dev
```

**Laravel Telescope:**
```bash
composer require laravel/telescope --dev
php artisan telescope:install
php artisan migrate
```

#### Step 4: Configure Environment
Edit `.env`:
```
APP_ENV=local
APP_DEBUG=true
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_manager
DB_USERNAME=root
DB_PASSWORD=
```

#### Step 5: Create Database
```bash
mysql -u root -p
CREATE DATABASE task_manager;
EXIT;
php artisan migrate
```

### Files to Create/Modify
- `.env` - Database configuration
- `.gitignore` - Already configured
- `config/app.php` - Debugbar and Telescope providers

### Deliverables
- ✅ Working Laravel environment
- ✅ Git repository with initial commit
- ✅ Debugbar accessible in browser
- ✅ Telescope accessible at `/telescope`

---

## 📌 TASK: PLANNING-001 - Database Design (MCD & MLD)

**Type:** Design  
**Priority:** P0 (Critical)  
**Estimated Time:** 2 hours  
**Status:** Todo  
**Deadline:** Monday 28/04 before 3:00 PM

### Description
Design the complete database schema before writing migrations. Document entities, relationships, and attributes.

### Acceptance Criteria
- [ ] MCD document created with all entities
- [ ] All relationships documented with cardinality
- [ ] MLD document created with table definitions
- [ ] Primary keys and foreign keys identified
- [ ] Attributes and data types defined
- [ ] Design approved before migrations start

### Instructions

#### MCD Entities

```
USER
├── id (PK)
├── name
├── email (UNIQUE)
├── password
├── email_verified_at
├── remember_token
└── created_at, updated_at

CATEGORY
├── id (PK)
├── name
├── description
└── created_at, updated_at

TASK
├── id (PK)
├── user_id (FK)
├── category_id (FK)
├── title
├── description
├── status
├── due_date (NULLABLE)
└── created_at, updated_at
```

#### Relationships
```
User (1) ----hasMany----> (N) Task
        
Category (1) ----hasMany----> (N) Task
```

**Cardinality:**
- One User has Many Tasks (1:N)
- One Category has Many Tasks (1:N)
- One Task belongs to One User (N:1)
- One Task belongs to One Category (N:1)

#### MLD Table Definitions

**USERS Table**
```
id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT
name VARCHAR(255) NOT NULL
email VARCHAR(255) UNIQUE NOT NULL
email_verified_at TIMESTAMP NULLABLE
password VARCHAR(255) NOT NULL
remember_token VARCHAR(100) NULLABLE
created_at TIMESTAMP NULLABLE
updated_at TIMESTAMP NULLABLE
```

**CATEGORIES Table**
```
id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT
name VARCHAR(255) NOT NULL
description TEXT NULLABLE
created_at TIMESTAMP NULLABLE
updated_at TIMESTAMP NULLABLE
```

**TASKS Table**
```
id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT
user_id BIGINT UNSIGNED NOT NULL FOREIGN KEY → USERS(id) ON DELETE CASCADE
category_id BIGINT UNSIGNED NOT NULL FOREIGN KEY → CATEGORIES(id) ON DELETE CASCADE
title VARCHAR(255) NOT NULL
description TEXT NULLABLE
status ENUM('pending', 'in_progress', 'completed') DEFAULT 'pending'
due_date DATE NULLABLE
created_at TIMESTAMP NULLABLE
updated_at TIMESTAMP NULLABLE

INDEXES:
- PRIMARY KEY (id)
- FOREIGN KEY (user_id)
- FOREIGN KEY (category_id)
- INDEX (user_id, status) -- For filtering
```

### Documents to Create
- `docs/MCD.md` - Conceptual model with diagram
- `docs/MLD.md` - Logical model with table definitions

### Deliverables
- ✅ MCD document with all entities and relationships
- ✅ MLD document with table definitions
- ✅ Clear cardinality on all relationships
- ✅ Design matching final implementation

---

---

# PHASE 2: DATABASE & MODELS

---

## 📌 TASK: DB-001 - Create Migrations (User, Category, Task Tables)

**Type:** Database  
**Priority:** P0 (Critical)  
**Estimated Time:** 1.5 hours  
**Status:** Todo  
**Depends on:** PLANNING-001

### Description
Create Laravel migrations for users, categories, and tasks tables with proper constraints and indexes.

### Acceptance Criteria
- [ ] Users table migration created with Laravel auth fields
- [ ] Categories table migration created
- [ ] Tasks table migration created with foreign keys
- [ ] All migrations run successfully
- [ ] Database schema matches MLD design
- [ ] Commit message: "Create database migrations for users, categories, and tasks"

### Instructions

#### Step 1: Generate Migrations
```bash
php artisan make:migration create_users_table --create=users
php artisan make:migration create_categories_table --create=categories
php artisan make:migration create_tasks_table --create=tasks
```

#### Step 2: Edit Users Migration
File: `database/migrations/xxxx_xx_xx_xxxxxx_create_users_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('users');
    }
};
```

#### Step 3: Edit Categories Migration
File: `database/migrations/xxxx_xx_xx_xxxxxx_create_categories_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('categories');
    }
};
```

#### Step 4: Edit Tasks Migration
File: `database/migrations/xxxx_xx_xx_xxxxxx_create_tasks_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');
            $table->foreignId('category_id')
                  ->constrained('categories')
                  ->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'completed'])
                  ->default('pending');
            $table->date('due_date')->nullable();
            $table->timestamps();

            // Indexes for filtering performance
            $table->index(['user_id', 'status']);
            $table->index('category_id');
        });
    }

    public function down(): void {
        Schema::dropIfExists('tasks');
    }
};
```

#### Step 5: Run Migrations
```bash
php artisan migrate
```

#### Step 6: Verify Database
```bash
php artisan tinker
DB::table('users')->get();
DB::table('categories')->get();
DB::table('tasks')->get();
```

### Deliverables
- ✅ Three migration files created
- ✅ All migrations run successfully
- ✅ Database tables created with correct structure
- ✅ Foreign keys and indexes in place
- ✅ Git commit: "Create database migrations for users, categories, and tasks"

---

## 📌 TASK: DB-002 - Create Seeders for Categories & Test Data

**Type:** Database  
**Priority:** P1 (High)  
**Estimated Time:** 1 hour  
**Status:** Todo  
**Depends on:** DB-001

### Description
Create seeders to populate database with initial categories and sample data for testing.

### Acceptance Criteria
- [ ] CategorySeeder created with 5-6 default categories
- [ ] DatabaseSeeder updated to call CategorySeeder
- [ ] Seeders run successfully
- [ ] Categories visible in database
- [ ] Commit message: "Add database seeders for categories"

### Instructions

#### Step 1: Generate Seeder
```bash
php artisan make:seeder CategorySeeder
```

#### Step 2: Edit CategorySeeder
File: `database/seeders/CategorySeeder.php`

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder {
    public function run(): void {
        DB::table('categories')->insert([
            [
                'name' => 'Work',
                'description' => 'Work-related tasks',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Personal',
                'description' => 'Personal development tasks',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Project',
                'description' => 'Project management tasks',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Meeting',
                'description' => 'Meeting and presentation tasks',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Learning',
                'description' => 'Learning and skill development',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Shopping',
                'description' => 'Shopping and errands',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
```

#### Step 3: Update DatabaseSeeder
File: `database/seeders/DatabaseSeeder.php`

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    public function run(): void {
        $this->call([
            CategorySeeder::class,
        ]);
    }
}
```

#### Step 4: Run Seeders
```bash
php artisan db:seed
```

#### Step 5: Verify Data
```bash
php artisan tinker
Category::all();
```

### Deliverables
- ✅ CategorySeeder created with sample data
- ✅ DatabaseSeeder updated
- ✅ Seeders run successfully
- ✅ Categories populated in database
- ✅ Git commit: "Add database seeders for categories"

---

## 📌 TASK: MODEL-001 - Create User Model with Relationships

**Type:** Model  
**Priority:** P0 (Critical)  
**Estimated Time:** 1 hour  
**Status:** Todo  
**Depends on:** DB-001

### Description
Create User model with Task relationship and proper $fillable configuration.

### Acceptance Criteria
- [ ] User model created with hasMany relationship to Task
- [ ] $fillable array defined with correct fields
- [ ] Hidden fields configured (password, remember_token)
- [ ] Casts defined for email_verified_at
- [ ] Test relationship in tinker

### Instructions

#### Step 1: Review Existing User Model
The User model already exists: `app/Models/User.php`

#### Step 2: Add Task Relationship
File: `app/Models/User.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable {
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get all tasks for this user
     */
    public function tasks(): HasMany {
        return $this->hasMany(Task::class);
    }
}
```

#### Step 3: Test in Tinker
```bash
php artisan tinker
$user = User::first();
$user->tasks;  // Should return empty collection
```

### Deliverables
- ✅ User model with hasMany(Task) relationship
- ✅ $fillable properly configured
- ✅ Hidden fields set
- ✅ Casts configured
- ✅ Relationship tested

---

## 📌 TASK: MODEL-002 - Create Task Model with Relationships

**Type:** Model  
**Priority:** P0 (Critical)  
**Estimated Time:** 1 hour  
**Status:** Todo  
**Depends on:** DB-001

### Description
Create Task model with relationships to User and Category, and proper $fillable configuration.

### Acceptance Criteria
- [ ] Task model created
- [ ] belongsTo User relationship defined
- [ ] belongsTo Category relationship defined
- [ ] $fillable array defined with all task fields
- [ ] Casts defined for dates
- [ ] Status enum (optional, advanced)
- [ ] Test relationships in tinker

### Instructions

#### Step 1: Generate Model
```bash
php artisan make:model Task
```

#### Step 2: Edit Task Model
File: `app/Models/Task.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model {
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'status',
        'due_date',
    ];

    protected function casts(): array {
        return [
            'due_date' => 'date',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the user that owns this task
     */
    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category of this task
     */
    public function category(): BelongsTo {
        return $this->belongsTo(Category::class);
    }

    /**
     * Check if task belongs to the given user
     */
    public function isOwnedBy($userId): bool {
        return $this->user_id === $userId;
    }
}
```

#### Step 3: Test Relationships
```bash
php artisan tinker
// After creating test data
$task = Task::first();
$task->user;       // Should return the user
$task->category;   // Should return the category
```

### Deliverables
- ✅ Task model created
- ✅ belongsTo(User) relationship defined
- ✅ belongsTo(Category) relationship defined
- ✅ $fillable configured correctly
- ✅ Casts configured
- ✅ Helper method isOwnedBy() defined
- ✅ Relationships tested

---

## 📌 TASK: MODEL-003 - Create Category Model

**Type:** Model  
**Priority:** P0 (Critical)  
**Estimated Time:** 30 minutes  
**Status:** Todo  
**Depends on:** DB-001

### Description
Create Category model with Task relationship.

### Acceptance Criteria
- [ ] Category model created
- [ ] hasMany Task relationship defined
- [ ] $fillable array configured
- [ ] Relationship tested

### Instructions

#### Step 1: Generate Model
```bash
php artisan make:model Category
```

#### Step 2: Edit Category Model
File: `app/Models/Category.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model {
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Get all tasks in this category
     */
    public function tasks(): HasMany {
        return $this->hasMany(Task::class);
    }
}
```

#### Step 3: Test Relationship
```bash
php artisan tinker
$category = Category::first();
$category->tasks;  // Should return tasks collection
```

### Deliverables
- ✅ Category model created
- ✅ hasMany(Task) relationship defined
- ✅ $fillable configured
- ✅ Relationship tested

---

---

# PHASE 3: AUTHENTICATION

---

## 📌 TASK: AUTH-001 - Setup Laravel Auth Scaffolding & Registration

**Type:** Feature  
**Priority:** P0 (Critical)  
**Estimated Time:** 2 hours  
**Status:** Todo  
**Branch:** `feature/auth`

### Description
Implement user registration and authentication using Laravel's built-in auth system.

### User Story
**US1 - Registration:** New users can create account with name, email, password

### Acceptance Criteria
- [ ] Laravel Auth scaffolding installed and configured
- [ ] Registration route created with name 'register'
- [ ] Registration form validates input
- [ ] Password hashed before storage
- [ ] @csrf token on registration form
- [ ] Validation: email unique, password confirmed
- [ ] User redirected to login or tasks after registration
- [ ] Error messages displayed on validation failure

### Instructions

#### Step 1: Install Laravel UI (if using Blade)
```bash
composer require laravel/ui
php artisan ui bootstrap --auth
npm install && npm run dev
```

Or use Jetstream (more modern):
```bash
composer require laravel/jetstream
php artisan jetstream:install livewire
php artisan migrate
npm install && npm run dev
```

**Alternative: Manual Implementation**

If you prefer to build from scratch:

#### Step 2: Create Registration Controller
File: `app/Http/Controllers/Auth/RegisterController.php`

```php
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisterController extends Controller {
    public function show(): View {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('tasks.index');
    }
}
```

#### Step 3: Create Registration View
File: `resources/views/auth/register.blade.php`

```blade
<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <label for="name">Name</label>
            <input id="name" class="block mt-1 w-full" type="text" name="name" 
                   value="{{ old('name') }}" required autofocus autocomplete="name">
            @error('name')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <label for="email">Email</label>
            <input id="email" class="block mt-1 w-full" type="email" name="email" 
                   value="{{ old('email') }}" required autocomplete="username">
            @error('email')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <!-- Password -->
        <div class="mt-4">
            <label for="password">Password</label>
            <input id="password" class="block mt-1 w-full" type="password" name="password" 
                   required autocomplete="new-password">
            @error('password')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <label for="password_confirmation">Confirm Password</label>
            <input id="password_confirmation" class="block mt-1 w-full" type="password" 
                   name="password_confirmation" required autocomplete="new-password">
            @error('password_confirmation')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex items-center justify-end mt-4">
            <a href="{{ route('login') }}">Already registered?</a>
            <button type="submit" class="ml-4">Register</button>
        </div>
    </form>
</x-guest-layout>
```

#### Step 4: Add Routes
File: `routes/web.php`

```php
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisterController::class, 'show'])->name('register');
    Route::post('register', [RegisterController::class, 'store']);
});
```

### Files to Modify/Create
- `app/Http/Controllers/Auth/RegisterController.php` (new)
- `resources/views/auth/register.blade.php` (new)
- `resources/views/layouts/app.blade.php` (main layout)
- `routes/web.php` (add routes)

### Testing
1. Navigate to /register
2. Fill form with valid data
3. Submit and verify user created
4. Check database for new user

### Deliverables
- ✅ Registration form working
- ✅ Form validation functional
- ✅ User created in database
- ✅ User auto-logged in after registration
- ✅ @csrf token present
- ✅ Redirects to tasks after registration

---

## 📌 TASK: AUTH-002 - Implement Login & Logout

**Type:** Feature  
**Priority:** P0 (Critical)  
**Estimated Time:** 1.5 hours  
**Status:** Todo  
**Branch:** `feature/auth`

### Description
Implement login and logout functionality with "Remember Me" option.

### User Story
**US2 - Login/Logout:** Users can log in with email/password and log out

### Acceptance Criteria
- [ ] Login form with email and password fields
- [ ] Remember me checkbox functional
- [ ] Form validation on login
- [ ] @csrf token on login form
- [ ] Invalid credentials error message
- [ ] User redirected to tasks after login
- [ ] Logout button in navigation
- [ ] Logout clears session and redirects to login
- [ ] Unauthenticated users redirected to /login

### Instructions

#### Step 1: Create Login Controller
File: `app/Http/Controllers/Auth/LoginController.php`

```php
<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Http\Controllers\Controller;

class LoginController extends Controller {
    public function show(): View {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('tasks.index'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function destroy(Request $request): RedirectResponse {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
```

#### Step 2: Create Login View
File: `resources/views/auth/login.blade.php`

```blade
<x-guest-layout>
    <h2>Login</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first('email') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email">Email</label>
            <input id="email" class="block mt-1 w-full" type="email" name="email" 
                   value="{{ old('email') }}" required autofocus autocomplete="username">
            @error('email')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <!-- Password -->
        <div class="mt-4">
            <label for="password">Password</label>
            <input id="password" class="block mt-1 w-full" type="password" name="password" 
                   required autocomplete="current-password">
            @error('password')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember" class="inline-flex items-center">
                <input id="remember" type="checkbox" class="rounded" 
                       name="remember" {{ old('remember') ? 'checked' : '' }}>
                <span class="ml-2">Remember me</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            <a href="{{ route('register') }}">Don't have an account?</a>
            <button type="submit" class="ml-4">Login</button>
        </div>
    </form>
</x-guest-layout>
```

#### Step 3: Create Logout Middleware/Route
File: `routes/web.php`

```php
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'show'])->name('login');
    Route::post('login', [LoginController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');
});
```

#### Step 4: Create Main Layout with Logout
File: `resources/views/layouts/app.blade.php`

```blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Task Manager')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="{{ route('tasks.index') }}">Task Manager</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item">
                            <span class="nav-link">{{ auth()->user()->name }}</span>
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-link nav-link">Logout</button>
                            </form>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
```

#### Step 5: Create Guest Layout
File: `resources/views/layouts/guest.blade.php`

```blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Task Manager')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
```

### Testing
1. Click logout from any page
2. Verify redirected to login
3. Try accessing /tasks, should redirect to login
4. Login with valid credentials
5. Verify logged in and redirected to tasks
6. Test Remember Me functionality

### Deliverables
- ✅ Login form with validation
- ✅ Logout functionality
- ✅ Remember me working
- ✅ Auth middleware protecting routes
- ✅ @csrf tokens on forms
- ✅ Proper redirects
- ✅ Error messages displayed

---

## 📌 TASK: AUTH-003 - Setup Auth Middleware & Protected Routes

**Type:** Configuration  
**Priority:** P0 (Critical)  
**Estimated Time:** 1 hour  
**Status:** Todo  
**Branch:** `feature/auth`

### Description
Configure Laravel middleware to protect all task routes and redirect unauthenticated users.

### Acceptance Criteria
- [ ] All task routes protected with 'auth' middleware
- [ ] Routes grouped properly in routes/web.php
- [ ] Unauthenticated users redirected to /login
- [ ] RedirectIfAuthenticated middleware redirects to tasks if logged in
- [ ] 403 Forbidden on unauthorized access
- [ ] Routes displayed with `php artisan route:list`

### Instructions

#### Step 1: Review Middleware
File: `app/Http/Middleware/Authenticate.php` (already exists)

Ensure redirect is configured:
```php
protected function redirectTo(Request $request): ?string {
    return $request->expectsJson() ? null : route('login');
}
```

#### Step 2: Setup Route Groups
File: `routes/web.php`

```php
<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisterController::class, 'show'])->name('register');
    Route::post('register', [RegisterController::class, 'store']);

    Route::get('login', [LoginController::class, 'show'])->name('login');
    Route::post('login', [LoginController::class, 'store']);
});

// Redirect to login by default
Route::get('/', function () {
    return redirect()->route('tasks.index');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');

    // Task routes
    Route::resource('tasks', TaskController::class);
});
```

#### Step 3: View All Routes
```bash
php artisan route:list
```

Should show output similar to:
```
GET|HEAD   /login                          login
POST       /login                          
GET|HEAD   /register                       register
POST       /register                       
GET|HEAD   /tasks                          tasks.index
GET|HEAD   /tasks/create                   tasks.create
POST       /tasks                          tasks.store
GET|HEAD   /tasks/{task}                   tasks.show
GET|HEAD   /tasks/{task}/edit              tasks.edit
PUT|PATCH  /tasks/{task}                   tasks.update
DELETE     /tasks/{task}                   tasks.destroy
POST       /logout                         logout
```

### Testing
1. Try accessing /tasks without login → redirected to /login
2. Login successfully
3. Access /tasks → should work
4. Try accessing /login after login → should redirect to /tasks
5. Logout and verify cannot access /tasks

### Deliverables
- ✅ All task routes protected with auth middleware
- ✅ Routes properly grouped
- ✅ Redirects working correctly
- ✅ Route listing shows all routes with names

---

---

# PHASE 4: TASK MANAGEMENT (CRUD)

---

## 📌 TASK: CRUD-001 - Create TaskController & Display All Tasks (Index)

**Type:** Feature  
**Priority:** P0 (Critical)  
**Estimated Time:** 2 hours  
**Status:** Todo  
**Branch:** `feature/task-crud`

### Description
Create TaskController and implement task listing with pagination and proper user isolation.

### User Story
**US3 - View Tasks:** Users can see all their tasks with title, category, status, created date

### Acceptance Criteria
- [ ] TaskController created with index() method
- [ ] Tasks filtered by current user
- [ ] Pagination implemented (8 items per page)
- [ ] Table displays: Title, Category, Status, Created Date
- [ ] All tasks sorted by created_at descending
- [ ] View created: resources/views/tasks/index.blade.php
- [ ] Proper use of Eloquent relationships (no N+1)
- [ ] Links to create, edit, delete tasks
- [ ] Commit message: "Implement task listing with pagination"

### Instructions

#### Step 1: Create TaskController
```bash
php artisan make:controller TaskController --resource
```

#### Step 2: Implement Index Method
File: `app/Http/Controllers/TaskController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Category;
use Illuminate\Http\Request;

class TaskController extends Controller {
    public function index(Request $request) {
        // Start with current user's tasks
        $query = auth()->user()->tasks();

        // Eager load relationships to avoid N+1
        $query->with('category');

        // Sort by newest first
        $query->latest();

        // Paginate
        $tasks = $query->paginate(8);

        // Get categories for filter dropdown
        $categories = Category::all();

        return view('tasks.index', [
            'tasks' => $tasks,
            'categories' => $categories,
        ]);
    }

    public function create() {
        $categories = Category::all();
        return view('tasks.create', [
            'categories' => $categories,
        ]);
    }

    public function store(Request $request) {
        // To be implemented in CRUD-002
    }

    public function show(Task $task) {
        // Authorization check
        $this->authorize('view', $task);
        return view('tasks.show', ['task' => $task]);
    }

    public function edit(Task $task) {
        // Authorization check
        $this->authorize('update', $task);
        $categories = Category::all();
        return view('tasks.edit', [
            'task' => $task,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, Task $task) {
        // To be implemented in CRUD-003
    }

    public function destroy(Task $task) {
        // To be implemented in CRUD-004
    }
}
```

#### Step 3: Create Task Index View
File: `resources/views/tasks/index.blade.php`

```blade
@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>My Tasks</h1>
    <a href="{{ route('tasks.create') }}" class="btn btn-primary">+ New Task</a>
</div>

<!-- Status Counts -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">Pending</h5>
                <p class="card-text display-4">
                    {{ auth()->user()->tasks()->where('status', 'pending')->count() }}
                </p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">In Progress</h5>
                <p class="card-text display-4">
                    {{ auth()->user()->tasks()->where('status', 'in_progress')->count() }}
                </p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">Completed</h5>
                <p class="card-text display-4">
                    {{ auth()->user()->tasks()->where('status', 'completed')->count() }}
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Tasks Table -->
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tasks as $task)
                    <tr>
                        <td>
                            <strong>{{ $task->title }}</strong>
                            @if ($task->description)
                                <br>
                                <small class="text-muted">{{ Str::limit($task->description, 50) }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $task->category->name }}</span>
                        </td>
                        <td>
                            <form action="{{ route('tasks.update', $task) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PUT')
                                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <option value="pending" {{ $task->status === 'pending' ? 'selected' : '' }}>
                                        To Do
                                    </option>
                                    <option value="in_progress" {{ $task->status === 'in_progress' ? 'selected' : '' }}>
                                        In Progress
                                    </option>
                                    <option value="completed" {{ $task->status === 'completed' ? 'selected' : '' }}>
                                        Done
                                    </option>
                                </select>
                            </form>
                        </td>
                        <td>
                            <small class="text-muted">{{ $task->created_at->format('M d, Y') }}</small>
                        </td>
                        <td>
                            <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('tasks.destroy', $task) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" 
                                        onclick="return confirm('Delete this task?')">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">
                            <p class="text-muted mb-0">No tasks yet. Create your first task!</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<div class="mt-4">
    {{ $tasks->links() }}
</div>
@endsection
```

#### Step 4: Test
1. Login as a user
2. Navigate to /tasks
3. See tasks listed (if any exist)
4. Check pagination works
5. Check Debugbar for N+1 queries

### Deliverables
- ✅ TaskController with index() method
- ✅ Tasks filtered by current user
- ✅ Pagination (8 items/page)
- ✅ Index view displays correctly
- ✅ Relationships loaded properly
- ✅ No N+1 queries
- ✅ Git commit: "Implement task listing with pagination"

---

## 📌 TASK: CRUD-002 - Create Task (Store Method)

**Type:** Feature  
**Priority:** P0 (Critical)  
**Estimated Time:** 1.5 hours  
**Status:** Todo  
**Branch:** `feature/task-crud`

### Description
Implement task creation with form validation and proper association with current user.

### User Story
**US4 - Create Task:** Users can create tasks with title, description, category, status

### Acceptance Criteria
- [ ] Create form page accessible at /tasks/create
- [ ] Form has fields: title, description, category, status
- [ ] Form validation: title required, category required
- [ ] @csrf token on form
- [ ] Task created with current user_id
- [ ] Status defaults to 'pending'
- [ ] Success message after creation
- [ ] User redirected to tasks list
- [ ] Commit message: "Add task creation with validation"

### Instructions

#### Step 1: Implement store() Method
File: `app/Http/Controllers/TaskController.php`

Update the store() method:

```php
public function store(Request $request) {
    // Validate input
    $validated = $request->validate([
        'title' => ['required', 'string', 'max:255'],
        'description' => ['nullable', 'string'],
        'category_id' => ['required', 'exists:categories,id'],
        'status' => ['nullable', 'in:pending,in_progress,completed'],
    ]);

    // Set current user_id
    $validated['user_id'] = auth()->id();

    // Create task
    Task::create($validated);

    return redirect()
        ->route('tasks.index')
        ->with('success', 'Task created successfully!');
}
```

#### Step 2: Create Create View
File: `resources/views/tasks/create.blade.php`

```blade
@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8">
        <h1>Create New Task</h1>

        <form action="{{ route('tasks.store') }}" method="POST" class="mt-4">
            @csrf

            <!-- Title -->
            <div class="mb-3">
                <label for="title" class="form-label">Task Title *</label>
                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                       id="title" name="title" value="{{ old('title') }}" 
                       placeholder="What do you need to do?" required>
                @error('title')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" name="description" rows="4" 
                          placeholder="Add more details...">{{ old('description') }}</textarea>
                @error('description')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Category -->
            <div class="mb-3">
                <label for="category_id" class="form-label">Category *</label>
                <select class="form-select @error('category_id') is-invalid @enderror" 
                        id="category_id" name="category_id" required>
                    <option value="">-- Select Category --</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" 
                                {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Status -->
            <div class="mb-3">
                <label for="status" class="form-label">Initial Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="pending" selected>To Do</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Done</option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Create Task</button>
                <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
```

#### Step 3: Test in Browser
1. Click "New Task" button
2. Fill form with valid data
3. Submit and verify success message
4. Check task appears in list
5. Test validation (leave title empty)

### Deliverables
- ✅ Create form view working
- ✅ Form validation functional
- ✅ @csrf token present
- ✅ Task created with user_id
- ✅ Success message displayed
- ✅ Redirect to task list
- ✅ Git commit: "Add task creation with validation"

---

## 📌 TASK: CRUD-003 - Edit & Update Task

**Type:** Feature  
**Priority:** P0 (Critical)  
**Estimated Time:** 2 hours  
**Status:** Todo  
**Branch:** `feature/task-crud`

### Description
Implement task editing with ownership verification and form validation.

### User Story
**US5 - Edit Task:** Users can modify title, description, category, status of own tasks

### Acceptance Criteria
- [ ] Edit form accessible at /tasks/{task}/edit
- [ ] Form pre-filled with current task data
- [ ] Ownership verification before allowing edit (abort 403 if unauthorized)
- [ ] Form validation same as create
- [ ] @csrf token on form
- [ ] Success message after update
- [ ] User redirected to task list
- [ ] 403 error if user tries to edit another user's task
- [ ] Commit message: "Add task editing with ownership check"

### Instructions

#### Step 1: Implement edit() & update() Methods
File: `app/Http/Controllers/TaskController.php`

```php
public function edit(Task $task) {
    // Check ownership
    if ($task->user_id !== auth()->id()) {
        abort(403, 'Unauthorized to edit this task');
    }

    $categories = Category::all();
    return view('tasks.edit', [
        'task' => $task,
        'categories' => $categories,
    ]);
}

public function update(Request $request, Task $task) {
    // Check ownership
    if ($task->user_id !== auth()->id()) {
        abort(403, 'Unauthorized to update this task');
    }

    // Validate input
    $validated = $request->validate([
        'title' => ['required', 'string', 'max:255'],
        'description' => ['nullable', 'string'],
        'category_id' => ['required', 'exists:categories,id'],
        'status' => ['nullable', 'in:pending,in_progress,completed'],
    ]);

    // Update task
    $task->update($validated);

    return redirect()
        ->route('tasks.index')
        ->with('success', 'Task updated successfully!');
}
```

#### Step 2: Create Edit View
File: `resources/views/tasks/edit.blade.php`

```blade
@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8">
        <h1>Edit Task</h1>

        <form action="{{ route('tasks.update', $task) }}" method="POST" class="mt-4">
            @csrf
            @method('PUT')

            <!-- Title -->
            <div class="mb-3">
                <label for="title" class="form-label">Task Title *</label>
                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                       id="title" name="title" value="{{ old('title', $task->title) }}" required>
                @error('title')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" name="description" rows="4">{{ old('description', $task->description) }}</textarea>
                @error('description')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Category -->
            <div class="mb-3">
                <label for="category_id" class="form-label">Category *</label>
                <select class="form-select @error('category_id') is-invalid @enderror" 
                        id="category_id" name="category_id" required>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" 
                                {{ old('category_id', $task->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Status -->
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="pending" {{ old('status', $task->status) === 'pending' ? 'selected' : '' }}>
                        To Do
                    </option>
                    <option value="in_progress" {{ old('status', $task->status) === 'in_progress' ? 'selected' : '' }}>
                        In Progress
                    </option>
                    <option value="completed" {{ old('status', $task->status) === 'completed' ? 'selected' : '' }}>
                        Done
                    </option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Update Task</button>
                <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
```

#### Step 3: Test
1. Create a task as User A
2. Try to edit as User A → should work
3. Edit task and verify changes saved
4. Use quick status change in list
5. Test with another user or manually change user_id in DB and try to edit

### Deliverables
- ✅ Edit form pre-populated with task data
- ✅ Ownership verification working
- ✅ Form validation functional
- ✅ Update saves to database
- ✅ Success message displayed
- ✅ 403 error on unauthorized edit
- ✅ Git commit: "Add task editing with ownership check"

---

## 📌 TASK: CRUD-004 - Delete Task with Confirmation

**Type:** Feature  
**Priority:** P0 (Critical)  
**Estimated Time:** 1 hour  
**Status:** Todo  
**Branch:** `feature/task-crud`

### Description
Implement task deletion with ownership verification and confirmation.

### User Story
**US6 - Delete Task:** Users can delete own tasks with confirmation

### Acceptance Criteria
- [ ] Delete button on each task
- [ ] Confirmation dialog before deletion
- [ ] Ownership verification before allowing delete
- [ ] @csrf token on delete form
- [ ] Success message after deletion
- [ ] User redirected to task list
- [ ] 403 error if user tries to delete another's task
- [ ] Task actually removed from database
- [ ] Commit message: "Add task deletion with confirmation"

### Instructions

#### Step 1: Implement destroy() Method
File: `app/Http/Controllers/TaskController.php`

```php
public function destroy(Task $task) {
    // Check ownership
    if ($task->user_id !== auth()->id()) {
        abort(403, 'Unauthorized to delete this task');
    }

    $task->delete();

    return redirect()
        ->route('tasks.index')
        ->with('success', 'Task deleted successfully!');
}
```

#### Step 2: Delete Button Already in Index View
The index view already has the delete button with confirmation:

```blade
<form action="{{ route('tasks.destroy', $task) }}" method="POST" style="display:inline;">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-sm btn-danger" 
            onclick="return confirm('Delete this task?')">
        Delete
    </button>
</form>
```

#### Step 3: Test
1. Create a task
2. Click Delete
3. Verify confirmation dialog appears
4. Click OK and verify task removed
5. Check database task is gone
6. Try to delete another user's task (403 error)

### Deliverables
- ✅ Delete functionality working
- ✅ Confirmation dialog appears
- ✅ Ownership verification in place
- ✅ Task removed from database
- ✅ Success message displayed
- ✅ @csrf token present
- ✅ Git commit: "Add task deletion with confirmation"

---

## 📌 TASK: CRUD-005 - Quick Status Update from List

**Type:** Feature  
**Priority:** P1 (High)  
**Estimated Time:** 1 hour  
**Status:** Todo  
**Branch:** `feature/task-crud`

### Description
Allow users to change task status directly from task list without opening edit form.

### User Story
**US7 - Quick Status Update:** Users can change status from list (pending → in_progress → completed)

### Acceptance Criteria
- [ ] Status dropdown on each task in list
- [ ] Clicking status dropdown submits form immediately
- [ ] Only user's own tasks can have status changed
- [ ] No page reload (optional: AJAX), or page reloads with success message
- [ ] Status visually changes on page
- [ ] Commit message: "Implement quick status update from task list"

### Instructions

#### Step 1: Status Dropdown Already in Index View
The index view already has the status update form:

```blade
<td>
    <form action="{{ route('tasks.update', $task) }}" method="POST" style="display:inline;">
        @csrf
        @method('PUT')
        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
            <option value="pending" {{ $task->status === 'pending' ? 'selected' : '' }}>
                To Do
            </option>
            <option value="in_progress" {{ $task->status === 'in_progress' ? 'selected' : '' }}>
                In Progress
            </option>
            <option value="completed" {{ $task->status === 'completed' ? 'selected' : '' }}>
                Done
            </option>
        </select>
    </form>
</td>
```

#### Step 2: Verify update() Method Handles Partial Update
The update() method should only update status when just status is sent:

```php
public function update(Request $request, Task $task) {
    // Check ownership
    if ($task->user_id !== auth()->id()) {
        abort(403, 'Unauthorized to update this task');
    }

    // Validate input
    $validated = $request->validate([
        'title' => ['nullable', 'string', 'max:255'],
        'description' => ['nullable', 'string'],
        'category_id' => ['nullable', 'exists:categories,id'],
        'status' => ['nullable', 'in:pending,in_progress,completed'],
    ]);

    // Only update provided fields
    $task->update(array_filter($validated));

    return redirect()
        ->route('tasks.index')
        ->with('success', 'Task updated successfully!');
}
```

#### Step 3: Test
1. Open task list
2. Click status dropdown on a task
3. Select new status
4. Page reloads with success message
5. Status is updated in display

### Deliverables
- ✅ Status dropdown on each task
- ✅ Submits on change
- ✅ Ownership verification working
- ✅ Status updates in database
- ✅ Success message shown
- ✅ Git commit: "Implement quick status update from task list"

---

---

# PHASE 5: FILTERING & ADVANCED FEATURES

---

## 📌 TASK: FILTER-001 - Implement Status Filter

**Type:** Feature  
**Priority:** P1 (High)  
**Estimated Time:** 1.5 hours  
**Status:** Todo  
**Branch:** `feature/filters`

### Description
Add ability to filter tasks by status (pending, in_progress, completed).

### User Story
**US8 - Filter by Status:** Users can filter tasks to see only specific statuses

### Acceptance Criteria
- [ ] Filter buttons/dropdown in task list header
- [ ] Shows only tasks with selected status
- [ ] "All" option shows all tasks
- [ ] Filter state persists in URL query string
- [ ] Filter works with pagination
- [ ] Active filter visually highlighted
- [ ] Commit message: "Add status filter to task list"

### Instructions

#### Step 1: Update index() Controller Method
File: `app/Http/Controllers/TaskController.php`

```php
public function index(Request $request) {
    $query = auth()->user()->tasks()
        ->with('category');

    // Apply status filter
    if ($request->filled('status')) {
        $query->where('status', $request->input('status'));
    }

    $tasks = $query->latest()->paginate(8);

    $categories = Category::all();

    return view('tasks.index', [
        'tasks' => $tasks,
        'categories' => $categories,
        'selectedStatus' => $request->input('status'),
    ]);
}
```

#### Step 2: Update Index View with Filter
File: `resources/views/tasks/index.blade.php`

Add filter buttons above task table:

```blade
<!-- Filter Section -->
<div class="mb-3">
    <div class="btn-group" role="group">
        <a href="{{ route('tasks.index') }}" 
           class="btn {{ !request('status') ? 'btn-primary' : 'btn-outline-primary' }}">
            All
        </a>
        <a href="{{ route('tasks.index', ['status' => 'pending']) }}" 
           class="btn {{ request('status') === 'pending' ? 'btn-primary' : 'btn-outline-primary' }}">
            📋 To Do
        </a>
        <a href="{{ route('tasks.index', ['status' => 'in_progress']) }}" 
           class="btn {{ request('status') === 'in_progress' ? 'btn-primary' : 'btn-outline-primary' }}">
            ⚙️ In Progress
        </a>
        <a href="{{ route('tasks.index', ['status' => 'completed']) }}" 
           class="btn {{ request('status') === 'completed' ? 'btn-primary' : 'btn-outline-primary' }}">
            ✅ Completed
        </a>
    </div>
</div>
```

#### Step 3: Test
1. Click each filter button
2. Verify only selected status tasks shown
3. Click "All" to see all tasks again
4. Test with pagination
5. Verify URL contains query string: ?status=pending

### Deliverables
- ✅ Filter buttons in view
- ✅ Filtering logic in controller
- ✅ Filter persists in URL
- ✅ Works with pagination
- ✅ Active filter highlighted
- ✅ Git commit: "Add status filter to task list"

---

## 📌 TASK: FILTER-002 - Implement Category Filter

**Type:** Feature  
**Priority:** P1 (High)  
**Estimated Time:** 1.5 hours  
**Status:** Todo  
**Branch:** `feature/filters`

### Description
Add ability to filter tasks by category.

### User Story
**US9 - Filter by Category:** Users can filter tasks by category

### Acceptance Criteria
- [ ] Category filter dropdown in task list header
- [ ] Shows only tasks with selected category
- [ ] "All" option shows all tasks
- [ ] Filter state persists in URL query string
- [ ] Can combine with status filter
- [ ] Works with pagination
- [ ] Commit message: "Add category filter to task list"

### Instructions

#### Step 1: Update index() Method for Category Filter
File: `app/Http/Controllers/TaskController.php`

```php
public function index(Request $request) {
    $query = auth()->user()->tasks()
        ->with('category');

    // Apply status filter
    if ($request->filled('status')) {
        $query->where('status', $request->input('status'));
    }

    // Apply category filter
    if ($request->filled('category')) {
        $query->where('category_id', $request->input('category'));
    }

    $tasks = $query->latest()->paginate(8);
    $categories = Category::all();

    return view('tasks.index', [
        'tasks' => $tasks,
        'categories' => $categories,
        'selectedStatus' => $request->input('status'),
        'selectedCategory' => $request->input('category'),
    ]);
}
```

#### Step 2: Add Category Filter to View
File: `resources/views/tasks/index.blade.php`

Add category dropdown after status filter:

```blade
<!-- Filter Section -->
<div class="row mb-3">
    <div class="col-md-6">
        <label class="form-label">Status:</label>
        <div class="btn-group" role="group">
            <a href="{{ route('tasks.index', ['category' => request('category')]) }}" 
               class="btn {{ !request('status') ? 'btn-primary' : 'btn-outline-primary' }}">
                All
            </a>
            <a href="{{ route('tasks.index', ['status' => 'pending', 'category' => request('category')]) }}" 
               class="btn {{ request('status') === 'pending' ? 'btn-primary' : 'btn-outline-primary' }}">
                To Do
            </a>
            <a href="{{ route('tasks.index', ['status' => 'in_progress', 'category' => request('category')]) }}" 
               class="btn {{ request('status') === 'in_progress' ? 'btn-primary' : 'btn-outline-primary' }}">
                In Progress
            </a>
            <a href="{{ route('tasks.index', ['status' => 'completed', 'category' => request('category')]) }}" 
               class="btn {{ request('status') === 'completed' ? 'btn-primary' : 'btn-outline-primary' }}">
                Completed
            </a>
        </div>
    </div>

    <div class="col-md-6">
        <label for="category" class="form-label">Category:</label>
        <select class="form-select" id="category" onchange="
            const cat = this.value;
            const status = new URLSearchParams(window.location.search).get('status');
            let url = '{{ route('tasks.index') }}';
            if (status || cat) {
                url += '?';
                if (status) url += 'status=' + status + '&';
                if (cat) url += 'category=' + cat;
            }
            window.location.href = url;
        ">
            <option value="">All Categories</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" 
                        {{ request('category') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>
</div>
```

#### Step 3: Test
1. Select a category from dropdown
2. Verify only tasks in that category shown
3. Select "All Categories"
4. Combine status + category filters
5. Verify URL contains both query parameters
6. Test pagination with filters active

### Deliverables
- ✅ Category filter dropdown
- ✅ Filtering logic in controller
- ✅ Can combine with status filter
- ✅ Filter persists in URL
- ✅ Works with pagination
- ✅ Git commit: "Add category filter to task list"

---

## 📌 TASK: BONUS-001 - Add Due Date Field & Overdue Highlighting (OPTIONAL)

**Type:** Feature  
**Priority:** P2 (Nice to Have)  
**Estimated Time:** 2 hours  
**Status:** Todo  
**Branch:** `feature/bonus`

### Description
Add due_date field to tasks and highlight overdue tasks in red.

### Bonus Features
- Date of due date field
- Highlight overdue tasks in red on list

### Instructions

#### Step 1: Due Date Already in Migration
The due_date field is already in the tasks migration, so no migration needed.

#### Step 2: Update Create/Edit Forms
Add due_date input to create and edit views:

```blade
<div class="mb-3">
    <label for="due_date" class="form-label">Due Date</label>
    <input type="date" class="form-control" id="due_date" name="due_date" 
           value="{{ old('due_date', $task->due_date ?? '') }}">
</div>
```

#### Step 3: Validate Due Date in Controller
```php
$validated = $request->validate([
    'title' => ['required', 'string', 'max:255'],
    'description' => ['nullable', 'string'],
    'category_id' => ['required', 'exists:categories,id'],
    'status' => ['nullable', 'in:pending,in_progress,completed'],
    'due_date' => ['nullable', 'date', 'after:today'],
]);
```

#### Step 4: Display Due Date in List
```blade
<td>
    @if ($task->due_date)
        <small class="{{ $task->due_date < now() && $task->status !== 'completed' ? 'text-danger' : 'text-muted' }}">
            {{ $task->due_date->format('M d, Y') }}
            @if ($task->due_date < now() && $task->status !== 'completed')
                🔴 Overdue
            @endif
        </small>
    @else
        <small class="text-muted">No due date</small>
    @endif
</td>
```

### Deliverables
- ✅ Due date field in forms
- ✅ Due date displayed in task list
- ✅ Overdue tasks highlighted in red
- ✅ Validation for future dates

---

---

# PHASE 6: QUALITY & DEBUGGING

---

## 📌 TASK: DEBUG-001 - Setup Xdebug with VS Code (ADVANCED)

**Type:** Quality  
**Priority:** P2 (Advanced)  
**Estimated Time:** 2 hours  
**Status:** Todo

### Description
Configure Xdebug for step-by-step debugging in TaskController::store() method.

### Requirements
- [ ] Xdebug installed and configured
- [ ] VS Code configured for debugging
- [ ] Breakpoint set in store() method
- [ ] Able to step through execution
- [ ] Able to inspect $request and auth()->id() values

### Instructions

#### Step 1: Install Xdebug (If Using Docker)
Add to Dockerfile or docker-compose.yml:
```dockerfile
RUN pecl install xdebug && docker-php-ext-enable xdebug
COPY xdebug.ini /usr/local/etc/php/conf.d/99-xdebug.ini
```

Or if not using Docker, install via PHP:
```bash
pecl install xdebug
```

#### Step 2: Configure xdebug.ini
Create/modify `/usr/local/etc/php/conf.d/xdebug.ini`:

```ini
[xdebug]
zend_extension = xdebug
xdebug.mode = debug
xdebug.start_with_request = trigger
xdebug.client_host = 127.0.0.1
xdebug.client_port = 9003
xdebug.log = /tmp/xdebug.log
```

#### Step 3: Configure VS Code
Install "PHP Debug" extension by Felix Becker.

Create `.vscode/launch.json`:
```json
{
    "version": "0.2.0",
    "configurations": [
        {
            "name": "Listen for Xdebug",
            "type": "php",
            "port": 9003,
            "pathMapping": {
                "/var/www/html": "${workspaceRoot}"
            }
        }
    ]
}
```

#### Step 4: Set Breakpoint
In `app/Http/Controllers/TaskController.php`, click in the gutter next to the first line of the store() method to set a breakpoint.

#### Step 5: Start Debugging
1. Click "Run and Debug" in VS Code
2. Click "Listen for Xdebug"
3. Open browser to task creation form
4. Fill and submit form
5. Debugger stops at breakpoint
6. Step through code line by line
7. Inspect variables in debug console

### Deliverables
- ✅ Xdebug installed and running
- ✅ VS Code configured
- ✅ Can set breakpoints and step through
- ✅ Can inspect variable values

---

## 📌 TASK: QUALITY-001 - Debugbar & N+1 Query Detection

**Type:** Quality  
**Priority:** P1 (High)  
**Estimated Time:** 1.5 hours  
**Status:** Todo

### Description
Use Laravel Debugbar to identify and fix N+1 query problems.

### Acceptance Criteria
- [ ] Debugbar installed (already done)
- [ ] Task list page checked for N+1 queries
- [ ] Relationships eager-loaded with `->with()`
- [ ] Query count optimized
- [ ] Test shows reduced query count
- [ ] Documentation in README about debugging

### Instructions

#### Step 1: Check Task List Queries
1. Navigate to /tasks
2. Open Debugbar at bottom right
3. Click "Queries" tab
4. Count total queries
5. Look for repeated queries for same data (N+1 pattern)

#### Step 2: Fix N+1 Issues
The index() method should use eager loading:

```php
public function index(Request $request) {
    $query = auth()->user()->tasks()
        ->with('category')  // ← Eager load to avoid N+1
        ->with('user');

    // ... filters ...

    $tasks = $query->latest()->paginate(8);
    // ...
}
```

Without `.with()`, each task loads its category in a separate query (N+1).

#### Step 3: Verify Optimization
1. Open task list again
2. Check Debugbar queries
3. Verify fewer total queries
4. Should be ~2-3 queries instead of 10+ for 8 tasks

### Deliverables
- ✅ Debugbar working properly
- ✅ N+1 queries identified
- ✅ Eager loading implemented
- ✅ Query count optimized

---

## 📌 TASK: QUALITY-002 - Laravel Telescope Request Analysis

**Type:** Quality  
**Priority:** P1 (High)  
**Estimated Time:** 1.5 hours  
**Status:** Todo

### Description
Use Laravel Telescope to trace and analyze HTTP requests.

### Requirements
- [ ] Telescope installed (already done)
- [ ] Can access /telescope dashboard
- [ ] Can locate specific requests
- [ ] Can read request payload
- [ ] Can view SQL queries executed
- [ ] Can identify exceptions
- [ ] Can trace complete request flow

### Instructions

#### Step 1: Access Telescope
1. Navigate to `http://localhost:8000/telescope` in browser
2. You should see dashboard with request list

#### Step 2: Analyze Task Creation Request
1. Submit task creation form
2. Look for POST /tasks request in Telescope
3. Click the request to expand
4. View "Payload" tab - see form data submitted
5. View "Queries" tab - see all SQL executed
6. View "Exceptions" tab - see any errors

#### Step 3: Trace Request Flow
1. Note the timeline of each query
2. See when database queries executed
3. Check for any slow queries
4. Note response status code and headers

#### Step 4: Test with Errors
1. Submit form with invalid data (no title)
2. View validation error in Telescope
3. See how validation works

### Deliverables
- ✅ Telescope dashboard accessible
- ✅ Can locate requests
- ✅ Can read payloads and queries
- ✅ Can analyze exceptions
- ✅ Understanding of request flow

---

## 📌 TASK: FINAL-001 - Code Review & Git Commits

**Type:** Quality  
**Priority:** P0 (Critical)  
**Estimated Time:** 2 hours  
**Status:** Todo

### Description
Final review of code quality and ensure proper git commit history.

### Acceptance Criteria
- [ ] Minimum 15 commits with clear messages
- [ ] Daily commits (at least 1 per working day)
- [ ] Feature branches used: feature/auth, feature/task-crud, feature/filters
- [ ] All commits merged to main
- [ ] Code follows Laravel conventions
- [ ] $fillable defined in all models
- [ ] CSRF tokens on all forms
- [ ] Form validation on all inputs
- [ ] Ownership checks before modification
- [ ] No debug code or var_dump() left

### Instructions

#### Step 1: Review Commit History
```bash
git log --oneline | head -20
```

Should show at least 15 commits with descriptive messages like:
- "Initial Laravel setup with Debugbar and Telescope"
- "Create database migrations for users, categories, and tasks"
- "Add User model with Task relationship"
- "Create registration and login controllers"
- "Implement task CRUD with validation"
- "Add status and category filters"
- etc.

#### Step 2: Check Code Quality
```bash
php artisan route:list  # Verify all routes exist and have names
php artisan tinker       # Test models work correctly
```

#### Step 3: Code Review Checklist
- [ ] All forms have @csrf tokens
- [ ] All forms have validation
- [ ] All models have $fillable defined
- [ ] All relations defined (hasMany, belongsTo)
- [ ] Ownership verified before update/delete
- [ ] Error messages for unauthorized access
- [ ] Success messages after operations
- [ ] No N+1 queries (eager loading used)
- [ ] Pagination implemented (8 items/page)
- [ ] Filters working correctly

#### Step 4: Final Commits
Make final commits if needed:
```bash
git add .
git commit -m "Final code review and cleanup"
git push origin main
```

### Deliverables
- ✅ Minimum 15 commits
- ✅ Clear, descriptive commit messages
- ✅ Feature branches used
- ✅ Code quality standards met
- ✅ All features working

---

## 📌 TASK: DOCUMENTATION-001 - README & API Documentation

**Type:** Documentation  
**Priority:** P1 (High)  
**Estimated Time:** 2 hours  
**Status:** Todo

### Description
Create comprehensive README with installation instructions and project documentation.

### Acceptance Criteria
- [ ] README.md created with project overview
- [ ] Installation instructions clear and complete
- [ ] Database setup documented
- [ ] How to run the application
- [ ] Debugging tools usage documented
- [ ] Key features listed
- [ ] Technologies used listed
- [ ] Known limitations noted
- [ ] Future improvements suggested

### Instructions

#### Step 1: Create README.md
File: `README.md`

```markdown
# Laravel Task Manager MVP

A simple, secure task management application built with Laravel for team collaboration.

## Features

- ✅ User authentication (register, login, logout)
- ✅ Full CRUD operations for tasks
- ✅ Filter tasks by status and category
- ✅ Quick status update from task list
- ✅ Due date tracking with overdue highlighting
- ✅ Data isolation (users only see their tasks)
- ✅ Responsive design with Bootstrap
- ✅ Debugging tools (Debugbar, Telescope)

## Tech Stack

- **Framework:** Laravel 11
- **Database:** MySQL / PostgreSQL
- **Frontend:** Blade Templating + Bootstrap 5
- **Debugging:** Laravel Debugbar + Telescope
- **ORM:** Eloquent

## Installation

### Requirements
- PHP 8.1+
- Composer
- MySQL 8.0+ or PostgreSQL 13+
- Node.js (for asset compilation)

### Setup Steps

1. **Clone Repository**
```bash
git clone <repository-url>
cd task-manager
```

2. **Install Dependencies**
```bash
composer install
npm install
```

3. **Environment Configuration**
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` with your database credentials:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_manager
DB_USERNAME=root
DB_PASSWORD=
```

4. **Create Database**
```bash
mysql -u root -p
CREATE DATABASE task_manager;
EXIT;
```

5. **Run Migrations & Seeders**
```bash
php artisan migrate
php artisan db:seed
```

6. **Build Assets**
```bash
npm run dev
```

7. **Start Development Server**
```bash
php artisan serve
```

Visit `http://localhost:8000`

## Usage

### User Registration
1. Go to `/register`
2. Fill in name, email, password
3. Submit to create account
4. Auto-redirected to task list

### Create Task
1. Click "+ New Task"
2. Fill title (required) and description
3. Select category
4. Optionally set due date
5. Submit

### Filter Tasks
- Click status buttons to filter by status
- Use category dropdown to filter by category
- Combine both filters
- Click "All" to clear filters

### Quick Status Update
- Click status dropdown on task row
- Select new status
- Status updates immediately

### Edit Task
- Click "Edit" button on task
- Modify fields
- Click "Update Task"

### Delete Task
- Click "Delete" button
- Confirm in dialog
- Task removed

## Debugging

### Laravel Debugbar
- Appears at bottom of every page in development
- Click to expand and view:
  - Query count and execution time
  - Route and controller info
  - Session variables
  - Log messages

### Laravel Telescope
- Access at `/telescope`
- View all requests and queries
- Identify slow queries
- Track exceptions

### Xdebug (VS Code)
- See `/docs/XDEBUG_SETUP.md` for configuration
- Set breakpoints in IDE
- Step through code execution
- Inspect variable values

## Project Structure

```
task-manager/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── Auth/
│   │       │   ├── RegisterController.php
│   │       │   └── LoginController.php
│   │       └── TaskController.php
│   └── Models/
│       ├── User.php
│       ├── Task.php
│       └── Category.php
├── database/
│   ├── migrations/
│   ├── seeders/
│   └── factories/
├── resources/
│   └── views/
│       ├── auth/
│       ├── tasks/
│       └── layouts/
├── routes/
│   └── web.php
└── README.md
```

## API Routes

### Authentication
- `GET /register` - Registration form
- `POST /register` - Create account
- `GET /login` - Login form
- `POST /login` - Authenticate user
- `POST /logout` - Logout user

### Tasks
- `GET /tasks` - List user's tasks
- `GET /tasks/create` - Create form
- `POST /tasks` - Store new task
- `GET /tasks/{task}/edit` - Edit form
- `PUT /tasks/{task}` - Update task
- `DELETE /tasks/{task}` - Delete task

## Security

- ✅ CSRF protection on all forms
- ✅ Ownership verification before modification
- ✅ Input validation on all forms
- ✅ Password hashing with bcrypt
- ✅ SQL injection prevention (Eloquent)
- ✅ XSS protection (Blade escaping)

## Performance

- ✅ Eager loading to prevent N+1 queries
- ✅ Pagination (8 items per page)
- ✅ Database indexing on foreign keys
- ✅ Query optimization using Debugbar

## Known Limitations

- Single user per account (no team collaboration)
- No real-time updates (page reload required)
- No file attachments for tasks
- No task comments or notes
- No email notifications

## Future Improvements

- [ ] Task sharing and team collaboration
- [ ] WebSocket real-time updates
- [ ] File attachments
- [ ] Task comments
- [ ] Email notifications for due tasks
- [ ] Dark mode
- [ ] Mobile app
- [ ] Calendar view for tasks
- [ ] Export to CSV/PDF
- [ ] Recurring tasks

## Troubleshooting

### "SQLSTATE[HY000]: General error: 1030 Got error... "
- Clear cache: `php artisan cache:clear`
- Ensure database permissions are correct

### "No database selected"
- Check `.env` DB_DATABASE value
- Run: `php artisan migrate`

### Assets not loading
- Run: `npm run dev`
- Clear browser cache

### Debugbar not showing
- Ensure `APP_DEBUG=true` in `.env`
- Check `config/debugbar.php` enabled

## Testing

Manual testing can be done using:
1. Browser navigation
2. Postman (for API endpoints)
3. Laravel Tinker: `php artisan tinker`

## Contributing

Follow these guidelines:
1. Create feature branch: `git checkout -b feature/feature-name`
2. Commit frequently with clear messages
3. Push to origin: `git push origin feature/feature-name`
4. Create pull request

## License

MIT License - see LICENSE file

## Support

For issues or questions:
1. Check `/docs` folder
2. Review Laravel documentation
3. Check Debugbar/Telescope output
```

#### Step 2: Create Additional Documentation
Create `/docs/DEBUGGING.md`:
```markdown
# Debugging Guide

## Laravel Debugbar

### Viewing Queries
1. Page loads
2. Look at Debugbar at bottom right
3. Click "Queries" tab
4. See SQL executed, time taken, variables

### Finding N+1 Issues
1. Check query count in Debugbar
2. Too many similar queries = N+1 problem
3. Add `.with()` to eager load relationships

## Laravel Telescope

### Accessing Telescope
- Visit `http://localhost:8000/telescope`

### Analyzing Requests
1. Click a request in the list
2. View "Payload" for form data
3. View "Queries" for SQL executed
4. View "Exceptions" for errors

## Xdebug with VS Code

[See XDEBUG_SETUP.md]
```

### Deliverables
- ✅ README.md with complete documentation
- ✅ Installation instructions clear
- ✅ Features documented
- ✅ Debugging guide included
- ✅ Troubleshooting section
- ✅ Architecture explained

---

---

# CHECKLIST - Final Verification

Use this checklist before submitting:

- [ ] All User Stories (US1-US9) implemented
- [ ] Bonus features completed (status counter, due dates)
- [ ] Authentication fully working
- [ ] CRUD operations complete
- [ ] Filters functional
- [ ] All forms have @csrf tokens
- [ ] All forms have validation
- [ ] Ownership checks in place
- [ ] Pagination working (8/page)
- [ ] No N+1 queries
- [ ] Debugbar working
- [ ] Telescope accessible
- [ ] Minimum 15 commits with clear messages
- [ ] Daily commits present
- [ ] Feature branches used
- [ ] MCD & MLD documents created
- [ ] README.md complete
- [ ] Code follows Laravel conventions
- [ ] All routes named and grouped
- [ ] All models have relationships defined
- [ ] All models have $fillable defined
- [ ] No debug code left (var_dump, dd, etc)

---

**Good luck with your Laravel project! 🚀**
