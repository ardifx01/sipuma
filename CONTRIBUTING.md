# 🤝 Contributing to SIPUMA

Terima kasih atas minat Anda untuk berkontribusi pada proyek SIPUMA! Dokumen ini akan membantu Anda memahami cara berkontribusi dengan efektif.

## 📋 Table of Contents

-   [Code of Conduct](#code-of-conduct)
-   [Getting Started](#getting-started)
-   [Development Setup](#development-setup)
-   [Making Changes](#making-changes)
-   [Pull Request Process](#pull-request-process)
-   [Reporting Bugs](#reporting-bugs)
-   [Suggesting Enhancements](#suggesting-enhancements)

## 📜 Code of Conduct

Proyek ini dan semua kontributornya diatur oleh [Code of Conduct](CODE_OF_CONDUCT.md). Dengan berpartisipasi, Anda diharapkan untuk mematuhi kode ini.

## 🚀 Getting Started

### Prerequisites

Sebelum berkontribusi, pastikan Anda memiliki:

-   **PHP** 8.2+
-   **Composer** 2.0+
-   **Node.js** 18.0+
-   **Git**
-   **Database** (MySQL/PostgreSQL)

### Development Setup

1. **Fork Repository**

    ```bash
    # Fork repository di GitHub, kemudian clone
    git clone https://github.com/YOUR_USERNAME/sipuma.git
    cd sipuma
    ```

2. **Setup Environment**

    ```bash
    # Install dependencies
    composer install
    npm install

    # Setup environment
    cp .env.example .env
    php artisan key:generate

    # Configure database di .env
    ```

3. **Database Setup**

    ```bash
    # Run migrations
    php artisan migrate

    # Seed database
    php artisan db:seed --class=RolePermissionSeeder
    php artisan db:seed --class=TestUserSeeder
    ```

4. **Start Development**

    ```bash
    # Build assets
    npm run dev

    # Start server
    php artisan serve
    ```

## 🔧 Development Guidelines

### Code Style

-   Ikuti [PSR-12](https://www.php-fig.org/psr/psr-12/) untuk PHP
-   Gunakan [Laravel Pint](https://laravel.com/docs/pint) untuk formatting
-   Ikuti [Laravel Best Practices](https://laravel.com/docs/best-practices)

### Database Changes

-   Buat migration untuk setiap perubahan database
-   Gunakan descriptive names untuk migration
-   Test migration up dan down

```bash
php artisan make:migration add_new_field_to_table_name
```

### Testing

-   Tulis test untuk fitur baru
-   Pastikan semua test pass sebelum submit PR
-   Gunakan descriptive test names

```bash
# Run tests
php artisan test

# Run specific test
php artisan test --filter=FeatureNameTest
```

## 🔄 Making Changes

### Branch Naming

Gunakan prefix yang sesuai untuk branch:

-   `feature/` - Fitur baru
-   `bugfix/` - Perbaikan bug
-   `hotfix/` - Perbaikan urgent
-   `docs/` - Dokumentasi
-   `refactor/` - Refactoring code

```bash
git checkout -b feature/user-dashboard
git checkout -b bugfix/login-validation
```

### Commit Messages

Gunakan conventional commits:

```
type(scope): description

feat(auth): add two-factor authentication
fix(api): resolve token validation issue
docs(readme): update installation guide
refactor(controllers): simplify dashboard logic
```

### Types:

-   `feat` - Fitur baru
-   `fix` - Perbaikan bug
-   `docs` - Dokumentasi
-   `style` - Formatting, missing semicolons, etc
-   `refactor` - Refactoring code
-   `test` - Adding tests
-   `chore` - Maintenance tasks

## 📤 Pull Request Process

### Before Submitting

1. **Update dari main branch**

    ```bash
    git checkout main
    git pull origin main
    git checkout your-branch
    git rebase main
    ```

2. **Run tests**

    ```bash
    php artisan test
    npm run build
    ```

3. **Check code style**
    ```bash
    ./vendor/bin/pint
    ```

### PR Template

Gunakan template berikut untuk PR:

```markdown
## Description

Brief description of changes

## Type of Change

-   [ ] Bug fix
-   [ ] New feature
-   [ ] Breaking change
-   [ ] Documentation update

## Testing

-   [ ] Unit tests pass
-   [ ] Integration tests pass
-   [ ] Manual testing completed

## Checklist

-   [ ] Code follows style guidelines
-   [ ] Self-review completed
-   [ ] Documentation updated
-   [ ] No breaking changes

## Screenshots (if applicable)
```

### Review Process

1. **Self Review** - Review code Anda sendiri
2. **Automated Checks** - Pastikan CI/CD pass
3. **Code Review** - Tunggu review dari maintainer
4. **Address Feedback** - Perbaiki sesuai feedback
5. **Merge** - Setelah approval

## 🐛 Reporting Bugs

### Bug Report Template

```markdown
**Describe the bug**
A clear and concise description of what the bug is.

**To Reproduce**
Steps to reproduce the behavior:

1. Go to '...'
2. Click on '....'
3. Scroll down to '....'
4. See error

**Expected behavior**
A clear and concise description of what you expected to happen.

**Screenshots**
If applicable, add screenshots to help explain your problem.

**Environment:**

-   OS: [e.g. macOS, Windows, Linux]
-   Browser: [e.g. Chrome, Safari, Firefox]
-   Version: [e.g. 22]

**Additional context**
Add any other context about the problem here.
```

## 💡 Suggesting Enhancements

### Feature Request Template

```markdown
**Is your feature request related to a problem? Please describe.**
A clear and concise description of what the problem is.

**Describe the solution you'd like**
A clear and concise description of what you want to happen.

**Describe alternatives you've considered**
A clear and concise description of any alternative solutions or features you've considered.

**Additional context**
Add any other context or screenshots about the feature request here.
```

## 📚 Documentation

### Updating Documentation

-   Update README.md untuk perubahan besar
-   Update inline comments untuk kode kompleks
-   Buat dokumentasi API jika menambah endpoint baru
-   Update CHANGELOG.md untuk release notes

### Documentation Standards

-   Gunakan bahasa Indonesia yang jelas
-   Sertakan contoh kode
-   Tambahkan screenshots jika diperlukan
-   Update daftar fitur jika ada perubahan

## 🎯 Areas for Contribution

### High Priority

-   [ ] Unit testing coverage
-   [ ] API documentation
-   [ ] Performance optimization
-   [ ] Security improvements

### Medium Priority

-   [ ] UI/UX improvements
-   [ ] Additional features
-   [ ] Code refactoring
-   [ ] Documentation updates

### Low Priority

-   [ ] Code style improvements
-   [ ] Minor bug fixes
-   [ ] Translation updates

## 🏆 Recognition

Kontributor akan diakui di:

-   [Contributors](https://github.com/[username]/sipuma/graphs/contributors) page
-   README.md contributors section
-   Release notes

## 📞 Getting Help

Jika Anda membutuhkan bantuan:

-   **Issues**: [GitHub Issues](https://github.com/[username]/sipuma/issues)
-   **Discussions**: [GitHub Discussions](https://github.com/[username]/sipuma/discussions)
-   **Email**: [your-email@domain.com]

---

Terima kasih atas kontribusi Anda untuk SIPUMA! 🚀
