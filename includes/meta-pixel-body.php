<?php
// Zuvio Global School - Meta Pixel (Body Noscript Image)
// Prevents duplicate rendering and guarantees exclusion from /admin/* routes.
if (!defined('ZUVIO_META_PIXEL_BODY_RENDERED')) {
    $__mp_uri = $_SERVER['REQUEST_URI'] ?? '';
    $__mp_is_admin = (strpos($__mp_uri, '/admin') === 0) || (isset($page_slug) && strpos((string)$page_slug, 'admin-') === 0);
    if (!$__mp_is_admin) {
        define('ZUVIO_META_PIXEL_BODY_RENDERED', true);
?>
<!-- Meta Pixel Code (noscript) -->
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=1671385628104457&ev=PageView&noscript=1"
/></noscript>
<!-- End Meta Pixel Code (noscript) -->
<?php
    }
}

