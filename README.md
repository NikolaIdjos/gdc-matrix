# GDC Matrix

## Prerequisites

Before running this project, make sure you have the following installed on your system:

- **Docker** and **Docker Compose** (required for Laravel Sail)
- **Git** (for cloning the repository)
- **Composer**

Optional tools you might want for development:

- **PHP** >= 8.2

## Project setup

1. **Clone the repository**
```bash
git clone https://github.com/NikolaIdjos/gdc-matrix.git
cd gdc-matrix
```
2. **Copy .env.example to .env**
```bash
cp .env.example .env
```
3. **Start Sail**
```bash
composer install
./vendor/bin/sail up -d
```
4. **PHP commands**
```bash
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate --seed
```
5. **npm commands**
```bash
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev
```

## How to Run **Tests**, **Larastan**, **Pint**

**Run PHPUnit tests**:
```bash
./vendor/bin/sail artisan test
```

**Run Pint**:
```bash
./vendor/bin/sail php ./vendor/bin/pint
```

**Run Larastan**:
```bash
./vendor/bin/sail php ./vendor/bin/phpstan analyse
```

### Swagger / API Documentation

All API endpoints are documented in Swagger.  
Once the app is running locally via Sail, you can access the Swagger UI at:
http://localhost/swagger
> This provides an interactive way to explore the API, see required parameters, and test endpoints directly from your browser.

## Notes on Trade-offs / Timeboxing

During development, certain decisions and trade-offs were made due to time constraints and prioritization:

- **Service layer**: I considered adding interfaces to follow SOLID principles and mocking, but decided it was unnecessary for this scope.
- **Validation rules**: I thought about placing all validation rules in controllers to avoid additional database calls for optimization, but chose not to, as I prefer not to keep such logic inside controllers.
- **Bonus features**: I decided to implement three optional features — GitHub Actions (CI), OpenAPI/Swagger, and Caching. The remaining two, Laravel Nova and Laravel Breeze, were not included due to time constraints.
- **Frontend structure**: I avoided splitting components into too many small parts and only extracted those that would make future development easier.
- **Caching**: Set to 5 minutes, added purely for demonstration purposes.
- **Swagger**: Added both Swagger UI and JSON. JSON was provided manually, although auto-generation from comments is possible.
- **Resources**: Implemented because I believe they improve code structure and readability.
- **Code comments**: Kept to a minimum, letting the code speak for itself.
- **ApiCache service**: Added later in development without writing tests for it.
- **Feature tests**: Tried to cover as many edge cases as possible.
- **HTML**: Mostly generated with ChatGPT to save time.
- **Pivot table model**: I initially skipped implementing the optional pivot table model, but later added it due to Larastan type-checking requirements.
- **Models and enums**: Generated to match provided specifications.
- **Inertia**: Used instead of Vue Router since there was no need for a full SPA routing setup in this project.
