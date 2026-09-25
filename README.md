# ShivKrupa Art Jewellery

The project is organized by implementation day so each scope stays isolated:

- `Day1/` - project foundation and database setup
- `Day2/` - authentication
- `Day3/` - products and categories
- `Day4/` - shopping cart
- `Day5/` - checkout, orders, and payment
- `Day6/` - administration
- `Day7/` - security, testing, and documentation

## Day 1 setup

1. Create a MySQL database using `Day1/database.sql`.
2. Copy `Day1/config/.env.example` to `Day1/config/.env` and update the local values.
3. From the project root, run:

```text
C:\xampp\php\php.exe Day1\tests\connection_test.php
```

The connection test reports whether the configured database is reachable. The SQL scripts use MySQL syntax for XAMPP. If you use the Microsoft SQL Server extension, this workspace marks the scripts as plain text to prevent incorrect SQL Server diagnostics. Execute them with MySQL/phpMyAdmin or the XAMPP MySQL client.

Run focused tests with:

```text
C:\xampp\php\php.exe Day2\tests\auth_test.php
C:\xampp\php\php.exe Day3\tests\catalog_test.php
C:\xampp\php\php.exe Day4\tests\cart_test.php
C:\xampp\php\php.exe Day5\tests\order_test.php
C:\xampp\php\php.exe Day7\tests\security_test.php
```

Day 6 administrator access requires changing a trusted user's role to `admin` in MySQL.
