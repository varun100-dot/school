import React, { useState, useEffect } from 'react';
import { getBeyondData } from '../services/api';
import SEO from '../components/SEO';
import { Compass, Music, Shield, Trophy, LayoutGrid, CheckCircle } from 'lucide-react';
import { Link } from 'react-router-dom';

export default function Beyond() {
  const [beyondData, setBeyondData] = useState({ sections: [], gallery: [] });
  const [isLoading, setIsLoading] = useState(true);

  useEffect(() => {
    getBeyondData().then(data => {
      setBeyondData(data);
      setIsLoading(false);
    }).catch(() => {
      setIsLoading(false);
    });
  }, []);

  const introSec = beyondData.sections.find(s => s.section_key === 'intro') || {};
  const placeholderSec = beyondData.sections.find(s => s.section_key === 'activities_placeholder') || {};

  return (
    <div>
      <SEO 
        title="Zuvio Beyond" 
        description="Explore the Zuvio Beyond extracurricular philosophy focusing on character development, sports, arts, and real-world skills."
      />

      {/* Hero Banner */}
      <section style={{
        backgroundImage: 'linear-gradient(rgba(0, 10, 66, 0.8), rgba(0, 10, 66, 0.85)), url("/assets/images/Students learning in classroom.png")',
        backgroundSize: 'cover',
        backgroundPosition: 'center',
        color: '#FFFFFF',
        padding: '6rem 2rem',
        textAlign: 'center',
        fontFamily: 'var(--font-secondary)'
      }}>
        <div style={{ maxWidth: '800px', margin: '0 auto' }}>
          <span style={{ fontSize: '0.85rem', fontWeight: 600, color: 'var(--color-gold)', textTransform: 'uppercase', letterSpacing: '2px', display: 'block', marginBottom: '1rem' }}>
            Holistic Growth
          </span>
          <h1 style={{ fontSize: '3rem', fontFamily: 'var(--font-primary)', color: '#FFFFFF', marginBottom: '1.25rem' }}>
            Zuvio Beyond Academics
          </h1>
          <p style={{ fontSize: '1.1rem', color: '#E2E8F0', fontWeight: 300, lineHeight: '1.6' }}>
            Fostering character, creativity, and physical development alongside world-class academics.
          </p>
        </div>
      </section>

      {/* Section 1: Philosophy Intro */}
      <section className="section" style={{ backgroundColor: '#FFFFFF' }}>
        <div className="container">
          <div className="grid-2" style={{ alignItems: 'center', gap: '4rem' }}>
            <div>
              <span style={{ fontSize: '0.85rem', fontWeight: 600, color: 'var(--color-gold)', textTransform: 'uppercase', letterSpacing: '1.5px', display: 'block', marginBottom: '0.5rem' }}>
                {introSec.subtitle || 'Holistic Development at Zuvio'}
              </span>
              <h2 style={{ fontSize: '2.5rem', fontFamily: 'var(--font-primary)', color: 'var(--color-navy)', marginBottom: '1.5rem', lineHeight: '1.3' }}>
                {introSec.title || 'Beyond Academics'}
              </h2>
              <p style={{ color: 'var(--color-text)', fontSize: '1.05rem', lineHeight: '1.8' }}>
                {introSec.content || 'Zuvio goes beyond textbooks and examinations, with a focus on curiosity, creativity, critical thinking, communication, collaboration, real-world learning, character and life skills. Technology, innovation, projects, and global opportunities are central themes.'}
              </p>
            </div>
            
            {/* Visual Icon Grid representing the pillars */}
            <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '1.5rem' }}>
              {[
                { icon: <Compass size={24} />, title: 'Character', desc: 'Values, empathy, and integrity.' },
                { icon: <Music size={24} />, title: 'Creativity', desc: 'Arts, music, and performance.' },
                { icon: <Trophy size={24} />, title: 'Leadership', desc: 'Taking ownership and solving goals.' },
                { icon: <Shield size={24} />, title: 'Wellness', desc: 'Physical and mental health care.' }
              ].map((item, idx) => (
                <div key={idx} style={{
                  padding: '1.5rem',
                  border: '1px solid var(--color-border)',
                  borderRadius: 'var(--radius-md)',
                  backgroundColor: 'var(--color-bg)'
                }}>
                  <div style={{ color: 'var(--color-gold)', marginBottom: '0.75rem', display: 'flex' }}>{item.icon}</div>
                  <h4 style={{ fontSize: '1.05rem', color: 'var(--color-navy)', marginBottom: '0.25rem' }}>{item.title}</h4>
                  <p style={{ color: 'var(--color-muted)', fontSize: '0.8rem', lineHeight: '1.5' }}>{item.desc}</p>
                </div>
              ))}
            </div>
          </div>
        </div>
      </section>

      {/* Section 2: Program placeholders (Draft content rules) */}
      <section className="section" style={{ backgroundColor: 'var(--color-surface-blue)', borderBottom: '1px solid var(--color-border)' }}>
        <div className="container">
          <div style={{ maxWidth: '800px', margin: '0 auto', textAlign: 'center', marginBottom: '4rem' }}>
            <span style={{ fontSize: '0.85rem', fontWeight: 600, color: 'var(--color-gold)', textTransform: 'uppercase', letterSpacing: '1.5px', display: 'block', marginBottom: '0.5rem' }}>
              {placeholderSec.subtitle || 'Sports, Arts & Clubs'}
            </span>
            <h2 style={{ fontSize: '2.5rem', fontFamily: 'var(--font-primary)', color: 'var(--color-navy)', marginBottom: '1rem' }}>
              {placeholderSec.title || 'Our Extracurricular Programs'}
            </h2>
            <p style={{ color: 'var(--color-muted)', fontSize: '0.95rem' }}>
              Detailed activity-by-activity listings, program names, schedules, and student leaders are currently being finalized.
            </p>
          </div>

          <div style={{
            display: 'grid',
            gridTemplateColumns: 'repeat(auto-fit, minmax(280px, 1fr))',
            gap: '2rem'
          }}>
            {[
              { title: 'Physical Education & Sports', details: 'Extracurricular sports programmes to develop physical health, discipline, and teamwork.' },
              { title: 'Performing & Visual Arts', details: 'Music, dance, theatre, and painting modules to foster creativity and expression.' },
              { title: 'Clubs & Tech Innovation', description: 'Robotics, AI, coding, and environmental clubs encouraging real-world application.' }
            ].map((activity, idx) => (
              <div key={idx} style={{
                backgroundColor: '#FFFFFF',
                borderRadius: 'var(--radius-lg)',
                padding: '2.5rem 2rem',
                border: '1px solid var(--color-border)',
                boxShadow: 'var(--shadow-md)',
                textAlign: 'center'
              }}>
                <div style={{ display: 'inline-flex', padding: '0.75rem', backgroundColor: '#FEF3C7', color: 'var(--color-gold)', borderRadius: '50%', marginBottom: '1.25rem' }}>
                  <LayoutGrid size={24} />
                </div>
                <h3 style={{ fontSize: '1.25rem', fontFamily: 'var(--font-primary)', color: 'var(--color-navy)', marginBottom: '0.75rem' }}>{activity.title}</h3>
                <p style={{ color: 'var(--color-muted)', fontSize: '0.85rem', lineHeight: '1.6', marginBottom: '1.25rem' }}>
                  {activity.details || activity.description}
                </p>
                <span style={{
                  fontSize: '0.8rem',
                  fontWeight: 600,
                  color: 'var(--color-gold)',
                  backgroundColor: '#FFFBEB',
                  padding: '0.3rem 0.8rem',
                  borderRadius: '20px',
                  display: 'inline-block'
                }}>
                  Content pending from school
                </span>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Section 3: Gallery placeholder (empty state) */}
      <section className="section" style={{ backgroundColor: '#FFFFFF' }}>
        <div className="container">
          <div className="text-center" style={{ marginBottom: '4rem' }}>
            <span style={{ fontSize: '0.85rem', fontWeight: 600, color: 'var(--color-gold)', textTransform: 'uppercase', letterSpacing: '1.5px', display: 'block', marginBottom: '0.5rem' }}>Visuals</span>
            <h2 style={{ fontSize: '2.5rem', fontFamily: 'var(--font-primary)', color: 'var(--color-navy)' }}>
              Learner Experience Gallery
            </h2>
          </div>
          
          <div style={{
            border: '2px dashed var(--color-border)',
            borderRadius: 'var(--radius-lg)',
            padding: '5rem 2rem',
            textAlign: 'center',
            backgroundColor: 'var(--color-bg)',
            maxWidth: '600px',
            margin: '0 auto'
          }}>
            <LayoutGrid size={36} style={{ color: 'var(--color-muted)', marginBottom: '1rem', opacity: 0.7 }} />
            <h3 style={{ fontSize: '1.1rem', color: 'var(--color-navy)', marginBottom: '0.5rem' }}>Gallery Assets Pending</h3>
            <p style={{ color: 'var(--color-muted)', fontSize: '0.85rem', maxWidth: '300px', margin: '0 auto' }}>
              Photographs from Sports Day, cultural events, and field visits will populate here when approved.
            </p>
          </div>
        </div>
      </section>

      {/* CTA Section */}
      <section className="section" style={{ backgroundColor: 'var(--color-navy-dark)', color: '#FFFFFF', textAlign: 'center', padding: '6rem 2rem' }}>
        <div className="container" style={{ maxWidth: '600px' }}>
          <h2 style={{ fontSize: '2.5rem', fontFamily: 'var(--font-primary)', color: '#FFFFFF', marginBottom: '1.5rem' }}>Want to Know More?</h2>
          <p style={{ fontSize: '1.05rem', color: '#E2E8F0', marginBottom: '2.5rem', lineHeight: '1.6' }}>
            Connect with our team to discuss program details for each grade stage.
          </p>
          <Link to="/contact-us" className="btn" style={{ padding: '0.9rem 2.5rem', backgroundColor: 'var(--color-gold)', color: 'var(--color-navy-dark)', fontWeight: 700 }}>Enquire Now</Link>
        </div>
      </section>
    </div>
  );
}
