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

  // Keyboard navigation
  useEffect(() => {
    const handleKeyDown = (e) => {
      if (e.key === 'ArrowLeft') prevSlide();
      if (e.key === 'ArrowRight') nextSlide();
    };
    window.addEventListener('keydown', handleKeyDown);
    return () => window.removeEventListener('keydown', handleKeyDown);
  }, [slides]);

  // Auto-play interval
  useEffect(() => {
    if (!isPaused && slides.length > 0) {
      autoPlayRef.current = setInterval(nextSlide, 5000);
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
        height: '70vh',
        minHeight: '500px',
        overflow: 'hidden',
        backgroundColor: '#0F172A',
        fontFamily: 'var(--font-secondary)'
      }}
      onMouseEnter={() => setIsPaused(true)}
      onMouseLeave={() => setIsPaused(false)}
      onFocus={() => setIsPaused(true)}
      onBlur={() => setIsPaused(false)}
    >
      {/* Slides Container */}
      {slides.map((slide, index) => {
        const isActive = index === current;
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
              transition: 'opacity 0.8s ease-in-out',
              zIndex: isActive ? 1 : 0,
              display: 'flex',
              alignItems: 'center'
            }}
          >
            {/* Background Image with Overlay */}
            <div
              style={{
                position: 'absolute',
                top: 0,
                left: 0,
                width: '100%',
                height: '100%',
                backgroundImage: `url("${slide.image}")`,
                backgroundSize: 'cover',
                backgroundPosition: 'center',
                filter: 'brightness(0.35)'
              }}
            />

            {/* Slide Content */}
            <div style={{
              position: 'relative',
              zIndex: 2,
              maxWidth: '800px',
              margin: '0 auto',
              padding: '0 2rem',
              color: '#FFFFFF',
              textAlign: 'center'
            }}>
              {slide.subtitle && (
                <span style={{
                  fontSize: '0.9rem',
                  fontWeight: 600,
                  textTransform: 'uppercase',
                  letterSpacing: '2px',
                  color: 'var(--color-gold)',
                  marginBottom: '1rem',
                  display: 'inline-block'
                }}>
                  {slide.subtitle}
                </span>
              )}
              <h1 style={{
                fontSize: '3.5rem',
                fontFamily: 'var(--font-primary)',
                fontWeight: 700,
                color: '#FFFFFF',
                lineHeight: '1.2',
                marginBottom: '1.5rem'
              }} className="hero-title">
                {slide.title}
              </h1>
              <p style={{
                fontSize: '1.2rem',
                color: '#E2E8F0',
                lineHeight: '1.6',
                marginBottom: '2.5rem',
                fontWeight: 300
              }}>
                {slide.description}
              </p>
              
              {/* CTA Buttons */}
              <div style={{ display: 'flex', gap: '1rem', justifyContent: 'center' }}>
                {slide.primary_cta_text && (
                  <Link to={slide.primary_cta_url || '/'} className="btn btn-primary" style={{ padding: '0.85rem 2rem' }}>
                    {slide.primary_cta_text}
                  </Link>
                )}
                {slide.secondary_cta_text && (
                  <Link to={slide.secondary_cta_url || '/'} className="btn btn-outline" style={{ padding: '0.85rem 2rem', color: '#FFFFFF', borderColor: '#FFFFFF' }}>
                    {slide.secondary_cta_text}
                  </Link>
                )}
              </div>
            </div>
          </div>
        );
      })}

      {/* Slide Navigation Arrows */}
      <button
        onClick={prevSlide}
        style={{
          position: 'absolute',
          left: '1.5rem',
          top: '50%',
          transform: 'translateY(-50%)',
          background: 'rgba(15,23,42,0.4)',
          border: 'none',
          color: '#FFFFFF',
          padding: '0.6rem',
          borderRadius: '50%',
          cursor: 'pointer',
          zIndex: 10,
          display: 'flex',
          transition: 'all 0.2s'
        }}
        onMouseEnter={(e) => e.target.style.background = 'var(--color-gold)'}
        onMouseLeave={(e) => e.target.style.background = 'rgba(15,23,42,0.4)'}
        aria-label="Previous banner slide"
      >
        <ChevronLeft size={24} />
      </button>
      <button
        onClick={nextSlide}
        style={{
          position: 'absolute',
          right: '1.5rem',
          top: '50%',
          transform: 'translateY(-50%)',
          background: 'rgba(15,23,42,0.4)',
          border: 'none',
          color: '#FFFFFF',
          padding: '0.6rem',
          borderRadius: '50%',
          cursor: 'pointer',
          zIndex: 10,
          display: 'flex',
          transition: 'all 0.2s'
        }}
        onMouseEnter={(e) => e.target.style.background = 'var(--color-gold)'}
        onMouseLeave={(e) => e.target.style.background = 'rgba(15,23,42,0.4)'}
        aria-label="Next banner slide"
      >
        <ChevronRight size={24} />
      </button>

      {/* Bottom Dot Indicators */}
      <div style={{
        position: 'absolute',
        bottom: '1.5rem',
        left: '50%',
        transform: 'translateX(-50%)',
        display: 'flex',
        gap: '0.6rem',
        zIndex: 10
      }}>
        {slides.map((_, index) => (
          <button
            key={index}
            onClick={() => setCurrent(index)}
            style={{
              width: '10px',
              height: '10px',
              borderRadius: '50%',
              border: 'none',
              backgroundColor: index === current ? 'var(--color-gold)' : 'rgba(255, 255, 255, 0.4)',
              cursor: 'pointer',
              transition: 'background-color 0.3s'
            }}
            aria-label={`Go to slide ${index + 1}`}
          />
        ))}
      </div>

      <style>{`
        @media (max-width: 768px) {
          .hero-title {
            font-size: 2.2rem !important;
          }
        }
      `}</style>
    </section>
  );
}
