/**
 * Real Google Chrome CDP Automated Multi-Touch Attribution Journey & Separation Test
 */
const http = require('http');
const { spawn, exec } = require('child_process');
const fs = require('fs');

const CDP_PORT = 9444;
const BASE_URL = 'http://127.0.0.1:8899';
const CHROME_PATH = '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome';
const PROFILE_DIR = '/tmp/cdp_journey_profile_' + Date.now();

function sendCDPCommand(ws, method, params = {}) {
  return new Promise((resolve, reject) => {
    const id = Math.floor(Math.random() * 1000000);
    const msg = JSON.stringify({ id, method, params });
    
    function onMessage(event) {
      const data = JSON.parse(event.data);
      if (data.id === id) {
        ws.removeEventListener('message', onMessage);
        if (data.error) {
          reject(new Error(JSON.stringify(data.error)));
        } else {
          resolve(data.result);
        }
      }
    }
    
    ws.addEventListener('message', onMessage);
    ws.send(msg);
  });
}

function putJson(url) {
  return new Promise((resolve, reject) => {
    const req = http.request(url, { method: 'PUT' }, (res) => {
      let data = '';
      res.on('data', chunk => data += chunk);
      res.on('end', () => {
        try { resolve(JSON.parse(data)); } catch (e) { resolve(data); }
      });
    });
    req.on('error', reject);
    req.end();
  });
}

function queryDB(sql) {
  return new Promise((resolve, reject) => {
    exec(`sqlite3 database/local.sqlite "${sql}"`, (err, stdout, stderr) => {
      if (err) reject(err);
      else resolve(stdout.trim());
    });
  });
}

function waitForCDP(port, retries = 25) {
  return new Promise((resolve, reject) => {
    function attempt(n) {
      if (n <= 0) return reject(new Error('Chrome CDP failed to start in time'));
      http.get(`http://127.0.0.1:${port}/json/version`, (res) => {
        if (res.statusCode === 200) resolve();
        else setTimeout(() => attempt(n - 1), 300);
      }).on('error', () => {
        setTimeout(() => attempt(n - 1), 300);
      });
    }
    attempt(retries);
  });
}

async function run() {
  console.log('='.repeat(70));
  console.log('STARTING REAL GOOGLE CHROME MULTI-TOUCH JOURNEY AUDIT');
  console.log('='.repeat(70));

  fs.mkdirSync(PROFILE_DIR, { recursive: true });

  console.log('Launching isolated Google Chrome with CDP on port ' + CDP_PORT + '...');
  const chromeProc = spawn(CHROME_PATH, [
    '--headless=new',
    '--disable-gpu',
    `--remote-debugging-port=${CDP_PORT}`,
    `--user-data-dir=${PROFILE_DIR}`
  ], { stdio: 'ignore' });

  try {
    await waitForCDP(CDP_PORT);
    console.log('Chrome CDP is live and ready.');

    // 1. Create a fresh target/tab in Chrome
    const target = await putJson(`http://127.0.0.1:${CDP_PORT}/json/new?${encodeURIComponent(BASE_URL + '/online-school/?utm_source=google&utm_medium=cpc&utm_campaign=google_search_pmax&gclid=GCLID_REAL_TEST_1')}`);
    const ws = new WebSocket(target.webSocketDebuggerUrl);

    await new Promise((resolve, reject) => {
      ws.addEventListener('open', resolve);
      ws.addEventListener('error', reject);
    });

    await sendCDPCommand(ws, 'Page.enable');
    await sendCDPCommand(ws, 'Runtime.enable');
    await new Promise(r => setTimeout(r, 1500));

    console.log('\n--- Step A: Visitor opens Online School via Google Ads (gclid) ---');
    let evalResult = await sendCDPCommand(ws, 'Runtime.evaluate', {
      expression: `JSON.stringify({
        first: JSON.parse(localStorage.getItem('zuvio_first_touch') || '{}'),
        last: JSON.parse(localStorage.getItem('zuvio_last_touch') || '{}')
      })`,
      returnByValue: true
    });
    let touches = JSON.parse(evalResult.result.value);
    console.log('Initial First-Touch Source:', touches.first.marketing_source);
    console.log('Initial GCLID:', touches.first.gclid);
    if (touches.first.marketing_source !== 'Google Ads' || touches.first.gclid !== 'GCLID_REAL_TEST_1') {
      throw new Error('Step A Failed: Expected Google Ads with GCLID_REAL_TEST_1');
    }
    console.log('✓ Step A PASS: Google Ads touchpoint recorded in browser storage.');

    console.log('\n--- Step B: Visitor navigates internally to Homeschooling ---');
    await sendCDPCommand(ws, 'Page.navigate', { url: `${BASE_URL}/homeschooling/` });
    await new Promise(r => setTimeout(r, 1500));

    evalResult = await sendCDPCommand(ws, 'Runtime.evaluate', {
      expression: `JSON.stringify({
        first: JSON.parse(localStorage.getItem('zuvio_first_touch') || '{}'),
        last: JSON.parse(localStorage.getItem('zuvio_last_touch') || '{}')
      })`,
      returnByValue: true
    });
    touches = JSON.parse(evalResult.result.value);
    console.log('After internal navigation First-Touch:', touches.first.marketing_source);
    console.log('After internal navigation Last-Touch:', touches.last.marketing_source);
    if (touches.first.marketing_source !== 'Google Ads' || touches.last.marketing_source !== 'Google Ads') {
      throw new Error('Step B Failed: Attribution lost during internal navigation');
    }
    console.log('✓ Step B PASS: Attribution preserved across internal navigation.');

    console.log('\n--- Step C: Visitor opens Nursery to Grade 12 via Instagram Ads campaign URL ---');
    const instaUrl = `${BASE_URL}/nursery-to-grade-12/?utm_source=instagram&utm_medium=paid-social&utm_campaign=insta_awareness&fbclid=IG_CLID_REAL_TEST_2`;
    await sendCDPCommand(ws, 'Page.navigate', { url: instaUrl });
    await new Promise(r => setTimeout(r, 1500));

    evalResult = await sendCDPCommand(ws, 'Runtime.evaluate', {
      expression: `JSON.stringify({
        first: JSON.parse(localStorage.getItem('zuvio_first_touch') || '{}'),
        last: JSON.parse(localStorage.getItem('zuvio_last_touch') || '{}')
      })`,
      returnByValue: true
    });
    touches = JSON.parse(evalResult.result.value);
    console.log('First-Touch Source (Should stay Google Ads):', touches.first.marketing_source);
    console.log('Last-Touch Source (Should transition to Instagram Ads):', touches.last.marketing_source);
    console.log('Last-Touch FBCLID:', touches.last.fbclid);

    if (touches.first.marketing_source !== 'Google Ads') {
      throw new Error('Step C Failed: First-touch was incorrectly overwritten!');
    }
    if (touches.last.marketing_source !== 'Instagram Ads') {
      throw new Error('Step C Failed: Last-touch did not update to Instagram Ads!');
    }
    if (touches.last.fbclid !== 'IG_CLID_REAL_TEST_2') {
      throw new Error('Step C Failed: Click ID not captured in last-touch!');
    }
    console.log('✓ Step C PASS: First-Touch preserved as Google Ads, Last-Touch updated to Instagram Ads.');

    console.log('\n--- Step D: Visitor submits form on Nursery to Grade 12 ---');
    evalResult = await sendCDPCommand(ws, 'Runtime.evaluate', {
      expression: `(function() {
        const form = document.getElementById('n12LeadForm');
        form.querySelector('#parent_name').value = 'Multi Touch Parent';
        form.querySelector('#student_name').value = 'Multi Touch Learner';
        form.querySelector('#email').value = 'multitouch_journey@example.com';
        form.querySelector('#phone').value = '+91 9123456789';
        form.querySelector('#grade').value = 'Grade 10';
        document.getElementById('n12SubmitBtn').click();
        return true;
      })()`,
      returnByValue: true
    });

    await new Promise(r => setTimeout(r, 2500));

    // Query Database to verify exact persisted multi-touch attribution
    const dbRow = await queryDB("SELECT id, source, marketing_source, gclid, fbclid, first_touch, last_touch FROM enquiries WHERE email = 'multitouch_journey@example.com' ORDER BY id DESC LIMIT 1;");
    console.log('Database Row:', dbRow);
    const parts = dbRow.split('|');
    const [id, source, marketing_source, gclid, fbclid, first_touch_raw, last_touch_raw] = parts;
    
    const ftJson = JSON.parse(first_touch_raw);
    const ltJson = JSON.parse(last_touch_raw);

    console.log('Persisted Source:', source);
    console.log('Persisted Active Marketing Channel:', marketing_source);
    console.log('First Touch Source:', ftJson.marketing_source);
    console.log('First Touch GCLID:', ftJson.gclid);
    console.log('Last Touch Source:', ltJson.marketing_source);
    console.log('Last Touch FBCLID:', ltJson.fbclid);

    if (source !== 'Nursery to Grade 12 Landing Page') {
      throw new Error(`Expected source 'Nursery to Grade 12 Landing Page', got '${source}'`);
    }
    if (marketing_source !== 'Instagram Ads') {
      throw new Error(`Expected marketing_source 'Instagram Ads', got '${marketing_source}'`);
    }
    if (ftJson.marketing_source !== 'Google Ads' || ftJson.gclid !== 'GCLID_REAL_TEST_1') {
      throw new Error('First Touch JSON does not match Google Ads GCLID_REAL_TEST_1');
    }
    if (ltJson.marketing_source !== 'Instagram Ads' || ltJson.fbclid !== 'IG_CLID_REAL_TEST_2') {
      throw new Error('Last Touch JSON does not match Instagram Ads IG_CLID_REAL_TEST_2');
    }
    console.log('✓ Step D PASS: Real browser multi-touch journey verified in database with 100% precision.');

    // Close tab
    await sendCDPCommand(ws, 'Page.close');
    ws.close();

    console.log('\n' + '='.repeat(70));
    console.log('REAL BROWSER MULTI-TOUCH JOURNEY AUDIT: 100% PASS');
    console.log('='.repeat(70));
  } finally {
    chromeProc.kill();
  }
}

run().catch(err => {
  console.error('FATAL TEST ERROR:', err);
  process.exit(1);
});
