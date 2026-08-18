import React from 'react';
import { BrowserRouter as Router, Routes, Route, Link } from 'react-router-dom';
import HealthCheck from './pages/HealthCheck';
import { ShieldAlert, BookOpen, Layers, Award, FileText, PhoneCall } from 'lucide-react';

function MockPage({ name }) {
  return (
    <div style={{ maxWidth: '800px', margin: '4rem auto', padding: '2rem', textAlign: 'center', fontFamily: 'var(--font-secondary)' }}>
      <h1 style={{ fontFamily: 'var(--font-primary)', fontSize: '2.5rem', marginBottom: '1rem' }}>{name}</h1>
      <p style={{ color: 'var(--color-muted)', fontSize: '1.1rem', marginBottom: '2rem' }}>This is a placeholder page structure for Phase 2.</p>
      <Link to="/" style={{ padding: '0.6rem 1.2rem', backgroundColor: 'var(--color-navy)', color: 'white', borderRadius: '4px' }}>Back to Diagnostics</Link>
    </div>
  );
}

function NavigationHeader() {
  return (
    <header style={{
      backgroundColor: 'var(--color-navy)',
      color: 'var(--color-white)',
      padding: '1rem 2rem',
      display: 'flex',
      justifyContent: 'space-between',
      alignItems: 'center',
      fontFamily: 'var(--font-secondary)'
    }}>
      <div style={{ display: 'flex', alignItems: 'center', gap: '0.5rem' }}>
        <BookOpen style={{ color: 'var(--color-gold)' }} />
        <span style={{ fontWeight: 'bold', fontSize: '1.2rem', fontFamily: 'var(--font-primary)' }}>Zuvio Global School</span>
      </div>
      <nav style={{ display: 'flex', gap: '1.5rem', fontSize: '0.9rem' }}>
        <Link to="/" style={{ hover: 'color: var(--color-gold)' }}>Diagnostics</Link>
        <Link to="/about">About Us</Link>
        <Link to="/curriculum">Curriculum</Link>
        <Link to="/beyond">Zuvio Beyond</Link>
        <Link to="/blogs">Blogs</Link>
        <Link to="/contact">Contact</Link>
      </nav>
    </header>
  );
}

function App() {
  return (
    <Router>
      <NavigationHeader />
      <Routes>
        <Route path="/" element={<HealthCheck />} />
        <Route path="/health" element={<HealthCheck />} />
        <Route path="/about" element={<MockPage name="About Us (Phase 2 Placeholder)" />} />
        <Route path="/curriculum" element={<MockPage name="Our Curriculum (Phase 2 Placeholder)" />} />
        <Route path="/beyond" element={<MockPage name="Zuvio Beyond (Phase 2 Placeholder)" />} />
        <Route path="/blogs" element={<MockPage name="Blogs (Phase 2 Placeholder)" />} />
        <Route path="/contact" element={<MockPage name="Contact Us (Phase 2 Placeholder)" />} />
      </Routes>
    </Router>
  );
}

export default App;
