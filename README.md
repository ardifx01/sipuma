# 🎓 SIPUMA - Sistem Publikasi Mahasiswa

[![Laravel](https://img.shields.io/badge/Laravel-12.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC.svg)](https://tailwindcss.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

Platform digital modern untuk mengelola dan mempublikasikan karya ilmiah mahasiswa dengan workflow review yang terstruktur dan antarmuka yang user-friendly.

## ✨ Fitur Utama

### 🔐 **Sistem Autentikasi & Role**

-   Multi-role authentication (Admin, Dosen, Mahasiswa)
-   Role-based access control dengan Spatie Laravel Permission
-   API authentication dengan Laravel Sanctum
-   Session management yang aman

### 📊 **Dashboard Analytics**

-   **Admin Dashboard**: Statistik publikasi, review pending, manage user
-   **Dosen Dashboard**: Review mahasiswa bimbingan, statistik bimbingan
-   **Mahasiswa Dashboard**: Status publikasi, progress tracking

### 📝 **Manajemen Publikasi**

-   Upload file publikasi (PDF, DOC, DOCX) - max 10MB
-   Multiple publication types (Artikel, HKI, Paten, Buku)
-   Letter of Acceptance (LoA) management
-   File storage dengan struktur folder yang terorganisir
-   Download dan preview file

### 🔍 **Review & Verifikasi**

-   Workflow review: Mahasiswa → Dosen → Admin
-   Feedback system untuk setiap tahap review
-   Status tracking (pending, approved, rejected)
-   Search dan filter publikasi

### 🎨 **User Interface**

-   Responsive design dengan Tailwind CSS
-   Modern UI/UX dengan DaisyUI components
-   Mobile-friendly interface
-   Progress indicators dan loading states

## 🛠️ Tech Stack

| Component             | Technology                | Version |
| --------------------- | ------------------------- | ------- |
| **Backend Framework** | Laravel                   | 12.x    |
| **Frontend**          | Blade Templates           | -       |
| **Styling**           | Tailwind CSS + DaisyUI    | 3.x     |
| **Authentication**    | Laravel Breeze            | 2.x     |
| **Authorization**     | Spatie Laravel Permission | 6.x     |
| **API Auth**          | Laravel Sanctum           | 4.x     |
| **Database**          | MySQL/PostgreSQL          | -       |
| **File Storage**      | Laravel Storage           | -       |

## 📋 Prerequisites

Sebelum menjalankan aplikasi, pastikan sistem Anda memenuhi requirement berikut:

-   **PHP** 8.2 atau lebih tinggi
-   **Composer** 2.0 atau lebih tinggi
-   **Node.js** 18.0 atau lebih tinggi
-   **npm** atau **yarn**
-   **Database** (MySQL 8.0+ atau PostgreSQL 13+)
-   **Web Server** (Apache/Nginx) atau PHP Built-in Server

## 🚀 Quick Start

### 1. Clone Repository

```bash
git clone https://github.com/[username]/sipuma.git
cd sipuma
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 3. Environment Setup

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure database in .env file
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=sipuma
# DB_USERNAME=root
# DB_PASSWORD=
```

### 4. Database Setup

```bash
# Run migrations
php artisan migrate

# Seed database with initial data
php artisan db:seed --class=RolePermissionSeeder
php artisan db:seed --class=TestUserSeeder
php artisan db:seed --class=PublicationTypeSeeder
```

### 5. File Storage Setup

```bash
# Create storage link
php artisan storage:link
```

### 6. Build Assets

```bash
# Build for production
npm run build

# Or for development
npm run dev
```

### 7. Start Development Server

```bash
# Start Laravel development server
php artisan serve

# Or use Laravel Sail (Docker)
./vendor/bin/sail up
```

Aplikasi akan berjalan di `http://localhost:8000`

## 👥 User Test Accounts

Setelah menjalankan seeder, Anda dapat login dengan akun berikut:

| Role          | Email                   | Password   | Description                        |
| ------------- | ----------------------- | ---------- | ---------------------------------- |
| **Admin**     | `admin@sipuma.test`     | `password` | Full access to all features        |
| **Dosen**     | `dosen@sipuma.test`     | `password` | Can review student publications    |
| **Mahasiswa** | `mahasiswa@sipuma.test` | `password` | Can upload and manage publications |

## 🏗️ Project Structure

```
sipuma/
├── app/
│   ├── Http/Controllers/     # Application controllers
│   ├── Models/              # Eloquent models
│   ├── Providers/           # Service providers
│   └── View/Components/     # Blade components
├── database/
│   ├── migrations/          # Database migrations
│   ├── seeders/            # Database seeders
│   └── factories/          # Model factories
├── resources/
│   ├── views/              # Blade templates
│   ├── css/                # Stylesheets
│   └── js/                 # JavaScript files
├── routes/
│   ├── web.php             # Web routes
│   ├── api.php             # API routes
│   └── auth.php            # Authentication routes
└── storage/
    └── app/public/         # Public file storage
        ├── publications/    # Publication files
        ├── publications/loa/ # LoA files
        ├── publications/hki/ # HKI certificates
        └── publications/books/ # Book PDFs
```

## 📊 Database Schema

### Core Tables

-   **users** - User accounts and authentication
-   **student_profiles** - Student information (NIM, supervisor)
-   **dosen_profiles** - Lecturer information
-   **publications** - Publication data and metadata
-   **publication_types** - Publication type definitions
-   **reviews** - Review and feedback data

### Key Relationships

-   User → StudentProfile (one-to-one)
-   User → DosenProfile (one-to-one)
-   User → Publications (one-to-many)
-   StudentProfile → User (supervisor relationship)
-   Publication → Reviews (one-to-many)

## 🔧 Configuration

### Environment Variables

```env
# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sipuma
DB_USERNAME=root
DB_PASSWORD=

# File Storage
FILESYSTEM_DISK=public

# Application
APP_NAME="SIPUMA"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000
```

### File Storage Configuration

Aplikasi menggunakan Laravel Storage dengan disk `public` untuk menyimpan file:

-   **Publikasi**: `storage/app/public/publications/`
-   **LoA**: `storage/app/public/publications/loa/`
-   **HKI**: `storage/app/public/publications/hki/`
-   **Buku**: `storage/app/public/publications/books/`

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter=PublicationTest

# Run with coverage
php artisan test --coverage
```

## 📦 Deployment

### Production Checklist

-   [ ] Set `APP_ENV=production`
-   [ ] Set `APP_DEBUG=false`
-   [ ] Configure production database
-   [ ] Set up file storage (S3 recommended)
-   [ ] Configure web server (Apache/Nginx)
-   [ ] Set up SSL certificate
-   [ ] Configure backup strategy

### Deployment Commands

```bash
# Install dependencies
composer install --optimize-autoloader --no-dev

# Build assets
npm run build

# Run migrations
php artisan migrate --force

# Clear caches
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

-   [Laravel](https://laravel.com) - The PHP framework for web artisans
-   [Tailwind CSS](https://tailwindcss.com) - A utility-first CSS framework
-   [DaisyUI](https://daisyui.com) - Tailwind CSS component library
-   [Spatie](https://spatie.be) - Laravel Permission package

## 📞 Support

Jika Anda mengalami masalah atau memiliki pertanyaan:

-   **Issues**: [GitHub Issues](https://github.com/[username]/sipuma/issues)
-   **Email**: [your-email@domain.com]
-   **Documentation**: [Wiki](https://github.com/[username]/sipuma/wiki)

---

**SIPUMA** - Empowering students to publish their research with ease and efficiency! 🚀
