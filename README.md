# Laravel Task Management System

A mini Task Management System built with **Laravel 11**, featuring:

- User Authentication (Laravel Breeze)
- Task CRUD (Web + API)
- RESTful APIs secured with Sanctum
- Tailwind CSS UI
- Background Job & Scheduler
- Email reminders for tasks due tomorrow

---

## Features

### Authentication
- User registration & login
- Protected routes (Web & API)
- Sanctum-based API authentication

### Task Management
- Create, update, delete tasks
- Task fields:
  - Title
  - Description
  - Status (pending, in-progress, completed)
  - Due date
- Pagination & filtering
- User-based task ownership

### Background Job
- Daily scheduled job
- Sends reminder emails for tasks due tomorrow
- Queue-based execution

### API Endpoints
| Method | Endpoint | Description |
|------|--------|------------|
| GET | /api/tasks | List tasks (paginated) |
| POST | /api/tasks | Create task |
| PUT | /api/tasks/{id} | Update task |
| DELETE | /api/tasks/{id} | Delete task |

---

## Tech Stack

- PHP 8.2+
- Laravel 11
- MySQL
- Laravel Breeze
- Laravel Sanctum
- Tailwind CSS
- Flatpickr (Date Picker)
- Queue & Scheduler

---

## Installation & Setup
### 1. Clone Repository
### 2. Install Dependencies
- composer install
- npm install
- npm run build

### 3. Environment Configuration
- cp .env.example .env

Generate application key:
- php artisan key:generate

### 4. Run Migrations
php artisan migrate

### 5. Queue Setup
php artisan queue:table
php artisan migrate

php artisan queue:work

### 6. Run Scheduler (Manual / Local)
- php artisan schedule:run

Emails are logged locally.
Check : storage/logs/laravel.log