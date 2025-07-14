# PBL-2 Fanya Publishing

Proyek ini bertujuan untuk mengembangkan sistem berbasis web yang lebih efisien dan terortomatisasi, sistem ini akan mempermudahkan dalam pengelolaan naskah, katalog buku, transaksi penjualan dan laporannya dengan proses yang otomatis dan real-time pada Fanya Publishing

## 🚀 Tech Stack

### Backend
- **Laravel** - PHP Framework
- **MySQL** - Database
- **PHP** - Bahasa Pemograman

### Frontend
- **Vite** - Build Tool & Development Server
- **JavaScript/TypeScript** - Programming Language
- **CSS/SCSS/Tailwind** - Styling

### Third-party Libraries & Packages

#### PHP Dependencies (Composer)
- `laravel/framework` - Laravel core framework
- `laravel/tinker` - Laravel REPL


#### JavaScript Dependencies (NPM)
- `vite` - Build tool
- `laravel-vite-plugin` - Laravel integration for Vite
- `bootstrap` - CSS framework 
- `@vitejs/plugin-vue` - Vue.js support 

## 📋 Prerequisites

Pastikan sistem Anda memiliki:
- PHP >= 8.1
- Composer
- Node.js >= 16.x
- NPM atau Yarn
- MySQL
- Git

## 🛠️ Installation & Setup

### 1. Clone Repository
```bash
git clone https://github.com/ReykelRaflen/PBL.git
cd PBL
```

### 2. Install PHP Dependencies
```bash
composer install
```

### 3. Install JavaScript Dependencies
```bash
npm install
```

### 4. Environment Configuration
```bash
cp .env.example .env
```

Edit file `.env` dan sesuaikan konfigurasi:
```env
APP_NAME="Your App Name"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dbfanya
DB_USERNAME=
DB_PASSWORD=
```

### 5. Generate Application Key
```bash
php artisan key:generate
```

### 6. Database Setup
```bash
# Buat database terlebih dahulu, kemudian jalankan:
php artisan migrate

# Jika ada seeder:
php artisan db:seed
```

### 7. Storage Link (jika diperlukan)
```bash
php artisan storage:link
```

## 🚀 Running the Application

### Development Mode

#### Terminal 1 - Laravel Development Server
```bash
php artisan serve
```
Aplikasi akan berjalan di: http://localhost:8000

#### Terminal 2 - Vite Development Server
```bash
npm run dev
```
Vite akan berjalan di: http://localhost:5173



## 🔐 Login Credentials

Jika aplikasi memiliki sistem authentication, sertakan akun default:

### Admin Account
- **Email:** admin@example.com
- **Password:** password123

### User Account
- **Email:** user@example.com
- **Password:** password123

*Note: Ganti password default setelah login pertama*

## 📱 Demo

- **Live Demo:** [https://your-demo-url.com](https://your-demo-url.com)
- **Admin Panel:** [https://your-demo-url.com/admin](https://your-demo-url.com/admin)

## 📁 Project Structure

```
├── app/                    # Laravel application logic
├── bootstrap/              # Laravel bootstrap files
├── config/                 # Configuration files
├── database/              # Migrations, seeders, factories
├── public/                # Public assets & entry point
├── resources/             # Views, CSS, JS source files
│   ├── css/              # Stylesheets
│   ├── js/               # JavaScript files
│   └── views/            # Blade templates
├── routes/                # Route definitions
├── storage/               # Logs, cache, uploads
├── tests/                 # Test files
├── vite.config.js         # Vite configuration
├── composer.json          # PHP dependencies
├── package.json           # JavaScript dependencies
└── README.md             # This file
```

## 🔧 Available Scripts

```bash
# Development
npm run dev          # Start Vite development server
npm run build        # Build for production
npm run preview      # Preview production build

# Laravel
php artisan serve    # Start Laravel development server
php artisan migrate  # Run database migrations
php artisan db:seed  # Run database seeders
php artisan test     # Run tests
```

## 👥 Team

- **Developer Name** - [GitHub](https://github.com/ReykelRaflen)
- **Email:** reykel210618@gmail.com

## 🙏 Acknowledgments

- Laravel Framework
- Vite Build Tool
