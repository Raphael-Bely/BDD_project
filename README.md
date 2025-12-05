# BDD Project - Restaurant Ordering System

## Web Application

The deployed web application is accessible at:

**URL**: https://tabeille001.zzz.bordeaux-inp.fr/src/index.php

## Project Overview

This project implements a complete restaurant menu ordering system with a PostgreSQL database backend and PHP web application frontend. The system supports multiple restaurants, customers with loyalty programs, and order management with formulas (menus) and complements.

## Prerequisites

- **Web Server**: Apache or compatible web server with PHP support
- **PHP 8.0+**: With PDO PostgreSQL extension
- **PostgreSQL 13+**: With PostGIS extension (database hosted on Bordeaux-INP server)
- **Network Access**: Connection to tabeille001.zzz.bordeaux-inp.fr

## Installation & Deployment

### Database Setup

The application connects to a PostgreSQL database hosted on the Bordeaux-INP server. The database has been initialized with the following SQL scripts:

#### 1. Initialize Database Schema

Execute the SQL initialization scripts on the Bordeaux-INP PostgreSQL server:

```bash
# Run the database initialization scripts in order
PGPASSWORD="bEe1974Ca!" psql -h tabeille001.zzz.bordeaux-inp.fr -U tabeille001 -d tabeille001 -f drop.sql
PGPASSWORD="bEe1974Ca!" psql -h tabeille001.zzz.bordeaux-inp.fr -U tabeille001 -d tabeille001 -f create.sql
PGPASSWORD="bEe1974Ca!" psql -h tabeille001.zzz.bordeaux-inp.fr -U tabeille001 -d tabeille001 -f insert.sql
```

#### 2. Application Deployment

The PHP application has been deployed to the Bordeaux-INP web server using FileZilla. The application files are already in place and accessible at:

```
https://tabeille001.zzz.bordeaux-inp.fr/src/index.php
```

The application automatically connects to the Bordeaux-INP PostgreSQL database using the credentials configured in `src/config/Database.php`:

```php
private $host = "tabeille001.zzz.bordeaux-inp.fr";    
private $db_name = "tabeille001"; 
private $username = "tabeille001"; 
private $password = "bEe1974Ca!";
```

## Database Verification

To verify that the database has been properly initialized with all tables and data, execute:

```bash
# Check that all tables were created
# Should display 27 tables: restaurants, items, clients, commandes, fidelite, etc.
PGPASSWORD="bEe1974Ca!" psql -h tabeille001.zzz.bordeaux-inp.fr -U tabeille001 -d tabeille001 -c "\dt"


# List all restaurants
PGPASSWORD="bEe1974Ca!" psql -h tabeille001.zzz.bordeaux-inp.fr -U tabeille001 -d tabeille001 -c "SELECT * FROM restaurants;"

# Count orders
PGPASSWORD="bEe1974Ca!" psql -h tabeille001.zzz.bordeaux-inp.fr -U tabeille001 -d tabeille001 -c "SELECT COUNT(*) FROM commandes;"
```
