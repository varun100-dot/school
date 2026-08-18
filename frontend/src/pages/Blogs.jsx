import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { getBlogs } from '../services/api';
import SEO from '../components/SEO';
import { BookOpen, Calendar, ChevronRight } from 'lucide-react';

const CATEGORIES = [
  { name: 'All', slug: '' },
  { name: 'Education', slug: 'education' },
  { name: 'Parenting', slug: 'parenting' },
  { name: 'Student Life', slug: 'student-life' },
  { name: 'Curriculum', slug: 'curriculum' },
  { name: 'School News', slug: 'school-news' },
  { name: 'Events', slug: 'events' },
  { name: 'Achievements', slug: 'achievements' },
  { name: 'Activities', slug: 'activities' },
  { name: 'Career Guidance', slug: 'career-guidance' },
  { name: 'Learning & Development', slug: 'learning-development' }
];

export default function Blogs() {
  const [blogs, setBlogs] = useState([]);
  const [selectedCat, setSelectedCat] = useState('');
  const [isLoading, setIsLoading] = useState(true);

  useEffect(() => {
    setIsLoading(true);
    getBlogs(selectedCat).then(data => {
      setBlogs(data || []);
      setIsLoading(false);
    }).catch(() => {
      setBlogs([]);
      setIsLoading(false);
    });
  }, [selectedCat]);

  return (
    <div>
      <SEO 
        title="Blogs & News" 
        description="Read articles on online education, parenting tips, CBSE curriculums, and announcements from Zuvio Global School."
      />

      {/* Hero Banner */}
      <section style={{
        backgroundColor: 'var(--color-navy)',
        color: '#FFFFFF',
        padding: '5rem 2rem',
        textAlign: 'center',
        fontFamily: 'var(--font-secondary)'
      }}>
        <div style={{ maxWidth: '800px', margin: '0 auto' }}>
          <span style={{ fontSize: '0.85rem', fontWeight: 600, color: 'var(--color-gold)', textTransform: 'uppercase', letterSpacing: '2px', display: 'block', marginBottom: '1rem' }}>
            News & Resources
          </span>
          <h1 style={{ fontSize: '3rem', fontFamily: 'var(--font-primary)', color: '#FFFFFF', marginBottom: '1rem' }}>
            The Zuvio Blog
          </h1>
          <p style={{ fontSize: '1.05rem', color: '#E2E8F0', fontWeight: 300 }}>
            Insights, parenting guides, and learning advice from our academic community.
          </p>
        </div>
      </section>

      {/* Category Tabs */}
      <section style={{ backgroundColor: '#FFFFFF', borderBottom: '1px solid var(--color-border)', padding: '1.5rem 0' }}>
        <div className="container" style={{ overflowX: 'auto', display: 'flex', gap: '0.75rem', whiteSpace: 'nowrap' }} className="cat-scroll">
          {CATEGORIES.map((cat, idx) => {
            const isSelected = selectedCat === cat.slug;
            return (
              <button
                key={idx}
                onClick={() => setSelectedCat(cat.slug)}
                style={{
                  padding: '0.5rem 1.25rem',
                  borderRadius: '20px',
                  border: isSelected ? '1px solid var(--color-gold)' : '1px solid var(--color-border)',
                  backgroundColor: isSelected ? 'var(--color-gold)' : 'transparent',
                  color: isSelected ? '#FFFFFF' : 'var(--color-text)',
                  fontSize: '0.85rem',
                  fontWeight: 500,
                  cursor: 'pointer',
                  transition: 'all var(--transition-fast)'
                }}
              >
                {cat.name}
              </button>
            );
          })}
        </div>
      </section>

      {/* Blogs Grid */}
      <section className="section" style={{ backgroundColor: 'var(--color-bg)' }}>
        <div className="container">
          {isLoading ? (
            <div style={{ textAlign: 'center', padding: '5rem 0' }}>
              <p style={{ color: 'var(--color-muted)' }}>Loading articles...</p>
            </div>
          ) : blogs.length === 0 ? (
            <div style={{
              textAlign: 'center',
              padding: '5rem 2rem',
              backgroundColor: '#FFFFFF',
              borderRadius: 'var(--radius-md)',
              border: '1px solid var(--color-border)',
              maxWidth: '600px',
              margin: '0 auto'
            }}>
              <BookOpen size={48} style={{ color: 'var(--color-muted)', marginBottom: '1rem', opacity: 0.5 }} />
              <h3 style={{ fontSize: '1.25rem', color: 'var(--color-navy)', marginBottom: '0.5rem' }}>No Articles Found</h3>
              <p style={{ color: 'var(--color-muted)', fontSize: '0.9rem' }}>
                There are currently no published articles in this category. Check back soon.
              </p>
            </div>
          ) : (
            <div style={{
              display: 'grid',
              gridTemplateColumns: 'repeat(auto-fit, minmax(320px, 1fr))',
              gap: '2.5rem'
            }}>
              {blogs.map((post, idx) => (
                <article key={idx} style={{
                  backgroundColor: '#FFFFFF',
                  border: '1px solid var(--color-border)',
                  borderRadius: 'var(--radius-md)',
                  overflow: 'hidden',
                  display: 'flex',
                  flexDirection: 'column',
                  boxShadow: 'var(--shadow-sm)',
                  transition: 'transform var(--transition-fast)'
                }}
                onMouseEnter={(e) => e.currentTarget.style.transform = 'translateY(-4px)'}
                onMouseLeave={(e) => e.currentTarget.style.transform = 'translateY(0)'}
                >
                  <div style={{ height: '220px', overflow: 'hidden', backgroundColor: 'var(--color-navy)' }}>
                    {post.featured_image ? (
                      <img src={post.featured_image} alt={post.title} style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
                    ) : (
                      <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'center', height: '100%', color: '#FFFFFF', opacity: 0.3 }}>
                        <BookOpen size={48} />
                      </div>
                    )}
                  </div>

                  <div style={{ padding: '2rem', flexGrow: 1, display: 'flex', flexDirection: 'column' }}>
                    <span style={{ fontSize: '0.75rem', fontWeight: 600, color: 'var(--color-gold)', textTransform: 'uppercase', marginBottom: '0.5rem', display: 'block' }}>
                      {post.category_name || 'General'}
                    </span>
                    <h3 style={{ fontSize: '1.3rem', fontFamily: 'var(--font-primary)', color: 'var(--color-navy)', marginBottom: '0.75rem', lineHeight: '1.4' }}>
                      <Link to={`/blogs/${post.slug}`} style={{ color: 'inherit' }}>{post.title}</Link>
                    </h3>
                    <p style={{ color: 'var(--color-muted)', fontSize: '0.85rem', lineHeight: '1.6', marginBottom: '2rem' }}>
                      {post.excerpt}
                    </p>

                    <div style={{
                      marginTop: 'auto',
                      borderTop: '1px solid var(--color-border)',
                      paddingTop: '1.25rem',
                      display: 'flex',
                      justifyContent: 'space-between',
                      alignItems: 'center',
                      fontSize: '0.75rem',
                      color: 'var(--color-muted)'
                    }}>
                      <span>By {post.author}</span>
                      <span style={{ display: 'flex', alignItems: 'center', gap: '0.3rem' }}>
                        <Calendar size={14} />
                        {post.publish_date}
                      </span>
                    </div>
                  </div>
                </article>
              ))}
            </div>
          )}
        </div>
      </section>

      <style>{`
        .cat-scroll::-webkit-scrollbar {
          display: none;
        }
        .cat-scroll {
          -ms-overflow-style: none;
          scrollbar-width: none;
          padding: 0 2rem;
        }
      `}</style>
    </div>
  );
}
