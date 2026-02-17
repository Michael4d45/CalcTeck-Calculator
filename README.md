# CalcTeck Calculator - Demo Exercise

## Introduction

The masterminds at CalcTek have decided to revolutionize the calculator industry by building an API-driven calculator. Genius! They've tasked you with developing the project.

This is a tech demo showcasing a Laravel-based backend API with a Vue.js frontend calculator interface.

## Requirements

Build an API that supports four types of calculation operators:

- Addition
- Subtraction
- Division
- Multiplication

Every calculation should be stored for historical reference.

A calculator interface should be created (Vue.js) that allows the user to enter their calculation and receive a response.

A "ticker tape" interface should be built that shows all previous calculations with the ability to delete an individual calculation or clear all previous calculations.

## Stretch Goal

To really impress the customers of CalcTek, your calculator should support more complex calculation chains and even additional calculation operators:

`sqrt((((9*9)/12)+(13-4))*2)^2`

## Technologies Used

- **Backend**: Laravel (PHP framework)
- **Frontend**: Vue.js with Vite
- **Database**: SQLite (for simplicity in demo)
- **Testing**: Pest/PHPUnit

## Installation

1. Clone the repository:
   ```bash
   git clone <repository-url>
   cd CalcTeck-Calculator
   ```

2. Install PHP dependencies:
   ```bash
   composer install
   ```

3. Install Node.js dependencies:
   ```bash
   npm install
   ```

4. Set up environment:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. Run database migrations:
   ```bash
   php artisan migrate
   ```

6. Build frontend assets:
   ```bash
   npm run build
   ```

7. Start the development server:
   ```bash
   php artisan serve
   ```

8. In another terminal, start the frontend dev server:
   ```bash
   npm run dev
   ```

## API Endpoints

- `POST /api/calculate` - Perform a calculation
- `GET /api/history` - Get calculation history
- `DELETE /api/history/{id}` - Delete a specific calculation
- `DELETE /api/history` - Clear all calculations

## Features

- Basic arithmetic operations (+, -, *, /)
- Calculation history storage
- Vue.js calculator interface
- Ticker tape display of previous calculations
- Individual and bulk deletion of history
- Support for complex mathematical expressions (stretch goal)

## Testing

Run the test suite:

```bash
php artisan test
```

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
