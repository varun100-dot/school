#!/usr/bin/env python3
"""
Test HTTP Error Handling across all 6 form handlers:
1. submit-callback.php
2. homeschooling/submit-lead.php
3. pages/nursery-to-grade-12.php
4. pages/online-school.php
5. pages/home.php
6. pages/contact.php
"""
import subprocess
import json
import urllib.request
import urllib.parse
import re

BASE_URL = "http://127.0.0.1:8899"

def run_php(snippet):
    p = subprocess.run(["php", "-r", snippet], capture_output=True, text=True)
    return p.stdout, p.stderr

def main():
    print("=" * 70)
    print("VERIFYING HTTP ERROR HANDLING ACROSS ALL 6 FORM HANDLERS")
    print("=" * 70)

    # 1. submit-callback.php
    print("\n1. submit-callback.php:")
    # 1a. Invalid CSRF -> 403
    req = urllib.request.Request(
        f"{BASE_URL}/submit-callback.php",
        data=urllib.parse.urlencode({"csrf_token": "invalid_csrf"}).encode('utf-8'),
        headers={"User-Agent": "Mozilla/5.0", "X-Requested-With": "XMLHttpRequest"}
    )
    try:
        urllib.request.urlopen(req)
        assert False, "Expected 403 on invalid CSRF"
    except urllib.error.HTTPError as he:
        print(f"  [CSRF Invalid] HTTP Code: {he.code}")
        assert he.code == 403, f"Expected 403, got {he.code}"
    print("  ✓ PASS: Invalid CSRF returned HTTP 403.")

    # 1b. Missing fields -> 400
    # Get valid CSRF from homepage
    home_req = urllib.request.Request(f"{BASE_URL}/", headers={"User-Agent": "Mozilla/5.0"})
    with urllib.request.urlopen(home_req) as r:
        cookie = r.headers.get("Set-Cookie", "")
        body = r.read().decode('utf-8')
        m = re.search(r'name=["\']csrf_token["\']\s+value=["\']([^"\']+)["\']', body)
        valid_csrf = m.group(1) if m else ""

    req_val = urllib.request.Request(
        f"{BASE_URL}/submit-callback.php",
        data=urllib.parse.urlencode({"csrf_token": valid_csrf, "parent_name": ""}).encode('utf-8'),
        headers={"User-Agent": "Mozilla/5.0", "Cookie": cookie, "X-Requested-With": "XMLHttpRequest"}
    )
    try:
        urllib.request.urlopen(req_val)
        assert False, "Expected 400 on missing validation"
    except urllib.error.HTTPError as he:
        print(f"  [Validation Missing Fields] HTTP Code: {he.code}")
        assert he.code == 400, f"Expected 400, got {he.code}"
    print("  ✓ PASS: Missing fields returned HTTP 400.")

    # 1c. DB Failure -> 500 error
    db_test_cb = """
    register_shutdown_function(function() {
        echo 'HTTP_CODE=' . http_response_code();
    });
    $_SERVER['REQUEST_METHOD'] = 'POST';
    require_once 'includes/db.php';
    require_once 'includes/helper.php';
    safe_session_start();
    $_SESSION['csrf_token'] = 'token123';
    $_POST = [
        'csrf_token' => 'token123',
        'parent_name' => 'Fail Test',
        'email' => 'fail_db@example.com',
        'phone' => '9999999999',
        'grade' => 'Grade 1'
    ];
    $GLOBALS['db'] = null; // simulate disconnected / failed DB
    include 'submit-callback.php';
    """
    out, _ = run_php(db_test_cb)
    print(f"  [DB Failure] Response: {out.strip()}")
    assert "HTTP_CODE=500" in out, "Expected HTTP_CODE=500 on DB failure"
    assert '"status":"error"' in out, "Expected status error on DB failure"
    print("  ✓ PASS: Database failure returned HTTP 500 error (zero false-positives).")

    # 2. homeschooling/submit-lead.php
    print("\n2. homeschooling/submit-lead.php:")
    # 2a. Invalid CSRF -> 403
    req_hs_csrf = urllib.request.Request(
        f"{BASE_URL}/homeschooling/submit-lead.php",
        data=urllib.parse.urlencode({"csrf_token": "invalid_csrf"}).encode('utf-8'),
        headers={"User-Agent": "Mozilla/5.0", "X-Requested-With": "XMLHttpRequest"}
    )
    try:
        urllib.request.urlopen(req_hs_csrf)
        assert False, "Expected 403 on invalid CSRF"
    except urllib.error.HTTPError as he:
        print(f"  [CSRF Invalid] HTTP Code: {he.code}")
        assert he.code == 403, f"Expected 403, got {he.code}"
    print("  ✓ PASS: Invalid CSRF returned HTTP 403.")

    # 2b. Missing fields -> 400
    req_hs_val = urllib.request.Request(
        f"{BASE_URL}/homeschooling/submit-lead.php",
        data=urllib.parse.urlencode({"csrf_token": valid_csrf, "parent_name": ""}).encode('utf-8'),
        headers={"User-Agent": "Mozilla/5.0", "Cookie": cookie, "X-Requested-With": "XMLHttpRequest"}
    )
    try:
        urllib.request.urlopen(req_hs_val)
        assert False, "Expected 400 on missing validation"
    except urllib.error.HTTPError as he:
        print(f"  [Validation Missing Fields] HTTP Code: {he.code}")
        assert he.code == 400, f"Expected 400, got {he.code}"
    print("  ✓ PASS: Missing fields returned HTTP 400.")

    # 2c. DB Failure -> 500 error
    db_test_hs = """
    register_shutdown_function(function() {
        echo 'HTTP_CODE=' . http_response_code();
    });
    $_SERVER['REQUEST_METHOD'] = 'POST';
    require_once 'includes/db.php';
    require_once 'includes/helper.php';
    safe_session_start();
    $_SESSION['csrf_token'] = 'token123';
    $_POST = [
        'csrf_token' => 'token123',
        'parent_name' => 'Fail Test',
        'email' => 'fail_hs@example.com',
        'phone' => '9999999999',
        'grade' => 'Grade 1',
        'is_ajax' => '1'
    ];
    $GLOBALS['db'] = null;
    include 'homeschooling/submit-lead.php';
    """
    out, _ = run_php(db_test_hs)
    print(f"  [DB Failure] Response: {out.strip()}")
    assert "HTTP_CODE=500" in out, "Expected HTTP_CODE=500 on DB failure"
    assert '"status":"error"' in out, "Expected status error on DB failure"
    print("  ✓ PASS: Database failure returned HTTP 500 error (zero false-positives).")

    # 3. pages/nursery-to-grade-12.php
    print("\n3. pages/nursery-to-grade-12.php:")
    db_test_n12 = """
    register_shutdown_function(function() {
        echo 'HTTP_CODE=' . http_response_code();
    });
    $_SERVER['REQUEST_METHOD'] = 'POST';
    require_once 'includes/db.php';
    require_once 'includes/helper.php';
    safe_session_start();
    $_SESSION['csrf_token'] = 'token123';
    $_POST = [
        'submit_n12_lead' => '1',
        'csrf_token' => 'token123',
        'parent_name' => 'Fail Test',
        'student_name' => 'Junior Fail',
        'email' => 'fail_n12@example.com',
        'phone' => '9999999999',
        'grade' => 'Grade 5',
        'is_ajax' => '1'
    ];
    $GLOBALS['db'] = null;
    include 'pages/nursery-to-grade-12.php';
    """
    out, _ = run_php(db_test_n12)
    print(f"  [DB Failure] Response: {out.strip()}")
    assert "HTTP_CODE=500" in out, "Expected HTTP_CODE=500 on DB failure"
    assert '"status":"error"' in out, "Expected status error on DB failure"
    print("  ✓ PASS: Database failure returned HTTP 500 error (zero false-positives).")

    # 4. pages/online-school.php
    print("\n4. pages/online-school.php:")
    db_test_os = """
    register_shutdown_function(function() {
        echo 'HTTP_CODE=' . http_response_code();
    });
    $_SERVER['REQUEST_METHOD'] = 'POST';
    require_once 'includes/db.php';
    require_once 'includes/helper.php';
    safe_session_start();
    $_SESSION['csrf_token'] = 'token123';
    $_POST = [
        'submit_landing_lead' => '1',
        'csrf_token' => 'token123',
        'parent_name' => 'Fail Test',
        'email' => 'fail_os@example.com',
        'phone' => '9999999999',
        'grade' => 'Grade 8',
        'is_ajax' => '1'
    ];
    $GLOBALS['db'] = null;
    include 'pages/online-school.php';
    """
    out, _ = run_php(db_test_os)
    print(f"  [DB Failure] Response: {out.strip()}")
    assert "HTTP_CODE=500" in out, "Expected HTTP_CODE=500 on DB failure"
    assert '"status":"error"' in out, "Expected status error on DB failure"
    print("  ✓ PASS: Database failure returned HTTP 500 error (zero false-positives).")

    # 5. pages/home.php
    print("\n5. pages/home.php:")
    db_test_home = """
    $_SERVER['REQUEST_METHOD'] = 'POST';
    require_once 'includes/db.php';
    require_once 'includes/helper.php';
    safe_session_start();
    $_SESSION['csrf_token'] = 'token123';
    $_POST = [
        'submit_enquiry' => '1',
        'csrf_token' => 'token123',
        'parent_name' => 'Fail Test',
        'email' => 'fail_home@example.com',
        'phone' => '9999999999',
        'grade' => 'Grade 10'
    ];
    $GLOBALS['db'] = null;
    ob_start();
    include 'pages/home.php';
    ob_end_clean();
    echo 'STATUS=' . $form_status . ' | MSG=' . $error_message;
    """
    out, _ = run_php(db_test_home)
    print(f"  [DB Failure] Result: {out.strip()}")
    assert "STATUS=error" in out, "Expected STATUS=error on DB failure"
    print("  ✓ PASS: Database failure cleanly triggers error state on homepage.")

    # 6. pages/contact.php
    print("\n6. pages/contact.php:")
    db_test_contact = """
    $_SERVER['REQUEST_METHOD'] = 'POST';
    require_once 'includes/db.php';
    require_once 'includes/helper.php';
    safe_session_start();
    $_SESSION['csrf_token'] = 'token123';
    $_POST = [
        'submit_enquiry' => '1',
        'csrf_token' => 'token123',
        'parent_name' => 'Fail Test',
        'email' => 'fail_contact@example.com',
        'phone' => '9999999999',
        'grade' => 'Grade 2'
    ];
    $GLOBALS['db'] = null;
    ob_start();
    include 'pages/contact.php';
    ob_end_clean();
    echo 'STATUS=' . $form_status . ' | MSG=' . $error_message;
    """
    out, _ = run_php(db_test_contact)
    print(f"  [DB Failure] Result: {out.strip()}")
    assert "STATUS=error" in out, "Expected STATUS=error on DB failure"
    print("  ✓ PASS: Database failure cleanly triggers error state on contact page.")

    print("\n" + "=" * 70)
    print("ALL 6 FORM HANDLERS ERROR HANDLING VERIFIED: 100% PASS")
    print("=" * 70)

if __name__ == "__main__":
    main()
