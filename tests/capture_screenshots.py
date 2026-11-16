#!/usr/bin/env python3
import subprocess
import os

CHROME = "/Applications/Google Chrome.app/Contents/MacOS/Google Chrome"
OUT_DIR = "/Users/varunkairalimac/.gemini/antigravity/brain/9a536fd7-79c3-4d73-a359-672adabd0cbe/verification_screenshots"
os.makedirs(OUT_DIR, exist_ok=True)

targets = [
    # 1. Online School Landing Page
    ("online_school_desktop.png", "http://127.0.0.1:8899/online-school/", "1440,900"),
    ("online_school_mobile.png", "http://127.0.0.1:8899/online-school/", "390,844"),
    
    # 2. Nursery to Grade 12 Landing Page
    ("nursery_grade12_desktop.png", "http://127.0.0.1:8899/nursery-to-grade-12/", "1440,900"),
    ("nursery_grade12_mobile.png", "http://127.0.0.1:8899/nursery-to-grade-12/", "390,844"),
    
    # 3. Homeschooling Landing Page
    ("homeschooling_desktop.png", "http://127.0.0.1:8899/homeschooling/", "1440,900"),
    ("homeschooling_mobile.png", "http://127.0.0.1:8899/homeschooling/", "390,844"),
    
    # 4. Admin CRM List View (with 6 cards & new columns)
    ("admin_enquiries_desktop.png", "http://127.0.0.1:8899/tests/render_admin.php", "1440,1100"),
    
    # 5. Admin CRM View Details (with Marketing Attribution Panel)
    ("admin_enquiries_detail_desktop.png", "http://127.0.0.1:8899/tests/render_admin.php?action=view&id=15", "1440,1100"),
]

for filename, url, size in targets:
    out_path = os.path.join(OUT_DIR, filename)
    cmd = [
        CHROME,
        "--headless=new",
        "--disable-gpu",
        f"--window-size={size}",
        f"--screenshot={out_path}",
        url
    ]
    print(f"Capturing {filename} ({size}) from {url}...")
    res = subprocess.run(cmd, capture_output=True, text=True)
    if os.path.exists(out_path):
        size_kb = os.path.getsize(out_path) // 1024
        print(f"✓ Saved {filename} ({size_kb} KB)")
    else:
        print(f"✗ Failed to capture {filename}: {res.stderr}")

