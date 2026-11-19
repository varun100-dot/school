<?php
// Zuvio Global School - Meta Pixel (Head Script)
// Prevents duplicate initialization and guarantees exclusion from /admin/* routes.
if (!defined('ZUVIO_META_PIXEL_HEAD_RENDERED')) {
    $__mp_uri = $_SERVER['REQUEST_URI'] ?? '';
    $__mp_is_admin = (strpos($__mp_uri, '/admin') === 0) || (isset($page_slug) && strpos((string)$page_slug, 'admin-') === 0);
    if (!$__mp_is_admin) {
        define('ZUVIO_META_PIXEL_HEAD_RENDERED', true);
?>
<!-- Meta Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '1671385628104457');
fbq('track', 'PageView');
</script>
<!-- End Meta Pixel Code -->
<?php
    }
}

