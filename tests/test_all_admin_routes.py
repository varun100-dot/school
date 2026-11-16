#!/usr/bin/env python3
"""
Test all admin routes, static assets, and authentication flows
"""
import subprocess
import time
import urllib.request
import http.cookiejar
import re

PORT = 8889
SERVER_URL = f"http://127.0.0.1:{PORT}"

proc = subprocess.Popen(['php', '-S', f'127.0.0.1:{PORT}', 'index.php'], stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL)
time.sleep(1)

routes = [
    ('login', '/admin/login', 200),
    ('dashboard', '/admin/', 302), # Should redirect to /admin/login when not authenticated
    ('blogs', '/admin/blogs', 302),
    ('hero', '/admin/hero', 302),
    ('seo', '/admin/seo', 302),
    ('navigation', '/admin/navigation', 302),
    ('announcements', '/admin/announcements', 302),
    ('migrate', '/admin/migrate', 302),
    ('enquiries', '/admin/enquiries', 302),
    ('settings', '/admin/settings', 302),
    ('users', '/admin/users', 302),
    ('media', '/admin/media', 302),
    ('homepage', '/admin/homepage', 302),
    ('faqs', '/admin/faqs', 302),
    ('testimonials', '/admin/testimonials', 302),
    ('accreditations', '/admin/accreditations', 302),
    ('about-cms', '/admin/about-cms', 302),
    ('academics-cms', '/admin/academics-cms', 302),
    ('admissions-cms', '/admin/admissions-cms', 302),
    ('beyond-cms', '/admin/beyond-cms', 302),
    ('contact-cms', '/admin/contact-cms', 302),
    ('profiles', '/admin/profiles', 302),
    ('header-settings', '/admin/header-settings', 302),
    ('sliding-strip', '/admin/sliding-strip', 302)
]

print("=== 1. TESTING UNAUTHENTICATED ADMIN ACCESS (SECURITY CHECK) ===")
class NoRedirect(urllib.request.HTTPRedirectHandler):
    def redirect_request(self, req, fp, code, msg, headers, newurl):
        return None

opener = urllib.request.build_opener(NoRedirect)

all_passed = True
for name, path, expected_code in routes:
    url = SERVER_URL + path
    try:
        resp = opener.open(url)
        code = resp.status
    except urllib.error.HTTPError as e:
        code = e.code
    
    if code == expected_code:
        print(f"  [PASS] {path:<25} -> HTTP {code}")
    else:
        print(f"  [FAIL] {path:<25} -> HTTP {code} (expected {expected_code})")
        all_passed = False

print("\n=== 2. TESTING STATIC ASSETS FOR ADMIN ===")
assets = [
    ('/css/main.css', 'text/css'),
    ('/css/variables.css', 'text/css'),
    ('/css/rich-editor.css', 'text/css'),
    ('/assets/images/logo.png', 'image/png'),
    ('/assets/images/logo.webp', 'image/webp')
]

for path, expected_mime in assets:
    url = SERVER_URL + path
    try:
        resp = urllib.request.urlopen(url)
        ct = resp.headers.get('Content-Type', '')
        size = len(resp.read())
        if resp.status == 200 and expected_mime in ct and size > 0:
            print(f"  [PASS] {path:<30} -> HTTP 200 | {ct} | {size} bytes")
        else:
            print(f"  [FAIL] {path:<30} -> HTTP {resp.status} | {ct} | {size} bytes")
            all_passed = False
    except Exception as e:
        print(f"  [FAIL] {path:<30} -> Error: {e}")
        all_passed = False

proc.terminate()
if all_passed:
    print("\n[SUCCESS] All unauthenticated checks & static assets passed.")
else:
    print("\n[WARNING] Some checks failed.")
