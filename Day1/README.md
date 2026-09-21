# Day 1: Project Foundation and Database

This folder contains only the initial PHP configuration, database schema, and connection test. Later day folders are intentionally kept separate so their feature work can be added without mixing scopes.

## Database setup

Run `database.sql` in MySQL/phpMyAdmin. The schema creates the `shivkrupa_art_jewellery` database and the tables required by the later application stages.

## Configuration

Copy `config/.env.example` to `config/.env` and set the local MySQL credentials. `config/config.php` reads those values, while `config/database.php` creates a PDO connection with exceptions and native prepared statements enabled.
