#!/usr/bin/env python3
"""
Verify Meta Pixel (ID: 1671385628104457) across all public routes and verify exclusion from /admin/* routes.
"""
import subprocess
import time
import urllib.request
import urllib.parse
import http.cookiejar
import re
import sys

PORT = 8893
BASE = f"http://127.0.0.1:{PORT}"
PIXEL_ID = "1671385628104457"

proc = subprocess.Popen(
    ['php', '-S', f'127.0.0.1:{PORT}', 'index.php'],
    stdout=subprocess.DEVNULL,
    stderr=subprocess.DEVNULL,
)
time.sleep(1)

all_passed = True

def check_public_route(label, path):
    global all_passed
    url = BASE + path
    try:
        resp = urllib.request.urlopen(url)
        html = resp.read().decode('utf-8', errors='ignore')
        status = resp.status
    except Exception as e:
        print(f"  [FAIL] {label:<28} ({path:<24}) -> Error: {e}")
        all_passed = False
        return ""

    head_match = re.search(r'<head[^>]*>(.*?)</head>', html, re.S | re.I)
    body_match = re.search(r'<body[^>]*>(.*?)</body>', html, re.S | re.I)
    head_html = head_match.group(1) if head_match else ""
    body_html = body_match.group(1) if body_match else ""

    init_count_total = len(re.findall(r"fbq\('init',\s*'" + PIXEL_ID + r"'\)", html))
    init_count_head = len(re.findall(r"fbq\('init',\s*'" + PIXEL_ID + r"'\)", head_html))
    pageview_count = len(re.findall(r"fbq\('track',\s*'PageView'\)", html))
    fbevents_count = html.count("https://connect.facebook.net/en_US/fbevents.js")
    noscript_pattern = r'<noscript><img height="1" width="1" style="display:none"\s+src="https://www\.facebook\.com/tr\?id=' + PIXEL_ID + r'&ev=PageView&noscript=1"\s*/></noscript>'
    noscript_total = len(re.findall(noscript_pattern, html))
    noscript_in_body_start = bool(re.match(r'^\s*(?:<!--.*?-->\s*)*' + noscript_pattern, body_html, re.S))
    has_attribution = "attribution.js" in html
    has_php_error = ("Fatal error" in html) or ("Parse error" in html)

    ok = (
        status == 200
        and init_count_total == 1
        and init_count_head == 1
        and pageview_count == 1
        and fbevents_count == 1
        and noscript_total == 1
        and noscript_in_body_start
        and has_attribution
        and not has_php_error
    )

    if ok:
        print(f"  [PASS] {label:<28} ({path:<24}) -> HTTP 200 | Head JS: 1 | Body Noscript: 1 (at top) | attribution.js: OK")
    else:
        print(
            f"  [FAIL] {label:<28} ({path:<24}) -> HTTP {status} | init_total={init_count_total} "
            f"init_head={init_count_head} pv={pageview_count} fbevents={fbevents_count} "
            f"noscript={noscript_total} body_top={noscript_in_body_start} attr={has_attribution} err={has_php_error}"
        )
        all_passed = False
    return html

try:
    print("=== 1. PUBLIC PAGES META PIXEL VERIFICATION ===")
    public_routes = [
        ("Homepage", "/"),
        ("About", "/about"),
        ("About Zuvio", "/about-zuvio"),
        ("Academics", "/academics"),
        ("Curriculum", "/curriculum"),
        ("Admissions", "/admissions"),
        ("Admissions Enrol Now", "/admissions/enrol-now"),
        ("Zuvio Beyond", "/beyond"),
        ("Blogs Listing", "/blogs"),
        ("Contact", "/contact"),
        ("Online School LP", "/online-school/"),
        ("Nursery to Grade 12 LP", "/nursery-to-grade-12/"),
        ("Homeschooling LP", "/homeschooling/"),
    ]

    blogs_html = ""
    for label, path in public_routes:
        html = check_public_route(label, path)
        if path == "/blogs":
            blogs_html = html

    # Discover a real blog detail route from /blogs
    blog_link_match = re.search(r'href="(/blogs/[^"]+)"', blogs_html)
    if blog_link_match:
        check_public_route("Blog Detail", blog_link_match.group(1))
    else:
        check_public_route("Blog Detail (fallback)", "/blogs/how-online-schooling-supports-personalized-learning")

    print("\n=== 2. ADMIN PAGES EXCLUSION VERIFICATION ===")
    cj = http.cookiejar.CookieJar()
    opener = urllib.request.build_opener(urllib.request.HTTPCookieProcessor(cj))

    login_html = opener.open(f"{BASE}/admin/login").read().decode('utf-8', errors='ignore')
    if PIXEL_ID in login_html or "fbevents.js" in login_html or "facebook.com/tr" in login_html:
        print("  [FAIL] /admin/login contains Meta Pixel!")
        all_passed = False
    else:
        print("  [PASS] /admin/login              -> HTTP 200 | Meta Pixel ABSENT")

    csrf_match = re.search(r'name="csrf_token"\s+value="([^"]+)"', login_html)
    csrf_token = csrf_match.group(1) if csrf_match else ""
    login_data = urllib.parse.urlencode({
        'login': '1',
        'csrf_token': csrf_token,
        'username': 'admin',
        'password': 'admin123',
    }).encode('utf-8')
    opener.open(urllib.request.Request(f"{BASE}/admin/login", data=login_data))

    for admin_path in ["/admin/", "/admin/enquiries", "/admin/settings", "/admin/blogs"]:
        r = opener.open(f"{BASE}{admin_path}")
        body = r.read().decode('utf-8', errors='ignore')
        # Note: admin/settings has a form input named facebook_pixel_id, so check for actual pixel execution/scripts
        has_pixel = (PIXEL_ID in body) or ("fbevents.js" in body) or ("facebook.com/tr" in body) or ("fbq(" in body)
        if r.status == 200 and not has_pixel:
            print(f"  [PASS] {admin_path:<25} -> HTTP 200 | Meta Pixel ABSENT")
        else:
            print(f"  [FAIL] {admin_path:<25} -> HTTP {r.status} | has_pixel={has_pixel}")
            all_passed = False

finally:
    proc.terminate()

if all_passed:
    print("\n[SUCCESS] All Meta Pixel checks passed 100%!")
    sys.exit(0)
else:
    print("\n[FAILURE] One or more Meta Pixel checks failed.")
    sys.exit(1)
