# Laravel Task Management MVP - Project Brief

## 📋 Project Overview

A local startup needs a simple internal tool for employees to manage their daily tasks. The application should allow each employee to log in, manage their own tasks, and track progress without complexity.

**Goal:** Develop a functional, secure Laravel MVP where each user has an isolated space and can only see/edit their own tasks.

---

## 📅 Project Timeline

| Milestone | Date | Time |
|-----------|------|------|
| **Project Launch** | Monday, April 27, 2026 | 10:00 AM |
| **MCD & MLD Deadline** | Monday, April 28, 2026 | 3:00 PM |
| **Project Deadline** | Friday, May 1, 2026 | 5:00 PM |
| **Duration** | 4 days | Individual work |

---

## 🎯 Core Requirements

### Technical Stack
- **Framework:** Laravel
- **Database:** MySQL/PostgreSQL via Laravel Migrations
- **Frontend:** Blade Templating
- **Authentication:** Laravel Built-in Auth
- **Debugging Tools:** Laravel Debugbar & Telescope

### Key Features
1. **User Authentication** - Registration, Login, Logout
2. **Task Management** - Full CRUD operations (Create, Read, Update, Delete)
3. **Task Filtering** - By Status and Category
4. **Quick Status Update** - Change status directly from list
5. **Data Isolation** - Users only see their own tasks
6. **Performance Optimization** - Pagination, N+1 query prevention

---

## 📐 Database Architecture

### Entities

#### User
- id (PK)
- name
- email (unique)
- email_verified_at
- password
- remember_token
- created_at, updated_at

#### Category
- id (PK)
- name
- description
- created_at, updated_at

#### Task
- id (PK)
- user_id (FK → User)
- category_id (FK → Category)
- title
- description
- status (enum: pending, in_progress, completed)
- due_date (nullable)
- created_at, updated_at

### Relationships
```
User (1) ───── hasMany ───── (N) Task
Category (1) ───── hasMany ───── (N) Task
```

---

## 👤 User Stories

### Authentication Module

#### US1 - User Registration
**As a** new user  
**I want to** create an account with name, email, and password  
**So that** I can access my task management space

**Acceptance Criteria:**
- Registration form with name, email, password fields
- Form validation (email unique, password strong)
- Success message and automatic login after registration
- Redirect to task list

---

#### US2 - Login & Logout
**As a** registered user  
**I want to** log in with email/password and log out  
**So that** only I can access my tasks

**Acceptance Criteria:**
- Login form with email and password
- Remember me functionality
- Logout button in navigation
- Redirect to login if accessing protected routes

---

### Task Management Module

#### US3 - View All Tasks
**As a** logged-in user  
**I want to** see a list of ALL my tasks with title, category, status, and creation date  
**So that** I can monitor all my work

**Acceptance Criteria:**
- Display all user's tasks in a table/list format
- Show: Title, Category, Status, Created Date
- Only show tasks belonging to the logged-in user
- Pagination (8 items per page)

---

#### US4 - Create Task
**As a** logged-in user  
**I want to** create a new task with title, description, category, and status  
**So that** I can add tasks to my list

**Acceptance Criteria:**
- Create task form with all required fields
- Status defaults to "pending"
- Form validation (title required, etc.)
- CSRF token on form
- Success message after creation
- Redirect to task list

---

#### US5 - Edit Task
**As a** logged-in user  
**I want to** modify title, description, category, or status of my task  
**So that** I can keep my tasks up to date

**Acceptance Criteria:**
- Edit form pre-populated with current task data
- Ownership verification before allowing edit
- Form validation
- CSRF token on form
- Success message after update
- 403 error if user tries to edit another user's task

---

#### US6 - Delete Task
**As a** logged-in user  
**I want to** delete a task I own with confirmation  
**So that** I can remove completed or irrelevant tasks

**Acceptance Criteria:**
- Delete button on task
- Confirmation modal/dialog before deletion
- Ownership verification before deletion
- Success message after deletion
- 403 error if user tries to delete another user's task

---

#### US7 - Quick Status Update
**As a** logged-in user  
**I want to** change task status (pending → in_progress → completed) from the list  
**So that** I can quickly update progress without opening the edit form

**Acceptance Criteria:**
- Status dropdown/selector on each task row
- AJAX update without page reload (optional)
- Success feedback to user
- Only allow status change on own tasks

---

### Filtering Module

#### US8 - Filter by Status
**As a** logged-in user  
**I want to** filter tasks by status (To Do / In Progress / Completed)  
**So that** I can focus on what's priority

**Acceptance Criteria:**
- Filter dropdown/buttons in task list header
- Show only tasks with selected status
- "All" option to show everything
- URL parameters for filter state (query strings)
- Selected filter persists in pagination

---

#### US9 - Filter by Category
**As a** logged-in user  
**I want to** filter tasks by category  
**So that** I can organize my work by type

**Acceptance Criteria:**
- Filter dropdown/buttons for categories
- Show only tasks with selected category
- "All" option to show everything
- Can combine with status filter
- URL parameters for filter state

---

## 🎁 Bonus Features

### Priority 1 (Nice to Have)
- **Task Counter Dashboard:** Display count of tasks per status (e.g., 3 To Do, 2 In Progress, 5 Completed)
- **Due Date Feature:** Add due_date column to tasks, highlight overdue tasks in red

### Priority 2 (Advanced)
- **Pagination:** Implement paginate(8) for task lists
- **Xdebug Integration:** Configure Xdebug with VS Code for step-by-step debugging in TaskController::store()

---

## 🔒 Security Requirements

### Authentication & Authorization
- ✅ All routes grouped under `auth` middleware
- ✅ Ownership verification: `if ($task->user_id !== auth()->id()) abort(403)`
- ✅ No direct access to .php files (web root configuration)
- ✅ Redirect to /login for unauthenticated users

### Data Protection
- ✅ CSRF tokens on all forms: `@csrf`
- ✅ Request validation: `$request->validate([...])`
- ✅ $fillable defined in all models
- ✅ Users cannot modify/delete others' tasks

---

## 🏗️ Technical Architecture

### Routing
- **Named Routes:** `php artisan route:list` shows all routes with names
- **Route Grouping:** Auth routes grouped with middleware
- **Convention:** RESTful naming (store, update, destroy, show, edit, create, index)

### Eloquent ORM
- **Model Relations:**
  - `User::hasMany(Task)`
  - `Category::hasMany(Task)`
  - `Task::belongsTo(User)`
  - `Task::belongsTo(Category)`

### Blade Templating
- **Master Layout:** `layouts/app.blade.php`
- **Template Inheritance:** `@extends`, `@section`, `@yield`
- **Directives:** `@auth`, `@guest`, `@foreach`, `@if`, `@include`

### Database
- **All tables via Migrations** (zero raw SQL)
- **Seeders:** Sample categories and test data
- **Structure:** Follow Laravel conventions

### Debugging
- **Laravel Debugbar:** Monitor SQL queries, identify N+1 problems
- **Laravel Telescope:** Track HTTP requests, view payloads, SQL queries, exceptions
- **Xdebug:** (Advanced) Step-through debugging in IDE

---

## 📦 Deliverables

### 1. GitHub Repository
- Minimum 15 commits with clear messages
- Daily commits required (at least 1 per working day)
- Descriptive commit messages: "Add Task migration", "Implement status filter"
- Feature branches: `feature/auth`, `feature/task-crud`, `feature/filters`

### 2. Jira Board
- Share with: abderahmane.merradou@gmail.com (Monday)
- All User Stories as tickets
- Daily updates on ticket status

### 3. Database Design Documents
**Deadline: Monday 28/04 before 3:00 PM**
- **MCD (Conceptual Data Model):** Entities, attributes, relationships with cardinality
- **MLD (Logical Data Model):** Tables, primary keys, foreign keys
- MLD must exactly match final migrations

### 4. Documentation
- **README.md:** Installation instructions, setup guide, running the application
- **Code Comments:** Explain complex logic, especially in security checks

---

## 📊 Evaluation Criteria

### Architecture & Laravel Conventions (40%)
- ✅ Named routes and auth middleware grouping
- ✅ Eloquent relations defined and used properly
- ✅ Clear separation: Logic in Controllers/Models, Display in Blade
- ✅ All tables via Migrations
- ✅ Ownership verification before modification

### Functionality (35%)
- ✅ Registration, Login, Logout working
- ✅ Complete CRUD for tasks
- ✅ Quick status update from list
- ✅ Filters by status and category functional
- ✅ Data isolation (users see only their tasks)

### Code Quality (25%)
- ✅ Form validation with `$request->validate()`
- ✅ CSRF tokens on all forms
- ✅ $fillable defined in models
- ✅ Readable code with Laravel naming conventions
- ✅ Minimum 15 commits with clear messages

---

## 🔍 Debugging Session Requirements

During the evaluation, you must demonstrate:

1. **Telescope Navigation**
   - Open /telescope and locate an HTTP request
   - Read payload data received
   - View associated SQL queries
   - Identify any exceptions

2. **Debugbar Analysis**
   - Identify number of queries on a page
   - Detect N+1 query problems
   - Optimize query count

3. **Request Tracing**
   - Trace complete flow: Form → Route → Middleware → Controller → Database → Response
   - Identify each step in the process

4. **Bug Fixing**
   - Find and fix bugs using only debugger output
   - No blind code searching

---

## 📝 Important Notes

- **Git Commits:** Daily commits are mandatory
- **Code Standards:** Follow PSR-2 and Laravel conventions
- **Testing:** Manually test all features before submission
- **Performance:** Monitor and optimize N+1 queries
- **Security:** Never store plaintext passwords, validate all input
- **Documentation:** Code must be readable without extensive comments

---

## 🚀 Getting Started

1. Create feature branches for each major functionality
2. Design database schema first (MCD/MLD)
3. Create migrations and models
4. Build controllers and routes
5. Design views with Blade
6. Test each feature thoroughly
7. Commit daily with clear messages
8. Update Jira board daily

---

**Good luck! Build clean, secure code and commit daily.** 🎯
