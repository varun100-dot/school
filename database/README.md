# Zuvio Global School — MySQL Database Setup

This folder contains the database schema definitions and initial seed configurations.

## Schema Layout

- `schema.sql`: Contains normalized MySQL DDL script representing user access, core page settings, page configurations, site properties, Dynamic banner registry, custom Extracurricular listings, media trackers, and contact form enquiries.
- `seed.sql`: Populates default content based on Zuvio Global School requirements including known founders, alignment with CBSE, contact info, menu settings, and "Content pending" markers where information is currently absent.

## Local Database Installation

1. Open a MySQL terminal or dashboard.
2. Create the database:
   ```sql
   CREATE DATABASE zuvio_global_school;
   ```
3. Load the schema and default content:
   ```bash
   mysql -u root -p zuvio_global_school < schema.sql
   mysql -u root -p zuvio_global_school < seed.sql
   ```
