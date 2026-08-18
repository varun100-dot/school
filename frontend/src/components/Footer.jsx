import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { Mail, Phone, MapPin, Clock, Instagram, Facebook, Linkedin } from 'lucide-react';
import { getSiteSettings } from '../services/api';

export default function Footer() {
  const [settings, setSettings] = useState({});

  useEffect(() => {
    getSiteSettings().then(setSettings);
  }, []);

  return (
    <footer style={{
      backgroundColor: 'var(--color-light-bg)',
      color: 'var(--color-text)',
      padding: '5rem 2rem 2rem 2rem',
      fontFamily: 'var(--font-secondary)',
      borderTop: '1px solid var(--color-border)'
    }}>
      <div style={{
        maxWidth: 'var(--max-width)',
        margin: '0 auto',
        display: 'grid',
        gridTemplateColumns: '1.2fr 0.8fr 1fr',
        gap: '4rem',
        marginBottom: '4rem'
      }} className="footer-grid">
        
        {/* Column 1: School Identity */}
        <div>
          <div style={{ marginBottom: '1.5rem', display: 'flex', alignItems: 'center' }}>
            <img 
              src="/assets/images/logo.png" 
              alt="Zuvio Global School" 
              className="footer-logo-img"
            />
          </div>
          <p style={{ color: 'var(--color-muted)', fontSize: '0.9rem', lineHeight: '1.7', marginBottom: '1.5rem' }}>
            A future-ready online school where academic excellence meets personalised learning. 
            We empower children to learn beyond boundaries and grow with confidence.
          </p>
          <div style={{ display: 'flex', gap: '1rem' }}>
            {settings.social_instagram && (
              <a href={settings.social_instagram} target="_blank" rel="noreferrer" style={{ color: 'var(--color-navy)', opacity: 0.8, transition: 'color 0.2s' }} onMouseEnter={e => e.currentTarget.style.color = 'var(--color-gold)'} onMouseLeave={e => e.currentTarget.style.color = 'var(--color-navy)'} aria-label="Zuvio Instagram">
                <Instagram size={20} />
              </a>
            )}
            {settings.social_facebook && (
              <a href={settings.social_facebook} target="_blank" rel="noreferrer" style={{ color: 'var(--color-navy)', opacity: 0.8, transition: 'color 0.2s' }} onMouseEnter={e => e.currentTarget.style.color = 'var(--color-gold)'} onMouseLeave={e => e.currentTarget.style.color = 'var(--color-navy)'} aria-label="Zuvio Facebook">
                <Facebook size={20} />
              </a>
            )}
            {settings.social_linkedin && (
              <a href={settings.social_linkedin} target="_blank" rel="noreferrer" style={{ color: 'var(--color-navy)', opacity: 0.8, transition: 'color 0.2s' }} onMouseEnter={e => e.currentTarget.style.color = 'var(--color-gold)'} onMouseLeave={e => e.currentTarget.style.color = 'var(--color-navy)'} aria-label="Zuvio LinkedIn">
                <Linkedin size={20} />
              </a>
            )}
          </div>
        </div>

        {/* Column 2: Navigation links */}
        <div>
          <h3 style={{ fontSize: '1.1rem', marginBottom: '1.5rem', fontFamily: 'var(--font-primary)', color: 'var(--color-navy)', borderBottom: '2px solid var(--color-gold)', display: 'inline-block', paddingBottom: '4px' }}>Quick Links</h3>
          <ul style={{ listStyle: 'none', display: 'flex', flexDirection: 'column', gap: '0.8rem', fontSize: '0.9rem' }}>
            <li><Link to="/" style={{ color: 'var(--color-navy)', fontWeight: 500, transition: 'color 0.2s' }} onMouseEnter={e => e.target.style.color = 'var(--color-gold)'} onMouseLeave={e => e.target.style.color = 'var(--color-navy)'}>Home</Link></li>
            <li><Link to="/about-us" style={{ color: 'var(--color-navy)', fontWeight: 500, transition: 'color 0.2s' }} onMouseEnter={e => e.target.style.color = 'var(--color-gold)'} onMouseLeave={e => e.target.style.color = 'var(--color-navy)'}>About Us</Link></li>
            <li><Link to="/our-curriculum" style={{ color: 'var(--color-navy)', fontWeight: 500, transition: 'color 0.2s' }} onMouseEnter={e => e.target.style.color = 'var(--color-gold)'} onMouseLeave={e => e.target.style.color = 'var(--color-navy)'}>Our Curriculum</Link></li>
            <li><Link to="/zuvio-beyond" style={{ color: 'var(--color-navy)', fontWeight: 500, transition: 'color 0.2s' }} onMouseEnter={e => e.target.style.color = 'var(--color-gold)'} onMouseLeave={e => e.target.style.color = 'var(--color-navy)'}>Zuvio Beyond</Link></li>
            <li><Link to="/blogs" style={{ color: 'var(--color-navy)', fontWeight: 500, transition: 'color 0.2s' }} onMouseEnter={e => e.target.style.color = 'var(--color-gold)'} onMouseLeave={e => e.target.style.color = 'var(--color-navy)'}>Blogs</Link></li>
            <li><Link to="/contact-us" style={{ color: 'var(--color-navy)', fontWeight: 500, transition: 'color 0.2s' }} onMouseEnter={e => e.target.style.color = 'var(--color-gold)'} onMouseLeave={e => e.target.style.color = 'var(--color-navy)'}>Contact Us</Link></li>
          </ul>
        </div>

        {/* Column 3: Address & Info */}
        <div>
          <h3 style={{ fontSize: '1.1rem', marginBottom: '1.5rem', fontFamily: 'var(--font-primary)', color: 'var(--color-navy)', borderBottom: '2px solid var(--color-gold)', display: 'inline-block', paddingBottom: '4px' }}>Contact Details</h3>
          <div style={{ display: 'flex', flexDirection: 'column', gap: '1rem', fontSize: '0.9rem', color: 'var(--color-text)' }}>
            <div style={{ display: 'flex', gap: '0.75rem', alignItems: 'flex-start' }}>
              <MapPin size={18} style={{ color: 'var(--color-gold)', flexShrink: 0 }} />
              <span>{settings.address}</span>
            </div>
            <div style={{ display: 'flex', gap: '0.75rem', alignItems: 'center' }}>
              <Phone size={18} style={{ color: 'var(--color-gold)' }} />
              <a href={`tel:${settings.phone}`} style={{ color: 'var(--color-navy)', fontWeight: 500, textDecoration: 'none', transition: 'color 0.2s' }} onMouseEnter={e => e.target.style.color = 'var(--color-gold)'} onMouseLeave={e => e.target.style.color = 'var(--color-navy)'}>{settings.phone}</a>
            </div>
            <div style={{ display: 'flex', gap: '0.75rem', alignItems: 'center' }}>
              <Mail size={18} style={{ color: 'var(--color-gold)' }} />
              <a href={`mailto:${settings.general_email}`} style={{ color: 'var(--color-navy)', fontWeight: 500, textDecoration: 'none', transition: 'color 0.2s' }} onMouseEnter={e => e.target.style.color = 'var(--color-gold)'} onMouseLeave={e => e.target.style.color = 'var(--color-navy)'}>{settings.general_email}</a>
            </div>
            <div style={{ display: 'flex', gap: '0.75rem', alignItems: 'center' }}>
              <Clock size={18} style={{ color: 'var(--color-gold)' }} />
              <span>Office Hours: {settings.office_timings} Daily</span>
            </div>
          </div>
        </div>
      </div>

      {/* Copyright border */}
      <div style={{
        maxWidth: 'var(--max-width)',
        margin: '0 auto',
        borderTop: '1px solid var(--color-border)',
        paddingTop: '2rem',
        display: 'flex',
        justifyContent: 'space-between',
        alignItems: 'center',
        fontSize: '0.8rem',
        color: 'var(--color-muted)',
        flexWrap: 'wrap',
        gap: '1rem'
      }}>
        <span>{settings.copyright}</span>
        <span>Learning Beyond Boundaries</span>
      </div>

      <style>{`
        .footer-logo-img {
          width: 180px;
          height: auto;
          object-fit: contain;
          display: block;
        }
        @media (max-width: 768px) {
          .footer-logo-img {
            width: 150px;
          }
          .footer-grid {
            grid-template-columns: 1fr !important;
            gap: 2.5rem !important;
          }
        }
      `}</style>
    </footer>
  );
}
