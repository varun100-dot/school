import React, { useState, useEffect, useRef } from 'react';
import { ChevronLeft, ChevronRight, Loader2, CheckCircle2, AlertTriangle } from 'lucide-react';
import { Link } from 'react-router-dom';
import { submitEnquiry } from '../services/api';
import FloatingBubbles from './FloatingBubbles';

export default function HeroSlider({ slides }) {
  const [current, setCurrent] = useState(0);
  const [isPaused, setIsPaused] = useState(false);
  const autoPlayRef = useRef();

  // Hero Enquiry Form state
  const [formData, setFormData] = useState({
    parent_name: '',
    email: '',
    phone: '',
    grade: '',
    message: ''
  });
  const [errors, setErrors] = useState({});
  const [formStatus, setFormStatus] = useState('idle'); // idle, submitting, success, error
  const [errorMessage, setErrorMessage] = useState('');

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

  const handleInputChange = (e) => {
    const { name, value } = e.target;
    setFormData((prev) => ({ ...prev, [name]: value }));
    if (errors[name]) {
      setErrors((prev) => ({ ...prev, [name]: '' }));
    }
  };

  const validateForm = () => {
    const tempErrors = {};
    if (!formData.parent_name.trim()) tempErrors.parent_name = 'Required';
    if (!formData.email.trim()) {
      tempErrors.email = 'Required';
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(formData.email)) {
      tempErrors.email = 'Invalid';
    }
    if (!formData.phone.trim()) {
      tempErrors.phone = 'Required';
    } else if (formData.phone.length < 8) {
      tempErrors.phone = 'Invalid';
    }
    if (!formData.grade) tempErrors.grade = 'Required';
    setErrors(tempErrors);
    return Object.keys(tempErrors).length === 0;
  };

  const handleFormSubmit = async (e) => {
    e.preventDefault();
    if (!validateForm()) return;

    setFormStatus('submitting');
    setErrorMessage('');

    try {
      const res = await submitEnquiry({
        parent_name: formData.parent_name,
        student_name: formData.parent_name + ' (Student)',
        email: formData.email,
        phone: formData.phone,
        grade: formData.grade,
        message: formData.message || 'Submitted via Hero Banner'
      });
      if (res && res.message && res.message.includes('Mock')) {
        setFormStatus('error');
        setErrorMessage('Database connection required. Form could not be persisted.');
      } else {
        setFormStatus('success');
        setFormData({ parent_name: '', email: '', phone: '', grade: '', message: '' });
      }
    } catch (err) {
      setFormStatus('error');
      setErrorMessage(err.message || 'Database connection required.');
    }
  };

  if (!slides || slides.length === 0) return null;

  return (
    <section 
      className="hero-container"
      onMouseEnter={() => setIsPaused(true)}
      onMouseLeave={() => setIsPaused(false)}
      onFocus={() => setIsPaused(true)}
      onBlur={() => setIsPaused(false)}
    >
      {/* Floating Bubbles Background (Layer 1) */}
      <FloatingBubbles />

      {/* Slides (Layer 2) */}
      {slides.map((slide, index) => {
        const isActive = index === current;
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
            {/* Background image */}
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

            {/* Dark Gradient Overlay */}
            <div
              style={{
                position: 'absolute',
                top: 0,
                left: 0,
                width: '100%',
                height: '100%',
                background: 'linear-gradient(to right, rgba(0, 10, 66, 0.95) 0%, rgba(0, 10, 66, 0.8) 50%, rgba(0, 10, 66, 0.3) 100%)',
                zIndex: 1
              }}
            />

            {/* Responsive Split Layout */}
            <div className="hero-split-grid" style={{ zIndex: 2 }}>
              <div className="hero-content">
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
                  fontSize: '3.5rem',
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
                  fontSize: '1.1rem',
                  color: '#E2E8F0',
                  lineHeight: '1.6',
                  marginBottom: '2.5rem',
                  fontWeight: 400
                }} className="hero-description">
                  {slide.description}
                </p>

                <div className="hero-btn-row" style={{ display: 'flex', gap: '1.25rem' }}>
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

              {/* Grid spacer on desktop to make room for the overlay form */}
              <div className="hero-form-spacer" />
            </div>
          </div>
        );
      })}

      {/* Absolute Form Overlay (Layer 3) */}
      <div className="hero-absolute-form">
        <div style={{
          backgroundColor: 'rgba(255, 255, 255, 0.96)',
          border: '1px solid rgba(255, 255, 255, 0.35)',
          borderTop: '4px solid var(--color-gold)',
          borderRadius: '14px',
          boxShadow: '0 20px 45px rgba(0, 0, 0, 0.18)',
          padding: '1.75rem',
          color: 'var(--color-navy)',
          width: '100%',
          maxWidth: '400px'
        }}>
          {formStatus === 'success' ? (
            <div style={{ textAlign: 'center', padding: '1.5rem 0' }}>
              <CheckCircle2 size={36} style={{ color: 'var(--color-success)', marginBottom: '1rem' }} />
              <h3 style={{ fontSize: '1.25rem', marginBottom: '0.5rem', fontFamily: 'var(--font-primary)' }}>Enquiry Received</h3>
              <p style={{ fontSize: '0.85rem', color: 'var(--color-muted)' }}>
                Thank you. We will get in touch with you shortly to plan your child's roadmap.
              </p>
            </div>
          ) : (
            <form onSubmit={handleFormSubmit} style={{ display: 'flex', flexDirection: 'column', gap: '0.8rem' }}>
              <div style={{ marginBottom: '0.25rem' }}>
                <h3 style={{ fontSize: '1.35rem', fontFamily: 'var(--font-primary)', color: 'var(--color-navy)', margin: 0 }}>Enquire Now</h3>
                <p style={{ fontSize: '0.75rem', color: 'var(--color-muted)', margin: '0.2rem 0 0 0' }}>
                  Take the first step towards your child's learning journey.
                </p>
              </div>

              {formStatus === 'error' && (
                <div style={{
                  display: 'flex',
                  gap: '0.5rem',
                  backgroundColor: '#FDF2F8',
                  border: '1px solid #FBCFE8',
                  padding: '0.5rem 0.75rem',
                  borderRadius: 'var(--radius-sm)',
                  color: '#C084FC',
                  fontSize: '0.75rem'
                }}>
                  <AlertTriangle size={14} style={{ flexShrink: 0, marginTop: '2px' }} />
                  <span>{errorMessage}</span>
                </div>
              )}

              <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '0.5rem' }}>
                <div>
                  <input
                    type="text"
                    name="parent_name"
                    placeholder="Parent Name"
                    value={formData.parent_name}
                    onChange={handleInputChange}
                    className="hero-input"
                    style={{
                      width: '100%',
                      padding: '0.5rem 0.75rem',
                      border: errors.parent_name ? '1px solid #D946EF' : '1px solid rgba(6, 43, 99, 0.2)',
                      borderRadius: 'var(--radius-sm)',
                      fontSize: '0.85rem',
                      outline: 'none',
                      backgroundColor: '#FFFFFF',
                      transition: 'border-color 0.2s'
                    }}
                  />
                </div>
                <div>
                  <input
                    type="email"
                    name="email"
                    placeholder="Email Address"
                    value={formData.email}
                    onChange={handleInputChange}
                    className="hero-input"
                    style={{
                      width: '100%',
                      padding: '0.5rem 0.75rem',
                      border: errors.email ? '1px solid #D946EF' : '1px solid rgba(6, 43, 99, 0.2)',
                      borderRadius: 'var(--radius-sm)',
                      fontSize: '0.85rem',
                      outline: 'none',
                      backgroundColor: '#FFFFFF',
                      transition: 'border-color 0.2s'
                    }}
                  />
                </div>
              </div>

              <div style={{ display: 'grid', gridTemplateColumns: '1.2fr 0.8fr', gap: '0.5rem' }}>
                <div>
                  <input
                    type="tel"
                    name="phone"
                    placeholder="Phone Number"
                    value={formData.phone}
                    onChange={handleInputChange}
                    className="hero-input"
                    style={{
                      width: '100%',
                      padding: '0.5rem 0.75rem',
                      border: errors.phone ? '1px solid #D946EF' : '1px solid rgba(6, 43, 99, 0.2)',
                      borderRadius: 'var(--radius-sm)',
                      fontSize: '0.85rem',
                      outline: 'none',
                      backgroundColor: '#FFFFFF',
                      transition: 'border-color 0.2s'
                    }}
                  />
                </div>
                <div>
                  <select
                    name="grade"
                    value={formData.grade}
                    onChange={handleInputChange}
                    className="hero-input"
                    style={{
                      width: '100%',
                      padding: '0.5rem 0.75rem',
                      border: errors.grade ? '1px solid #D946EF' : '1px solid rgba(6, 43, 99, 0.2)',
                      borderRadius: 'var(--radius-sm)',
                      fontSize: '0.85rem',
                      outline: 'none',
                      backgroundColor: '#FFFFFF',
                      transition: 'border-color 0.2s'
                    }}
                  >
                    <option value="">Grade</option>
                    <option value="Early Years">Early Years</option>
                    <option value="Primary (1-5)">Grades 1-5</option>
                    <option value="Middle School (6-8)">Grades 6-8</option>
                  </select>
                </div>
              </div>

              <div>
                <textarea
                  name="message"
                  placeholder="Message / Question (Optional)"
                  rows={2}
                  value={formData.message}
                  onChange={handleInputChange}
                  className="hero-input"
                  style={{
                    width: '100%',
                    padding: '0.5rem 0.75rem',
                    border: '1px solid rgba(6, 43, 99, 0.2)',
                    borderRadius: 'var(--radius-sm)',
                    fontSize: '0.85rem',
                    outline: 'none',
                    backgroundColor: '#FFFFFF',
                    resize: 'none',
                    transition: 'border-color 0.2s'
                  }}
                />
              </div>

              <button
                type="submit"
                disabled={formStatus === 'submitting'}
                className="btn btn-primary"
                style={{
                  width: '100%',
                  padding: '0.75rem',
                  fontSize: '0.9rem',
                  marginTop: '0.2rem',
                  display: 'flex',
                  justifyContent: 'center',
                  alignItems: 'center',
                  gap: '0.5rem'
                }}
              >
                {formStatus === 'submitting' ? (
                  <>
                    <Loader2 size={16} className="spin-anim" />
                    <span>Submitting...</span>
                  </>
                ) : (
                  <span>Submit Enquiry</span>
                )}
              </button>
            </form>
          )}
        </div>
      </div>

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
      <div className="hero-indicators" style={{
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
        .hero-container {
          position: relative;
          height: 80vh;
          min-height: 620px;
          overflow: hidden;
          background-color: #000A42;
        }
        .hero-split-grid {
          display: grid;
          grid-template-columns: 1.2fr 0.8fr;
          gap: 4rem;
          align-items: center;
          height: 100%;
          position: relative;
          z-index: 10;
          max-width: var(--max-width);
          margin: 0 auto;
          padding: 0 3rem;
          width: 100%;
        }
        .hero-absolute-form {
          position: absolute;
          right: calc((100vw - var(--max-width)) / 2 + 3rem);
          top: 50%;
          transform: translateY(-50%);
          z-index: 20;
        }
        .hero-input:focus {
          border-color: var(--color-navy) !important;
          box-shadow: 0 0 0 2px rgba(6, 43, 99, 0.08);
        }
        @media (max-width: 1280px) {
          .hero-absolute-form {
            right: 3rem;
          }
        }
        @media (max-width: 1024px) {
          .hero-container {
            height: auto;
            min-height: auto;
            display: flex;
            flex-direction: column;
          }
          .hero-split-grid {
            grid-template-columns: 1fr;
            gap: 2.5rem;
            padding: 5rem 2rem;
            height: auto;
          }
          .hero-absolute-form {
            position: relative;
            right: auto;
            top: auto;
            transform: none;
            margin: 0 auto 4rem auto;
            max-width: 480px;
            width: calc(100% - 4rem);
            display: flex;
            justify-content: center;
          }
          .hero-form-spacer {
            display: none;
          }
          .hero-indicators {
            display: none !important;
          }
        }
        @media (max-width: 768px) {
          .hero-title {
            font-size: 2.5rem !important;
          }
          .hero-description {
            font-size: 1rem !important;
          }
          .hero-content {
            text-align: center !important;
            margin: 0 auto !important;
          }
          .hero-btn-row {
            justify-content: center;
          }
        }
      `}</style>
    </section>
  );
}
