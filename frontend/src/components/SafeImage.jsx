import React, { useState } from 'react';
import { Image as ImageIcon } from 'lucide-react';

export default function SafeImage({ src, alt, style, fallbackText = 'Image Pending' }) {
  const [hasError, setHasError] = useState(false);
  const [isLoading, setIsLoading] = useState(true);

  const handleImageError = () => {
    setHasError(true);
    setIsLoading(false);
  };

  const handleImageLoad = () => {
    setIsLoading(false);
  };

  // Convert space characters in local filenames
  const safeSrc = src ? src.replace(/ /g, '%20') : '';

  if (hasError || !src) {
    return (
      <div 
        style={{
          width: '100%',
          height: '100%',
          minHeight: '150px',
          display: 'flex',
          flexDirection: 'column',
          alignItems: 'center',
          justifyContent: 'center',
          backgroundColor: '#F1F5F9',
          color: '#94A3B8',
          gap: '0.5rem',
          padding: '1.5rem',
          textAlign: 'center',
          border: '1.5px dashed #CBD5E1',
          fontFamily: 'var(--font-secondary)',
          ...style
        }}
      >
        <ImageIcon size={32} style={{ opacity: 0.7 }} />
        <span style={{ fontSize: '0.8rem', fontWeight: 500, letterSpacing: '0.5px' }}>{fallbackText}</span>
      </div>
    );
  }

  return (
    <div style={{ position: 'relative', width: '100%', height: '100%', overflow: 'hidden' }}>
      {isLoading && (
        <div style={{
          position: 'absolute',
          top: 0,
          left: 0,
          width: '100%',
          height: '100%',
          backgroundColor: '#F8FAFC',
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'center'
        }} />
      )}
      <img
        src={safeSrc}
        alt={alt || 'Zuvio Asset'}
        onError={handleImageError}
        onLoad={handleImageLoad}
        style={{
          width: '100%',
          height: '100%',
          objectFit: 'cover',
          display: isLoading ? 'none' : 'block',
          ...style
        }}
      />
    </div>
  );
}
