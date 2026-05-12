# Runtracker

[![Lint](https://github.com/arzamaskov/runtracker/actions/workflows/lint.yml/badge.svg)](https://github.com/arzamaskov/runtracker/actions/workflows/lint.yml)
[![Test](https://github.com/arzamaskov/runtracker/actions/workflows/test.yml/badge.svg)](https://github.com/arzamaskov/runtracker/actions/workflows/test.yml)
[![GitHub tag](https://img.shields.io/github/v/tag/arzamaskov/runtracker)](https://github.com/arzamaskov/runtracker/tags)
[![License: AGPL-3.0](https://img.shields.io/badge/License-AGPL--3.0-blue.svg)](https://www.gnu.org/licenses/agpl-3.0)
[![PHP](https://img.shields.io/badge/PHP-8.3-777BB4.svg)](https://www.php.net/)
[![Laravel](https://img.shields.io/badge/Laravel-13-FF2D20.svg)](https://laravel.com/)

A self-hosted running log for runners who want more structure than a watch app, without the weight of a full training platform.

## Stack

- Laravel 13
- PHP 8.3
- PostgreSQL
- Tailwind CSS 4
- Vite
- Docker Compose

## Local Development

Start the Docker environment:

```bash
cp .env.docker.example .env.docker
make up
```

Install dependencies, generate the application key, and run migrations:

```bash
make install
make migrate
```

The app runs at http://localhost:8080 and Vite at http://localhost:5173.

## Useful Commands

```bash
make help
make logs
make shell
make artisan cmd=migrate
make app-key
make composer cmd=install
make test
make lint
make phpstan
make deptrac
make qa
```

## Production

Build and start the production Docker environment:

```bash
make prod-up
```

Production images do not include Node.js. Build frontend assets before creating the production image so the compiled files are available under `public/build`.
