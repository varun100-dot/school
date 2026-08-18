import React, { useState, useEffect } from 'react';
import { getAboutData } from '../services/api';
import SEO from '../components/SEO';
import { Award, Compass, Eye, Target, Users } from 'lucide-react';
import { Link } from 'react-router-dom';

export default function About() {
  const [aboutData, setAboutData] = useState({ sections: [], timeline: [], leadership: [] });
  const [isLoading, setIsLoading] = useState(true);

  useEffect(() => {
    getAboutData().then(data => {
      setAboutData(data);
      setIsLoading(false);
    }).catch(() => {
      setIsLoading(false);
    });
  }, []);

  const sections = aboutData?.sections || [];
  const storySec = sections.find(s => s.section_key === 'our_story') || {};
  const visionSec = sections.find(s => s.section_key === 'vision_mission') || {};

  return (
    <div>
      <SEO 
        title="About Us" 
        description="Learn about the founders, vision, mission, and the child-centered educational story of Zuvio Global School."
      />

      {/* Hero Banner */}
      <section style={{
        backgroundImage: 'linear-gradient(rgba(15, 23, 42, 0.8), rgba(15, 23, 42, 0.85)), url("/assets/images/Hero image 2.png")',
        backgroundSize: 'cover',
        backgroundPosition: 'center',
        color: '#FFFFFF',
        padding: '6rem 2rem',
        textAlign: 'center',
        fontFamily: 'var(--font-secondary)'
      }}>
        <div style={{ maxWidth: '800px', margin: '0 auto' }}>
          <span style={{ fontSize: '0.85rem', fontWeight: 600, color: 'var(--color-gold)', textTransform: 'uppercase', letterSpacing: '2px', display: 'block', marginBottom: '1rem' }}>
            Who We Are
          </span>
          <h1 style={{ fontSize: '3rem', fontFamily: 'var(--font-primary)', color: '#FFFFFF', marginBottom: '1.25rem' }}>
            About Zuvio Global School
          </h1>
          <p style={{ fontSize: '1.1rem', color: '#E2E8F0', fontWeight: 300, lineHeight: '1.6' }}>
            Reimagining education for a world without boundaries—where every child has the freedom to learn, explore, and grow.
          </p>
        </div>
      </section>

      {/* Section 1: Zuvio story */}
      <section className="section" style={{ backgroundColor: '#FFFFFF' }}>
        <div className="container">
          <div className="grid-2" style={{ alignItems: 'center', gap: '4rem' }}>
            <div>
              <span style={{ fontSize: '0.85rem', fontWeight: 600, color: 'var(--color-gold)', textTransform: 'uppercase', letterSpacing: '1.5px', display: 'block', marginBottom: '0.5rem' }}>
                {storySec.subtitle || 'How Zuvio Began'}
              </span>
              <h2 style={{ fontSize: '2.5rem', fontFamily: 'var(--font-primary)', color: 'var(--color-navy)', marginBottom: '1.5rem', lineHeight: '1.3' }}>
                {storySec.title || 'Our Story'}
              </h2>
              <p style={{ color: 'var(--color-text)', fontSize: '1.05rem', lineHeight: '1.8', whiteSpace: 'pre-line' }}>
                {storySec.content || 'Zuvio Global School began from a question: “What if education was designed for the child instead of expecting the child to fit the system?” As technology, AI and global connectivity changed the world, education also needed to evolve. Zuvio was conceived to provide flexible, personalised, globally connected learning that nurtures creativity, critical thinking, communication and adaptability.'}
              </p>
            </div>
            <div style={{
              borderRadius: 'var(--radius-lg)',
              overflow: 'hidden',
              boxShadow: 'var(--shadow-md)',
              backgroundColor: 'var(--color-navy)',
              height: '350px'
            }}>
              <img src="/assets/images/Hero image 2.png" alt="Students studying online" style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
            </div>
          </div>
        </div>
      </section>

      {/* Section 2: Vision & Mission */}
      <section className="section" style={{ backgroundColor: 'var(--color-bg)' }}>
        <div className="container">
          <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '3rem' }} className="grid-2">
            
            {/* Vision */}
            <div style={{
              backgroundColor: '#FFFFFF',
              padding: '3rem 2.5rem',
              borderRadius: 'var(--radius-md)',
              boxShadow: 'var(--shadow-sm)',
              borderTop: '4px solid var(--color-gold)'
            }}>
              <div style={{ display: 'flex', alignItems: 'center', gap: '0.75rem', marginBottom: '1.5rem' }}>
                <Eye size={24} style={{ color: 'var(--color-gold)' }} />
                <h3 style={{ fontSize: '1.5rem', fontFamily: 'var(--font-primary)', color: 'var(--color-navy)', margin: 0 }}>Our Vision</h3>
              </div>
              <p style={{ color: 'var(--color-text)', fontSize: '0.95rem', lineHeight: '1.7' }}>
                To reimagine education for a world without boundaries—where every child has the freedom to learn, explore, grow, and discover their unique potential. We envision a global learning community that combines academic excellence, innovation, technology, creativity, and real-world learning to prepare confident, capable, and compassionate learners for the future.
              </p>
            </div>

            {/* Mission */}
            <div style={{
              backgroundColor: '#FFFFFF',
              padding: '3rem 2.5rem',
              borderRadius: 'var(--radius-md)',
              boxShadow: 'var(--shadow-sm)',
              borderTop: '4px solid var(--color-gold)'
            }}>
              <div style={{ display: 'flex', alignItems: 'center', gap: '0.75rem', marginBottom: '1.5rem' }}>
                <Target size={24} style={{ color: 'var(--color-gold)' }} />
                <h3 style={{ fontSize: '1.5rem', fontFamily: 'var(--font-primary)', color: 'var(--color-navy)', margin: 0 }}>Our Mission</h3>
              </div>
              <p style={{ color: 'var(--color-text)', fontSize: '0.95rem', lineHeight: '1.7' }}>
                At Zuvio Global School, our mission is to create a meaningful learning experience that nurtures curiosity, builds strong academic foundations, develops essential life skills, and empowers every child to grow with confidence and purpose. We strive to prepare learners not only for academic success, but for a changing world—helping them become capable, compassionate, and confident individuals.
              </p>
            </div>

          </div>
        </div>
      </section>

      {/* Section 3: Educational Philosophy / Zuvio Compass */}
      <section className="section" style={{ backgroundColor: '#FFFFFF' }}>
        <div className="container">
          <div style={{ maxWidth: '800px', margin: '0 auto', textAlign: 'center', marginBottom: '4rem' }}>
            <span style={{ fontSize: '0.85rem', fontWeight: 600, color: 'var(--color-gold)', textTransform: 'uppercase', letterSpacing: '1.5px', display: 'block', marginBottom: '0.5rem' }}>Philosophy</span>
            <h2 style={{ fontSize: '2.5rem', fontFamily: 'var(--font-primary)', color: 'var(--color-navy)', marginBottom: '1rem' }}>
              The Zuvio Compass™ & 8C Philosophy™
            </h2>
            <p style={{ color: 'var(--color-muted)', fontSize: '0.95rem' }}>
              “Every Child. Every Mind. Every Possibility.” Our approach is built to unlock minds rather than fill them.
            </p>
          </div>

          <div style={{
            display: 'grid',
            gridTemplateColumns: 'repeat(auto-fit, minmax(220px, 1fr))',
            gap: '1.5rem'
          }}>
            {[
              { title: 'Curiosity', desc: 'The starting point of all learning and discovery.' },
              { title: 'Creativity', desc: 'Thinking beyond boundaries to configure new solutions.' },
              { title: 'Character', desc: 'Developing values, empathy, and personal integrity.' },
              { title: 'Compassion', desc: 'Understanding others and contributing to the community.' },
              { title: 'Confidence', desc: 'Growing with the belief in one\'s own unique potential.' },
              { title: 'Collaboration', desc: 'Working together across international perspective boundaries.' },
              { title: 'Critical Thinking', desc: 'Evaluating information and solving real-world challenges.' },
              { title: 'Communication', desc: 'Expressing ideas clearly and listening with respect.' }
            ].map((item, idx) => (
              <div key={idx} style={{
                backgroundColor: 'var(--color-bg)',
                padding: '2rem 1.5rem',
                borderRadius: 'var(--radius-md)',
                textAlign: 'center',
                border: '1px solid var(--color-border)'
              }}>
                <h4 style={{ fontSize: '1.1rem', color: 'var(--color-navy)', marginBottom: '0.5rem' }}>{item.title}</h4>
                <p style={{ color: 'var(--color-muted)', fontSize: '0.8rem', lineHeight: '1.6' }}>{item.desc}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Section 4: Founders & Leadership (Factual Profile placeholders) */}
      <section className="section" style={{ backgroundColor: 'var(--color-bg)' }}>
        <div className="container">
          <div style={{ maxWidth: '800px', margin: '0 auto', textAlign: 'center', marginBottom: '4rem' }}>
            <span style={{ fontSize: '0.85rem', fontWeight: 600, color: 'var(--color-gold)', textTransform: 'uppercase', letterSpacing: '1.5px', display: 'block', marginBottom: '0.5rem' }}>Our Leadership</span>
            <h2 style={{ fontSize: '2.5rem', fontFamily: 'var(--font-primary)', color: 'var(--color-navy)' }}>
              Founders & Directors
            </h2>
          </div>

          <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(280px, 400px))', gap: '3rem', justifyContent: 'center' }}>
            {(aboutData?.leadership || []).map((person, idx) => (
              <div key={idx} style={{
                backgroundColor: '#FFFFFF',
                borderRadius: 'var(--radius-lg)',
                overflow: 'hidden',
                boxShadow: 'var(--shadow-sm)',
                border: '1px solid var(--color-border)'
              }}>
                {/* Visual Placeholder for portrait */}
                <div style={{
                  height: '240px',
                  backgroundColor: 'var(--color-navy)',
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                  color: '#FFFFFF',
                  flexDirection: 'column',
                  gap: '0.5rem',
                  opacity: 0.95
                }}>
                  <Users size={40} style={{ color: 'var(--color-gold)' }} />
                  <span style={{ fontSize: '0.8rem', letterSpacing: '1.5px', textTransform: 'uppercase', color: 'var(--color-gold)' }}>Portrait Coming Soon</span>
                </div>
                <div style={{ padding: '2rem' }}>
                  <h3 style={{ fontSize: '1.4rem', fontFamily: 'var(--font-primary)', color: 'var(--color-navy)', marginBottom: '0.25rem' }}>{person.name}</h3>
                  <span style={{ display: 'block', fontSize: '0.85rem', fontWeight: 600, color: 'var(--color-gold)', marginBottom: '1.25rem' }}>{person.designation}</span>
                  <p style={{ color: 'var(--color-muted)', fontSize: '0.85rem', lineHeight: '1.6', fontStyle: 'italic' }}>
                    Profile details, bio, and personal messages: <span style={{ color: 'var(--color-gold)', fontWeight: 'bold' }}>Content pending from school.</span>
                  </p>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* CTA Section */}
      <section className="section" style={{ backgroundColor: 'var(--color-navy)', color: '#FFFFFF', textAlign: 'center' }}>
        <div className="container" style={{ maxWidth: '600px' }}>
          <h2 style={{ fontSize: '2.5rem', fontFamily: 'var(--font-primary)', color: '#FFFFFF', marginBottom: '1.5rem' }}>Empower Your Child Today</h2>
          <p style={{ fontSize: '1.05rem', color: '#E2E8F0', marginBottom: '2.5rem', lineHeight: '1.6' }}>
            Get in touch to learn more about our CBSE aligned, child-centered digital education modules.
          </p>
          <Link to="/contact" className="btn btn-primary" style={{ padding: '0.9rem 2.5rem' }}>Enquire Now</Link>
        </div>
      </section>
    </div>
  );
}
