# 🛍️ NextDigits – eCommerce Admin Panel - Technical task

This is a Laravel 12 + Filament-based admin panel designed as part of a technical assignment to demonstrate core eCommerce backend functionality. It includes management modules for **Categories**, **Products**, and **Orders** with real-time stock validation, automated emails, and a clean admin dashboard.

---

## 📦 Features

- **Category Management**
  - Create/edit categories with automatic slug generation

- **Product Management**
  - Add products with name, price, stock, and category
  - Stock is validated and cannot go negative

- **Order Management**
  - Add multiple products to an order using a repeater
  - Auto-calculate total amount dynamically
  - Stock is automatically reduced on save
  - Real-time stock validation per product
  - Order details with confirm email sent to the customer

- **Admin Dashboard (Filament 3)**
  - Dashboard with charts, KPIs, top-selling products, etc.

- **Other Highlights**
  - Laravel Mailable integration
  - Export product table to Excel
  - Saved SQLite database with demo data (no need to seed)
  - Clean Tailwind landing page
  - Follows Laravel best practices (Eloquent, Middleware, Jobs)

---

## 🚀 Tech Stack

- **Laravel 12**
- **Filament 3.x**
- **Tailwind CSS**
- **SQLite (default) or MySQL/PostgreSQL supported**
- **Laravel Mail**
- **PHP 8.2+**

---

## 🔧 Installation Steps

### 1. Clone the Repository

```bash
git clone https://github.com/your-username/your-repo-name.git
cd your-repo-name
```

### 2. Install Dependencies

```bash
composer install
```

### 3. (Optional) Build Frontend Assets

```bash
npm install
npm run build
```

### 4. Setup Environment

```bash
cp .env.example .env
```

Update your `.env` with DB and Mail settings:

```env
APP_NAME=NextDigits
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=sqlite
#  DB_DATABASE=${DB_DATABASE}/database/database.sqlite  Only For Production use 

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_user
MAIL_PASSWORD=your_mailtrap_pass
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=admin@example.com
MAIL_FROM_NAME="NextDigits"
```

#### ✅ OR Use Gmail SMTP (for testing)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=dilipwaghmarep15@gmail.com
MAIL_PASSWORD=fidkrxapnkfmvwvn
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=dilipwaghmarep15@gmail.com
MAIL_FROM_NAME="NextDigits Orders"
```

### 5. Generate App Key

```bash
php artisan key:generate
```

### 6. Run Migrations & Seeders

```bash
php artisan migrate --seed
```

---

## 🌐 Accessing the Application

### Public Landing Page
> [http://127.0.0.1:8000](http://127.0.0.1:8000)

### Admin Panel
> [http://127.0.0.1:8000/admin/login](http://127.0.0.1:8000/admin/login)

#### 🔐 Default Admin Credentials

```text
Email: admin@nextdigits.in
Password: admin
```

---

## ✉️ Email Integration

On successful order submission, a confirmation email is sent to the customer's email using **Laravel's Mailable** class.

---

## 📤 Export to Excel

The product table (with image, name, category, price, stock, created date) can be exported to Excel using **Laravel Excel**.

---
## 🧾 Export Order Report to PDF


The Order details can be exported to PDF using **Laravel DomPdf Package**.

---

## 🛠 Deployment Notes

Set your `.env` as:

```env
APP_ENV=production
APP_DEBUG=false
```

Then run:

```bash
php artisan config:cache
php artisan route:cache
```

Ensure queues are running if you're using jobs or notifications.

**⚠️ Use HTTPS in production environments**

---

## 📚 References

- [Filament Docs](https://filamentphp.com/docs)
- [Youtube Tutorial](https://youtu.be/JOPe7DvUq1Y?si=cDoksTkSXkOaBJLu)


