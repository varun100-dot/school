#!/usr/bin/env python3
"""
Verify strict separation between Instagram and Facebook traffic across paid and organic channels.
"""
import subprocess
import json

php_test = """
require_once 'includes/helper.php';

$matrix = [
    // 1. Instagram Paid variants
    ['instagram', 'cpc', '', '', '', 'Instagram Ads'],
    ['instagram', 'paid-social', '', '', '', 'Instagram Ads'],
    ['ig', 'cpc', '', '', '', 'Instagram Ads'],
    ['ig', 'story', '', '', '', 'Instagram Ads'],
    ['instagram', '', '', 'test_fbc_ig', '', 'Instagram Ads'],
    ['ig', '', '', 'test_fbc_ig2', '', 'Instagram Ads'],

    // 2. Facebook Paid variants
    ['facebook', 'cpc', '', '', '', 'Facebook Ads'],
    ['facebook', 'paid-social', '', '', '', 'Facebook Ads'],
    ['fb', 'cpc', '', '', '', 'Facebook Ads'],
    ['fb', 'sponsored', '', '', '', 'Facebook Ads'],
    ['facebook', '', '', 'test_fbc_fb', '', 'Facebook Ads'],
    ['fb', '', '', 'test_fbc_fb2', '', 'Facebook Ads'],
    ['', '', '', 'test_fbc_general', '', 'Facebook Ads'], // general fbclid defaults to Facebook Ads unless instagram indicated

    // 3. Organic Social / Unpaid Traffic
    ['instagram', 'referral', '', '', '', 'Referral'],
    ['facebook', 'referral', '', '', '', 'Referral'],
    ['instagram', 'bio', '', '', '', 'Other / Unknown'],
    ['facebook', 'group', '', '', '', 'Other / Unknown'],
    ['', '', '', '', 'https://www.instagram.com/', 'Referral'],
    ['', '', '', '', 'https://www.facebook.com/', 'Referral']
];

$results = [];
foreach ($matrix as $idx => $row) {
    list($src, $med, $gcl, $fbc, $ref, $expected) = $row;
    $got = classify_marketing_source($src, $med, $gcl, $fbc, $ref);
    $pass = ($got === $expected);
    $results[] = [
        'test_id' => $idx + 1,
        'input' => ['utm_source' => $src, 'utm_medium' => $med, 'fbclid' => $fbc, 'referrer' => $ref],
        'expected' => $expected,
        'got' => $got,
        'pass' => $pass
    ];
}

echo json_encode($results, JSON_PRETTY_PRINT);
"""

p = subprocess.run(["php", "-r", php_test], capture_output=True, text=True)
results = json.loads(p.stdout)
print("=" * 70)
print("VERIFYING INSTAGRAM AND FACEBOOK SEPARATION MATRIX")
print("=" * 70)

all_passed = True
for r in results:
    status = "✓ PASS" if r['pass'] else "✗ FAIL"
    if not r['pass']:
        all_passed = False
    print(f"[{status}] Test {r['test_id']}: src='{r['input']['utm_source']}', med='{r['input']['utm_medium']}', fbc='{r['input']['fbclid']}' -> Got: '{r['got']}' (Expected: '{r['expected']}')")

assert all_passed, "Some classification tests failed!"
print("\n" + "=" * 70)
print("ALL 19 INSTAGRAM VS FACEBOOK SEPARATION TESTS: 100% PASS")
print("=" * 70)

