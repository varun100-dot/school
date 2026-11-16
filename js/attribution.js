/**
 * Zuvio Global School - Marketing Attribution Engine
 * Tracks UTM parameters, ad click IDs (gclid, fbclid), referrer, landing page,
 * and maintains 30-day first-touch & last-touch attribution models across sessions.
 * Automatically injects attribution inputs into all website forms.
 */
(function() {
  'use strict';

  var STORAGE_KEY_FIRST = 'zuvio_first_touch';
  var STORAGE_KEY_LAST  = 'zuvio_last_touch';
  var COOKIE_EXPIRY_DAYS = 30;

  // --- Cookie Helpers ---
  function setCookie(name, value, days) {
    try {
      var expires = '';
      if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = '; expires=' + date.toUTCString();
      }
      var secure = window.location.protocol === 'https:' ? '; Secure' : '';
      document.cookie = name + '=' + encodeURIComponent(value) + expires + '; path=/; SameSite=Lax' + secure;
    } catch (e) {
      // Cookie storage fallback blocked
    }
  }

  function getCookie(name) {
    try {
      var nameEQ = name + '=';
      var ca = document.cookie.split(';');
      for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) === ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0) return decodeURIComponent(c.substring(nameEQ.length, c.length));
      }
    } catch (e) {}
    return null;
  }

  // --- Storage Helpers (localStorage with Cookie fallback) ---
  function getStoredObject(key) {
    var raw = null;
    try {
      raw = localStorage.getItem(key);
    } catch (e) {}

    if (!raw) {
      raw = getCookie(key);
    }

    if (raw) {
      try {
        var parsed = JSON.parse(raw);
        if (parsed && typeof parsed === 'object') {
          return parsed;
        }
      } catch (e) {}
    }
    return null;
  }

  function setStoredObject(key, obj) {
    var str = JSON.stringify(obj);
    try {
      localStorage.setItem(key, str);
    } catch (e) {}
    setCookie(key, str, COOKIE_EXPIRY_DAYS);
  }

  // --- URL & Referrer Analysis ---
  function getUrlParams() {
    var params = {};
    try {
      var search = window.location.search.substring(1);
      if (search) {
        var pairs = search.split('&');
        for (var i = 0; i < pairs.length; i++) {
          var pair = pairs[i].split('=');
          if (pair[0]) {
            var k = decodeURIComponent(pair[0]).trim();
            var v = pair[1] ? decodeURIComponent(pair[1].replace(/\+/g, ' ')).trim() : '';
            params[k] = v;
          }
        }
      }
    } catch (e) {}
    return params;
  }

  function isExternalReferrer(ref) {
    if (!ref) return false;
    try {
      var refHost = new URL(ref).hostname.toLowerCase();
      var curHost = window.location.hostname.toLowerCase();
      if (refHost === curHost || refHost.indexOf(curHost) !== -1 || curHost.indexOf(refHost) !== -1) {
        return false;
      }
      return true;
    } catch (e) {
      return false;
    }
  }

  function classifySource(utm_source, utm_medium, gclid, fbclid, referrer) {
    var src = (utm_source || '').toLowerCase().trim();
    var med = (utm_medium || '').toLowerCase().trim();
    var gcl = (gclid || '').trim();
    var fbc = (fbclid || '').trim();
    var ref = (referrer || '').trim();

    if (gcl || (src.indexOf('google') !== -1 && /^(cpc|ppc|paid|ad|ads|search_ad|display|smart|gdn|search)$/i.test(med))) {
      return 'Google Ads';
    }

    if (fbc) {
      if (src.indexOf('instagram') !== -1 || src.indexOf('ig') !== -1) {
        return 'Instagram Ads';
      }
      return 'Facebook Ads';
    }

    if (src.indexOf('instagram') !== -1 || src.indexOf('ig') !== -1) {
      if (/^(cpc|ppc|paid|ad|ads|paidsocial|paid-social|sponsored|story|reel)$/i.test(med) || fbc) {
        return 'Instagram Ads';
      }
    }

    if (src.indexOf('facebook') !== -1 || src.indexOf('fb') !== -1 || src.indexOf('meta') !== -1) {
      if (/^(cpc|ppc|paid|ad|ads|paidsocial|paid-social|sponsored)$/i.test(med) || fbc) {
        return 'Facebook Ads';
      }
    }

    if (med === 'organic' || med === 'organic-search') {
      return 'Organic Search';
    }

    if (ref) {
      try {
        var host = new URL(ref).hostname.toLowerCase();
        var searchEngines = ['google.', 'bing.', 'yahoo.', 'duckduckgo.', 'baidu.', 'yandex.', 'ask.', 'ecosia.', 'brave.'];
        for (var i = 0; i < searchEngines.length; i++) {
          if (host.indexOf(searchEngines[i]) !== -1) {
            return 'Organic Search';
          }
        }
      } catch (e) {}
    }

    if (med === 'referral') {
      return 'Referral';
    }

    if (ref && isExternalReferrer(ref)) {
      return 'Referral';
    }

    if (!src && !med && !gcl && !fbc) {
      return 'Direct';
    }

    if (src || med) {
      return 'Other / Unknown';
    }

    return 'Direct';
  }

  // --- Build & Persist Current Touchpoint ---
  var urlParams = getUrlParams();
  var rawReferrer = document.referrer || '';
  var currentUrl = window.location.href;

  var currentTouch = {
    utm_source:   urlParams.utm_source   || '',
    utm_medium:   urlParams.utm_medium   || '',
    utm_campaign: urlParams.utm_campaign || '',
    utm_content:  urlParams.utm_content  || '',
    utm_term:     urlParams.utm_term     || '',
    gclid:        urlParams.gclid        || '',
    fbclid:       urlParams.fbclid       || '',
    landing_page: currentUrl,
    referrer:     rawReferrer,
    timestamp:    new Date().toISOString()
  };

  currentTouch.marketing_source = classifySource(
    currentTouch.utm_source,
    currentTouch.utm_medium,
    currentTouch.gclid,
    currentTouch.fbclid,
    currentTouch.referrer
  );

  var hasExplicitCampaign = Boolean(
    urlParams.utm_source ||
    urlParams.utm_medium ||
    urlParams.utm_campaign ||
    urlParams.utm_content ||
    urlParams.utm_term ||
    urlParams.gclid ||
    urlParams.fbclid ||
    (rawReferrer && isExternalReferrer(rawReferrer))
  );

  // 1. First Touch Attribution (Preserved forever/30-days, never overwritten)
  var firstTouch = getStoredObject(STORAGE_KEY_FIRST);
  if (!firstTouch) {
    firstTouch = currentTouch;
    setStoredObject(STORAGE_KEY_FIRST, firstTouch);
  }

  // 2. Last Touch Attribution (Updated when a new campaign or external referrer is detected)
  var lastTouch = getStoredObject(STORAGE_KEY_LAST);
  if (hasExplicitCampaign || !lastTouch) {
    lastTouch = currentTouch;
    setStoredObject(STORAGE_KEY_LAST, lastTouch);
  }

  // Active combined attribution (prefer Last Touch values for campaign execution, fallback to First Touch)
  var activeAttribution = {
    marketing_source: lastTouch.marketing_source || firstTouch.marketing_source || 'Direct',
    utm_source:       lastTouch.utm_source       || firstTouch.utm_source       || '',
    utm_medium:       lastTouch.utm_medium       || firstTouch.utm_medium       || '',
    utm_campaign:     lastTouch.utm_campaign     || firstTouch.utm_campaign     || '',
    utm_content:      lastTouch.utm_content      || firstTouch.utm_content      || '',
    utm_term:         lastTouch.utm_term         || firstTouch.utm_term         || '',
    gclid:            lastTouch.gclid            || firstTouch.gclid            || '',
    fbclid:           lastTouch.fbclid           || firstTouch.fbclid           || '',
    landing_page:     lastTouch.landing_page     || firstTouch.landing_page     || currentUrl,
    referrer:         lastTouch.referrer         || firstTouch.referrer         || rawReferrer,
    first_touch:      JSON.stringify(firstTouch),
    last_touch:       JSON.stringify(lastTouch)
  };

  // --- Form Auto-Injection ---
  function injectAttributionFields(form) {
    if (!form || form.nodeName !== 'FORM') return;

    var fields = [
      'marketing_source', 'utm_source', 'utm_medium', 'utm_campaign',
      'utm_content', 'utm_term', 'gclid', 'fbclid', 'landing_page',
      'referrer', 'first_touch', 'last_touch'
    ];

    for (var i = 0; i < fields.length; i++) {
      var fieldName = fields[i];
      var val = activeAttribution[fieldName] || '';

      var existing = form.querySelector('input[name="' + fieldName + '"]');
      if (existing) {
        if (!existing.value && val) {
          existing.value = val;
        }
      } else {
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = fieldName;
        input.value = val;
        form.appendChild(input);
      }
    }
  }

  function populateAllForms() {
    var forms = document.querySelectorAll('form');
    for (var i = 0; i < forms.length; i++) {
      injectAttributionFields(forms[i]);
    }
  }

  // Populate on DOM ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', populateAllForms);
  } else {
    populateAllForms();
  }

  // Intercept form submissions to ensure freshly injected values are present
  document.addEventListener('submit', function(e) {
    if (e.target && e.target.nodeName === 'FORM') {
      injectAttributionFields(e.target);
    }
  }, true);

  // Expose global attribution utility API
  window.ZuvioAttribution = {
    getAttribution: function() {
      return Object.assign({}, activeAttribution);
    },
    getFirstTouch: function() {
      return Object.assign({}, firstTouch);
    },
    getLastTouch: function() {
      return Object.assign({}, lastTouch);
    },
    attachToForm: function(form) {
      injectAttributionFields(form);
    },
    appendFormData: function(formData) {
      if (!formData || typeof formData.append !== 'function') return;
      var fields = [
        'marketing_source', 'utm_source', 'utm_medium', 'utm_campaign',
        'utm_content', 'utm_term', 'gclid', 'fbclid', 'landing_page',
        'referrer', 'first_touch', 'last_touch'
      ];
      for (var i = 0; i < fields.length; i++) {
        var key = fields[i];
        if (!formData.has(key)) {
          formData.append(key, activeAttribution[key] || '');
        }
      }
    }
  };

})();

