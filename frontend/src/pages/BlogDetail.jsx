import React, { useState, useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';
import { getBlogBySlug } from '../services/api';
import SEO from '../components/SEO';
import SafeImage from '../components/SafeImage';
import { Calendar, User, ArrowLeft, BookOpen } from 'lucide-react';

export default function BlogDetail() {
  const { slug } = useParams();
  const [post, setPost] = useState(null);
  const [error, setError] = useState(null);
  const [isLoading, setIsLoading] = useState(true);

  useEffect(() => {
    setIsLoading(true);
    setError(null);
    getBlogBySlug(slug).then(data => {
      setPost(data);
      setIsLoading(false);
    }).catch(err => {
      setError(err.message || 'Article not found');
      setIsLoading(false);
    });
  }, [slug]);

  if (isLoading) {
    return (
      <div style={{ textAlign: 'center', padding: '10rem 0', fontFamily: 'var(--font-secondary)' }}>
        <p style={{ color: 'var(--color-muted)' }}>Loading article content...</p>
      </div>
    );
  }

  if (error || !post) {
    return (
      <div style={{
        maxWidth: '600px',
        margin: '6rem auto',
        padding: '3rem 2rem',
        textAlign: 'center',
        border: '1px solid var(--color-border)',
        borderRadius: 'var(--radius-md)',
        backgroundColor: '#FFFFFF',
        fontFamily: 'var(--font-secondary)'
      }}>
        <h3 style={{ fontSize: '1.5rem', fontFamily: 'var(--font-primary)', color: 'var(--color-navy)', marginBottom: '1rem' }}>Article Not Found</h3>
        <p style={{ color: 'var(--color-muted)', marginBottom: '2rem' }}>{error || 'This article might have been archived or removed.'}</p>
        <Link to="/blogs" className="btn btn-primary">Back to Blogs</Link>
      </div>
    );
  }

  return (
    <article style={{ backgroundColor: 'var(--color-bg)', minHeight: '80vh', fontFamily: 'var(--font-secondary)', paddingBottom: '5rem' }}>
      <SEO 
        title={post.title} 
        description={post.excerpt} 
        ogImage={post.featured_image}
      />

      {/* Hero Banner header */}
      <header style={{
        backgroundColor: 'var(--color-navy)',
        color: '#FFFFFF',
        padding: '4rem 2rem',
        borderBottom: '4px solid var(--color-gold)'
      }}>
        <div style={{ maxWidth: '800px', margin: '0 auto' }}>
          <Link to="/blogs" style={{ display: 'inline-flex', alignItems: 'center', gap: '0.5rem', color: 'var(--color-gold)', fontSize: '0.85rem', fontWeight: 600, marginBottom: '1.5rem' }}>
            <ArrowLeft size={16} />
            Back to Blogs
          </Link>
          <span style={{ fontSize: '0.8rem', fontWeight: 600, color: 'var(--color-gold)', textTransform: 'uppercase', letterSpacing: '1.5px', display: 'block', marginBottom: '0.5rem' }}>
            {post.category_name || 'School News'}
          </span>
          <h1 style={{
            fontSize: '3rem',
            fontFamily: 'var(--font-primary)',
            lineHeight: '1.2',
            color: '#FFFFFF',
            marginBottom: '1.5rem'
          }}>
            {post.title}
          </h1>

          <div style={{ display: 'flex', gap: '2rem', fontSize: '0.85rem', color: '#E2E8F0', flexWrap: 'wrap' }}>
            <span style={{ display: 'flex', alignItems: 'center', gap: '0.4rem' }}>
              <User size={16} style={{ color: 'var(--color-gold)' }} />
              {post.author} ({post.author_designation})
            </span>
            <span style={{ display: 'flex', alignItems: 'center', gap: '0.4rem' }}>
              <Calendar size={16} style={{ color: 'var(--color-gold)' }} />
              {post.publish_date}
            </span>
          </div>
        </div>
      </header>

      {/* Main Content Area */}
      <div style={{ maxWidth: '800px', margin: '3rem auto 0 auto', padding: '0 2rem' }}>
        
        {/* Featured Image */}
        {post.featured_image && (
          <div style={{
            borderRadius: 'var(--radius-lg)',
            overflow: 'hidden',
            maxHeight: '450px',
            height: '400px',
            backgroundColor: '#000A42',
            marginBottom: '3rem',
            boxShadow: 'var(--shadow-md)'
          }}>
            <SafeImage src={post.featured_image} alt={post.title} fallbackText="No featured image" />
          </div>
        )}

        {/* Content Body */}
        <div style={{
          backgroundColor: '#FFFFFF',
          padding: '3rem 2.5rem',
          borderRadius: 'var(--radius-md)',
          border: '1px solid var(--color-border)',
          boxShadow: 'var(--shadow-sm)'
        }}>
          <p style={{
            fontSize: '1.1rem',
            color: 'var(--color-text)',
            lineHeight: '1.8',
            marginBottom: '2rem',
            fontStyle: 'italic',
            borderLeft: '4px solid var(--color-gold)',
            paddingLeft: '1.25rem'
          }}>
            {post.excerpt}
          </p>

          <div style={{
            fontSize: '1.05rem',
            color: 'var(--color-text)',
            lineHeight: '1.8',
            whiteSpace: 'pre-line'
          }}>
            {post.content}
          </div>
        </div>
      </div>
    </article>
  );
}
