import React, { useState, useEffect } from 'react';
import { Link, useLocation } from 'react-router-dom';
import { Menu, X } from 'lucide-react';
import { getNavigation } from '../services/api';

export default function Header() {
  const [navItems, setNavItems] = useState([]);
  const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);
  const [isScrolled, setIsScrolled] = useState(false);
  const location = useLocation();

  useEffect(() => {
    getNavigation().then(setNavItems);

    const handleScroll = () => {
      if (window.scrollY > 30) {
        setIsScrolled(true);
      } else {
        setIsScrolled(false);
      }
    };
    window.addEventListener('scroll', handleScroll);
    return () => window.removeEventListener('scroll', handleScroll);
  }, []);

  useEffect(() => {
    setIsMobileMenuOpen(false);
  }, [location]);

  return (
    <>
      <header style={{
        position: 'sticky',
        top: 0,
        zIndex: 1000,
        backgroundColor: '#FFFFFF',
        boxShadow: isScrolled ? '0 4px 20px rgba(1, 37, 92, 0.08)' : '0 2px 10px rgba(1, 37, 92, 0.04)',
        borderBottom: '1px solid var(--color-border)',
        color: 'var(--color-navy)',
        padding: isScrolled ? '0.75rem 2rem' : '1.15rem 2rem',
        display: 'flex',
        justifyContent: 'space-between',
        alignItems: 'center',
        transition: 'all var(--transition-normal)',
        fontFamily: 'var(--font-secondary)'
      }}>
        {/* Official Logo */}
        <Link to="/" style={{ display: 'flex', alignItems: 'center', height: '42px' }}>
          <img 
            src="/assets/images/logo.png" 
            alt="Zuvio Global School Logo" 
            style={{ 
              height: '100%', 
              width: 'auto', 
              objectFit: 'contain',
              display: 'block'
            }} 
          />
        </Link>

        {/* Desktop Links */}
        <nav style={{ display: 'flex', alignItems: 'center', gap: '2rem' }} className="desktop-nav">
          {navItems.map((item, idx) => {
            const isActive = location.pathname === item.url || (item.url !== '/' && location.pathname.startsWith(item.url));
            return (
              <Link
                key={idx}
                to={item.url}
                style={{
                  fontSize: '0.9rem',
                  fontWeight: 600,
                  color: isActive ? 'var(--color-gold)' : 'var(--color-navy)',
                  transition: 'all var(--transition-fast)',
                  borderBottom: isActive ? '2px solid var(--color-gold)' : '2px solid transparent',
                  paddingBottom: '6px'
                }}
                onMouseEnter={(e) => {
                  if (!isActive) e.target.style.color = 'var(--color-teal)';
                }}
                onMouseLeave={(e) => {
                  if (!isActive) e.target.style.color = 'var(--color-navy)';
                }}
              >
                {item.label}
              </Link>
            );
          })}
          
          <Link 
            to="/contact" 
            className="btn" 
            style={{ 
              padding: '0.6rem 1.4rem', 
              fontSize: '0.85rem',
              fontWeight: 600,
              backgroundColor: 'var(--color-navy)',
              color: '#FFFFFF',
              border: '1.5px solid var(--color-navy)',
              transition: 'all 0.2s ease'
            }}
            onMouseEnter={(e) => {
              e.target.style.backgroundColor = 'var(--color-gold)';
              e.target.style.borderColor = 'var(--color-gold)';
            }}
            onMouseLeave={(e) => {
              e.target.style.backgroundColor = 'var(--color-navy)';
              e.target.style.borderColor = 'var(--color-navy)';
            }}
          >
            Enquire Now
          </Link>
        </nav>

        {/* Mobile menu trigger */}
        <button
          onClick={() => setIsMobileMenuOpen(!isMobileMenuOpen)}
          style={{
            display: 'none',
            background: 'none',
            border: 'none',
            color: 'var(--color-navy)',
            cursor: 'pointer'
          }}
          className="mobile-trigger"
          aria-label="Toggle navigation menu"
        >
          {isMobileMenuOpen ? <X size={24} /> : <Menu size={24} />}
        </button>
      </header>

      {/* Mobile navigation drawer */}
      {isMobileMenuOpen && (
        <div style={{
          position: 'fixed',
          top: '64px',
          left: 0,
          right: 0,
          bottom: 0,
          backgroundColor: '#FFFFFF',
          borderTop: '1px solid var(--color-border)',
          zIndex: 999,
          padding: '2rem',
          display: 'flex',
          flexDirection: 'column',
          gap: '1.5rem',
          fontFamily: 'var(--font-secondary)'
        }}>
          {navItems.map((item, idx) => (
            <Link
              key={idx}
              to={item.url}
              style={{
                fontSize: '1.1rem',
                fontWeight: 600,
                color: location.pathname === item.url ? 'var(--color-gold)' : 'var(--color-navy)',
                borderBottom: '1px solid var(--color-border)',
                paddingBottom: '0.75rem'
              }}
            >
              {item.label}
            </Link>
          ))}
          <Link 
            to="/contact" 
            className="btn" 
            style={{ 
              padding: '0.8rem', 
              textAlign: 'center', 
              marginTop: '1rem',
              backgroundColor: 'var(--color-navy)',
              color: '#FFFFFF',
              fontWeight: 600
            }}
          >
            Enquire Now
          </Link>
        </div>
      )}

      <style>{`
        @media (max-width: 900px) {
          .desktop-nav {
            display: none !important;
          }
          .mobile-trigger {
            display: block !important;
          }
        }
      `}</style>
    </>
  );
}
