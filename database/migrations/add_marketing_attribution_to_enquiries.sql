-- ==============================================================================
-- Zuvio Global School - Database Migration
-- Script: add_marketing_attribution_to_enquiries.sql
-- Description: Non-destructive, idempotent migration to add marketing attribution
--              and touchpoint tracking fields to the `enquiries` table.
-- Compatibility: MySQL 5.7+ / MySQL 8.0+ / MariaDB
-- Date: 2026-11-16
-- ==============================================================================

-- 1. Helper Stored Procedure for Idempotent Column Addition (MySQL 5.7+ / MariaDB)
DELIMITER $$

DROP PROCEDURE IF EXISTS `AddColumnIfNotExists`$$

CREATE PROCEDURE `AddColumnIfNotExists`(
    IN in_table VARCHAR(64),
    IN in_column VARCHAR(64),
    IN in_type_clause VARCHAR(255)
)
BEGIN
    DECLARE col_count INT;
    SELECT COUNT(*) INTO col_count
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = in_table
      AND COLUMN_NAME = in_column;

    IF col_count = 0 THEN
        SET @alter_sql = CONCAT('ALTER TABLE `', in_table, '` ADD COLUMN `', in_column, '` ', in_type_clause);
        PREPARE stmt FROM @alter_sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
    END IF;
END$$

DELIMITER ;

-- 2. Execute Column Additions Safely
CALL AddColumnIfNotExists('enquiries', 'marketing_source', 'VARCHAR(100) NULL DEFAULT "Direct" AFTER `source`');
CALL AddColumnIfNotExists('enquiries', 'utm_source', 'VARCHAR(150) NULL DEFAULT NULL AFTER `marketing_source`');
CALL AddColumnIfNotExists('enquiries', 'utm_medium', 'VARCHAR(150) NULL DEFAULT NULL AFTER `utm_source`');
CALL AddColumnIfNotExists('enquiries', 'utm_campaign', 'VARCHAR(255) NULL DEFAULT NULL AFTER `utm_medium`');
CALL AddColumnIfNotExists('enquiries', 'utm_content', 'VARCHAR(255) NULL DEFAULT NULL AFTER `utm_campaign`');
CALL AddColumnIfNotExists('enquiries', 'utm_term', 'VARCHAR(255) NULL DEFAULT NULL AFTER `utm_content`');
CALL AddColumnIfNotExists('enquiries', 'gclid', 'VARCHAR(255) NULL DEFAULT NULL AFTER `utm_term`');
CALL AddColumnIfNotExists('enquiries', 'fbclid', 'VARCHAR(255) NULL DEFAULT NULL AFTER `gclid`');
CALL AddColumnIfNotExists('enquiries', 'landing_page', 'VARCHAR(255) NULL DEFAULT NULL AFTER `fbclid`');
CALL AddColumnIfNotExists('enquiries', 'referrer', 'VARCHAR(500) NULL DEFAULT NULL AFTER `landing_page`');
CALL AddColumnIfNotExists('enquiries', 'first_touch', 'TEXT NULL DEFAULT NULL AFTER `referrer`');
CALL AddColumnIfNotExists('enquiries', 'last_touch', 'TEXT NULL DEFAULT NULL AFTER `first_touch`');

-- 3. Clean up the migration helper procedure
DROP PROCEDURE IF EXISTS `AddColumnIfNotExists`;

-- 4. Create Indexes for CRM Performance Filtering
DELIMITER $$

DROP PROCEDURE IF EXISTS `AddIndexIfNotExists`$$

CREATE PROCEDURE `AddIndexIfNotExists`(
    IN in_table VARCHAR(64),
    IN in_index VARCHAR(64),
    IN in_column_list VARCHAR(255)
)
BEGIN
    DECLARE idx_count INT;
    SELECT COUNT(*) INTO idx_count
    FROM INFORMATION_SCHEMA.STATISTICS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = in_table
      AND INDEX_NAME = in_index;

    IF idx_count = 0 THEN
        SET @idx_sql = CONCAT('ALTER TABLE `', in_table, '` ADD INDEX `', in_index, '` (', in_column_list, ')');
        PREPARE stmt FROM @idx_sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
    END IF;
END$$

DELIMITER ;

CALL AddIndexIfNotExists('enquiries', 'idx_enquiries_mktg_source', '`marketing_source`');
CALL AddIndexIfNotExists('enquiries', 'idx_enquiries_campaign', '`utm_campaign`');
CALL AddIndexIfNotExists('enquiries', 'idx_enquiries_created_at', '`created_at`');

DROP PROCEDURE IF EXISTS `AddIndexIfNotExists`;

-- End of migration

