import React, { useState, useEffect } from 'react';
import { Link, useLocation } from 'react-router-dom';
import { Menu, X, BookOpen } from 'lucide-react';
import { getNavigation } from '../services/api';

export default function Header() {
  const [navItems, setNavItems] = useState([]);
  const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);
  const [isScrolled, setIsScrolled] = useState(false);
  const location = useLocation();

  useEffect(() => {
    // Fetch menu options
    getNavigation().then(setNavItems);

    // Scroll listener
    const handleScroll = () => {
      if (window.scrollY > 50) {
        setIsScrolled(true);
      } else {
        setIsScrolled(false);
      }
    };
    window.addEventListener('scroll', handleScroll);
    return () => window.removeEventListener('scroll', handleScroll);
  }, []);

  // Close mobile navigation drawer on page redirects
  useEffect(() => {
    setIsMobileMenuOpen(false);
  }, [location]);

  return (
    <>
      <header style={{
        position: 'sticky',
        top: 0,
        zIndex: 1000,
        backgroundColor: isScrolled ? 'rgba(15, 23, 42, 0.95)' : 'var(--color-navy)',
        backdropFilter: isScrolled ? 'blur(8px)' : 'none',
        color: 'var(--color-white)',
        padding: isScrolled ? '0.75rem 2rem' : '1.25rem 2rem',
        display: 'flex',
        justifyContent: 'space-between',
        alignItems: 'center',
        borderBottom: isScrolled ? '1px solid rgba(226, 232, 240, 0.1)' : 'none',
        transition: 'all var(--transition-normal)',
        fontFamily: 'var(--font-secondary)'
      }}>
        {/* Logo Wordmark */}
        <Link to="/" style={{ display: 'flex', alignItems: 'center', gap: '0.6rem' }}>
          <div style={{ padding: '0.4rem', backgroundColor: 'var(--color-gold)', borderRadius: 'var(--radius-sm)', color: '#FFFFFF', display: 'flex' }}>
            <BookOpen size={20} />
          </div>
          <span style={{ 
            fontWeight: 700, 
            fontSize: '1.25rem', 
            letterSpacing: '0.5px', 
            fontFamily: 'var(--font-primary)' 
          }}>
            ZUVIO
          </span>
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
                  fontWeight: 500,
                  color: isActive ? 'var(--color-gold)' : 'var(--color-white)',
                  opacity: isActive ? 1 : 0.85,
                  transition: 'all var(--transition-fast)',
                  borderBottom: isActive ? '2px solid var(--color-gold)' : '2px solid transparent',
                  paddingBottom: '4px'
                }}
                onMouseEnter={(e) => {
                  if (!isActive) e.target.style.opacity = '1';
                }}
                onMouseLeave={(e) => {
                  if (!isActive) e.target.style.opacity = '0.85';
                }}
              >
                {item.label}
              </Link>
            );
          })}
          
          <Link to="/contact" className="btn btn-primary" style={{ padding: '0.5rem 1.25rem', fontSize: '0.85rem' }}>
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
            color: 'var(--color-white)',
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
          top: '60px',
          left: 0,
          right: 0,
          bottom: 0,
          backgroundColor: 'var(--color-navy)',
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
                fontWeight: 500,
                color: location.pathname === item.url ? 'var(--color-gold)' : 'var(--color-white)',
                borderBottom: '1px solid rgba(255,255,255,0.08)',
                paddingBottom: '0.75rem'
              }}
            >
              {item.label}
            </Link>
          ))}
          <Link to="/contact" className="btn btn-primary" style={{ padding: '0.8rem', textAlign: 'center', marginTop: '1rem' }}>
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
