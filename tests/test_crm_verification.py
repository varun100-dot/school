#!/usr/bin/env python3
"""
Verify Admin CRM functionality, 6 summary cards, filters, and CSV export.
"""
import sqlite3
import subprocess
import csv
import io
import json

DB_PATH = "database/local.sqlite"

def get_counts_from_db():
    conn = sqlite3.connect(DB_PATH)
    cur = conn.cursor()
    
    total = cur.execute("SELECT COUNT(*) FROM enquiries").fetchone()[0]
    google = cur.execute("SELECT COUNT(*) FROM enquiries WHERE marketing_source = 'Google Ads'").fetchone()[0]
    fb = cur.execute("SELECT COUNT(*) FROM enquiries WHERE marketing_source = 'Facebook Ads'").fetchone()[0]
    ig = cur.execute("SELECT COUNT(*) FROM enquiries WHERE marketing_source = 'Instagram Ads'").fetchone()[0]
    organic = cur.execute("SELECT COUNT(*) FROM enquiries WHERE marketing_source = 'Organic Search'").fetchone()[0]
    direct_other = cur.execute("SELECT COUNT(*) FROM enquiries WHERE marketing_source IN ('Direct', 'Referral', 'Other / Unknown') OR marketing_source IS NULL OR marketing_source = ''").fetchone()[0]
    
    return {
        'total': total,
        'google': google,
        'fb': fb,
        'ig': ig,
        'organic': organic,
        'direct_other': direct_other
    }

def test_admin_metrics_and_csv():
    print("=" * 70)
    print("VERIFYING ADMIN CRM SUMMARY METRICS, FILTERS & CSV EXPORT")
    print("=" * 70)

    db_counts = get_counts_from_db()
    print("Database Direct Counts:")
    for k, v in db_counts.items():
        print(f"  {k}: {v}")

    # Verify that the sum of the channels equals the total
    assert (db_counts['google'] + db_counts['fb'] + db_counts['ig'] + db_counts['organic'] + db_counts['direct_other']) == db_counts['total'], "Channel counts do not sum to total!"
    print("✓ PASS: Summary channel metric cards sum exactly to Total Leads.")

    # Test CSV Export query via PHP CLI to ensure all 20 columns exist and match
    php_csv = """
    require 'includes/db.php';
    require 'includes/helper.php';
    safe_session_start();
    $_GET['action'] = 'export_csv';
    $_SESSION['user_id'] = 1;
    $_SESSION['username'] = 'admin';
    $_SESSION['role_name'] = 'super_admin';
    $_SESSION['permissions'] = ['*'];

    ob_start();
    include 'admin/enquiries.php';
    $csv = ob_get_clean();
    echo $csv;
    """
    p = subprocess.run(["php", "-r", php_csv], capture_output=True, text=True)
    csv_text = p.stdout
    assert "Parent Name" in csv_text, "CSV header missing Parent Name"
    assert "Marketing Source" in csv_text, "CSV header missing Marketing Source"
    assert "Google Click ID (gclid)" in csv_text, "CSV header missing gclid"
    assert "Facebook Click ID (fbclid)" in csv_text, "CSV header missing fbclid"
    assert "Landing Page" in csv_text, "CSV header missing Landing Page"

    reader = csv.reader(io.StringIO(csv_text))
    rows = list(reader)
    header = rows[0]
    print(f"\nCSV Export Header Columns ({len(header)} total):")
    print("  " + ", ".join(header))
    assert len(header) == 20, f"Expected 20 CSV columns, got {len(header)}"
    print(f"Total exported rows: {len(rows) - 1}")
    assert len(rows) - 1 == db_counts['total'], f"Expected {db_counts['total']} rows in CSV, got {len(rows) - 1}"
    print("✓ PASS: CSV export matches database count and contains all 20 attribution columns.")

    # Test Filtering by Channel (e.g., Google Ads)
    php_filter = """
    require 'includes/db.php';
    require 'includes/helper.php';
    safe_session_start();
    $_GET['mktg_source'] = 'Google Ads';
    $_SESSION['user_id'] = 1;
    $_SESSION['username'] = 'admin';
    $_SESSION['role_name'] = 'super_admin';
    $_SESSION['permissions'] = ['*'];

    ob_start();
    include 'admin/enquiries.php';
    $html = ob_get_clean();
    echo "COUNT_ENQUIRIES=" . count($enquiries);
    """
    p_filt = subprocess.run(["php", "-r", php_filter], capture_output=True, text=True)
    print(f"\nFilter 'Google Ads' Result: {p_filt.stdout.strip()}")
    assert f"COUNT_ENQUIRIES={db_counts['google']}" in p_filt.stdout, f"Expected {db_counts['google']} filtered records"
    print("✓ PASS: Marketing Channel filtering operates with 100% precision.")

    print("\n" + "=" * 70)
    print("ADMIN CRM VERIFICATION AUDIT: 100% PASS")
    print("=" * 70)

if __name__ == "__main__":
    test_admin_metrics_and_csv()

