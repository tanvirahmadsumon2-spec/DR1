# 🚀 Daktar Serial (ডাক্তার সিরিয়াল) - cPanel Deployment Guide (PHP 8.2+ & MySQL)

“সহজেই ডাক্তার দেখানোর সিরিয়াল নিন” - A Complete Bangladesh-focused Doctor Chamber Serial Booking Platform.

---

## 📁 1. Directory Overview & What to Upload

The complete production PHP application is ready in the `php_app/` folder.

```text
php_app/
├── .htaccess                  <- LiteSpeed / Apache routing & security rules
├── index.php                  <- Front controller entry point
├── config/
│   └── config.php             <- Database and app environment configuration
├── app/
│   ├── Core/                  <- Database (PDO), Session (CSRF), Router, Controller, Helpers
│   ├── Models/                <- User, Doctor, Appointment (Atomic lock FOR UPDATE)
│   ├── Controllers/           <- Home, Auth, Doctor, Appointment, Patient, DoctorPortal, Admin
│   ├── Middleware/            <- Auth, Doctor, Admin route guards
│   └── Views/                 <- Blade-like clean PHP views with Bootstrap 5
│       ├── layouts/main.php   <- Main header, navigation, footer, flash alerts
│       ├── home/index.php     <- Hero, search bar, specialties, featured doctors
│       ├── doctors/           <- Search catalog, interactive profile with real-time serials
│       ├── appointments/      <- Serial booking confirmation
│       ├── auth/              <- Login, Patient register, Doctor register
│       ├── patient/           <- Patient dashboard, appointments list, queue progress, reviews
│       ├── doctor/            <- Doctor portal, chamber/schedule management, queue caller
│       ├── admin/             <- Super admin dashboard, BMDC verification, logs
│       └── errors/            <- Clean 404 page
├── lang/                      <- Full Bilingual support (Bangla 'bn' & English 'en')
└── routes/
    └── web.php                <- Clean RESTful MVC URL routing
```

---

## 🗄️ 2. Database Setup on cPanel

1. Log into your **cPanel**.
2. Go to **MySQL® Databases**:
   - Create Database: `pixeswpo_dr` (or your chosen database name).
   - Create User: e.g. `pixeswpo_druser` with a strong password.
   - Add User to Database with **ALL PRIVILEGES**.
3. Open **phpMyAdmin**:
   - Select your database `pixeswpo_dr`.
   - Click **Import**.
   - Choose and import:
     1. `database/mysql_schema.sql` (Tables: users, doctors, chambers, schedules, appointments, etc.)
     2. `database/seed_data.sql` (Demo admin, verified doctors, chambers, and slots)
     3. `database/migrations_002_extensions.sql` (Reviews, notifications, payments)

---

## ⚙️ 3. Configure Database Connection

Open `php_app/config/config.php` (or use cPanel File Manager) and ensure credentials match your MySQL database:

```php
return [
    'app' => [
        'name' => 'Daktar Serial',
        'tagline_bn' => 'সহজেই ডাক্তার দেখানোর সিরিয়াল নিন',
        'url' => 'https://dakatarseial.bd', // Your actual domain
        'lang' => 'bn',
        'debug' => false,
    ],
    'db' => [
        'host' => 'localhost',
        'port' => 3306,
        'database' => 'pixeswpo_dr', // Your cPanel DB name
        'username' => 'pixeswpo_druser', // Your cPanel DB user
        'password' => 'Your_Strong_Password_Here', // Your DB password
        'charset' => 'utf8mb4',
    ],
];
```

---

## 🌐 4. Deploying to `public_html` on Namecheap / LiteSpeed

1. If deploying to root domain (e.g., `dakatarseial.bd`):
   - In cPanel File Manager, navigate to `/home/pixeswpo/public_html` (or your domain root).
   - Upload all contents inside `php_app/` into `public_html/` (including `.htaccess` and `index.php`).
2. Make sure PHP version in cPanel is set to **PHP 8.1, 8.2, or 8.3**:
   - In cPanel, find **Select PHP Version** or **MultiPHP Manager**.
   - Select **PHP 8.2**.
   - Ensure the following extensions are enabled: `pdo`, `pdo_mysql`, `mbstring`, `openssl`, `json`, `session`.

---

## 🔑 5. Default Credentials

| Role | Email | Password | Access |
|---|---|---|---|
| **Admin** | `admin@daktarserial.com` | `admin123` | `/admin/dashboard` |
| **Doctor 1** | `dr.rahman@daktarserial.com` | `doctor123` | `/doctor/dashboard` |
| **Doctor 2** | `dr.fatima@daktarserial.com` | `doctor123` | `/doctor/dashboard` |
| **Patient** | `patient@daktarserial.com` | `patient123` | `/patient/dashboard` |

---

## 🛡️ 6. Concurrency & Double-Booking Protection

The booking engine is located in `app/Models/Appointment.php`:
- Uses **PDO Transactions (`beginTransaction` / `commit`)**.
- Uses **Row-level pessimistic locking (`SELECT ... FOR UPDATE`)** on both `appointments` and `serials` tables.
- Enforces strict unique constraints `uq_appointment_slot (doctor_id, chamber_id, schedule_date, serial_number)` at database level.
- If two users submit simultaneously for serial slot #5, the first one gets confirmed and the second one receives an instantaneous *"Slot already booked"* message with no corrupted state.
