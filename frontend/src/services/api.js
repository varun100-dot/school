const API_BASE_URL = 'http://localhost:5001/api';

// Helper to make fetch calls and catch connection errors
async function fetchAPI(endpoint, options = {}) {
  try {
    const res = await fetch(`${API_BASE_URL}${endpoint}`, options);
    if (!res.ok) {
      const errData = await res.json().catch(() => ({}));
      throw { status: res.status, message: errData.message || errData.error || 'Request failed' };
    }
    return await res.json();
  } catch (err) {
    if (err.status) throw err;
    // Network errors (e.g. backend offline)
    throw { status: 503, message: 'Database connection required or backend server offline.' };
  }
}

// 1. Get Site Settings
export async function getSiteSettings() {
  try {
    return await fetchAPI('/site/settings');
  } catch (err) {
    return {
      school_name: 'Zuvio Global School',
      phone: '7827262956',
      general_email: 'info@zuvioglobalschool.com',
      admissions_email: 'info@zuvioglobalschool.com',
      address: 'B-09, Lower Ground Floor, ITL Twin Tower, Netaji Subhash Place, Pitampura, Delhi - 110034',
      office_timings: '10-7',
      copyright: '© 2026 Zuvio Global School. All rights reserved.',
      social_instagram: 'https://www.instagram.com/thezuvio?igsh=cmUwZGV1YWI3eXc=',
      social_facebook: 'https://www.facebook.com/share/1F77iQS86d/',
      social_linkedin: 'https://www.linkedin.com/company/142914253/admin/dashboard/'
    };
  }
}

// 2. Get Navigation items
export async function getNavigation() {
  try {
    return await fetchAPI('/site/navigation');
  } catch (err) {
    return [
      { label: 'Home', url: '/' },
      { label: 'About Us', url: '/about' },
      { label: 'Our Curriculum', url: '/curriculum' },
      { label: 'Zuvio Beyond', url: '/zuvio-beyond' },
      { label: 'Blogs', url: '/blogs' },
      { label: 'Contact Us', url: '/contact' }
    ];
  }
}

// 3. Get Hero Slides
export async function getHeroSlides() {
  try {
    const data = await fetchAPI('/home');
    return data.slides || [];
  } catch (err) {
    // Local assets fallback using actual 4 supplied images
    return [
      {
        title: 'Learning Beyond Boundaries',
        subtitle: 'Zuvio Global School',
        description: 'A future-ready online school where academic excellence meets personalised learning. We empower children to grow with confidence.',
        image: '/assets/images/Hero image 1.png',
        primary_cta_text: 'Explore Zuvio',
        primary_cta_url: '/curriculum',
        secondary_cta_text: 'Enquire Now',
        secondary_cta_url: '/contact'
      },
      {
        title: 'Academic & Global Partnerships',
        subtitle: 'Oxford & IAO Collaborations',
        description: 'Zuvio Global School collaborates with Oxford and IAO, strengthening our commitment to globally benchmarked learning and international standards.',
        image: '/assets/images/Hero image 2.png',
        primary_cta_text: 'About Us',
        primary_cta_url: '/about',
        secondary_cta_text: 'Enquire Now',
        secondary_cta_url: '/contact'
      },
      {
        title: 'CBSE & NIOS Aligned Curriculum',
        subtitle: 'Future-Ready Education',
        description: 'A well-designed curriculum aligned with CBSE, NEP 2020, and NCF, combining academic rigour with future-ready skills.',
        image: '/assets/images/Students learning in classroom.png',
        primary_cta_text: 'Our Curriculum',
        primary_cta_url: '/curriculum',
        secondary_cta_text: 'Enquire Now',
        secondary_cta_url: '/contact'
      },
      {
        title: 'World-Class Teachers',
        subtitle: 'Personalised Learning Paths',
        description: 'Experienced educators who bring expertise, diverse perspectives, and engaging teaching practices.',
        image: '/assets/images/Teacher interacting with students.png',
        primary_cta_text: 'Read Blog',
        primary_cta_url: '/blogs',
        secondary_cta_text: 'Enquire Now',
        secondary_cta_url: '/contact'
      }
    ];
  }
}

// 4. Get Homepage Sections
export async function getHomepageData() {
  try {
    return await fetchAPI('/home');
  } catch (err) {
    return {
      sections: [
        {
          section_key: 'brand_promise',
          title: 'Every child deserves an education that prepares them for life, not just examinations.',
          subtitle: 'Brand Promise & Beliefs',
          content: 'We are not building another school. We are building a future where every child has the opportunity to learn beyond boundaries.'
        }
      ],
      stats: [
        { label: 'Established', value: '2026' },
        { label: 'Student-Teacher Ratio', value: '15:1' },
        { label: 'Students Enrolled', value: 'Content pending' },
        { label: 'World-Class Educators', value: 'Content pending' }
      ],
      features: [
        { title: 'Global Presence', description: 'A globally connected learning community with an international outlook.' },
        { title: 'International Credibility', description: 'Global standards, perspectives, and learning practices designed for a changing world.' },
        { title: 'World-Class Teachers', description: 'Experienced educators who bring expertise, diverse perspectives, and engaging teaching practices.' },
        { title: 'US-Based Learning Platform', description: 'A powerful, thoughtfully designed US-based LMS that brings learning, collaboration, resources, and progress tracking together.' },
        { title: 'CBSE, NEP & NCF Aligned', description: 'A well-designed curriculum aligned with CBSE, NEP 2020, and NCF, combining academic rigour with future-ready skills.' },
        { title: 'Personalised Learning', description: 'Learning experiences that recognise every child\'s unique pace, strengths, interests, and potential.' },
        { title: 'Inclusive Learning', description: 'A supportive environment with a special focus on special learners, ensuring every child feels included, valued, and empowered.' },
        { title: 'World-Class Learning Experiences', description: 'Beyond academics—with technology, creativity, collaboration, projects, and real-world experiences.' }
      ]
    };
  }
}

// 5. Get About page sections
export async function getAboutData() {
  try {
    return await fetchAPI('/about');
  } catch (err) {
    return {
      sections: [
        {
          section_key: 'our_story',
          title: 'Our Story',
          subtitle: 'How Zuvio Began',
          content: 'Zuvio Global School began from a question: “What if education was designed for the child instead of expecting the child to fit the system?” As technology, AI and global connectivity changed the world, education also needed to evolve. Zuvio was conceived to provide flexible, personalised, globally connected learning that nurtures creativity, critical thinking, communication and adaptability.'
        },
        {
          section_key: 'vision_mission',
          title: 'Vision, Mission & Beliefs',
          subtitle: 'Our Compass',
          content: 'Vision: To create a world where every child can access future-ready, personalised and globally connected learning without boundaries, and to empower every child to become a confident global citizen of tomorrow.\n\nMission: To empower every child to discover their potential and thrive in a changing world by combining academic excellence with creativity, critical thinking, technology and life skills.\n\nEducational Philosophy: “Every Child. Every Mind. Every Possibility.”\n\nCurriculum Alignment: CBSE / NIOS'
        }
      ],
      timeline: [
        { year: '2026', title: 'Foundation', description: 'Zuvio Global School is officially established, introducing the 8C Philosophy™, Zuvio Compass™, and Learning Model™.' }
      ],
      leadership: [
        { name: 'Pragya Jain', designation: 'Co-Founder & Director', bio: 'Content pending', message: 'Content pending' },
        { name: 'Deepak Jain', designation: 'Co-Founder & Director', bio: 'Content pending', message: 'Content pending' }
      ]
    };
  }
}

// 6. Get Curriculum Stages
export async function getCurriculumData() {
  try {
    return await fetchAPI('/curriculum');
  } catch (err) {
    return [
      {
        name: 'Early Years',
        slug: 'early-years',
        description: 'Introduction to fundamental social, cognitive, and physical development steps.',
        items: [
          { title: 'Curiosity and Discovery', description: 'Focus on building exploratory senses and baseline language abilities.' }
        ]
      },
      {
        name: 'Primary School',
        slug: 'primary-school',
        description: 'Core subjects foundational study (Grades 1 to 5).',
        items: [
          { title: 'Core Foundations', description: 'Mathematics, Science, English, and Social Studies aligned with CBSE/NIOS.' }
        ]
      },
      {
        name: 'Middle School',
        slug: 'middle-school',
        description: 'Analytical thinking and specialized modules alignment (Grades 6 to 8).',
        items: [
          { title: 'Analytical Growth', description: 'Critical thinking, advanced science foundations, and initial technology exposure.' },
          { title: 'Extracurricular Activities', description: 'Content pending - detailed grade-wise extracurricular activity lists will follow.' }
        ]
      }
    ];
  }
}

// 7. Get Beyond page details
export async function getBeyondData() {
  try {
    return await fetchAPI('/beyond');
  } catch (err) {
    return {
      sections: [
        {
          section_key: 'intro',
          title: 'Beyond Academics',
          subtitle: 'Holistic Development at Zuvio',
          content: 'Zuvio goes beyond textbooks and examinations, with a focus on curiosity, creativity, critical thinking, communication, collaboration, real-world learning, character and life skills. Technology, innovation, projects, and family partnerships are central themes.'
        },
        {
          section_key: 'activities_placeholder',
          title: 'Our Extracurricular Programs',
          subtitle: 'Sports, Arts & Clubs',
          content: 'Content pending - Specific program descriptions, grades, and schedules for Sports, Music, Dance, Theatre, Visual Arts, Clubs, and Trips will remain draft placeholders until finalized.'
        }
      ],
      gallery: []
    };
  }
}

// 8. Get Blogs List
export async function getBlogs(category = '', page = 1, limit = 10) {
  try {
    let url = `/blogs?page=${page}&limit=${limit}`;
    if (category) url += `&category=${category}`;
    return await fetchAPI(url);
  } catch (err) {
    // Return sample static blog post
    return [
      {
        title: '[Demo Seed] Welcome to Zuvio Global School: Learning Beyond Boundaries',
        slug: 'welcome-to-zuvio-global-school',
        excerpt: 'An introductory post explaining Zuvio\'s vision of a borderless classroom, CBSE-aligned paths, and child-centered systems.',
        content: 'At Zuvio Global School, we believe that education should adapt to the child, rather than the child fitting the system. Launched in 2026, Zuvio represents a new paradigm of digital-first school models. Over the coming weeks, we will explore our 8C Philosophy™, including the development of Curiosity, Creativity, Compassion, and Character alongside academic achievements. Read about our partnerships with IAO and Oxford, and join us on this global learning journey.',
        featured_image: '/assets/images/Hero image 1.png',
        author: 'Zuvio Editorial',
        author_designation: 'Content Writer',
        category_name: 'School News',
        publish_date: '2026-08-18'
      }
    ];
  }
}

// 9. Get Blog Details
export async function getBlogBySlug(slug) {
  try {
    return await fetchAPI(`/blogs/${slug}`);
  } catch (err) {
    if (slug === 'welcome-to-zuvio-global-school') {
      return {
        title: '[Demo Seed] Welcome to Zuvio Global School: Learning Beyond Boundaries',
        slug: 'welcome-to-zuvio-global-school',
        excerpt: 'An introductory post explaining Zuvio\'s vision of a borderless classroom...',
        content: 'At Zuvio Global School, we believe that education should adapt to the child, rather than the child fitting the system. Launched in 2026, Zuvio represents a new paradigm of digital-first school models. Over the coming weeks, we will explore our 8C Philosophy™, including the development of Curiosity, Creativity, Compassion, and Character alongside academic achievements. Read about our partnerships with IAO and Oxford, and join us on this global learning journey.',
        featured_image: '/assets/images/Hero image 1.png',
        author: 'Zuvio Editorial',
        author_designation: 'Content Writer',
        category_name: 'School News',
        publish_date: '2026-08-18'
      };
    }
    throw { status: 404, message: 'Blog post not found' };
  }
}

// 10. Submit Enquiry (Always requires database write operation)
export async function submitEnquiry(data) {
  return await fetchAPI('/enquiries', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(data)
  });
}
