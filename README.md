
# Event Reminder App

---

## 🛠 System Requirements

- PHP: 8.1
- Laravel: 10.10
- MySQL: 8.0
- Supervisor (for job queue in production)

---

## ✨ Features

- Event Create
- Event Update
- Event Delete
- Event List
- Event Details
- Event Reminder (Job Queue)
- User Authentication
- User Registration
- User Profile Management


## 🚀 Installation

```bash
# Clone the project and move to the directory
git clone https://github.com/alshahriar042/event-reminder-app.git
cd event-reminder-app

# Copy environment file and install PHP dependencies
cp .env.example .env
composer install

# Generate app key
php artisan key:generate


# Run database migrations and seeders
php artisan migrate:fresh

#run application 
php artisan serve



