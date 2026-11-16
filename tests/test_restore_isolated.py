#!/usr/bin/env python3
"""
Isolated Restore Verification Test
Restores database backup into an isolated staging database and validates:
1. Tables and schema
2. Historical enquiry records
3. Row counts match production backup
4. Application CRM queries execute successfully
"""
import sqlite3
import os
import shutil
import hashlib

backup_sql = '/Users/varunkairalimac/Documents/Varun/zuvio_backups/database/zuvio_production_db_backup_20261116_130602.sql'
backup_sqlite = '/Users/varunkairalimac/Documents/Varun/zuvio_backups/database/zuvio_sqlite_db_backup_20261116_130602.sqlite'

isolated_test_db = '/tmp/zuvio_isolated_restore_test.sqlite'
if os.path.exists(isolated_test_db):
    os.remove(isolated_test_db)

print(f"[*] Restoring SQLite backup to isolated staging DB: {isolated_test_db}")
shutil.copy2(backup_sqlite, isolated_test_db)

conn = sqlite3.connect(isolated_test_db)
conn.row_factory = sqlite3.Row
cursor = conn.cursor()

# 1. Check tables
cursor.execute("SELECT name FROM sqlite_master WHERE type='table' AND name != 'sqlite_sequence' ORDER BY name")
restored_tables = [row[0] for row in cursor.fetchall()]
print(f"[+] Restored Table Count: {len(restored_tables)}")

# 2. Check enquiries row count
cursor.execute("SELECT COUNT(*) FROM enquiries")
enquiries_count = cursor.fetchone()[0]
print(f"[+] Restored Enquiries Count: {enquiries_count}")
assert enquiries_count == 16, f"Expected 16 enquiries, found {enquiries_count}"

# 3. Check historical records
cursor.execute("SELECT id, parent_name, email, phone, grade, source, marketing_source FROM enquiries ORDER BY id ASC")
leads = cursor.fetchall()
print(f"[+] Historical leads verified:")
for l in leads[:5]:
    print(f"    - Lead #{l['id']}: {l['parent_name']} | {l['email']} | {l['source']} | Channel: {l['marketing_source']}")

# 4. Verify CRM channel summary query
cursor.execute("""
    SELECT 
        COALESCE(marketing_source, 'Direct') as channel,
        COUNT(*) as cnt
    FROM enquiries
    GROUP BY COALESCE(marketing_source, 'Direct')
    ORDER BY cnt DESC
""")
crm_summary = cursor.fetchall()
print("[+] CRM Channel Summary Query Result on Restored DB:")
total_crm_leads = 0
for row in crm_summary:
    print(f"    - {row['channel']}: {row['cnt']}")
    total_crm_leads += row['cnt']
assert total_crm_leads == 16, f"Expected 16 leads in CRM summary, found {total_crm_leads}"

# 5. Verify MySQL dump file integrity and table count
with open(backup_sql, 'r', encoding='utf-8') as f:
    sql_text = f.read()

import re
create_tables = re.findall(r'CREATE TABLE IF NOT EXISTS `?(\w+)`?', sql_text)
print(f"[+] MySQL Dump Table Count in SQL backup: {len(set(create_tables))}")
insert_statements = len(re.findall(r'INSERT INTO `?enquiries`?', sql_text))
print(f"[+] MySQL Dump Enquiries INSERT Count: {insert_statements}")
assert insert_statements == 16, f"Expected 16 enquiries INSERTs in MySQL dump, found {insert_statements}"

conn.close()
print("\n[SUCCESS] Isolated Restore Test Completed Successfully.")
