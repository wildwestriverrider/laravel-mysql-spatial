# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Laravel MySQL Spatial extension package that provides spatial data types and functions for MySQL 8.0+ with SRID support in Laravel 8+. This is a fork that adds support for working with geographic/geometric data in Laravel applications.

## Development Commands

### Testing
```bash
# Run all tests
composer test

# Run unit tests only
composer test:unit

# Run integration tests only (requires MySQL database)
composer test:integration

# Run a specific test file
./vendor/bin/phpunit tests/Unit/Types/PointTest.php

# Run a specific test method
./vendor/bin/phpunit --filter testFromWKT tests/Unit/Types/PointTest.php
```

### Database Setup for Testing
```bash
# Start MySQL 8.0 in Docker (default)
make start_db

# Start MySQL 5.7 in Docker
make start_db V=5.7

# Start MariaDB 10.3 in Docker
make start_db_maria

# Stop and remove database
make rm_db

# Refresh database (stop, remove, start)
make refresh_db
```

### Package Development
```bash
# Install dependencies
composer install

# Update dependencies
composer update
```

## Architecture

### Core Components

1. **Spatial Types** (`src/Types/`): Geometry classes representing MySQL spatial data types
   - Point, LineString, Polygon and their Multi* variants
   - GeometryCollection for mixed geometry types
   - All implement GeometryInterface and support WKT, GeoJSON, iteration

2. **Eloquent Integration** (`src/Eloquent/`):
   - `SpatialTrait`: Must be used by models with spatial fields
   - `Builder`: Extended query builder with spatial scopes (distance, within, intersects, etc.)
   - Models must define `$spatialFields` array listing spatial columns

3. **Schema/Migration Support** (`src/Schema/`):
   - `Blueprint`: Extends Laravel's Blueprint with spatial column types
   - `MySqlGrammar`: Handles spatial column and index SQL generation
   - Supports SRID specification for all spatial types

4. **Database Connection** (`src/MysqlConnection.php`):
   - Custom connection handling spatial data serialization/deserialization
   - Integrates with Laravel's database layer

5. **Doctrine Type Mappings** (`src/Doctrine/`):
   - Maps MySQL spatial types to Doctrine types for migrations

### Key Patterns

- **Spatial Data Flow**: Database → WKB format → Parser → Geometry objects → Model attributes
- **Saving**: Geometry objects → WKT format → ST_GeomFromText() → Database
- **SRID Support**: All geometry constructors accept optional SRID parameter (default 0)
- **Collection Geometries**: Only top-level geometry should have SRID in constructor

### Testing Approach

- Unit tests mock database interactions and test geometry operations
- Integration tests require actual MySQL database connection
- Tests use `spatial_test` database (configured in phpunit.xml.dist)
- Database credentials: root/empty password for local, root/root for CI

## Working with Spatial Data

When implementing features:
1. Spatial fields must be defined in model's `$spatialFields` array
2. Use appropriate Geometry class for the spatial type
3. Collection geometries (Polygon, MultiPoint, etc.) contain nested geometries
4. Spatial indexes require columns to be NOT NULL
5. Check MySQL version compatibility for spatial functions usage