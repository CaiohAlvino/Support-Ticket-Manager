# Support Ticket Manager

![Ticket System](https://img.shields.io/badge/Sistema-Tickets%20de%20Suporte-blue)
![PHP](https://img.shields.io/badge/PHP-8.2-777BB4)
![MySQL](https://img.shields.io/badge/MySQL-Database-orange)
![Docker](https://img.shields.io/badge/Docker-Containerized-blue)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.x-purple)

## 📋 Description

**Support Ticket Manager** is a complete support ticket management system developed in PHP. The system allows companies to efficiently manage support requests, with control over users, companies, clients, and services.

## 🎓 Graduation Project

This system was developed as a **Final Graduation Project (TCC)** and received the **maximum grade of 10.0** in the academic evaluation. The project demonstrates the practical application of web development concepts, databases, software architecture, and programming best practices.

**Final Grade**: 📊 **10.0/10.0** ⭐

## ✨ Main Features

### 🎫 Ticket Management

-   **Ticket Creation**: Open tickets with subject, description, and category
-   **Dynamic Status**: Status control (Open, Waiting for Support, Answered, Closed)
-   **Messaging System**: Two-way communication between client and support
-   **Full History**: Track all interactions
-   **Intuitive Dashboard**: Overview with charts and statistics

### 👥 User and Permission Management

-   **Multiple Groups**: Permission system by user groups
-   **Access Control**: Different access levels (Admin, Support, Client)
-   **Secure Authentication**: Login system

### 🏢 Company Management

-   **Company Registration**: Full company management
-   **Client Linking**: Associate clients to companies
-   **Services per Company**: Categorize offered services
-   **User Control**: Associate users to companies

### 📊 Reports and Dashboard

-   **Interactive Dashboard**: Ticket distribution charts
-   **Real-Time Statistics**: Ticket counters by status
-   **User Reports**: Tickets by responsible user
-   **Performance Metrics**: Analysis of most requested services

## 🚀 Technologies Used

### Backend

-   **PHP 8.2**: Main language
-   **MySQL**: Relational database
-   **PDO**: Database abstraction
-   **Session Management**: Session control

### Frontend

-   **Bootstrap 5**: Responsive CSS framework
-   **jQuery**: JavaScript library
-   **Bootstrap Icons**: System icons
-   **Chart.js**: Interactive charts
-   **Select2**: Advanced selection component
-   **Noty**: Notification system

### Infrastructure

-   **Docker**: Application containerization
-   **Apache**: Web server
-   **EasyPanel**: Server management
-   **Digital Ocean**: VPS hosting

## 🏗️ System Architecture

### Directory Structure

```
Support-Ticket-Manager/
├── 📁 api/                    # API Endpoints
│   └── logs.php               # Request logs
├── 📁 config/                 # Config and model classes
│   ├── Cliente.php
│   ├── Database.php
│   ├── Empresa.php
│   └── ...
├── 📁 controller/             # Application controllers
│   ├── 📁 cliente/
│   │       ├── cadastrar.php
│   │       ├── editar.php
│   │       ├── excluir.php
│   │       └── ...
│   ├── 📁 empresa/
│   └── ...
├── 📁 css/                    # CSS styles
├── 📁 database/               # Database scripts
├── 📁 js/                     # JavaScript scripts
│   ├── 📁 Utils/
│   │   ├── Noty.js
│   │   ├── Select2.js
│   │   ├── Validador.js
│   │   └── ...
│   ├── cliente.js
│   ├── empresa.js
│   ├── suporte.js
│   └── ...
├── 📁 libs/                   # External libraries
│   ├── 📁 Bootstrap/
│   ├── 📁 Chart.js/
│   ├── 📁 jQuery/
│   ├── 📁 Select2/
│   ├── 📁 Noty/
│   └── ...
├── 📁 logs/                   # Application logs
│   ├── 📁 2025-06-29_a_2025-07-05/
│   │   ├── all.json
│   │   ├── all.log
│   │   └── error.log
│   ├── 📁 2025-07-06_a_2025-07-12/
│   └── ...
├── 📁 pages/                  # Application pages
│   ├── 📁 empresa/
│   │   ├── cadastro.php
│   │   ├── edicao.php
│   │   ├── index.php
│   │   └── ...
│   ├── 📁 suporte/
│   └── ...
├── 🐳 docker-compose.yml      # Docker config
├── 🐳 Dockerfile              # Docker image
├── 📄 index.php               # Home page
├── 📄 LICENSE.txt             # Project license
└── 📚 README.md               # This documentation
```

### Data Models

-   **Company**: System companies
-   **Service**: Service categorization
-   **Client**: Client management (individual/legal entity)
-   **User**: User control
-   **Groups**: User group control
-   **Support**: Support tickets
-   **SupportMessage**: Ticket messages

## 🌐 Development Environments

### Branches

-   **main**: Stable production code
-   **development**: Development version
-   **production**: Production deployment version

### Deploy

The system runs on a Digital Ocean VPS, managed by EasyPanel, with:

-   **Docker Container**: Containerized application
-   **Dedicated database**: Dedicated MySQL
-   **SSL/HTTPS**: Security certificate
-   **Automated backup**: Backup routines

## 👥 User Types

### 1. Administrator (Group 1)

-   Full system access
-   Manage companies, services, clients, users, and groups
-   View all tickets
-   Reports

### 2. Client (Group 2)

-   Open support tickets
-   View own tickets
-   Communicate with support
-   Personalized dashboard

### 3. Support (Groups 3-...)

-   Handle tickets
-   Communicate with clients
-   Close tickets
-   Restricted access to company, service, and client
-   Reports

## 📱 System Interface

### Main Pages

-   **Login**: Authentication with company selection
-   **Dashboard**: Overview with charts and statistics
-   **Tickets**: Ticket listing and management
-   **My Tickets**: Logged-in user's tickets
-   **Registers**: Management of companies, services, clients, users, and groups

### Interface Features

-   **Responsive Design**: Mobile compatible
-   **Modern Theme**: Clean and professional interface
-   **Notifications**: Alerts and confirmations system
-   **Intuitive Navigation**: Collapsible sidebar and breadcrumb

## 🔒 Security

### Implemented Measures

-   **Prepared Statements**: Protection against SQL Injection
-   **Input Validation**: Data sanitization
-   **Session Control**: Secure session management
-   **Permissions**: Group and permission system
-   **HTTPS**: Secure connection (in production)

## 🛠️ Development

### Code Standards

-   **PSR-4**: Class autoloading
-   **Separation of Concerns**: MVC pattern
-   **Validation**: Dedicated validation classes
-   **Documentation**: Comments on important methods

## 📊 Monitoring

### Available Metrics

-   Total tickets by status
-   Tickets by responsible user
-   Distribution by services
-   Average response time
-   Tickets by company/client

## 📄 License

This project is under the MIT license. See the [LICENSE](LICENSE.txt) file for more details.

## 📞 Support

For technical support or questions about the system:

-   **Email**: caioh.alvino22@gmail.com
-   **Hours**: Business days, 8am to 6pm
-   **Documentation**: Available in the system

---

## 🎯 Next Features

### Current Improvements
-   [ ] Email integration
-   [ ] Attachment system
-   [ ] Integration with external tools

### 🚀 Version 2.0 - Laravel
A **completely rewritten version in Laravel** is planned, which will bring:
-   🔧 **Modern Framework**: Migration to Laravel (most popular PHP framework)
-   🏗️ **Improved Architecture**: Use of Eloquent ORM, Migrations, Seeders
-   🎨 **Renewed Interface**: Frontend with more modern technologies
-   ⚡ **Optimized Performance**: Better performance and scalability
-   🧪 **Automated Tests**: Full test coverage
-   📱 **RESTful API**: Robust API for integrations
-   🔐 **Advanced Security**: Implementation of Laravel's best security practices

---

**Developed with ❤️ to optimize customer service**
