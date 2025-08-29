# Humano Core

[![Latest Version on Packagist](https://img.shields.io/packagist/v/idoneo/humano-core.svg?style=flat-square)](https://packagist.org/packages/idoneo/humano-core)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/idoneo/humano-core/run-tests?label=tests)](https://github.com/idoneo/humano-core/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/idoneo/humano-core/Check%20&%20fix%20styling?label=code%20style)](https://github.com/idoneo/humano-core/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/idoneo/humano-core.svg?style=flat-square)](https://packagist.org/packages/idoneo/humano-core)

Core functionality for the Humano CRM system. This package provides the foundational framework including user management, teams, authentication, categories, and the modular system architecture.

## Features

- **User & Team Management**: Built on Laravel Jetstream with team switching capabilities
- **Authentication**: Complete authentication system with permissions via Spatie Permission
- **Modular System**: Base architecture for adding CRM, Billing, Communications, and Hosting modules
- **Categories**: Flexible categorization system for organizing data across modules
- **Notes**: Polymorphic notes system that can be attached to any model
- **Activity Logging**: Comprehensive activity tracking via Spatie ActivityLog
- **Dashboard**: Analytics dashboard with team statistics and module status
- **UI Framework**: Based on Vuexy template with Livewire 3 integration

## Installation

Install the package via Composer:

```bash
composer require idoneo/humano-core
```

Publish and run the migrations:

```bash
php artisan vendor:publish --tag="humano-core-migrations"
php artisan migrate
```

Publish the config file (optional):

```bash
php artisan vendor:publish --tag="humano-core-config"
```

Install the core system:

```bash
php artisan humano:install
```

## Usage

### Basic Setup

After installation, the package provides:

- Dashboard at `/dashboard/analytics`
- Category management at `/categories`
- Team settings and management
- User authentication via Jetstream

### Module System

The core package includes a module management system. Other Humano packages register themselves automatically:

```php
// Automatically registered by other Humano packages
$modules = \Idoneo\HumanoCore\Models\Module::active()->get();
```

### Categories

Create categories for organizing data across modules:

```php
use Idoneo\HumanoCore\Models\Category;

$category = Category::create([
    'name' => 'Important Contacts',
    'description' => 'High priority contacts',
    'module_key' => 'crm',
    'color' => '#dc3545',
    'icon' => 'ti ti-star',
    'team_id' => auth()->user()->currentTeam->id,
]);
```

### Notes System

Add notes to any model using the polymorphic relationship:

```php
use Idoneo\HumanoCore\Models\Note;

// Add note to any model
$contact = Contact::find(1);
$note = $contact->notes()->create([
    'content' => 'Important follow-up needed',
    'user_id' => auth()->id(),
    'team_id' => auth()->user()->currentTeam->id,
]);
```

### Installation Command

Install additional modules selectively:

```bash
# Install specific modules
php artisan humano:install --modules=crm,billing

# Interactive installation
php artisan humano:install
```

## Configuration

The config file allows customization of:

```php
return [
    'dashboard' => [
        'default_route' => 'dashboard.analytics',
        'show_analytics' => true,
    ],
    'teams' => [
        'allow_team_creation' => true,
        'max_teams_per_user' => 5,
    ],
    'modules' => [
        'enabled_modules' => ['crm', 'billing', 'communications', 'hosting'],
    ],
];
```

## Related Packages

- **idoneo/humano-crm**: Contact and project management
- **idoneo/humano-billing**: Invoicing and payment processing
- **idoneo/humano-communications**: Email, chat, and notifications
- **idoneo/humano-hosting**: Server and domain management

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Diego Mascarenhas](https://github.com/diego-mascarenhas)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
