/**
 * Real Headless Google Chrome CDP Network & Runtime Verification for Meta Pixel (1671385628104457)
 */
const http = require('http');
const { spawn } = require('child_process');
const fs = require('fs');

const CDP_PORT = 9446;
const BASE_URL = 'http://127.0.0.1:8895';
const PIXEL_ID = '1671385628104457';
const CHROME_PATH = '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome';
const PROFILE_DIR = '/tmp/cdp_pixel_profile_' + Date.now();

function sendCDP(ws, method, params = {}) {
  return new Promise((resolve, reject) => {
    const id = Math.floor(Math.random() * 10000000);
    function onMsg(event) {
      const data = JSON.parse(event.data);
      if (data.id === id) {
        ws.removeEventListener('message', onMsg);
        if (data.error) reject(new Error(JSON.stringify(data.error)));
        else resolve(data.result);
      }
    }
    ws.addEventListener('message', onMsg);
    ws.send(JSON.stringify({ id, method, params }));
  });
}

function getJson(url) {
  return new Promise((resolve, reject) => {
    http.get(url, (res) => {
      let data = '';
      res.on('data', (c) => (data += c));
      res.on('end', () => {
        try { resolve(JSON.parse(data)); } catch (e) { reject(e); }
      });
    }).on('error', reject);
  });
}

async function sleep(ms) {
  return new Promise((r) => setTimeout(r, ms));
}

async function run() {
  const chrome = spawn(CHROME_PATH, [
    '--headless=new',
    `--remote-debugging-port=${CDP_PORT}`,
    `--user-data-dir=${PROFILE_DIR}`,
    '--no-first-run',
    '--no-default-browser-check',
    'about:blank',
  ], { stdio: 'ignore' });

  let targets = null;
  for (let i = 0; i < 30; i++) {
    try {
      targets = await getJson(`http://127.0.0.1:${CDP_PORT}/json/list`);
      if (targets && targets.length > 0) break;
    } catch (_) {}
    await sleep(300);
  }

  if (!targets || targets.length === 0) {
    chrome.kill();
    throw new Error('Failed to connect to headless Chrome CDP');
  }

  const pageTarget = targets.find((t) => t.type === 'page') || targets[0];
  const ws = new WebSocket(pageTarget.webSocketDebuggerUrl);
  await new Promise((res, rej) => {
    ws.addEventListener('open', res);
    ws.addEventListener('error', rej);
  });

  await sendCDP(ws, 'Page.enable');
  await sendCDP(ws, 'Network.enable');
  await sendCDP(ws, 'Runtime.enable');

  let currentRequests = [];
  ws.addEventListener('message', (event) => {
    const data = JSON.parse(event.data);
    if (data.method === 'Network.requestWillBeSent') {
      currentRequests.push(data.params.request.url);
    }
  });

  const testCases = [
    {
      label: 'Homepage + UTM/FBCLID',
      url: `${BASE_URL}/?utm_source=facebook&utm_medium=cpc&utm_campaign=pixel_verify&fbclid=TEST_FBCLID_999`,
      expectPixel: true,
      checkAttribution: true,
    },
    { label: 'About', url: `${BASE_URL}/about`, expectPixel: true },
    { label: 'Academics', url: `${BASE_URL}/academics`, expectPixel: true },
    { label: 'Admissions', url: `${BASE_URL}/admissions`, expectPixel: true },
    { label: 'Zuvio Beyond', url: `${BASE_URL}/beyond`, expectPixel: true },
    { label: 'Blogs', url: `${BASE_URL}/blogs`, expectPixel: true },
    { label: 'Contact', url: `${BASE_URL}/contact`, expectPixel: true },
    { label: 'Online School LP', url: `${BASE_URL}/online-school/`, expectPixel: true },
    { label: 'Nursery to Grade 12 LP', url: `${BASE_URL}/nursery-to-grade-12/`, expectPixel: true },
    { label: 'Homeschooling LP', url: `${BASE_URL}/homeschooling/`, expectPixel: true },
    { label: 'Admin Login (Excluded)', url: `${BASE_URL}/admin/login`, expectPixel: false },
  ];

  let allPassed = true;
  console.log('=== REAL CHROME CDP NETWORK & RUNTIME VERIFICATION ===');

  for (const tc of testCases) {
    currentRequests = [];
    await sendCDP(ws, 'Page.navigate', { url: tc.url });
    await sleep(2200);

    const fbeventsReqs = currentRequests.filter((u) => u.includes('connect.facebook.net/en_US/fbevents.js'));
    const configReqs = currentRequests.filter((u) => u.includes(`connect.facebook.net/signals/config/${PIXEL_ID}`));
    const trReqs = currentRequests.filter((u) => u.includes('facebook.com/tr'));
    const anyFbReqs = currentRequests.filter((u) => u.includes('connect.facebook.net') || u.includes('facebook.com/tr'));

    const evalRes = await sendCDP(ws, 'Runtime.evaluate', {
      expression: `JSON.stringify({
        hasFbq: typeof window.fbq === 'function',
        state: window.fbq && window.fbq.getState ? window.fbq.getState() : null,
        perfTr: performance.getEntriesByType('resource').map(e => e.name).filter(n => n.includes('facebook.com/tr')),
        attrStored: localStorage.getItem('zuvio_first_touch') || localStorage.getItem('zuvio_last_touch') || ''
      })`,
      returnByValue: true,
    });
    const runtime = JSON.parse(evalRes.result.value);

    if (tc.expectPixel) {
      const pixels = runtime.state && runtime.state.pixels ? runtime.state.pixels : [];
      const singlePixelOk = pixels.length === 1 && pixels[0].id === PIXEL_ID && pixels[0].eventCount === 1;
      const ok = fbeventsReqs.length === 1 && configReqs.length === 1 && runtime.hasFbq && singlePixelOk;

      let attrInfo = '';
      if (tc.checkAttribution) {
        const attrOk = runtime.attrStored.includes('TEST_FBCLID_999') && runtime.attrStored.includes('facebook');
        attrInfo = ` | UTM/FBCLID attr: ${attrOk ? 'OK' : 'FAIL'}`;
        if (!attrOk) allPassed = false;
      }

      if (ok) {
        console.log(`  [PASS] ${tc.label.padEnd(24)} -> fbevents.js: 1 | config(${PIXEL_ID}): 1 | fbq pixels: 1, eventCount: 1${attrInfo}`);
      } else {
        console.log(`  [FAIL] ${tc.label.padEnd(24)} -> fbevents.js: ${fbeventsReqs.length} | config: ${configReqs.length} | tr: ${trReqs.length} | pixels: ${JSON.stringify(pixels)}`);
        allPassed = false;
      }
    } else {
      const ok = anyFbReqs.length === 0 && !runtime.hasFbq;
      if (ok) {
        console.log(`  [PASS] ${tc.label.padEnd(24)} -> FB Network Requests: 0 | fbq: undefined (Excluded)`);
      } else {
        console.log(`  [FAIL] ${tc.label.padEnd(24)} -> FB Network Requests: ${anyFbReqs.length} | fbq: ${runtime.hasFbq}`);
        allPassed = false;
      }
    }
  }

  ws.close();
  chrome.kill();
  await sleep(500);
  try { fs.rmSync(PROFILE_DIR, { recursive: true, force: true }); } catch (_) {}

  if (allPassed) {
    console.log('\n[SUCCESS] All real Chrome CDP network, PageView, UTM attribution, and admin exclusion checks passed!');
    process.exit(0);
  } else {
    console.log('\n[FAILURE] One or more Chrome CDP checks failed.');
    process.exit(1);
  }
}

run().catch((err) => {
  console.error(err);
  process.exit(1);
});
