import React, { useState, useEffect } from 'react';
import { getSiteSettings } from '../services/api';
import EnquiryForm from '../components/EnquiryForm';
import SEO from '../components/SEO';
import { MapPin, Phone, Mail, Clock, ShieldAlert } from 'lucide-react';

export default function Contact() {
  const [settings, setSettings] = useState({});

  useEffect(() => {
    getSiteSettings().then(setSettings);
  }, []);

  return (
    <div>
      <SEO 
        title="Contact Us" 
        description="Connect with Zuvio Global School admissions office. Find phone numbers, general email, address, and office timings."
      />

      {/* Hero Banner */}
      <section style={{
        backgroundImage: 'linear-gradient(rgba(15, 23, 42, 0.8), rgba(15, 23, 42, 0.85)), url("/assets/images/Teacher interacting with students.png")',
        backgroundSize: 'cover',
        backgroundPosition: 'center',
        color: '#FFFFFF',
        padding: '6rem 2rem',
        textAlign: 'center',
        fontFamily: 'var(--font-secondary)'
      }}>
        <div style={{ maxWidth: '800px', margin: '0 auto' }}>
          <span style={{ fontSize: '0.85rem', fontWeight: 600, color: 'var(--color-gold)', textTransform: 'uppercase', letterSpacing: '2px', display: 'block', marginBottom: '1rem' }}>
            Get In Touch
          </span>
          <h1 style={{ fontSize: '3rem', fontFamily: 'var(--font-primary)', color: '#FFFFFF', marginBottom: '1.25rem' }}>
            Contact Zuvio Global School
          </h1>
          <p style={{ fontSize: '1.1rem', color: '#E2E8F0', fontWeight: 300, lineHeight: '1.6' }}>
            Connect with our admissions office to ask questions, explore curriculum modules, or schedule consultations.
          </p>
        </div>
      </section>

      {/* Main Split Layout */}
      <section className="section" style={{ backgroundColor: 'var(--color-bg)' }}>
        <div className="container">
          <div className="grid-2" style={{ gap: '4rem', alignItems: 'flex-start' }}>
            
            {/* Left Column: Contact Cards */}
            <div style={{ display: 'flex', flexDirection: 'column', gap: '1.5rem', fontFamily: 'var(--font-secondary)' }}>
              <h2 style={{ fontSize: '2rem', fontFamily: 'var(--font-primary)', color: 'var(--color-navy)', marginBottom: '0.5rem' }}>
                Office Contacts
              </h2>
              <p style={{ color: 'var(--color-muted)', fontSize: '0.95rem', marginBottom: '1.5rem' }}>
                You can reach Zuvio Global School through the following contact lines. Feel free to call us or submit the admissions query form.
              </p>

              {/* Address Card */}
              <div style={{
                backgroundColor: '#FFFFFF',
                border: '1px solid var(--color-border)',
                borderRadius: 'var(--radius-md)',
                padding: '1.5rem',
                display: 'flex',
                gap: '1rem',
                boxShadow: 'var(--shadow-sm)'
              }}>
                <MapPin size={24} style={{ color: 'var(--color-gold)', flexShrink: 0 }} />
                <div>
                  <h4 style={{ fontSize: '1.05rem', color: 'var(--color-navy)', marginBottom: '0.4rem' }}>Physical Address</h4>
                  <p style={{ color: 'var(--color-text)', fontSize: '0.9rem', lineHeight: '1.6' }}>{settings.address}</p>
                </div>
              </div>

              {/* Phone Card */}
              <div style={{
                backgroundColor: '#FFFFFF',
                border: '1px solid var(--color-border)',
                borderRadius: 'var(--radius-md)',
                padding: '1.5rem',
                display: 'flex',
                gap: '1rem',
                boxShadow: 'var(--shadow-sm)'
              }}>
                <Phone size={24} style={{ color: 'var(--color-gold)', flexShrink: 0 }} />
                <div>
                  <h4 style={{ fontSize: '1.05rem', color: 'var(--color-navy)', marginBottom: '0.4rem' }}>Phone Lines</h4>
                  <p style={{ color: 'var(--color-text)', fontSize: '0.9rem', lineHeight: '1.6' }}>
                    Main: {settings.phone} <br />
                    WhatsApp: {settings.phone}
                  </p>
                </div>
              </div>

              {/* Email Card */}
              <div style={{
                backgroundColor: '#FFFFFF',
                border: '1px solid var(--color-border)',
                borderRadius: 'var(--radius-md)',
                padding: '1.5rem',
                display: 'flex',
                gap: '1rem',
                boxShadow: 'var(--shadow-sm)'
              }}>
                <Mail size={24} style={{ color: 'var(--color-gold)', flexShrink: 0 }} />
                <div>
                  <h4 style={{ fontSize: '1.05rem', color: 'var(--color-navy)', marginBottom: '0.4rem' }}>Email Support</h4>
                  <p style={{ color: 'var(--color-text)', fontSize: '0.9rem', lineHeight: '1.6' }}>
                    General: {settings.general_email} <br />
                    Admissions: {settings.admissions_email}
                  </p>
                </div>
              </div>

              {/* Office hours Card */}
              <div style={{
                backgroundColor: '#FFFFFF',
                border: '1px solid var(--color-border)',
                borderRadius: 'var(--radius-md)',
                padding: '1.5rem',
                display: 'flex',
                gap: '1rem',
                boxShadow: 'var(--shadow-sm)'
              }}>
                <Clock size={24} style={{ color: 'var(--color-gold)', flexShrink: 0 }} />
                <div>
                  <h4 style={{ fontSize: '1.05rem', color: 'var(--color-navy)', marginBottom: '0.4rem' }}>Office Timings</h4>
                  <p style={{ color: 'var(--color-text)', fontSize: '0.9rem', lineHeight: '1.6' }}>
                    Hours: {settings.office_timings} Daily
                  </p>
                </div>
              </div>

            </div>

            {/* Right Column: Enquiry Form */}
            <div>
              <EnquiryForm />
            </div>

          </div>
        </div>
      </section>

      {/* Google Maps Placeholder Section */}
      <section className="section" style={{ backgroundColor: '#FFFFFF', borderTop: '1px solid var(--color-border)' }}>
        <div className="container">
          <div className="text-center" style={{ marginBottom: '3rem' }}>
            <span style={{ fontSize: '0.85rem', fontWeight: 600, color: 'var(--color-gold)', textTransform: 'uppercase', letterSpacing: '1.5px', display: 'block', marginBottom: '0.5rem' }}>Location Map</span>
            <h2 style={{ fontSize: '2rem', fontFamily: 'var(--font-primary)', color: 'var(--color-navy)' }}>Physical Head Office</h2>
          </div>

          <div style={{
            height: '350px',
            backgroundColor: 'var(--color-bg)',
            border: '2px dashed var(--color-border)',
            borderRadius: 'var(--radius-lg)',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            flexDirection: 'column',
            gap: '1rem',
            textAlign: 'center',
            maxWidth: '800px',
            margin: '0 auto',
            padding: '2rem'
          }}>
            <ShieldAlert size={36} style={{ color: 'var(--color-muted)', opacity: 0.7 }} />
            <h3 style={{ fontSize: '1.1rem', color: 'var(--color-navy)', margin: 0 }}>Google Maps Listing Pending</h3>
            <p style={{ color: 'var(--color-muted)', fontSize: '0.85rem', maxWidth: '350px' }}>
              The official Google Business Profile maps listing URL is pending school verification. Location map widgets will render here upon validation.
            </p>
          </div>
        </div>
      </section>
    </div>
  );
}
