<div align="center">

# 🚀 NexaERP
### Business Management & CRM Platform

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com)

A production-style ERP + CRM system built with Laravel 12, demonstrating
real-world skills in MVC architecture, Eloquent ORM, REST API, RBAC,
database design, and modern UI development.

[Live Demo](#) · [API Docs](#api-documentation) · [Installation](#installation)

</div>

---

## 📋 About The Project

NexaERP is a full-featured Business Management and CRM platform built as a
portfolio project to demonstrate professional Laravel development skills.
It is NOT a simple CRUD app — it includes real business logic such as
inventory management, invoice generation with tax/discount calculations,
payment tracking, lead pipeline management, and role-based access control.

---

## ✨ Features

- 🔐 **Authentication** — Secure login, logout, remember me
- 👥 **Role-Based Access Control** — Super Admin, Admin, Manager, Employee
- 📊 **Dashboard** — Real-time stats, revenue charts, lead pipeline
- 👤 **CRM — Customers** — Full CRUD, search, filter, activity history
- 🎯 **CRM — Leads** — Pipeline management with status tracking
- 📝 **Activities** — Log calls, emails, meetings, follow-ups
- 📦 **Products** — Catalog with SKU, pricing, stock management
- 🏷️ **Categories** — Product categorization
- 📈 **Inventory** — Stock in/out/adjustment with full history
- 🧾 **Invoices** — Dynamic line items, tax, discount, auto-numbering
- 💳 **Payments** — Multiple methods, auto status update
- 📉 **Reports** — Sales, Customer, Inventory, Payment, Lead reports
- 📤 **CSV Export** — All reports exportable
- 🔌 **REST API** — Full API with Sanctum token authentication
- 👤 **User Management** — Admin panel for managing users & roles

---

## 🛠️ Technology Stack

| Technology | Purpose |
|-----------|---------|
| Laravel 12 | PHP Framework (MVC) |
| PHP 8.2+ | Server-side language |
| MySQL 8.0 | Relational database |
| Blade | Templating engine |
| Bootstrap 5.3 | UI framework |
| Chart.js | Dashboard charts |
| Laravel Sanctum | API authentication |
| Eloquent ORM | Database abstraction |
| JavaScript (Vanilla) | Dynamic invoice builder |

---

## 📦 Modules
nexaerp/
├── Authentication + RBAC
├── Dashboard (Charts + Stats)
├── CRM
│ ├── Customers
│ ├── Leads
│ └── Activities
├── Inventory
│ ├── Categories
│ ├── Products
│ └── Stock Transactions
├── Sales
│ ├── Invoices
│ └── Payments
├── Reports (+ CSV Export)
├── REST API
└── User Management

---

## 🗄️ Database Design

### Tables (14 total)

| Table | Description |
|-------|-------------|
| `users` | System users |
| `roles` | User roles (Super Admin, Admin, Manager, Employee) |
| `customers` | CRM customer records |
| `leads` | Sales pipeline leads |
| `activities` | Polymorphic — linked to customers or leads |
| `categories` | Product categories |
| `products` | Product catalog with stock |
| `stock_transactions` | Every stock movement logged |
| `invoices` | Sales invoices with tax/discount |
| `invoice_items` | Line items on each invoice |
| `payments` | Payment records per invoice |

### Key Relationships
users ──< customers (created_by)
users ──< leads (assigned_to, created_by)
customers ──< invoices
customers ──< payments
invoices ──< invoice_items >── products
invoices ──< payments
activities ──> customers (polymorphic)
activities ──> leads (polymorphic)
products ──< stock_transactions


---

## ⚙️ Installation

### Requirements
- PHP 8.2+
- Composer 2.x
- MySQL 8.0+
- Node.js 20+

### Steps

```bash
# 1. Clone the repository
git clone https://github.com/anshi3124/nexaerp.git
cd nexaerp

# 2. Install PHP dependencies
composer install

# 3. Install Node dependencies
npm install && npm run build

# 4. Configure environment
cp .env.example .env
php artisan key:generate

# 5. Set up database in .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nexaerp
DB_USERNAME=root
DB_PASSWORD=

# 6. Run migrations and seed demo data
php artisan migrate --seed

# 7. Start development server
php artisan serve
```

Visit: `http://localhost:8000`

---

## 🔑 Demo Login

| Role | Email | Password |
|------|-------|----------|
| Admin | demo@example.com | Demo@123 |
| Super Admin | superadmin@nexaerp.com | Admin@123 |
| Manager | manager@nexaerp.com | Admin@123 |

---

## 🔌 API Documentation

Base URL: `http://localhost:8000/api`

### Authentication

```http
POST /api/login
Content-Type: application/json

{
    "email": "demo@example.com",
    "password": "Demo@123"
}
```

Response:
```json
{
    "success": true,
    "data": {
        "user": { "id": 1, "name": "Demo User", "role": "Admin" },
        "token": "1|abc123...",
        "token_type": "Bearer"
    }
}
```

### Endpoints

All endpoints require: `Authorization: Bearer {token}`

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/login` | Get access token |
| POST | `/api/logout` | Revoke token |
| GET | `/api/me` | Authenticated user |
| GET | `/api/customers` | List customers |
| POST | `/api/customers` | Create customer |
| GET | `/api/customers/{id}` | Get customer |
| PUT | `/api/customers/{id}` | Update customer |
| DELETE | `/api/customers/{id}` | Delete customer |
| GET | `/api/products` | List products |
| GET | `/api/products/{id}` | Get product |
| GET | `/api/invoices` | List invoices |
| GET | `/api/invoices/{id}` | Get invoice with items |
| GET | `/api/leads` | List leads |
| GET | `/api/leads/{id}` | Get lead |
| GET | `/api/payments` | List payments |

### Query Parameters

```http
GET /api/customers?search=rahul&status=active&per_page=10
GET /api/products?low_stock=1&status=active
GET /api/invoices?status=paid&customer_id=1
GET /api/leads?status=qualified
```

### Response Format

```json
{
    "success": true,
    "data": {
        "current_page": 1,
        "data": [...],
        "total": 12,
        "per_page": 15
    }
}
```

---

## 🏗️ Project Architecture

app/
├── Http/
│ ├── Controllers/
│ │ ├── Api/ ← REST API controllers
│ │ └── *.php ← Web controllers
│ ├── Middleware/
│ │ └── RoleMiddleware.php
│ └── Requests/ ← Form Request validation
├── Models/ ← Eloquent models
└── Services/
└── InvoiceService.php ← Business logic layer

resources/views/
├── layouts/
│ ├── app.blade.php ← Main layout (sidebar + topbar)
│ └── auth.blade.php ← Login layout
├── dashboard/
├── customers/
├── leads/
├── products/
├── invoices/
├── payments/
├── reports/
└── users/

routes/
├── web.php ← Web routes with middleware
└── api.php ← API routes with Sanctum


---

## 🔒 Security Features

- CSRF protection on all forms
- Password hashing with bcrypt
- Role-based route protection
- Mass assignment protection (`$fillable`)
- SQL injection prevention (Eloquent ORM)
- API token authentication (Sanctum)
- Input validation (Form Requests)

---

## 📈 Key Laravel Concepts Demonstrated

| Concept | Where Used |
|---------|-----------|
| MVC Architecture | Entire application |
| Eloquent ORM | All models with relationships |
| Blade Templates | All views with layouts |
| Form Request Validation | CustomerRequest, InvoiceRequest, etc. |
| Middleware | Auth, RoleMiddleware |
| Route Model Binding | All resource controllers |
| Soft Deletes | Customers, Products, Invoices |
| Polymorphic Relations | Activities (customers + leads) |
| Database Transactions | Invoice creation with stock update |
| Service Classes | InvoiceService |
| API Resources | REST API with Sanctum |
| Accessors | Model computed attributes |

---

## 🔮 Future Improvements

- [ ] Email notifications for invoices
- [ ] PDF invoice generation
- [ ] Multi-company support
- [ ] Advanced analytics dashboard
- [ ] WhatsApp integration
- [ ] Mobile app (React Native)
- [ ] Docker containerization

---

## 👨‍💻 Author

**Your Name**
- GitHub: [@anshi3124](https://github.com/anshi3124)
- LinkedIn: https://www.linkedin.com/in/anshika-tiwari-7a9309357
- Email: tiwarianshika1132@gmail.com

---

<div align="center">
Built with ❤️ using Laravel 12
</div>