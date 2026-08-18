import React, { useState } from 'react';
import { submitEnquiry } from '../services/api';
import { Loader2, CheckCircle2, AlertTriangle } from 'lucide-react';

export default function EnquiryForm() {
  const [formData, setFormData] = useState({
    parent_name: '',
    student_name: '',
    grade: '',
    phone: '',
    email: '',
    message: ''
  });

  const [errors, setErrors] = useState({});
  const [status, setStatus] = useState('idle'); // idle, submitting, success, error
  const [errorMessage, setErrorMessage] = useState('');

  const validate = () => {
    const tempErrors = {};
    if (!formData.parent_name.trim()) tempErrors.parent_name = 'Parent name is required';
    if (!formData.student_name.trim()) tempErrors.student_name = 'Student name is required';
    if (!formData.grade) tempErrors.grade = 'Please select a grade';
    
    // Email regex
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!formData.email.trim()) {
      tempErrors.email = 'Email is required';
    } else if (!emailRegex.test(formData.email)) {
      tempErrors.email = 'Please enter a valid email address';
    }

    // Phone validation
    if (!formData.phone.trim()) {
      tempErrors.phone = 'Phone number is required';
    } else if (formData.phone.length < 8) {
      tempErrors.phone = 'Please enter a valid phone number';
    }

    setErrors(tempErrors);
    return Object.keys(tempErrors).length === 0;
  };

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData((prev) => ({ ...prev, [name]: value }));
    // Clear validation error when editing
    if (errors[name]) {
      setErrors((prev) => ({ ...prev, [name]: '' }));
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!validate()) return;

    setStatus('submitting');
    setErrorMessage('');

    try {
      const res = await submitEnquiry(formData);
      // Verify if backend was running in mock mode or database was unavailable
      if (res && res.message && res.message.includes('Mock')) {
        // Mock state counts as DB unavailable for writes
        setStatus('error');
        setErrorMessage('Database connection required. Form could not be persisted.');
      } else {
        setStatus('success');
        setFormData({ parent_name: '', student_name: '', grade: '', phone: '', email: '', message: '' });
      }
    } catch (err) {
      setStatus('error');
      setErrorMessage(err.message || 'Database connection required.');
    }
  };

  if (status === 'success') {
    return (
      <div style={{
        textAlign: 'center',
        padding: '3rem 2rem',
        border: '1px solid var(--color-border)',
        borderRadius: 'var(--radius-md)',
        backgroundColor: '#FFFFFF',
        fontFamily: 'var(--font-secondary)'
      }}>
        <CheckCircle2 size={48} style={{ color: 'var(--color-emerald)', marginBottom: '1.5rem' }} />
        <h3 style={{ fontSize: '1.5rem', marginBottom: '0.75rem', fontFamily: 'var(--font-primary)' }}>Enquiry Received</h3>
        <p style={{ color: 'var(--color-muted)', fontSize: '0.95rem' }}>
          Thank you for contacting Zuvio Global School. Our admissions officer will review your enquiry and get back to you shortly.
        </p>
      </div>
    );
  }

  return (
    <form onSubmit={handleSubmit} style={{
      border: '1px solid var(--color-border)',
      borderRadius: 'var(--radius-md)',
      backgroundColor: 'var(--color-surface-blue)',
      padding: '2.5rem',
      fontFamily: 'var(--font-secondary)',
      display: 'flex',
      flexDirection: 'column',
      gap: '1.25rem',
      boxShadow: 'var(--shadow-sm)'
    }}>
      <h3 style={{ fontSize: '1.4rem', fontFamily: 'var(--font-primary)', color: 'var(--color-navy)', borderBottom: '2px solid var(--color-gold)', paddingBottom: '0.5rem', marginBottom: '0.5rem' }}>Admissions Enquiry</h3>

      {status === 'error' && (
        <div style={{
          backgroundColor: '#FEF2F2',
          borderLeft: '4px solid #EF4444',
          padding: '1rem',
          borderRadius: 'var(--radius-sm)',
          display: 'flex',
          gap: '0.75rem',
          alignItems: 'center',
          color: '#B91C1C',
          fontSize: '0.85rem'
        }}>
          <AlertTriangle size={18} style={{ flexShrink: 0 }} />
          <span>{errorMessage}</span>
        </div>
      )}

      {/* Row 1: Names */}
      <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '1rem' }} className="form-row">
        <div>
          <label style={{ display: 'block', fontSize: '0.85rem', fontWeight: 600, color: 'var(--color-navy)', marginBottom: '0.4rem' }}>Parent Name *</label>
          <input
            type="text"
            name="parent_name"
            value={formData.parent_name}
            onChange={handleChange}
            style={{
              width: '100%',
              padding: '0.7rem',
              border: `1.5px solid ${errors.parent_name ? 'var(--color-error)' : 'var(--color-border)'}`,
              borderRadius: 'var(--radius-sm)',
              outline: 'none',
              fontSize: '0.9rem'
            }}
          />
          {errors.parent_name && <span style={{ color: 'var(--color-error)', fontSize: '0.75rem', marginTop: '0.2rem', display: 'block' }}>{errors.parent_name}</span>}
        </div>
        <div>
          <label style={{ display: 'block', fontSize: '0.85rem', fontWeight: 600, color: 'var(--color-navy)', marginBottom: '0.4rem' }}>Student Name *</label>
          <input
            type="text"
            name="student_name"
            value={formData.student_name}
            onChange={handleChange}
            style={{
              width: '100%',
              padding: '0.7rem',
              border: `1.5px solid ${errors.student_name ? 'var(--color-error)' : 'var(--color-border)'}`,
              borderRadius: 'var(--radius-sm)',
              outline: 'none',
              fontSize: '0.9rem'
            }}
          />
          {errors.parent_name && <span style={{ color: 'var(--color-error)', fontSize: '0.75rem', marginTop: '0.2rem', display: 'block' }}>{errors.student_name}</span>}
        </div>
      </div>

      {/* Row 2: Grade & Email */}
      <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '1rem' }} className="form-row">
        <div>
          <label style={{ display: 'block', fontSize: '0.85rem', fontWeight: 600, color: 'var(--color-navy)', marginBottom: '0.4rem' }}>Grade Applying For *</label>
          <select
            name="grade"
            value={formData.grade}
            onChange={handleChange}
            style={{
              width: '100%',
              padding: '0.7rem',
              border: `1.5px solid ${errors.grade ? 'var(--color-error)' : 'var(--color-border)'}`,
              borderRadius: 'var(--radius-sm)',
              backgroundColor: '#FFFFFF',
              outline: 'none',
              fontSize: '0.9rem'
            }}
          >
            <option value="">Select Grade</option>
            <option value="Early Years">Early Years (Pre-K)</option>
            <option value="Kindergarten">Kindergarten</option>
            <option value="Grade 1">Grade 1</option>
            <option value="Grade 2">Grade 2</option>
            <option value="Grade 3">Grade 3</option>
            <option value="Grade 4">Grade 4</option>
            <option value="Grade 5">Grade 5</option>
            <option value="Grade 6">Grade 6</option>
            <option value="Grade 7">Grade 7</option>
            <option value="Grade 8">Grade 8</option>
          </select>
          {errors.grade && <span style={{ color: 'var(--color-error)', fontSize: '0.75rem', marginTop: '0.2rem', display: 'block' }}>{errors.grade}</span>}
        </div>
        <div>
          <label style={{ display: 'block', fontSize: '0.85rem', fontWeight: 600, color: 'var(--color-navy)', marginBottom: '0.4rem' }}>Email Address *</label>
          <input
            type="email"
            name="email"
            value={formData.email}
            onChange={handleChange}
            style={{
              width: '100%',
              padding: '0.7rem',
              border: `1.5px solid ${errors.email ? 'var(--color-error)' : 'var(--color-border)'}`,
              borderRadius: 'var(--radius-sm)',
              outline: 'none',
              fontSize: '0.9rem'
            }}
          />
          {errors.email && <span style={{ color: 'var(--color-error)', fontSize: '0.75rem', marginTop: '0.2rem', display: 'block' }}>{errors.email}</span>}
        </div>
      </div>

      {/* Row 3: Phone */}
      <div>
        <label style={{ display: 'block', fontSize: '0.85rem', fontWeight: 600, color: 'var(--color-navy)', marginBottom: '0.4rem' }}>Phone Number *</label>
        <input
          type="text"
          name="phone"
          value={formData.phone}
          onChange={handleChange}
          style={{
            width: '100%',
            padding: '0.7rem',
            border: `1.5px solid ${errors.phone ? 'var(--color-error)' : 'var(--color-border)'}`,
            borderRadius: 'var(--radius-sm)',
            outline: 'none',
            fontSize: '0.9rem'
          }}
        />
        {errors.phone && <span style={{ color: 'var(--color-error)', fontSize: '0.75rem', marginTop: '0.2rem', display: 'block' }}>{errors.phone}</span>}
      </div>

      {/* Row 4: Message */}
      <div>
        <label style={{ display: 'block', fontSize: '0.85rem', fontWeight: 600, color: 'var(--color-navy)', marginBottom: '0.4rem' }}>Message / Queries</label>
        <textarea
          name="message"
          value={formData.message}
          onChange={handleChange}
          rows={4}
          style={{
            width: '100%',
            padding: '0.7rem',
            border: '1.5px solid var(--color-border)',
            borderRadius: 'var(--radius-sm)',
            outline: 'none',
            fontSize: '0.9rem',
            resize: 'vertical'
          }}
        />
      </div>

      <button
        type="submit"
        disabled={status === 'submitting'}
        style={{
          width: '100%',
          padding: '0.85rem',
          backgroundColor: 'var(--color-navy)',
          color: '#FFFFFF',
          border: 'none',
          borderRadius: 'var(--radius-sm)',
          fontWeight: 600,
          cursor: 'pointer',
          display: 'flex',
          justifyContent: 'center',
          alignItems: 'center',
          gap: '0.5rem',
          transition: 'background-color 0.2s',
          marginTop: '0.5rem'
        }}
        onMouseEnter={(e) => e.target.style.backgroundColor = 'var(--color-gold)'}
        onMouseLeave={(e) => e.target.style.backgroundColor = 'var(--color-navy)'}
      >
        {status === 'submitting' ? (
          <>
            <Loader2 size={16} className="spin-anim" />
            <span>Submitting...</span>
          </>
        ) : (
          <span>Submit Enquiry</span>
        )}
      </button>

      <style>{`
        @media (max-width: 580px) {
          .form-row {
            grid-template-columns: 1fr !important;
            gap: 1.25rem !important;
          }
        }
      `}</style>
    </form>
  );
}
