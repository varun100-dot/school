import React, { useState, useEffect } from 'react';
import { getCurriculumData } from '../services/api';
import SEO from '../components/SEO';
import { HelpCircle, ChevronDown, Check, Layers, RefreshCw } from 'lucide-react';
import { Link } from 'react-router-dom';
import FloatingBubbles from '../components/FloatingBubbles';

export default function Curriculum() {
  const [stages, setStages] = useState([]);
  const [isLoading, setIsLoading] = useState(true);
  const [activeFAQ, setActiveFAQ] = useState(null);

  useEffect(() => {
    getCurriculumData().then(data => {
      setStages(data);
      setIsLoading(false);
    }).catch(() => {
      setIsLoading(false);
    });
  }, []);

  const faqs = [
    { q: 'Is the Zuvio curriculum aligned with major educational boards?', a: 'Yes, Zuvio Global School’s curriculum is aligned with CBSE (Central Board of Secondary Education), NIOS, and is built in accordance with the National Education Policy (NEP 2020) and National Curriculum Framework (NCF).' },
    { q: 'How does online schooling work for younger children (Early Years)?', a: 'Our Early Years programme focuses on short, highly engaging, interactive digital blocks with world-class teachers. We blend technology, play, and sensory discovery elements so screen-time is active and collaborative.' },
    { q: 'How is student progress measured without exam pressure?', a: 'We measure learning not only by what a child remembers for a test, but by what they understand, apply, create, and become. Progress is tracked through projects, ongoing collaborations, and portfolios.' },
    { q: 'What is the student-teacher ratio at Zuvio Global School?', a: 'We maintain a highly personalised learning environment with a student-teacher ratio of 15:1, ensuring every child receives individualised support.' }
  ];

  return (
    <div>
      <SEO 
        title="Our Curriculum" 
        description="Explore CBSE and NIOS aligned online schooling programmes for Grades K-8, built around personalisation and future-ready skills."
      />

      {/* Hero Banner */}
      <section style={{
        backgroundImage: 'linear-gradient(rgba(0, 10, 66, 0.8), rgba(0, 10, 66, 0.85)), url("/assets/images/Hero image 1.png")',
        backgroundSize: 'cover',
        backgroundPosition: 'center',
        color: '#FFFFFF',
        padding: '6rem 2rem',
        textAlign: 'center',
        fontFamily: 'var(--font-secondary)'
      }}>
        <div style={{ maxWidth: '800px', margin: '0 auto' }}>
          <span style={{ fontSize: '0.85rem', fontWeight: 600, color: 'var(--color-gold)', textTransform: 'uppercase', letterSpacing: '2px', display: 'block', marginBottom: '1rem' }}>
            Academic Excellence
          </span>
          <h1 style={{ fontSize: '3rem', fontFamily: 'var(--font-primary)', color: '#FFFFFF', marginBottom: '1.25rem' }}>
            Our Curriculum
          </h1>
          <p style={{ fontSize: '1.1rem', color: '#E2E8F0', fontWeight: 300, lineHeight: '1.6' }}>
            A future-ready K-8 online learning ecosystem aligned with CBSE, NEP 2020, and NCF.
          </p>
        </div>
      </section>

      {/* Section 1: Philosophy & Learning Loop */}
      <section className="section" style={{ backgroundColor: '#FFFFFF' }}>
        <div className="container">
          <div className="grid-2" style={{ alignItems: 'center', gap: '4rem' }}>
            <div>
              <span style={{ fontSize: '0.85rem', fontWeight: 600, color: 'var(--color-gold)', textTransform: 'uppercase', letterSpacing: '1.5px', display: 'block', marginBottom: '0.5rem' }}>Methodology</span>
              <h2 style={{ fontSize: '2.5rem', fontFamily: 'var(--font-primary)', color: 'var(--color-navy)', marginBottom: '1.5rem', lineHeight: '1.3' }}>
                The Zuvio Learning Model™
              </h2>
              <p style={{ color: 'var(--color-text)', fontSize: '1.05rem', lineHeight: '1.8', marginBottom: '1.5rem' }}>
                Our model operates as a continuous learning cycle designed around curiosity, engagement, and real-world application.
              </p>
              <div style={{ display: 'flex', flexDirection: 'column', gap: '0.8rem' }}>
                {[
                  { t: 'Discover', d: 'Igniting curiosity and exploring new concepts.' },
                  { t: 'Understand', d: 'Deep-diving into principles through structured interactive modules.' },
                  { t: 'Apply', d: 'Engaging in real-world projects and application tasks.' },
                  { t: 'Collaborate', d: 'Working with global peers to build communication and teamwork.' },
                  { t: 'Grow & Inspire', d: 'Reflecting on personal development to inspire the next cycle of learning.' }
                ].map((item, idx) => (
                  <div key={idx} style={{ display: 'flex', gap: '0.75rem', alignItems: 'flex-start' }}>
                    <div style={{ display: 'flex', padding: '0.2rem', backgroundColor: '#ECFDF5', borderRadius: '50%', color: 'var(--color-emerald)' }}>
                      <Check size={14} />
                    </div>
                    <span style={{ fontSize: '0.9rem', color: 'var(--color-text)' }}>
                      <strong>{item.t}:</strong> {item.d}
                    </span>
                  </div>
                ))}
              </div>
            </div>
            
            {/* Visual Abstract Diagram */}
            <div style={{
              backgroundColor: 'var(--color-navy)',
              borderRadius: 'var(--radius-lg)',
              padding: '3rem 2.5rem',
              color: '#FFFFFF',
              display: 'flex',
              flexDirection: 'column',
              justifyContent: 'center',
              alignItems: 'center',
              textAlign: 'center',
              minHeight: '350px'
            }}>
              <RefreshCw size={48} style={{ color: 'var(--color-gold)', marginBottom: '1.5rem' }} />
              <h3 style={{ fontSize: '1.5rem', fontFamily: 'var(--font-primary)', color: '#FFFFFF', marginBottom: '0.75rem' }}>Continuous Development Cycle</h3>
              <p style={{ color: '#E2E8F0', fontSize: '0.85rem', lineHeight: '1.6', maxWidth: '300px' }}>
                Blending academic standards with core lifecycle development parameters (Confidence, Creativity, Critical Thinking).
              </p>
            </div>
          </div>
        </div>
      </section>

      {/* Section 2: Curriculum Stages (CMS Ready) */}
      <section className="section" style={{ backgroundColor: 'var(--color-surface-blue)', borderBottom: '1px solid var(--color-border)' }}>
        <div className="container">
          <div className="text-center" style={{ marginBottom: '4rem' }}>
            <span style={{ fontSize: '0.85rem', fontWeight: 600, color: 'var(--color-gold)', textTransform: 'uppercase', letterSpacing: '1.5px', display: 'block', marginBottom: '0.5rem' }}>Programmes</span>
            <h2 style={{ fontSize: '2.5rem', fontFamily: 'var(--font-primary)', color: 'var(--color-navy)' }}>
              Curriculum Stages
            </h2>
          </div>

          <div style={{ display: 'flex', flexDirection: 'column', gap: '3rem' }}>
            {(stages || []).map((stage, idx) => (
              <div key={idx} style={{
                backgroundColor: '#FFFFFF',
                borderRadius: 'var(--radius-lg)',
                padding: '3rem 2.5rem',
                border: '1px solid var(--color-border)',
                boxShadow: 'var(--shadow-sm)'
              }}>
                <div style={{ borderBottom: '2px solid var(--color-gold)', paddingBottom: '1rem', marginBottom: '1.5rem' }}>
                  <h3 style={{ fontSize: '1.75rem', fontFamily: 'var(--font-primary)', color: 'var(--color-navy)', margin: 0 }}>{stage.name}</h3>
                  <p style={{ color: 'var(--color-muted)', fontSize: '0.9rem', marginTop: '0.25rem' }}>{stage.description}</p>
                </div>
                
                <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(250px, 1fr))', gap: '1.5rem' }}>
                  {(stage?.items || []).map((item, itemIdx) => (
                    <div key={itemIdx} style={{
                      backgroundColor: 'var(--color-bg)',
                      padding: '1.5rem',
                      borderRadius: 'var(--radius-md)',
                      border: '1px solid var(--color-border)'
                    }}>
                      <h4 style={{ fontSize: '1.1rem', color: 'var(--color-navy)', marginBottom: '0.5rem' }}>{item.title}</h4>
                      <p style={{ color: 'var(--color-muted)', fontSize: '0.85rem', lineHeight: '1.6' }}>{item.description}</p>
                    </div>
                  ))}
                  
                  {/* CMS Placeholder to handle missing details */}
                  <div style={{
                    border: '2px dashed var(--color-border)',
                    padding: '1.5rem',
                    borderRadius: 'var(--radius-md)',
                    display: 'flex',
                    flexDirection: 'column',
                    justifyContent: 'center',
                    alignItems: 'center',
                    textAlign: 'center',
                    backgroundColor: 'rgba(255, 255, 255, 0.4)'
                  }}>
                    <Layers size={24} style={{ color: 'var(--color-muted)', marginBottom: '0.5rem' }} />
                    <span style={{ fontSize: '0.85rem', fontWeight: 600, color: 'var(--color-muted)' }}>Additional Pathways</span>
                    <span style={{ fontSize: '0.75rem', color: 'var(--color-gold)', marginTop: '0.2rem' }}>Content pending from school</span>
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Section 3: FAQ list */}
      <section className="section" style={{ backgroundColor: 'var(--color-surface-blue)', borderBottom: '1px solid var(--color-border)' }}>
        <div className="container" style={{ maxWidth: '800px' }}>
          <div className="text-center" style={{ marginBottom: '4.5rem' }}>
            <span style={{ fontSize: '0.85rem', fontWeight: 600, color: 'var(--color-gold)', textTransform: 'uppercase', letterSpacing: '1.5px', display: 'block', marginBottom: '0.5rem' }}>FAQ</span>
            <h2 style={{ fontSize: '2.5rem', fontFamily: 'var(--font-primary)', color: 'var(--color-navy)' }}>
              Curriculum Questions
            </h2>
          </div>

          <div style={{ display: 'flex', flexDirection: 'column', gap: '1rem' }}>
            {faqs.map((faq, idx) => {
              const isOpen = activeFAQ === idx;
              return (
                <div key={idx} style={{
                  border: '1px solid var(--color-border)',
                  borderRadius: 'var(--radius-md)',
                  overflow: 'hidden',
                  backgroundColor: 'var(--color-bg)'
                }}>
                  <button
                    onClick={() => setActiveFAQ(isOpen ? null : idx)}
                    style={{
                      width: '100%',
                      padding: '1.25rem 1.5rem',
                      display: 'flex',
                      justifyContent: 'space-between',
                      alignItems: 'center',
                      background: 'none',
                      border: 'none',
                      textAlign: 'left',
                      fontWeight: 600,
                      color: 'var(--color-navy)',
                      fontSize: '0.95rem',
                      cursor: 'pointer'
                    }}
                  >
                    <span style={{ display: 'flex', gap: '0.75rem', alignItems: 'center' }}>
                      <HelpCircle size={18} style={{ color: 'var(--color-gold)' }} />
                      {faq.q}
                    </span>
                    <ChevronDown size={18} style={{ transform: isOpen ? 'rotate(180deg)' : 'rotate(0)', transition: 'transform 0.2s' }} />
                  </button>
                  {isOpen && (
                    <div style={{
                      padding: '0 1.5rem 1.5rem 2.75rem',
                      color: 'var(--color-text)',
                      fontSize: '0.85rem',
                      lineHeight: '1.6'
                    }}>
                      {faq.a}
                    </div>
                  )}
                </div>
              );
            })}
          </div>
        </div>
      </section>

      {/* CTA Section */}
      <section className="section" style={{ position: 'relative', backgroundColor: 'var(--color-navy-dark)', color: '#FFFFFF', textAlign: 'center', padding: '6rem 2rem', overflow: 'hidden' }}>
        <FloatingBubbles />
        <div className="container" style={{ position: 'relative', zIndex: 2, maxWidth: '600px' }}>
          <h2 style={{ fontSize: '2.5rem', fontFamily: 'var(--font-primary)', color: '#FFFFFF', marginBottom: '1.5rem' }}>Interested in Joining Zuvio?</h2>
          <p style={{ fontSize: '1.05rem', color: '#E2E8F0', marginBottom: '2.5rem', lineHeight: '1.6' }}>
            Submit an enquiry to explore options with our academic coordinators.
          </p>
          <Link to="/contact-us" className="btn" style={{ padding: '0.9rem 2.5rem', backgroundColor: 'var(--color-gold)', color: 'var(--color-navy-dark)', fontWeight: 700 }}>Enquire Now</Link>
        </div>
      </section>
    </div>
  );
}
