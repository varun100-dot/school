import React, { useEffect } from 'react';

export default function SEO({ title, description, canonicalUrl, ogImage, indexStatus }) {
  useEffect(() => {
    // 1. Update Document Title
    const finalTitle = title ? `${title} | Zuvio Global School` : 'Zuvio Global School | Learning Beyond Boundaries';
    document.title = finalTitle;

    // Helper to find or create meta tag
    const updateMetaTag = (attrName, attrVal, contentVal) => {
      if (!contentVal) return;
      let el = document.querySelector(`meta[${attrName}="${attrVal}"]`);
      if (!el) {
        el = document.createElement('meta');
        el.setAttribute(attrName, attrVal);
        document.head.appendChild(el);
      }
      el.setAttribute('content', contentVal);
    };

    // Helper to find or create link tag
    const updateLinkTag = (relVal, hrefVal) => {
      if (!hrefVal) return;
      let el = document.querySelector(`link[rel="${relVal}"]`);
      if (!el) {
        el = document.createElement('link');
        el.setAttribute('rel', relVal);
        document.head.appendChild(el);
      }
      el.setAttribute('href', hrefVal);
    };

    // 2. Meta description
    updateMetaTag('name', 'description', description || 'A future-ready online school where academic excellence meets personalised learning.');

    // 3. Robots index status
    updateMetaTag('name', 'robots', indexStatus || 'index, follow');

    // 4. Open Graph Tags
    updateMetaTag('property', 'og:title', title || 'Zuvio Global School');
    updateMetaTag('property', 'og:description', description || 'Learning Beyond Boundaries');
    updateMetaTag('property', 'og:type', 'website');
    updateMetaTag('property', 'og:image', ogImage || '/assets/images/Hero image 1.png');

    // 5. Twitter Card Tags
    updateMetaTag('name', 'twitter:card', 'summary_large_image');
    updateMetaTag('name', 'twitter:title', title || 'Zuvio Global School');
    updateMetaTag('name', 'twitter:description', description || 'Learning Beyond Boundaries');
    updateMetaTag('name', 'twitter:image', ogImage || '/assets/images/Hero image 1.png');

    // 6. Canonical link
    updateLinkTag('canonical', canonicalUrl || window.location.href);

  }, [title, description, canonicalUrl, ogImage, indexStatus]);

  return null; // Side-effect only component
}
