#!/usr/bin/env python3
"""
Comprehensive authenticated test for all 24 admin pages
"""
import subprocess
import time
import urllib.request
import urllib.parse
import http.cookiejar
import re

PORT = 8891
SERVER_URL = f"http://127.0.0.1:{PORT}"

proc = subprocess.Popen(['php', '-S', f'127.0.0.1:{PORT}', 'index.php'], stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL)
time.sleep(1)

cj = http.cookiejar.CookieJar()
opener = urllib.request.build_opener(urllib.request.HTTPCookieProcessor(cj))

try:
    # 1. Fetch login page to get CSRF token
    resp = opener.open(f"{SERVER_URL}/admin/login")
    html = resp.read().decode('utf-8')
    csrf_match = re.search(r'name="csrf_token"\s+value="([^"]+)"', html)
    csrf_token = csrf_match.group(1) if csrf_match else ''
    print(f"Obtained CSRF token: {csrf_token}")

    # 2. Submit login
    data = urllib.parse.urlencode({
        'login': '1',
        'csrf_token': csrf_token,
        'username': 'admin',
        'password': 'admin123'
    }).encode('utf-8')

    login_req = urllib.request.Request(f"{SERVER_URL}/admin/login", data=data)
    login_resp = opener.open(login_req)
    login_html = login_resp.read().decode('utf-8')
    print("Login response status:", login_resp.status)

    # 3. Test all admin pages authenticated
    admin_pages = [
        ('Dashboard', '/admin/'),
        ('Blogs', '/admin/blogs'),
        ('Hero CMS', '/admin/hero'),
        ('SEO Manager', '/admin/seo'),
        ('Navigation', '/admin/navigation'),
        ('Announcements', '/admin/announcements'),
        ('Migrate', '/admin/migrate'),
        ('Enquiries CRM', '/admin/enquiries'),
        ('Settings', '/admin/settings'),
        ('Users', '/admin/users'),
        ('Media Library', '/admin/media'),
        ('Homepage CMS', '/admin/homepage'),
        ('FAQs CMS', '/admin/faqs'),
        ('Testimonials CMS', '/admin/testimonials'),
        ('Accreditations CMS', '/admin/accreditations'),
        ('About CMS', '/admin/about-cms'),
        ('Academics CMS', '/admin/academics-cms'),
        ('Admissions CMS', '/admin/admissions-cms'),
        ('Beyond CMS', '/admin/beyond-cms'),
        ('Contact CMS', '/admin/contact-cms'),
        ('Profiles CMS', '/admin/profiles'),
        ('Header Settings', '/admin/header-settings'),
        ('Sliding Strip CMS', '/admin/sliding-strip')
    ]

    print("\n=== VERIFYING ALL 23 AUTHENTICATED ADMIN PAGES ===")
    failed = []
    for name, path in admin_pages:
        url = SERVER_URL + path
        try:
            r = opener.open(url)
            body = r.read().decode('utf-8', errors='ignore')
            has_sidebar = 'admin-sidebar' in body
            has_error = 'Fatal error' in body or 'Parse error' in body
            has_css = '/css/main.css' in body and '/css/variables.css' in body
            
            if r.status == 200 and has_sidebar and not has_error and has_css:
                print(f"  [PASS] {name:<22} ({path:<24}) -> HTTP 200, Sidebar OK, CSS OK")
            else:
                print(f"  [FAIL] {name:<22} ({path:<24}) -> HTTP {r.status}, Sidebar={has_sidebar}, Error={has_error}, CSS={has_css}")
                failed.append(path)
        except Exception as e:
            print(f"  [FAIL] {name:<22} ({path:<24}) -> Exception: {e}")
            failed.append(path)

    if not failed:
        print("\n[SUCCESS] All authenticated admin pages rendered flawlessly with sidebar, design tokens, and CSS!")
    else:
        print(f"\n[FAILURE] {len(failed)} pages failed.")

finally:
    proc.terminate()
