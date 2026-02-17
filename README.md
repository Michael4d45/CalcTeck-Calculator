# CalcTeck Calculator - Demo Exercise

## Introduction

This is a tech demo showcasing a Laravel-based backend API with a Vue.js frontend calculator interface.

## Project Overview

The application centers on an API that performs four core arithmetic operations:

- Addition
- Subtraction
- Division
- Multiplication

Each calculation is persisted so previous results are available as historical records.

A Vue.js calculator interface provides input, submits expressions to the API, and renders the returned result.

A ticker-tape style history view displays prior calculations and supports both single-entry deletion and full history clearing.

## Stretch Goal

The extended capability includes support for more complex calculation chains and additional operators, for example:

`sqrt((((9*9)/12)+(13-4))*2)^2`

## Technologies Used

- **Backend**: Laravel (PHP framework)
- **Frontend**: Vue.js with Vite
- **Database**: SQLite (for simplicity in demo)
- **Testing**: Pest/PHPUnit

## Environment and Setup

The repository is designed to run in a standard Laravel + Vite development environment.

Typical setup includes PHP dependency installation with Composer, JavaScript dependency installation with npm, Laravel environment initialization, database migration, and frontend asset compilation through Vite.

The application is commonly served through Laravel's local server tooling with Vite running for frontend development.

## API Endpoints

- `POST /api/calculate` - Performs a calculation request
- `GET /api/history` - Returns calculation history
- `DELETE /api/history/{id}` - Deletes a specific history item
- `DELETE /api/history` - Clears all stored calculations

## Features

- Basic arithmetic operations (+, -, *, /)
- Calculation history storage
- Vue.js calculator interface
- Ticker tape display of previous calculations
- Individual and bulk deletion of history
- Support for complex mathematical expressions (stretch goal)

## Testing

Automated tests are provided through Pest/PHPUnit to validate API behavior and application functionality.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
