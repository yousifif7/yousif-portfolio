# Yousif Elfarra — Laravel 11 Portfolio

A complete portfolio / showcase application built with **Laravel 11**. It includes a public landing site and a full admin panel where you can control every piece of content: profile/about, projects (with images, links, tech stack), skills, services, work experience, custom sections, and incoming contact messages with SMTP reply support.

## ✨ Features

### Public site
- Home / landing page with hero, stats, services, skills, featured projects, experience timeline, dynamic sections, and a CTA
- Projects listing with search and skill filtering, pagination
- Detail page for each project (cover, gallery, tech stack, related projects, view counter)
- About page with full bio, skills grouped by category, experience timeline, custom sections
- Contact page with form (with honeypot anti-spam) — sends email to admin via SMTP and stores the message in the DB
- Responsive design (mobile, tablet, desktop)
- Separate CSS files for site and admin (in `public/css/`)

### Admin panel (at `/admin`)
- Login (email + password)
- Dashboard with stats and recent activity
- Profile / About editor (name, title, bio, education, social links, avatar, CV)
- Projects CRUD with cover image and multi-image gallery
- Skills CRUD (name, icon, color, proficiency %, category)
- Services CRUD
- Work experience CRUD
- Dynamic custom sections (admin can add any extra section)
- Contact messages inbox — view, reply via SMTP, mark read/unread, delete

## 🛠 Requirements

- PHP 8.2+
- Composer
- MySQL 5.7+ (or MariaDB / PostgreSQL / SQLite)
- An SMTP account (Gmail, Mailgun, Mailtrap, etc.) for sending emails

## 🚀 Installation

```bash
# 1. Install PHP dependencies
composer install

# 2. Copy environment file and generate app key
cp .env.example .env
php artisan key:generate

# 3. Edit .env and set:
#    - DB_DATABASE, DB_USERNAME, DB_PASSWORD
#    - MAIL_HOST, MAIL_USERNAME, MAIL_PASSWORD, MAIL_PORT, MAIL_ENCRYPTION
#    - MAIL_FROM_ADDRESS
#    - ADMIN_EMAIL  (where contact-form notifications go)

# 4. Run migrations and seed the database
php artisan migrate --seed

# 5. Create the storage symlink (so uploaded images are publicly accessible)
php artisan storage:link

# 6. Run the development server
php artisan serve
```

Then open `http://localhost:8000` for the public site.

## 🔑 Admin login

After seeding, log in at `http://localhost:8000/admin/login` with:

- **Email:** `admin@yousifelfarra.com`
- **Password:** `password`

**Change the password immediately after first login** (via your DB or by extending the admin profile editor — currently you can change the user's password directly in `tinker`).

## 📧 SMTP Configuration

Update these in your `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=hello@yousifelfarra.com
MAIL_FROM_NAME="Yousif Elfarra"

ADMIN_EMAIL=your@email.com
```

For Gmail you'll need an "App Password" (not your normal account password). For testing locally, [Mailtrap](https://mailtrap.io) is a great free option.

## 📁 Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/      # Admin panel controllers (Auth, Dashboard, Projects, Skills, Messages, About, Experiences, Services, Sections)
│   │   └── Site/       # Public site controllers (Home, About, Projects, Contact)
│   ├── Middleware/     # EnsureUserIsAdmin
│   └── Requests/       # Form Request validation
├── Mail/               # ContactFormMail, ContactReplyMail
├── Models/             # User, About, Project, ProjectImage, Skill, ContactMessage, Section, Experience, Service, Setting
└── Providers/

database/
├── migrations/         # All DB schema
└── seeders/            # DatabaseSeeder with Yousif's bio + default skills/services/experience

resources/views/
├── admin/              # Admin panel views (separate layout)
│   ├── layouts/        # app.blade.php + sidebar
│   ├── auth/, dashboard/, projects/, skills/, messages/, about/, experiences/, services/, sections/
├── site/               # Public site views (separate layout)
│   ├── layouts/        # app.blade.php
│   ├── partials/       # navbar, footer, project-card
│   └── home/about/projects/contact .blade.php
├── emails/             # contact-form.blade.php, contact-reply.blade.php
└── errors/             # 403.blade.php, 404.blade.php

public/
├── css/
│   ├── site.css        # Public-site styles (separate file as requested)
│   └── admin.css       # Admin panel styles (separate file)
└── images/             # Default avatar and project placeholder
```

## 🎨 Customization

- **Colors & theme:** edit the CSS variables in `public/css/site.css` and `public/css/admin.css`
- **Bio & profile:** log in to admin → "Profile / About"
- **Add projects:** admin → "Projects" → "New Project"
- **Add custom sections** (anything you want to dynamically add): admin → "Custom Sections"

## 🔒 Security notes

1. **Change the admin password** immediately after first login.
2. **Never commit `.env`** — it's in `.gitignore`.
3. The contact form has a honeypot field; consider adding rate limiting or reCAPTCHA for production.
4. Set `APP_DEBUG=false` and `APP_ENV=production` before deploying.

## 📜 License

MIT
