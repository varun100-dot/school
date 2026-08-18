import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { getHeroSlides, getHomepageData, getBlogs } from '../services/api';
import HeroSlider from '../components/HeroSlider';
import SEO from '../components/SEO';
import SafeImage from '../components/SafeImage';
import FloatingBubbles from '../components/FloatingBubbles';
import { ArrowRight, BookOpen, Layers, CheckCircle2, UserCheck, Calendar } from 'lucide-react';

export default function Home() {
  const [slides, setSlides] = useState([]);
  const [homeData, setHomeData] = useState({ sections: [], stats: [], features: [] });
  const [recentBlogs, setRecentBlogs] = useState([]);
  const [isLoading, setIsLoading] = useState(true);

  useEffect(() => {
    Promise.all([
      getHeroSlides(),
      getHomepageData(),
      getBlogs('', 1, 3)
    ]).then(([slidesData, homeDataContent, blogsData]) => {
      setSlides(slidesData);
      setHomeData(homeDataContent);
      // Filter out fallback blogs if they look like empty stubs or just show the real ones
      setRecentBlogs(blogsData || []);
      setIsLoading(false);
    }).catch(() => {
      setIsLoading(false);
    });
  }, []);

  const promiseSection = homeData.sections.find(s => s.section_key === 'brand_promise') || {};

  return (
    <div>
      <SEO 
        title="Learning Beyond Boundaries" 
        description="Zuvio Global School is a future-ready online school where academic excellence meets personalised learning. CBSE & NIOS aligned." 
      />

      {/* Hero Carousel */}
      <HeroSlider slides={slides} />

      {/* Section 2: Split Introduction / Brand Promise */}
      <section className="section" style={{ backgroundColor: '#FFFFFF', borderBottom: '1px solid var(--color-border)' }}>
        <div className="container">
          <div className="grid-2" style={{ alignItems: 'center' }}>
            <div>
              <span style={{ fontSize: '0.85rem', fontWeight: 600, color: 'var(--color-gold)', textTransform: 'uppercase', letterSpacing: '1.5px', display: 'block', marginBottom: '0.5rem' }}>
                {promiseSection.subtitle || 'Brand Promise'}
              </span>
              <h2 style={{ fontSize: '2.5rem', fontFamily: 'var(--font-primary)', color: 'var(--color-navy)', lineHeight: '1.3' }}>
                {promiseSection.title || 'Every child deserves an education that prepares them for life, not just examinations.'}
              </h2>
            </div>
            <div>
              <p style={{ color: 'var(--color-text)', fontSize: '1.1rem', lineHeight: '1.8', marginBottom: '1.5rem' }}>
                {promiseSection.content || 'We are not building another school. We are building a future where every child has the opportunity to learn beyond boundaries.'}
              </p>
              <div style={{ display: 'flex', gap: '1.5rem', alignItems: 'center' }}>
                <Link to="/about" style={{ display: 'flex', alignItems: 'center', gap: '0.5rem', fontWeight: 600, color: 'var(--color-gold)' }}>
                  <span>Read Our Philosophy</span>
                  <ArrowRight size={18} />
                </Link>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Section 3: Why Zuvio (Editorial Asymmetric Layout) */}
      <section className="section" style={{ position: 'relative', backgroundColor: 'var(--color-surface-blue)', borderBottom: '1px solid var(--color-border)', overflow: 'hidden' }}>
        <FloatingBubbles />
        <div className="container" style={{ position: 'relative', zIndex: 2 }}>
          <div style={{
            display: 'grid',
            gridTemplateColumns: '1.1fr 1.9fr',
            gap: '4rem',
            alignItems: 'flex-start'
          }} className="why-zuvio-grid">
            
            {/* Left Column: Big Editorial Statement */}
            <div className="why-zuvio-intro-col" style={{ position: 'sticky', top: '120px' }}>
              <span style={{ fontSize: '0.85rem', fontWeight: 600, color: 'var(--color-gold)', textTransform: 'uppercase', letterSpacing: '2px', display: 'block', marginBottom: '0.5rem' }}>
                Why Zuvio
              </span>
              <h2 style={{ fontSize: '2.8rem', fontFamily: 'var(--font-primary)', color: 'var(--color-navy)', lineHeight: '1.25', marginBottom: '1.5rem' }}>
                A Future-Ready Online School
              </h2>
              <div style={{ width: '60px', height: '3px', backgroundColor: 'var(--color-gold)', marginBottom: '1.5rem' }} />
              <p style={{ color: 'var(--color-muted)', fontSize: '1.05rem', lineHeight: '1.7', marginBottom: '2rem' }}>
                At Zuvio Global School, academic excellence meets personalised learning. We prepare children for a changing world by helping them become capable, compassionate global learners.
              </p>
              <Link to="/about" className="btn btn-outline" style={{ display: 'inline-block' }}>
                Read Our Story
              </Link>
            </div>

            {/* Right Column: Grid of Benefit Blocks */}
            <div style={{
              display: 'grid',
              gridTemplateColumns: '1fr 1fr',
              gap: '1.5rem'
            }} className="why-zuvio-blocks">
              {homeData.features.slice(0, 6).map((feature, idx) => (
                <div key={idx} style={{
                  backgroundColor: '#FFFFFF',
                  padding: '2rem',
                  borderRadius: 'var(--radius-md)',
                  boxShadow: 'var(--shadow-sm)',
                  borderLeft: '4px solid var(--color-gold)',
                  transition: 'transform var(--transition-fast)'
                }}
                onMouseEnter={(e) => e.currentTarget.style.transform = 'translateY(-2px)'}
                onMouseLeave={(e) => e.currentTarget.style.transform = 'translateY(0)'}
                >
                  <h3 style={{ fontSize: '1.15rem', fontFamily: 'var(--font-primary)', color: 'var(--color-navy)', marginBottom: '0.75rem' }}>
                    {feature.title}
                  </h3>
                  <p style={{ color: 'var(--color-muted)', fontSize: '0.85rem', lineHeight: '1.6', margin: 0 }}>
                    {feature.description}
                  </p>
                </div>
              ))}
            </div>

          </div>
        </div>
      </section>

      {/* Section 4: Learning Stages / Curriculum Overview */}
      <section className="section" style={{ backgroundColor: '#FFFFFF' }}>
        <div className="container">
          <div style={{ maxWidth: '800px', margin: '0 auto', textAlign: 'center', marginBottom: '4rem' }}>
            <span style={{ fontSize: '0.85rem', fontWeight: 600, color: 'var(--color-gold)', textTransform: 'uppercase', letterSpacing: '1.5px', display: 'block', marginBottom: '0.5rem' }}>Programs Offered</span>
            <h2 style={{ fontSize: '2.5rem', fontFamily: 'var(--font-primary)', color: 'var(--color-navy)', marginBottom: '1rem' }}>
              Academic Pathways K-8
            </h2>
            <p style={{ color: 'var(--color-muted)' }}>
              CBSE and NIOS aligned curriculum structures designed to facilitate personalised learning pathways for every child.
            </p>
          </div>

          <div className="grid-2" style={{ gap: '3rem' }}>
            <div style={{
              border: '1px solid var(--color-border)',
              borderRadius: 'var(--radius-lg)',
              padding: '3rem 2.5rem',
              backgroundColor: 'var(--color-bg)'
            }}>
              <span style={{ fontSize: '0.8rem', fontWeight: 600, color: 'var(--color-emerald)', textTransform: 'uppercase', letterSpacing: '1px', display: 'block', marginBottom: '0.5rem' }}>Grades K - 5</span>
              <h3 style={{ fontSize: '1.75rem', fontFamily: 'var(--font-primary)', color: 'var(--color-navy)', marginBottom: '1rem' }}>Primary Education</h3>
              <p style={{ color: 'var(--color-muted)', fontSize: '0.95rem', lineHeight: '1.7', marginBottom: '2rem' }}>
                Focusing on building strong core foundations in Mathematics, Science, and Languages, combined with interactive activities.
              </p>
              <Link to="/curriculum" className="btn btn-outline" style={{ width: '100%' }}>Explore Primary Pathway</Link>
            </div>
            <div style={{
              border: '1px solid var(--color-border)',
              borderRadius: 'var(--radius-lg)',
              padding: '3rem 2.5rem',
              backgroundColor: 'var(--color-bg)'
            }}>
              <span style={{ fontSize: '0.8rem', fontWeight: 600, color: 'var(--color-emerald)', textTransform: 'uppercase', letterSpacing: '1px', display: 'block', marginBottom: '0.5rem' }}>Grades 6 - 8</span>
              <h3 style={{ fontSize: '1.75rem', fontFamily: 'var(--font-primary)', color: 'var(--color-navy)', marginBottom: '1rem' }}>Middle School</h3>
              <p style={{ color: 'var(--color-muted)', fontSize: '0.95rem', lineHeight: '1.7', marginBottom: '2rem' }}>
                Developing analytical reasoning, technology exposure, and student leadership opportunities alongside academic pathways.
              </p>
              <Link to="/curriculum" className="btn btn-outline" style={{ width: '100%' }}>Explore Middle Pathway</Link>
            </div>
          </div>
        </div>
      </section>

      {/* Section 5: Interactive Learning Journey (Timeline) */}
      <section className="section" style={{ position: 'relative', backgroundColor: 'var(--color-surface-blue)', borderBottom: '1px solid var(--color-border)', overflow: 'hidden' }}>
        <FloatingBubbles />
        <div className="container" style={{ position: 'relative', zIndex: 2 }}>
          <div className="text-center" style={{ marginBottom: '4rem' }}>
            <span style={{ fontSize: '0.85rem', fontWeight: 600, color: 'var(--color-gold)', textTransform: 'uppercase', letterSpacing: '1.5px', display: 'block', marginBottom: '0.5rem' }}>Process</span>
            <h2 style={{ fontSize: '2.5rem', fontFamily: 'var(--font-primary)', color: 'var(--color-navy)' }}>
              How Learning Works at Zuvio
            </h2>
          </div>

          {/* Desktop Timeline */}
          <div className="desktop-timeline" style={{
            display: 'grid',
            gridTemplateColumns: 'repeat(4, 1fr)',
            gap: '2rem',
            position: 'relative'
          }}>
            <div style={{ position: 'absolute', top: '24px', left: '10%', right: '10%', height: '2px', backgroundColor: 'var(--color-border)', zIndex: 1 }} className="timeline-line"></div>
            {[
              { num: '01', title: 'Personalised Setup', desc: 'Identify individual student pace, strengths, and specific interests.' },
              { num: '02', title: 'Interactive Learning', desc: 'Engage in live digital classroom blocks with world-class teachers.' },
              { num: '03', title: 'Progress Assessment', desc: 'Evaluate application, understanding, and creativity (no memorisation tests).' },
              { num: '04', title: 'Global Exposure', desc: 'Connect with an international learning community and activities.' }
            ].map((step, idx) => (
              <div key={idx} style={{ position: 'relative', zIndex: 2, textAlign: 'center' }}>
                <div style={{
                  width: '50px',
                  height: '50px',
                  borderRadius: '50%',
                  backgroundColor: 'var(--color-navy)',
                  color: 'var(--color-gold)',
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                  fontWeight: 700,
                  fontSize: '1.2rem',
                  margin: '0 auto 1.5rem auto',
                  border: '3px solid #FFFFFF'
                }}>
                  {step.num}
                </div>
                <h3 style={{ fontSize: '1.2rem', color: 'var(--color-navy)', marginBottom: '0.75rem' }}>{step.title}</h3>
                <p style={{ color: 'var(--color-muted)', fontSize: '0.85rem', lineHeight: '1.6', padding: '0 0.5rem' }}>{step.desc}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Section 6: Recent Blog Posts */}
      <section className="section" style={{ backgroundColor: 'var(--color-surface-warm)', borderBottom: '1px solid var(--color-border)' }}>
        <div className="container">
          <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-end', marginBottom: '3rem' }}>
            <div>
              <span style={{ fontSize: '0.85rem', fontWeight: 600, color: 'var(--color-gold)', textTransform: 'uppercase', letterSpacing: '1.5px', display: 'block', marginBottom: '0.5rem' }}>News & Insights</span>
              <h2 style={{ fontSize: '2.5rem', fontFamily: 'var(--font-primary)', color: 'var(--color-navy)', margin: 0 }}>Latest from Zuvio</h2>
            </div>
            <Link to="/blogs" style={{ display: 'flex', alignItems: 'center', gap: '0.5rem', color: 'var(--color-gold)', fontWeight: 600 }}>
              <span>View All Blogs</span>
              <ArrowRight size={16} />
            </Link>
          </div>

          {recentBlogs.length === 0 ? (
            <div style={{ textAlign: 'center', padding: '3rem', border: '1px dashed var(--color-border)', borderRadius: 'var(--radius-md)' }}>
              <p style={{ color: 'var(--color-muted)' }}>No blog posts available currently.</p>
            </div>
          ) : (
            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(300px, 1fr))', gap: '2rem' }}>
              {recentBlogs.map((post, idx) => (
                <article key={idx} style={{
                  border: '1px solid var(--color-border)',
                  borderRadius: 'var(--radius-md)',
                  overflow: 'hidden',
                  backgroundColor: '#FFFFFF',
                  display: 'flex',
                  flexDirection: 'column'
                }}>
                  <div style={{ height: '200px', overflow: 'hidden', backgroundColor: 'var(--color-navy)' }}>
                    <SafeImage src={post.featured_image} alt={post.title} fallbackText="No featured image" />
                  </div>
                  <div style={{ padding: '1.5rem', flexGrow: 1, display: 'flex', flexDirection: 'column' }}>
                    <span style={{ fontSize: '0.75rem', fontWeight: 600, color: 'var(--color-gold)', textTransform: 'uppercase', marginBottom: '0.5rem', display: 'block' }}>{post.category_name}</span>
                    <h3 style={{ fontSize: '1.2rem', fontFamily: 'var(--font-primary)', color: 'var(--color-navy)', marginBottom: '0.75rem', lineHeight: '1.4' }}>
                      <Link to={`/blogs/${post.slug}`} style={{ color: 'inherit' }}>{post.title}</Link>
                    </h3>
                    <p style={{ color: 'var(--color-muted)', fontSize: '0.85rem', lineHeight: '1.6', marginBottom: '1.5rem' }}>{post.excerpt}</p>
                    <div style={{ marginTop: 'auto', borderTop: '1px solid var(--color-border)', paddingTop: '1rem', display: 'flex', justifyContent: 'space-between', alignItems: 'center', fontSize: '0.75rem', color: 'var(--color-muted)' }}>
                      <span>By {post.author}</span>
                      <span style={{ display: 'flex', alignItems: 'center', gap: '0.3rem' }}><Calendar size={12} /> {post.publish_date}</span>
                    </div>
                  </div>
                </article>
              ))}
            </div>
          )}
        </div>
      </section>

      {/* Section 7: Final CTA Conversion Area */}
      <section className="section" style={{
        position: 'relative',
        backgroundColor: 'var(--color-navy-dark)',
        color: '#FFFFFF',
        textAlign: 'center',
        padding: '6rem 2rem',
        overflow: 'hidden'
      }}>
        <FloatingBubbles />
        <div style={{ position: 'relative', zIndex: 2, maxWidth: '700px', margin: '0 auto' }}>
          <h2 style={{ fontSize: '3rem', fontFamily: 'var(--font-primary)', color: '#FFFFFF', marginBottom: '1.5rem' }}>Ready to Learn Beyond Boundaries?</h2>
          <p style={{ fontSize: '1.1rem', color: '#E2E8F0', marginBottom: '2.5rem', lineHeight: '1.7' }}>
            Join Zuvio Global School today. Connect with our academic advisors to configure a personalised pathway tailored specifically for your child.
          </p>
          <div style={{ display: 'flex', gap: '1rem', justifyContent: 'center' }}>
            <Link to="/contact-us" className="btn" style={{ padding: '1rem 2.5rem', backgroundColor: 'var(--color-gold)', color: 'var(--color-navy-dark)', fontWeight: 700 }}>Enquire Now</Link>
            <Link to="/our-curriculum" className="btn btn-outline" style={{ padding: '1rem 2.5rem', color: '#FFFFFF', borderColor: '#FFFFFF' }}>Explore Curriculum</Link>
          </div>
        </div>
      </section>

      <style>{`
        @media (max-width: 900px) {
          .why-zuvio-intro-col {
            position: static !important;
          }
          .why-zuvio-grid {
            grid-template-columns: 1fr !important;
            gap: 2.5rem !important;
          }
          .why-zuvio-blocks {
            grid-template-columns: 1fr !important;
            gap: 1.25rem !important;
          }
        }
        @media (max-width: 768px) {
          .timeline-line {
            display: none !important;
          }
          .desktop-timeline {
            grid-template-columns: 1fr !important;
            gap: 2.5rem !important;
          }
        }
      `}</style>
    </div>
  );
}
