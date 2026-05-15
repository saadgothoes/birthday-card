# Admin Side Documentation

## Overview

This document provides comprehensive documentation for the admin side functionality of the Birthday Card application. The admin system is built with Laravel 13.6.0 and PHP 8.3.6, featuring a complete client management system with payment tracking, automated subscription management, and user-friendly dashboards.

## Architecture

### Tech Stack

- **Framework**: Laravel 13.6.0
- **PHP Version**: 8.3.6
- **Database**: MySQL
- **Frontend**: Blade Templates with Custom CSS
- **Email**: Laravel Mail with Mailpit for testing
- **Authentication**: Laravel Sanctum/Breeze
- **Task Scheduling**: Laravel Scheduler

### Database Schema

The `users` table has been extended with subscription management fields:

```sql
-- Additional fields added via migration 2026_05_15_052355
ALTER TABLE users ADD COLUMN status ENUM('active', 'disabled') DEFAULT 'active';
ALTER TABLE users ADD COLUMN subscription_start_date DATE NULL;
ALTER TABLE users ADD COLUMN subscription_fee DECIMAL(10,2) DEFAULT 0;
ALTER TABLE users ADD COLUMN default_subscription_fee DECIMAL(10,2) DEFAULT 0;
```

### User Roles

- **Super Admin**: Full system access, client management, payment settings
- **Client**: Birthday card creation access (limited functionality)

## Core Features

### 1. Authentication System

#### Super Admin Login

- **Route**: `GET/POST /admin/login`
- **Controller**: `SuperAdminController@loginPage()`, `SuperAdminController@login()`
- **Validation**: Email and password required
- **Middleware**: `super_admin` middleware restricts access
- **Redirect**: After login → `/admin/dashboard`

#### Session Management

- Automatic session regeneration on login
- Guest redirect to appropriate login page based on route prefix
- Secure logout with token regeneration

### 2. Client Management System

#### Client CRUD Operations

- **List Clients**: `GET /admin/clients` - Display all clients with status
- **Create Client**: `GET/POST /admin/clients/create` - Form with validation
- **Toggle Status**: `PATCH /admin/clients/{id}/toggle-status` - Enable/disable clients

#### Client Creation Process

```php
// Validation rules
$name  => 'required|string|max:100'
$email => 'required|email|unique:users,email'
$phone => 'required|string|max:20'
$city  => 'required|string|max:100'
$age   => 'required|integer|min:1|max:120'
```

#### Automatic Features

- **Password Generation**: 10-character random password
- **Welcome Email**: Automatic email with credentials
- **Subscription Setup**: Auto-sets start date and default fee
- **Role Assignment**: Automatically set as 'client'

### 3. Payment Management System

#### Payment Dashboard

- **Route**: `GET /admin/payments`
- **Controller**: `PaymentController@index()`
- **Features**:
    - Total payment calculations
    - Client payment status overview
    - Subscription fee tracking

#### Payment Statistics

- Total payments received
- Active vs disabled client counts
- Subscription fee summaries
- Payment status indicators

### 4. Subscription Settings

#### Fee Management

- **Route**: `POST /admin/settings`
- **Controller**: `SuperAdminController@updateSettings()`
- **Features**:
    - Set default subscription fee
    - Apply to new clients automatically
    - Update existing client fees

#### Subscription Lifecycle

- **30-Day Trial Period**: Automatic activation
- **Expiration Handling**: Auto-disable after 30 days
- **Fee Calculation**: Decimal precision (2 decimal places)

### 5. Automated Tasks

#### Daily Client Disable Command

```bash
php artisan app:disable-expired-clients
```

**Command Details**:

- **File**: `app/Console/Commands/DisableExpiredClients.php`
- **Schedule**: Daily execution via Laravel scheduler
- **Logic**: Disable clients where `subscription_start_date < now() - 30 days`

#### Scheduler Configuration

```php
// bootstrap/app.php
$schedule->command('app:disable-expired-clients')->daily();
```

### 6. Email System

#### Welcome Email

- **Mail Class**: `ClientWelcomeMail`
- **Trigger**: After client creation
- **Content**: Name, email, generated password
- **Error Handling**: Graceful degradation if email fails

#### Mail Configuration

- Uses Laravel's mail system
- Configurable SMTP/Mailpit settings
- Error reporting for failed sends

## User Interface Components

### Admin Dashboard

- **File**: `resources/views/admin/dashboard.blade.php`
- **Features**:
    - Payment statistics cards
    - Navigation sidebar
    - Settings toggle interface
    - Responsive design

### Payment Management Interface

- **File**: `resources/views/admin/payments/index.blade.php`
- **Features**:
    - Client payment table
    - Status indicators
    - Total calculations
    - Settings management form

### Client Management Interface

- **Files**: `resources/views/admin/clients/index.blade.php`, `create.blade.php`
- **Features**:
    - Client listing with status badges
    - Create/edit forms
    - Status toggle buttons
    - Responsive tables

## Security Features

### Middleware Protection

- **SuperAdminMiddleware**: Restricts access to super admin role only
- **Auth Middleware**: Requires authentication for all admin routes
- **CSRF Protection**: Automatic token validation

### Data Validation

- Email uniqueness checks
- Password hashing via casts
- Input sanitization
- Numeric validation for fees

### Session Security

- Session regeneration on login
- Secure token handling
- Automatic logout on unauthorized access

## API Endpoints

### Authentication

```
GET  /admin/login          - Login page
POST /admin/login          - Process login
POST /admin/logout         - Logout
```

### Client Management

```
GET  /admin/clients         - List clients
GET  /admin/clients/create  - Create form
POST /admin/clients         - Store client
PATCH /admin/clients/{id}/toggle-status - Toggle status
```

### Payment Management

```
GET  /admin/payments        - Payment dashboard
```

### Settings

```
POST /admin/settings       - Update settings
GET  /admin/dashboard      - Main dashboard
```

## Error Handling

### Email Failures

- Welcome emails may fail due to mail configuration
- System continues operation with warning message
- Errors are logged for debugging

### Database Constraints

- Unique email validation
- Foreign key relationships
- Data type enforcement via casts

### Validation Errors

- Form validation with detailed error messages
- Redirect with input preservation
- User-friendly error display

## Performance Considerations

### Database Optimization

- Eager loading where appropriate
- Indexed queries for client listing
- Efficient status updates

### Caching

- Laravel's view caching enabled
- Session data optimization
- Static asset optimization

## Testing

### Automated Tests

- PHPUnit configuration in `phpunit.xml`
- Feature tests for controllers
- Unit tests for models

### Manual Testing Checklist

- [ ] Admin login/logout
- [ ] Client creation with email
- [ ] Status toggling
- [ ] Payment calculations
- [ ] Settings updates
- [ ] Automated disable command
- [ ] Email delivery

## Deployment Considerations

### Environment Variables

```env
MAIL_MAILER=smtp
MAIL_HOST=localhost
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="admin@birthdaycard.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### Scheduler Setup

```bash
# Add to crontab
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

### Permissions

- Ensure web server can write to `storage/` directory
- Database user with appropriate permissions
- Mail service configuration

## Troubleshooting

### Common Issues

#### Email Not Sending

- Check Mailpit is running: `mailpit --webport 8026`
- Verify mail configuration in `.env`
- Check Laravel logs: `storage/logs/laravel.log`

#### Command Not Running

- Verify scheduler is configured in `bootstrap/app.php`
- Check cron jobs are set up
- Run manually: `php artisan app:disable-expired-clients`

#### Permission Denied

- Check file permissions on `storage/` directory
- Verify database user permissions
- Check web server user permissions

### Debug Commands

```bash
# Check scheduler
php artisan schedule:list

# Run specific command
php artisan app:disable-expired-clients

# Clear cache
php artisan config:clear
php artisan view:clear
php artisan cache:clear

# Check mail configuration
php artisan config:show mail
```

## Future Enhancements

### Planned Features

- Payment gateway integration
- Invoice generation
- Advanced reporting
- Client activity logs
- Bulk operations
- Export functionality

### Scalability Improvements

- Queue system for email sending
- Database indexing optimization
- Caching layer implementation
- API rate limiting

---

**Last Updated**: May 15, 2026
**Version**: 1.0.0
**Laravel Version**: 13.6.0
**PHP Version**: 8.3.6</content>
<filePath">/home/saad/Documents/birthday-card/admin.md
