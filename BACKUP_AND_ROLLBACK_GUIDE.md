# ZUVIO GLOBAL SCHOOL — COMPREHENSIVE BACKUP & SAFE ROLLBACK GUIDE

**Project:** Zuvio Global School  
**Website:** https://zuvioglobalschool.com/  
**Repository:** `varun100-dot/school`  
**Document Classification:** Production Operations & Disaster Recovery  
**Version:** 2.0 (Phase 11 Production-Ready Release)  
**Last Updated:** November 16, 2026  

---

## 1. Verified Backup Inventory & Cryptographic Checksums

All verified backups are stored strictly **outside the public web document root** in a dedicated, permission-locked directory (`chmod 700` for directories, `chmod 600` for files).

- **Master Backup Root:** `/Users/varunkairalimac/Documents/Varun/zuvio_backups/`
- **Hostinger Server Equivalent:** `/home/u869064717/zuvio_backups/`

### A. Primary Production Backup Manifest

| Backup Component | Filename & Storage Path | Size | SHA-256 Checksum | Description |
|---|---|---|---|---|
| **Production MySQL Database** | `database/zuvio_production_db_backup_20261116_130602.sql` | 265,239 bytes (~259 KB) | `e465826f9423e862d082723e8f47bacb3c29c52b0f8da4cbf90109018b2422e2` | Full DDL schema (29 tables), UTF-8 mb4, all 16 historical enquiries. |
| **Compressed MySQL Database** | `database/zuvio_production_db_backup_20261116_130602.sql.gz` | 32,859 bytes (~32 KB) | `ad4ea89ec50b820422e769490ca09a932757d8916f4630384e1cf63f7f52c185` | Gzip compressed MySQL database dump. |
| **SQLite Application Database** | `database/zuvio_sqlite_db_backup_20261116_130602.sqlite` | 405,504 bytes (~396 KB) | `3f3a88e5ca3adf1e105483c6dca4613aea252386d4813386cc456d420eff0323` | Standalone binary snapshot of 28 tables + SQLite sequence. |
| **Website Application Files** | `website/zuvio_production_web_backup_20261116_130602.tar.gz` | 154,233,138 bytes (~147 MB) | `d48dd1a46ae1288b6cda2a90a334a2d5cf412f24300de3b2c273e3c4e109e1b0` | Full website files (PHP, CSS, JS, assets, uploads, `.htaccess`, sitemap). |
| **External Master Config** | `config/zuvio_config_backup_20261116_145500.tar.gz` | 774 bytes | `3cac1ebd94298ee5d0a81b8d3cd7e5bf6e66a66d125626bb15be30af5a4aaa13` | External config archive (`config.php`) with restricted permissions. |
| **External Master Config File** | `config/zuvio_config_backup_20261116_145500.php` | 760 bytes | `6ac1546c1e72b18f32602fe8ed91e78f971fb0fbfad417cb541098744ad324c5` | Raw configuration backup file. |

### B. AES-256 Encrypted Off-Site Backup Staging
Directory: `/Users/varunkairalimac/Documents/Varun/zuvio_backups/encrypted_offsite_staging/`

| Encrypted File | Original Target | Size | SHA-256 Checksum |
|---|---|---|---|
| `zuvio_production_db_backup_20261116_130602.sql.gz.enc` | MySQL DB Dump | 32,880 bytes | `faf89c1ebbd6dceb8e549a2f6cfa8e847ad37b9d540c0aaf1a751e68b5d65200` |
| `zuvio_production_web_backup_20261116_130602.tar.gz.enc` | Website Files | 154,425,488 bytes | `1eec3001485c6f4ae268f56eaed90d05033ac00e7dcfcbd20627e38897ec723f` |
| `zuvio_config_backup_20261116_145500.tar.gz.enc` | Config Backup | 800 bytes | `3554b4ef7a98fbe8b8b5498bd887e965127010e0f438c2ff0d522c8acc43f481` |

> Encryption Key Path: Stored separately at `/Users/varunkairalimac/Documents/Varun/zuvio_backups/keys/backup_aes256.key` (`chmod 600`). Never commit keys or store them in the same archive directory.

---

## 2. Environment Architecture & Path Specifications

- **Hosting Environment:** Hostinger Cloud / Shared cPanel Architecture (`u869064717`)
- **Web Server:** Apache 2.4 with `mod_rewrite`, `mod_headers`, `mod_expires`
- **PHP Runtime:** PHP 8.1+ / PHP 8.4+ (FastCGI / CLI)
- **Production Document Root:** `/home/u869064717/domains/zuvioglobalschool.com/public_html`
- **External Configuration Location:** `/home/u869064717/config.php` (stored 2 levels above public web root)
- **Database Engine & Version:** MySQL 8.0 / MariaDB 10.11 on Hostinger cloud infrastructure (`srv1113.hstgr.io`)
- **Production Database Name:** `u869064717_school`
- **Charset & Collation:** `utf8mb4` / `utf8mb4_unicode_ci`

---

## 3. Pre-Restore Checksum & Integrity Verification

Before restoring any file, verify its integrity:

```bash
# 1. Verify Gzip Archives
gzip -t /path/to/backup/database/zuvio_production_db_backup_20261116_130602.sql.gz
gzip -t /path/to/backup/website/zuvio_production_web_backup_20261116_130602.tar.gz
gzip -t /path/to/backup/config/zuvio_config_backup_20261116_145500.tar.gz

# 2. Check Cryptographic Hashes
shasum -a 256 /path/to/backup/database/zuvio_production_db_backup_20261116_130602.sql
shasum -a 256 /path/to/backup/website/zuvio_production_web_backup_20261116_130602.tar.gz
shasum -a 256 /path/to/backup/config/zuvio_config_backup_20261116_145500.tar.gz
```

---

## 4. Scenario-Specific Failure Recovery Playbooks

### Playbook A: Code Deployment Fails
*Symptoms: PHP fatal syntax error, white screen of death, 500 error on page loads after git pull.*

1. **Activate Emergency Holding / Maintenance Rule in `.htaccess`:**
   ```apache
   # Prepend to .htaccess:
   RewriteEngine On
   RewriteCond %{REMOTE_ADDR} !=127.0.0.1
   RewriteRule ^.*$ - [R=503,L]
   ```
2. **Execute Clean Application Rollback (Do not rely solely on `git checkout`):**
   ```bash
   cd /home/u869064717/domains/zuvioglobalschool.com/public_html
   
   # Restore code from verified snapshot archive:
   tar -xzf /home/u869064717/zuvio_backups/website/zuvio_production_web_backup_20261116_130602.tar.gz -C .
   ```
3. **Verify Git State Alignment:**
   ```bash
   git status
   git reset --hard <last_known_good_commit_hash>
   ```
4. **Remove Maintenance Rule from `.htaccess` and test homepage.**

---

### Playbook B: Database Migration Fails Halfway
*Symptoms: SQL error during column creation, lock timeout, partial schema state.*

> [!IMPORTANT]
> The Zuvio migration procedure (`add_marketing_attribution_to_enquiries.sql`) is non-destructive and idempotent. Each column is guarded by `IF NOT EXISTS (SELECT * FROM information_schema.COLUMNS...)`.

If a migration aborts halfway:
1. **Do NOT drop the table.** Historical enquiry leads reside in `enquiries`.
2. **Check Current Columns:**
   ```sql
   SELECT COLUMN_NAME, DATA_TYPE FROM information_schema.COLUMNS 
   WHERE TABLE_SCHEMA = 'u869064717_school' AND TABLE_NAME = 'enquiries';
   ```
3. **Rollback Schema Safely (Drop added columns only if needed, leaving historical data untouched):**
   ```sql
   -- Only drop new attribution columns if you need to return to baseline schema:
   ALTER TABLE enquiries
     DROP COLUMN IF EXISTS marketing_source,
     DROP COLUMN IF EXISTS first_touch,
     DROP COLUMN IF EXISTS last_touch,
     DROP COLUMN IF EXISTS utm_source,
     DROP COLUMN IF EXISTS utm_medium,
     DROP COLUMN IF EXISTS utm_campaign,
     DROP COLUMN IF EXISTS utm_content,
     DROP COLUMN IF EXISTS utm_term,
     DROP COLUMN IF EXISTS gclid,
     DROP COLUMN IF EXISTS fbclid,
     DROP COLUMN IF EXISTS landing_page,
     DROP COLUMN IF EXISTS referrer;
   ```
4. **Alternative: Full Database Restore from Snapshot:**
   ```bash
   gunzip -c /home/u869064717/zuvio_backups/database/zuvio_production_db_backup_20261116_130602.sql.gz > /tmp/restore.sql
   mysql -h 127.0.0.1 -u <db_user> -p u869064717_school < /tmp/restore.sql
   rm -f /tmp/restore.sql
   ```
5. **Verify Row Count:** `SELECT COUNT(*) FROM enquiries;` must return `16`.

---

### Playbook C: Forms Stop Saving Leads
*Symptoms: Form submission returns HTTP 500 or error notification.*

1. **Check Database Connectivity:**
   Run quick diagnostic via CLI:
   ```bash
   php -r "require 'includes/db.php'; print_r(testConnection());"
   ```
2. **Dual-Schema Backward Compatibility Activation:**
   - The application form handlers in `submit-callback.php`, `pages/nursery-to-grade-12.php`, `homeschooling/submit-lead.php`, `pages/online-school.php`, `pages/home.php`, and `pages/contact.php` are built with automatic dual-schema fallback.
   - If an "Unknown column" error occurs, handlers automatically fall back to the legacy 8 columns (`parent_name`, `student_name`, `grade`, `phone`, `email`, `message`, `source`, `status_id`).
   - If error persists, inspect `/home/u869064717/domains/zuvioglobalschool.com/public_html/error_log` for specific PDO exceptions.

---

### Playbook D: Admin CRM Stops Loading
*Symptoms: 500 error or blank screen on `/admin/enquiries.php`.*

1. **Restore `admin/enquiries.php` from Verified Backup:**
   ```bash
   tar -xzf /home/u869064717/zuvio_backups/website/zuvio_production_web_backup_20261116_130602.tar.gz admin/enquiries.php
   ```
2. **Clear PHP OpCache (if applicable):**
   ```bash
   touch index.php
   ```
3. **Verify Session & Auth:**
   Verify `safe_session_start()` and cookie permissions in `includes/auth.php`.

---

### Playbook E: Website Redirects Break or Infinite Loop Occurs
*Symptoms: Browser reports ERR_TOO_MANY_REDIRECTS on `/online-school/` or `/landing-page/`.*

1. **Restore Production `.htaccess` from Backup:**
   ```bash
   tar -xzf /home/u869064717/zuvio_backups/website/zuvio_production_web_backup_20261116_130602.tar.gz .htaccess
   ```
2. **Restore Router `index.php` from Backup:**
   ```bash
   tar -xzf /home/u869064717/zuvio_backups/website/zuvio_production_web_backup_20261116_130602.tar.gz index.php
   ```
3. **Verify with Curl:**
   ```bash
   curl -I https://zuvioglobalschool.com/landing-page/
   # Expected: HTTP/1.1 301 Moved Permanently -> Location: /online-school/
   
   curl -I https://zuvioglobalschool.com/online-school/
   # Expected: HTTP/1.1 200 OK
   ```

---

## 5. Recovery of External Configuration & User Uploads

### A. External Configuration Recovery
If `/home/u869064717/config.php` is accidentally deleted, overwritten, or corrupted:
```bash
# 1. Restore from backup
tar -xzf /home/u869064717/zuvio_backups/config/zuvio_config_backup_20261116_145500.tar.gz -C /tmp/
cp -p /tmp/zuvio_config_backup_20261116_145500.php /home/u869064717/config.php

# 2. Enforce strict permissions
chmod 600 /home/u869064717/config.php
rm -f /tmp/zuvio_config_backup_20261116_145500.php
```

### B. User Uploads and Media Recovery
If files in `uploads/` are missing:
```bash
tar -xzf /home/u869064717/zuvio_backups/website/zuvio_production_web_backup_20261116_130602.tar.gz uploads/
chmod -R 775 uploads/
```

---

## 6. Complete End-to-End Restoration Verification Checklist

Following any restoration, complete every check in this verification suite:

- [ ] **DB Connectivity & Counts:**
  `SELECT count(*) FROM enquiries;` equals `16`.
  `SELECT count(*) FROM blogs;` equals `30`.
  `SELECT count(*) FROM users;` equals `2`.
- [ ] **Landing Page Responses:**
  `curl -s -o /dev/null -w "%{http_code}" https://zuvioglobalschool.com/online-school/` returns `200`.
  `curl -s -o /dev/null -w "%{http_code}" https://zuvioglobalschool.com/nursery-to-grade-12/` returns `200`.
  `curl -s -o /dev/null -w "%{http_code}" https://zuvioglobalschool.com/homeschooling/` returns `200`.
- [ ] **Redirect Rule:**
  `curl -s -o /dev/null -w "%{http_code}" https://zuvioglobalschool.com/landing-page/` returns `301`.
- [ ] **Admin Authentication & CRM Access:**
  Able to log into `/admin/login.php` and view `/admin/enquiries.php`.
- [ ] **Lead Submission Smoke Test:**
  Submit a test enquiry from the modal or landing page and verify the lead is stored in the database.
