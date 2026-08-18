import React, { useState, useEffect, useRef } from 'react';
import { ChevronLeft, ChevronRight } from 'lucide-react';
import { Link } from 'react-router-dom';

export default function HeroSlider({ slides }) {
  const [current, setCurrent] = useState(0);
  const [isPaused, setIsPaused] = useState(false);
  const autoPlayRef = useRef();

  const nextSlide = () => {
    setCurrent((prev) => (prev === slides.length - 1 ? 0 : prev + 1));
  };

  const prevSlide = () => {
    setCurrent((prev) => (prev === 0 ? slides.length - 1 : prev - 1));
  };

  useEffect(() => {
    const handleKeyDown = (e) => {
      if (e.key === 'ArrowLeft') prevSlide();
      if (e.key === 'ArrowRight') nextSlide();
    };
    window.addEventListener('keydown', handleKeyDown);
    return () => window.removeEventListener('keydown', handleKeyDown);
  }, [slides]);

  useEffect(() => {
    if (!isPaused && slides.length > 0) {
      autoPlayRef.current = setInterval(nextSlide, 6000);
    }
    return () => {
      if (autoPlayRef.current) clearInterval(autoPlayRef.current);
    };
  }, [isPaused, current, slides]);

  if (!slides || slides.length === 0) return null;

  return (
    <section 
      style={{
        position: 'relative',
        height: '80vh',
        minHeight: '600px',
        overflow: 'hidden',
        backgroundColor: '#0F172A',
        fontFamily: 'var(--font-secondary)'
      }}
      onMouseEnter={() => setIsPaused(true)}
      onMouseLeave={() => setIsPaused(false)}
      onFocus={() => setIsPaused(true)}
      onBlur={() => setIsPaused(false)}
    >
      {/* Slides */}
      {slides.map((slide, index) => {
        const isActive = index === current;
        // Robust URI space escaping for local filenames with spaces
        const safeImageUrl = slide.image ? slide.image.replace(/ /g, '%20') : '';

        return (
          <div
            key={index}
            style={{
              position: 'absolute',
              top: 0,
              left: 0,
              width: '100%',
              height: '100%',
              opacity: isActive ? 1 : 0,
              transition: 'opacity 1s ease-in-out',
              zIndex: isActive ? 2 : 1,
              display: 'flex',
              alignItems: 'center'
            }}
          >
            {/* Full-bleed Background image */}
            <div
              style={{
                position: 'absolute',
                top: 0,
                left: 0,
                width: '100%',
                height: '100%',
                backgroundImage: `url("${safeImageUrl}")`,
                backgroundSize: 'cover',
                backgroundPosition: 'center',
                transition: 'transform 6s ease'
              }}
              className={isActive ? 'scale-zoom' : ''}
            />

            {/* Premium Left-to-Right Dark Gradient Overlay (helps contrast without muddying the image) */}
            <div
              style={{
                position: 'absolute',
                top: 0,
                left: 0,
                width: '100%',
                height: '100%',
                background: 'linear-gradient(to right, rgba(15, 23, 42, 0.92) 0%, rgba(15, 23, 42, 0.75) 45%, rgba(15, 23, 42, 0.25) 100%)',
                zIndex: 1
              }}
            />

            {/* Slide Content Box */}
            <div style={{
              position: 'relative',
              zIndex: 2,
              width: '100%',
              maxWidth: 'var(--max-width)',
              margin: '0 auto',
              padding: '0 3rem',
              display: 'flex',
              flexDirection: 'column',
              justifyContent: 'center',
              height: '100%'
            }}>
              <div style={{ maxWidth: '650px', textAlign: 'left' }} className="hero-content">
                {slide.subtitle && (
                  <span style={{
                    fontSize: '0.85rem',
                    fontWeight: 600,
                    textTransform: 'uppercase',
                    letterSpacing: '2.5px',
                    color: 'var(--color-gold)',
                    marginBottom: '1rem',
                    display: 'inline-block'
                  }}>
                    {slide.subtitle}
                  </span>
                )}
                
                <h1 style={{
                  fontSize: '3.75rem',
                  fontFamily: 'var(--font-primary)',
                  fontWeight: 700,
                  color: '#FFFFFF',
                  lineHeight: '1.15',
                  marginBottom: '1.25rem',
                  letterSpacing: '-0.5px'
                }} className="hero-title">
                  {slide.title}
                </h1>
                
                <p style={{
                  fontSize: '1.15rem',
                  color: '#E2E8F0',
                  lineHeight: '1.7',
                  marginBottom: '2.5rem',
                  fontWeight: 400
                }}>
                  {slide.description}
                </p>

                <div style={{ display: 'flex', gap: '1.25rem' }}>
                  {slide.primary_cta_text && (
                    <Link to={slide.primary_cta_url || '/'} className="btn btn-primary" style={{ padding: '0.9rem 2.25rem' }}>
                      {slide.primary_cta_text}
                    </Link>
                  )}
                  {slide.secondary_cta_text && (
                    <Link to={slide.secondary_cta_url || '/'} className="btn btn-outline" style={{ padding: '0.9rem 2.25rem', color: '#FFFFFF', borderColor: '#FFFFFF' }}>
                      {slide.secondary_cta_text}
                    </Link>
                  )}
                </div>
              </div>
            </div>
          </div>
        );
      })}

      {/* Navigation Arrows */}
      <button
        onClick={prevSlide}
        style={{
          position: 'absolute',
          left: '2rem',
          bottom: '2rem',
          background: 'rgba(255,255,255,0.08)',
          border: '1px solid rgba(255,255,255,0.2)',
          color: '#FFFFFF',
          padding: '0.65rem',
          borderRadius: '50%',
          cursor: 'pointer',
          zIndex: 10,
          display: 'flex',
          transition: 'all 0.2s'
        }}
        onMouseEnter={(e) => { e.currentTarget.style.background = 'var(--color-gold)'; e.currentTarget.style.borderColor = 'var(--color-gold)'; }}
        onMouseLeave={(e) => { e.currentTarget.style.background = 'rgba(255,255,255,0.08)'; e.currentTarget.style.borderColor = 'rgba(255,255,255,0.2)'; }}
        aria-label="Previous banner slide"
      >
        <ChevronLeft size={20} />
      </button>

      <button
        onClick={nextSlide}
        style={{
          position: 'absolute',
          left: '5.5rem',
          bottom: '2rem',
          background: 'rgba(255,255,255,0.08)',
          border: '1px solid rgba(255,255,255,0.2)',
          color: '#FFFFFF',
          padding: '0.65rem',
          borderRadius: '50%',
          cursor: 'pointer',
          zIndex: 10,
          display: 'flex',
          transition: 'all 0.2s'
        }}
        onMouseEnter={(e) => { e.currentTarget.style.background = 'var(--color-gold)'; e.currentTarget.style.borderColor = 'var(--color-gold)'; }}
        onMouseLeave={(e) => { e.currentTarget.style.background = 'rgba(255,255,255,0.08)'; e.currentTarget.style.borderColor = 'rgba(255,255,255,0.2)'; }}
        aria-label="Next banner slide"
      >
        <ChevronRight size={20} />
      </button>

      {/* Slide Indicators */}
      <div style={{
        position: 'absolute',
        right: '3rem',
        bottom: '2.5rem',
        display: 'flex',
        gap: '0.75rem',
        zIndex: 10
      }}>
        {slides.map((_, index) => (
          <button
            key={index}
            onClick={() => setCurrent(index)}
            style={{
              width: index === current ? '24px' : '8px',
              height: '8px',
              borderRadius: '4px',
              border: 'none',
              backgroundColor: index === current ? 'var(--color-gold)' : 'rgba(255, 255, 255, 0.3)',
              cursor: 'pointer',
              transition: 'all 0.3s ease'
            }}
            aria-label={`Go to slide ${index + 1}`}
          />
        ))}
      </div>

      <style>{`
        @keyframes zoom {
          0% { transform: scale(1); }
          100% { transform: scale(1.05); }
        }
        .scale-zoom {
          animation: zoom 6s forwards;
        }
        @media (max-width: 768px) {
          .hero-title {
            font-size: 2.5rem !important;
          }
          .hero-content {
            text-align: center !important;
            margin: 0 auto !important;
            padding-bottom: 4rem;
          }
        }
      `}</style>
    </section>
  );
}
