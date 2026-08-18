import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { Mail, Phone, MapPin, Clock, Instagram, Facebook, Linkedin, BookOpen } from 'lucide-react';
import { getSiteSettings } from '../services/api';

export default function Footer() {
  const [settings, setSettings] = useState({});

  useEffect(() => {
    getSiteSettings().then(setSettings);
  }, []);

  return (
    <footer style={{
      backgroundColor: 'var(--color-navy)',
      color: '#FFFFFF',
      padding: '5rem 2rem 2rem 2rem',
      fontFamily: 'var(--font-secondary)',
      borderTop: '4px solid var(--color-gold)'
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
          <div style={{ marginBottom: '1.5rem', height: '60px', display: 'flex', alignItems: 'center' }}>
            <img src="/assets/images/logo-emblem.png" alt="Zuvio Global School emblem" style={{ height: '100%', width: 'auto', objectFit: 'contain' }} />
          </div>
          <p style={{ color: 'var(--color-muted)', fontSize: '0.9rem', lineHeight: '1.7', marginBottom: '1.5rem' }}>
            A future-ready online school where academic excellence meets personalised learning. 
            We empower children to learn beyond boundaries and grow with confidence.
          </p>
          <div style={{ display: 'flex', gap: '1rem' }}>
            {settings.social_instagram && (
              <a href={settings.social_instagram} target="_blank" rel="noreferrer" style={{ color: '#FFFFFF', opacity: 0.8 }} aria-label="Zuvio Instagram">
                <Instagram size={20} />
              </a>
            )}
            {settings.social_facebook && (
              <a href={settings.social_facebook} target="_blank" rel="noreferrer" style={{ color: '#FFFFFF', opacity: 0.8 }} aria-label="Zuvio Facebook">
                <Facebook size={20} />
              </a>
            )}
            {settings.social_linkedin && (
              <a href={settings.social_linkedin} target="_blank" rel="noreferrer" style={{ color: '#FFFFFF', opacity: 0.8 }} aria-label="Zuvio LinkedIn">
                <Linkedin size={20} />
              </a>
            )}
          </div>
        </div>

        {/* Column 2: Navigation links */}
        <div>
          <h3 style={{ fontSize: '1.1rem', marginBottom: '1.5rem', fontFamily: 'var(--font-primary)', color: 'var(--color-gold)' }}>Quick Links</h3>
          <ul style={{ listStyle: 'none', display: 'flex', flexDirection: 'column', gap: '0.8rem', fontSize: '0.9rem' }}>
            <li><Link to="/" style={{ opacity: 0.85 }}>Home</Link></li>
            <li><Link to="/about" style={{ opacity: 0.85 }}>About Us</Link></li>
            <li><Link to="/curriculum" style={{ opacity: 0.85 }}>Our Curriculum</Link></li>
            <li><Link to="/zuvio-beyond" style={{ opacity: 0.85 }}>Zuvio Beyond</Link></li>
            <li><Link to="/blogs" style={{ opacity: 0.85 }}>Blogs</Link></li>
            <li><Link to="/contact" style={{ opacity: 0.85 }}>Contact Us</Link></li>
          </ul>
        </div>

        {/* Column 3: Address & Info */}
        <div>
          <h3 style={{ fontSize: '1.1rem', marginBottom: '1.5rem', fontFamily: 'var(--font-primary)', color: 'var(--color-gold)' }}>Contact Details</h3>
          <div style={{ display: 'flex', flexDirection: 'column', gap: '1rem', fontSize: '0.9rem', color: '#E2E8F0' }}>
            <div style={{ display: 'flex', gap: '0.75rem', alignItems: 'flex-start' }}>
              <MapPin size={18} style={{ color: 'var(--color-gold)', flexShrink: 0 }} />
              <span>{settings.address}</span>
            </div>
            <div style={{ display: 'flex', gap: '0.75rem', alignItems: 'center' }}>
              <Phone size={18} style={{ color: 'var(--color-gold)' }} />
              <a href={`tel:${settings.phone}`} style={{ color: '#E2E8F0', textDecoration: 'none', transition: 'color 0.2s' }} onMouseEnter={e => e.target.style.color = 'var(--color-gold)'} onMouseLeave={e => e.target.style.color = '#E2E8F0'}>{settings.phone}</a>
            </div>
            <div style={{ display: 'flex', gap: '0.75rem', alignItems: 'center' }}>
              <Mail size={18} style={{ color: 'var(--color-gold)' }} />
              <a href={`mailto:${settings.general_email}`} style={{ color: '#E2E8F0', textDecoration: 'none', transition: 'color 0.2s' }} onMouseEnter={e => e.target.style.color = 'var(--color-gold)'} onMouseLeave={e => e.target.style.color = '#E2E8F0'}>{settings.general_email}</a>
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
        borderTop: '1px solid rgba(226, 232, 240, 0.1)',
        paddingTop: '2rem',
        display: 'flex',
        justifyContent: 'space-between',
        alignItems: 'center',
        fontSize: '0.8rem',
        color: '#94A3B8',
        flexWrap: 'wrap',
        gap: '1rem'
      }}>
        <span>{settings.copyright}</span>
        <span>Learning Beyond Boundaries</span>
      </div>

      <style>{`
        @media (max-width: 768px) {
          .footer-grid {
            grid-template-columns: 1fr !important;
            gap: 2.5rem !important;
          }
        }
      `}</style>
    </footer>
  );
}
