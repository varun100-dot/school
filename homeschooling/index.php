<?php
// Zuvio Global School - Homeschooling Landing Page (/homeschooling/)
require_once dirname(__FILE__) . '/../includes/db.php';
require_once dirname(__FILE__) . '/../includes/helper.php';

safe_session_start();
$csrf_token = get_csrf_token();
$feedback = $_SESSION['lead_feedback'] ?? null;
unset($_SESSION['lead_feedback']);
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Homeschooling with Live Teacher Support | Zuvio Global School</title>
<meta name="description" content="Explore homeschooling with Zuvio Global School. Live online classes, a CBSE-mapped curriculum and personalised learning support from Nursery to Grade 8. Book a free demo.">
<link rel="canonical" href="https://zuvioglobalschool.com/homeschooling/">
<style>
:root{--navy:#05364c;--teal:#0098ae;--gold:#ffbf36;--pale:#edf8fa}*{box-sizing:border-box}html{scroll-behavior:smooth;scroll-padding-top:100px}body{margin:0;color:var(--navy);font:16px/1.65 Arial,sans-serif}h1,h2,h3{font-family:Georgia,serif;line-height:1.12;margin:0 0 22px}h1{font-size:clamp(42px,4.5vw,66px);letter-spacing:-2px}h2{font-size:clamp(32px,3vw,44px);letter-spacing:-1px}h3{font-size:25px}p{margin:0 0 22px}a{color:inherit}.wrap{width:min(1200px,90%);margin:auto}.btn{display:inline-flex;align-items:center;justify-content:center;gap:20px;background:var(--gold);border:0;border-radius:50px;padding:14px 25px;font-weight:700;text-decoration:none;color:var(--navy);cursor:pointer;font-size:16px;transition:background 0.2s,opacity 0.2s}.btn:hover{background:#ffd26b}.eyebrow{font-size:12px;letter-spacing:2px;font-weight:bold;text-transform:uppercase;color:var(--teal);margin-bottom:16px}header{background:#fff;position:sticky;top:0;z-index:10;border-bottom:1px solid #eef1f2}nav{height:92px;display:flex;align-items:center;justify-content:space-between;gap:25px}nav img{width:210px}nav .links{display:flex;gap:25px;font-size:14px}nav .links a{text-decoration:none}.hero{background:linear-gradient(115deg,#fff 30%,#eff8f7);padding:58px 0 0}.hero-grid{display:grid;grid-template-columns:1.22fr .8fr .88fr;gap:22px;align-items:center}.hero-copy{padding-bottom:50px}.hero-copy>p{font-size:18px}.hero-photo{height:530px;width:100%;object-fit:cover;object-position:center;border-radius:180px 180px 0 0;align-self:end}.accent{color:var(--teal)}.pills{display:flex;flex-wrap:wrap;gap:8px;margin:25px 0}.pill{font-size:12px;padding:6px 10px;border:1px solid #cee2e5;border-radius:30px;background:#ffffffaa}.form{background:var(--navy);color:white;border-radius:18px;padding:27px 24px;margin-bottom:35px;box-shadow:0 15px 40px #06384e1a}.form h2{font-size:29px;letter-spacing:0}.form p{font-size:13px;color:#d4e8ee}.form label{font-size:12px;display:block;margin-top:10px}.form input,.form select{width:100%;border:1px solid #dce6e9;border-radius:7px;padding:12px;background:#fff;color:#123b4e;font:14px Arial;margin-top:4px}.form .btn{width:100%;margin-top:18px;border-radius:8px;font-size:14px}.form small{display:block;font-size:11px;margin-top:12px;color:#cce0e8}.trust{padding:25px 0;border-bottom:1px solid #e2ecef}.trust .wrap{display:flex;justify-content:space-between;gap:20px;flex-wrap:wrap;font-size:14px}.trust b{color:var(--teal)}section{padding:85px 0}.split{display:grid;grid-template-columns:1fr 1fr;gap:85px;align-items:center}.intro p{color:#466071}.quote{border-left:3px solid var(--gold);padding-left:24px;font-size:24px;font-family:Georgia,serif}.steps{display:grid;grid-template-columns:repeat(3,1fr);gap:22px;margin-top:40px}.step{border-top:1px solid #cce0e5;padding-top:22px}.num{font-size:14px;color:var(--teal);font-weight:bold;display:block;margin-bottom:22px}.step p{font-size:15px;color:#466071}.pale{background:var(--pale)}.section-head{max-width:650px}.benefits{display:grid;grid-template-columns:repeat(4,1fr);gap:25px;margin-top:40px}.benefit{background:white;border-radius:14px;padding:28px}.benefit span{color:var(--teal);font-size:25px}.benefit h3{font-size:23px;margin-top:18px}.benefit p{font-size:14px;margin:0;color:#466071}.dark{background:var(--navy);color:white}.dark .eyebrow{color:#70d7df}.learning-photo{width:100%;border-radius:18px}.check{padding:0;list-style:none}.check li{padding:12px 0;border-bottom:1px solid #ffffff25}.check li:before{content:'✓';color:var(--gold);margin-right:14px}.grades{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-top:36px}.grade{border:1px solid #d7e4e7;border-radius:12px;padding:23px}.grade h3{font-size:24px}.grade p{font-size:14px;color:#466071;margin:0}.faq{max-width:850px;margin:auto}details{border-bottom:1px solid #d8e7eb;padding:20px 0}summary{cursor:pointer;font-weight:bold;font-size:18px}details p{margin:16px 0 0;color:#466071}.cta{background:linear-gradient(120deg,#06364c,#006f84);color:#fff;padding:65px 0}.cta .wrap{display:flex;align-items:center;justify-content:space-between;gap:40px}.cta h2{max-width:650px}.cta p{margin:0;color:#d5edf1}footer{padding:30px 0;background:#032b3c;color:#cce0e6;font-size:13px}footer .wrap{display:flex;justify-content:space-between;gap:20px;flex-wrap:wrap}a:focus-visible,button:focus-visible,summary:focus-visible{outline:3px solid var(--teal);outline-offset:5px}@media(max-width:1000px){.hero-grid{grid-template-columns:1fr 1fr}.hero-photo{display:none}.benefits{grid-template-columns:repeat(2,1fr)}nav .links{display:none}.split{gap:35px}.grades{grid-template-columns:repeat(2,1fr)}}@media(max-width:640px){nav{height:78px}nav img{width:150px}nav .btn{font-size:12px;padding:10px 14px}.hero{padding-top:35px}.hero-grid,.split{grid-template-columns:1fr}.hero-copy{padding-bottom:10px}h1{letter-spacing:-1px}.form{margin-bottom:30px}.steps,.benefits,.grades{grid-template-columns:1fr}section{padding:55px 0}.cta .wrap{display:block}.cta .btn{margin-top:25px}.trust .wrap{display:grid;grid-template-columns:1fr 1fr}.split{gap:30px}}

.hero-grid{grid-template-columns:1.12fr .88fr .9fr}.hero-photo{height:570px;object-position:48% center;border-radius:150px 150px 20px 20px;margin-bottom:30px}.hero-copy{padding-bottom:30px}h1{font-size:clamp(40px,4.1vw,61px)}.trust{background:var(--navy);color:white}.trust b{color:#ffcd60;font-size:19px}.usp-lead{max-width:640px;color:#466071}.usp-grid{display:grid;grid-template-columns:1.2fr 1fr 1fr 1fr;gap:18px;margin-top:35px}.usp{padding:28px 23px;background:#f1f8f9;border-radius:17px}.usp h3{font-size:24px;margin-top:20px}.usp p{font-size:14px;color:#466071}.usp.featured{background:var(--navy);color:white}.usp.featured p{color:#d5e8ed}.usp-icon{font-size:30px;color:var(--teal)}.featured .usp-icon{color:var(--gold)}.tag{font-size:10px;letter-spacing:1px;color:#ffcf64}.usp-note{font-size:11px;color:#c9e1e8;display:block}.story-grid{display:grid;grid-template-columns:1fr 1fr;gap:30px;margin-top:40px}.story{background:#fff;border-radius:20px;overflow:hidden;display:grid;grid-template-columns:1fr 1fr;align-items:center}.story img{width:100%;height:340px;object-fit:cover}.story>div{padding:26px}.story h3{font-size:28px}.story p{font-size:14px;color:#466071;margin-bottom:0}.story .eyebrow{font-size:10px}.activity-pills{display:flex;gap:10px;flex-wrap:wrap;margin:25px 0}.activity-pills span{border:1px solid #bcdde2;background:#eff9fa;border-radius:30px;padding:8px 15px;font-size:14px}.fine{font-size:12px;color:#59717e}.image-stack{position:relative;padding-bottom:30px}.image-stack img{width:100%;height:460px;object-fit:cover;border-radius:120px 20px 20px 20px}.image-caption{position:absolute;bottom:0;left:30px;right:30px;background:var(--gold);padding:20px 25px;border-radius:12px;box-shadow:0 15px 35px #00334415}.image-caption b{font:27px Georgia;display:block}.image-caption span{font-size:13px}.journey-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:30px;margin-top:40px}.journey-grid article{border-top:2px solid #bddde3;padding-top:20px}.journey-grid b{display:inline-flex;background:var(--gold);width:44px;height:44px;align-items:center;justify-content:center;border-radius:50%;margin-bottom:20px}.journey-grid h3{font-size:25px}.journey-grid p{font-size:14px;color:#466071}.learning-photo{height:440px;object-fit:cover}.cta{position:relative}.cta:before{content:'✦';position:absolute;right:36%;top:20px;color:#ffc43d;font-size:55px}
@media(max-width:1050px){.usp-grid{grid-template-columns:1fr 1fr}.story{display:block}.story img{height:280px}.hero-grid{grid-template-columns:1fr 1fr}}@media(max-width:640px){.hero-grid,.usp-grid,.story-grid,.journey-grid{grid-template-columns:1fr}.hero-photo{display:block;height:310px;grid-row:2;border-radius:20px;width:100%;object-position:center 40%}.story img{height:260px}.image-stack img{height:350px}.learning-photo{height:300px}.cta:before{display:none}.trust b{font-size:16px}}

/* Form Feedback & Interactive Submission Styles */
.form-feedback{margin-top:14px;padding:12px 14px;border-radius:8px;font-size:13px;line-height:1.45;font-weight:500}
.form-feedback.success{background:rgba(16,185,129,0.2);border:1px solid #10b981;color:#a7f3d0}
.form-feedback.error{background:rgba(239,68,68,0.2);border:1px solid #ef4444;color:#fca5a5}
.form .btn:disabled{opacity:0.65;cursor:not-allowed}
</style>
</head>
<body>
<header>
  <nav class="wrap">
    <a href="/" aria-label="Zuvio Global School home"><img src="assets/logo.webp" alt="Zuvio Global School — Learning beyond boundaries"></a>
    <div class="links">
      <a href="#approach">Our approach</a>
      <a href="#why">Why Zuvio</a>
      <a href="#learning">How it works</a>
      <a href="#faq">FAQs</a>
    </div>
    <a class="btn" href="#demo">Book a Free Demo ↗</a>
  </nav>
</header>
<main>
  <div class="hero">
    <div class="wrap hero-grid">
      <div class="hero-copy">
        <div class="eyebrow">Homeschooling • Nursery to Grade 8</div>
        <h1>Homeschooling.<br><span class="accent">With a world<br>of possibilities.</span></h1>
        <p>Homeschooling with the structure of a school and the care your child deserves.</p>
        <p>Live online classes, teacher guidance and room to explore—so you can build a learning journey beyond marks.</p>
        <div class="pills">
          <span class="pill">Live interactive classes</span>
          <span class="pill">CBSE-mapped curriculum</span>
          <span class="pill">Personalised support</span>
        </div>
        <a href="#demo" class="btn">Explore Homeschooling →</a>
      </div>
      <img class="hero-photo" src="assets/home-learning.webp" alt="A child wearing headphones and learning at home">
      <form class="form" id="demo" method="POST" action="submit-lead.php">
        <input type="hidden" name="csrf_token" value="<?php echo h($csrf_token); ?>">
        <h2>Let’s find your child’s way to learn.</h2>
        <p>Book a free demo and talk to our team.</p>
        
        <label for="parent">Parent’s name</label>
        <input id="parent" name="parent_name" autocomplete="name" required placeholder="Your name">
        
        <label for="phone">Phone number</label>
        <input id="phone" name="phone" type="tel" autocomplete="tel" required pattern="[+0-9 ()-]{7,20}" placeholder="Your contact number">
        
        <label for="email">Email address</label>
        <input id="email" name="email" type="email" autocomplete="email" required placeholder="you@example.com">
        
        <label for="grade">Child’s grade</label>
        <select id="grade" name="grade" required>
          <option value="">Select grade</option>
          <option value="Nursery">Nursery</option>
          <option value="LKG">LKG</option>
          <option value="UKG">UKG</option>
          <option value="Grade 1">Grade 1</option>
          <option value="Grade 2">Grade 2</option>
          <option value="Grade 3">Grade 3</option>
          <option value="Grade 4">Grade 4</option>
          <option value="Grade 5">Grade 5</option>
          <option value="Grade 6">Grade 6</option>
          <option value="Grade 7">Grade 7</option>
          <option value="Grade 8">Grade 8</option>
        </select>
        
        <button class="btn" type="submit" id="submit-btn">Book a Free Demo →</button>
        <div id="form-feedback" class="form-feedback<?php echo $feedback ? ' ' . h($feedback['type']) : ''; ?>" style="<?php echo $feedback ? 'display:block;' : 'display:none;'; ?>" role="alert"><?php echo $feedback ? h($feedback['message']) : ''; ?></div>
        <small>Our admissions team will get in touch with you shortly to schedule your free demo.</small>
      </form>
    </div>
  </div>

  <div class="trust">
    <div class="wrap">
      <span><b>CBSE</b><br>Mapped curriculum</span>
      <span><b>Oxford Advantage</b><br>Curriculum resources</span>
      <span><b>IBM Young Learners</b><br>AI & Coding programme</span>
      <span><b>Nursery–Grade 8</b><br>Live online schooling</span>
    </div>
  </div>

  <section id="usps">
    <div class="wrap">
      <div class="eyebrow">The Zuvio advantage</div>
      <h2>Homeschooling freedom.<br><span class="accent">A school’s guidance.</span></h2>
      <p class="usp-lead">An academic foundation, real human connection and future-ready exploration—brought together in one learning journey.</p>
      <div class="usp-grid">
        <article class="usp featured">
          <div class="usp-icon">✦</div>
          <span class="tag">FUTURE-READY LEARNING</span>
          <h3>AI & Coding.<br>Powered by possibility.</h3>
          <p>Explore AI and Coding through the IBM Young Learners Program, alongside creative and practical learning opportunities.</p>
          <span class="usp-note">Ask our team about grade eligibility, fees and certification.</span>
        </article>
        <article class="usp">
          <div class="usp-icon">◎</div>
          <h3>Live classes.<br>Real interaction.</h3>
          <p>Teacher-led lessons and small-group learning create space for questions, participation and connection.</p>
        </article>
        <article class="usp">
          <div class="usp-icon">↗</div>
          <h3>A strong academic<br>foundation.</h3>
          <p>A CBSE-mapped programme with Oxford Advantage curriculum resources, guided activities and projects.</p>
        </article>
        <article class="usp">
          <div class="usp-icon">♡</div>
          <h3>Support that sees<br>the individual.</h3>
          <p>Personalised learning guidance and special education support, with a discussion of your child’s needs before admission.</p>
        </article>
      </div>
    </div>
  </section>

  <section id="approach" class="intro">
    <div class="wrap">
      <div class="split">
        <div>
          <div class="eyebrow">A supported approach to homeschooling</div>
          <h2>You know your child.<br>We help their learning grow.</h2>
        </div>
        <div>
          <p>Choosing homeschooling means thinking carefully about how, where and when your child learns best. Zuvio brings live teacher-led online schooling into your home, with a curriculum, guided activities and ongoing learning support.</p>
          <div class="quote">More curiosity. More confidence.<br>More room to be themselves.</div>
        </div>
      </div>
      <div class="steps">
        <article class="step">
          <span class="num">01 / KNOW</span>
          <h3>Build strong foundations</h3>
          <p>Develop subject knowledge through guided lessons and age-appropriate learning.</p>
        </article>
        <article class="step">
          <span class="num">02 / THINK</span>
          <h3>Go beyond memorising</h3>
          <p>Ask questions, explore ideas and build a deeper understanding of concepts.</p>
        </article>
        <article class="step">
          <span class="num">03 / CREATE</span>
          <h3>Put learning into action</h3>
          <p>Connect ideas through projects, creative expression and practical activities.</p>
        </article>
      </div>
    </div>
  </section>

  <section class="pale" id="why">
    <div class="wrap">
      <div class="section-head">
        <div class="eyebrow">Space to learn. Room to become.</div>
        <h2>For the learner.<br>And everything they love.</h2>
        <p>Support academics while making space for your child’s interests, wellbeing and everyday discoveries.</p>
      </div>
      <div class="story-grid">
        <article class="story">
          <img src="assets/active-learner.webp" alt="Young learner with a basketball, art supplies and notebook at home">
          <div>
            <span class="eyebrow">BEYOND THE CLASSROOM</span>
            <h3>Big interests.<br>Bright possibilities.</h3>
            <p>Explore how live online schooling can fit alongside sports and creative pursuits. Our team will help you understand class timings and attendance.</p>
          </div>
        </article>
        <article class="story">
          <img src="assets/parent-support.webp" alt="Mother supporting her daughter with a creative home-learning activity">
          <div>
            <span class="eyebrow">A PARTNERSHIP WITH PARENTS</span>
            <h3>You’re part of<br>their learning story.</h3>
            <p>Stay connected with educators, support a steady routine and help your child build confidence at home.</p>
          </div>
        </article>
      </div>
    </div>
  </section>

  <section class="dark" id="learning">
    <div class="wrap split">
      <img src="assets/parent-support.webp" class="learning-photo" alt="Mother supporting her daughter during home learning">
      <div>
        <div class="eyebrow">How homeschooling with Zuvio works</div>
        <h2>At home.<br>Connected to a learning community.</h2>
        <ul class="check">
          <li>Attend live, teacher-led online classes</li>
          <li>Learn through a CBSE-mapped, Oxford Advantage curriculum</li>
          <li>Participate in interactive activities and projects</li>
          <li>Receive feedback and learning guidance</li>
          <li>Build independent learning habits at home</li>
        </ul>
        <a href="#demo" class="btn">Meet your learning partner →</a>
      </div>
    </div>
  </section>

  <section class="pale journey">
    <div class="wrap">
      <div class="eyebrow">Your first steps with Zuvio</div>
      <h2>A simple start to a bigger journey.</h2>
      <div class="journey-grid">
        <article>
          <b>01</b>
          <h3>Let’s talk</h3>
          <p>Share your child’s grade, interests and learning needs.</p>
        </article>
        <article>
          <b>02</b>
          <h3>Experience a class</h3>
          <p>Book a free demo and explore the live learning format.</p>
        </article>
        <article>
          <b>03</b>
          <h3>Plan together</h3>
          <p>Discuss curriculum, timetable, fees and suitable support.</p>
        </article>
        <article>
          <b>04</b>
          <h3>Begin with confidence</h3>
          <p>Set up your learning space and join the Zuvio community.</p>
        </article>
      </div>
    </div>
  </section>

  <section>
    <div class="wrap">
      <div class="eyebrow">A journey from foundations to independence</div>
      <h2>Find the right starting point.</h2>
      <div class="grades">
        <article class="grade">
          <h3>Early Years</h3>
          <p>Nursery, LKG & UKG<br>Literacy, numeracy, stories, movement and creative expression.</p>
        </article>
        <article class="grade">
          <h3>Grades 1–2</h3>
          <p>Build confidence in reading, communication and foundational concepts.</p>
        </article>
        <article class="grade">
          <h3>Grades 3–5</h3>
          <p>Explore ideas, strengthen understanding and apply learning creatively.</p>
        </article>
        <article class="grade">
          <h3>Grades 6–8</h3>
          <p>Develop deeper subject understanding and independent learning habits.</p>
        </article>
      </div>
    </div>
  </section>

  <section class="pale" id="faq">
    <div class="wrap faq">
      <div class="eyebrow">Questions parents ask</div>
      <h2>Considering homeschooling?</h2>
      <details open>
        <summary>How does Zuvio support homeschooling families?</summary>
        <p>Zuvio offers a structured online school programme with live classes, curriculum guidance, activities and teacher support. Families provide the home learning environment while our educators guide academic learning.</p>
      </details>
      <details>
        <summary>Do parents need to teach every lesson?</summary>
        <p>Teachers lead the live classes. Parents support routines, attendance and a comfortable learning space. Younger children may need a parent nearby; discuss the expected involvement during your counselling conversation.</p>
      </details>
      <details>
        <summary>Is the curriculum CBSE-mapped?</summary>
        <p>Zuvio describes its programme as CBSE-mapped and uses Oxford Advantage curriculum resources. Curriculum mapping is distinct from board affiliation. Ask our team about the programme, assessment and documentation for your child’s grade.</p>
      </details>
      <details>
        <summary>Can homeschooling work alongside sports or travel?</summary>
        <p>Online access can help families reduce travel to school and organise other pursuits. Live classes follow a timetable, so please discuss your child’s schedule and attendance requirements before enrolling.</p>
      </details>
      <details>
        <summary>How will my child connect with other learners?</summary>
        <p>Live group classes, interactive activities and projects give children opportunities to participate, communicate and learn with peers.</p>
      </details>
      <details>
        <summary>What do we need to get started?</summary>
        <p>A suitable laptop or desktop, a stable internet connection, a quiet learning space and basic stationery. Book a demo to experience the class format and discuss your child’s grade, learning needs, fees and schedule.</p>
      </details>
      <details>
        <summary>What if we plan to return to an offline school?</summary>
        <p>Admission and document requirements vary by receiving school. Before enrolling, check those requirements and discuss available academic records and transition support with Zuvio’s team.</p>
      </details>
    </div>
  </section>

  <section class="cta">
    <div class="wrap">
      <div>
        <div class="eyebrow" style="color:#96e3e9">Their curiosity deserves room to grow</div>
        <h2>Your homeschooling journey starts with a conversation.</h2>
        <p>Tell us about your child. Explore what learning with Zuvio could look like.</p>
      </div>
      <a class="btn" href="#demo">Book a Free Demo →</a>
    </div>
  </section>
</main>

<footer>
  <div class="wrap">
    <span>© <span id="year"></span> Zuvio Global School · Learning beyond boundaries</span>
    <a href="tel:+917827262956">+91 7827262956</a>
    <a href="mailto:info@zuvioglobalschool.com">info@zuvioglobalschool.com</a>
  </div>
</footer>

<script>
document.getElementById('year').textContent = new Date().getFullYear();

document.getElementById('demo').addEventListener('submit', async function(e) {
  e.preventDefault();
  const form = this;
  const btn = document.getElementById('submit-btn');
  const feedback = document.getElementById('form-feedback');
  
  feedback.style.display = 'none';
  feedback.className = 'form-feedback';
  feedback.textContent = '';
  
  const parent = form.querySelector('[name="parent_name"]').value.trim();
  const phone = form.querySelector('[name="phone"]').value.trim();
  const email = form.querySelector('[name="email"]').value.trim();
  const grade = form.querySelector('[name="grade"]').value.trim();
  
  if (!parent || !phone || !email || !grade) {
    feedback.className = 'form-feedback error';
    feedback.textContent = 'All four fields are required: Parent Name, Phone Number, Email Address, and Child’s Grade.';
    feedback.style.display = 'block';
    return;
  }
  
  const originalText = btn.innerHTML;
  btn.disabled = true;
  btn.textContent = 'Submitting...';
  
  try {
    const formData = new FormData(form);
    const response = await fetch(form.action || 'submit-lead.php', {
      method: 'POST',
      body: formData,
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      }
    });
    
    const result = await response.json().catch(() => null);
    
    if (response.ok && result && result.status === 'success') {
      feedback.className = 'form-feedback success';
      feedback.textContent = result.message || 'Thank you! Your demo request has been received.';
      feedback.style.display = 'block';
      form.reset();
      btn.textContent = 'Demo Requested ✓';
    } else {
      btn.disabled = false;
      btn.innerHTML = originalText;
      feedback.className = 'form-feedback error';
      feedback.textContent = (result && result.message) ? result.message : 'Submission failed. Please check your details and try again.';
      feedback.style.display = 'block';
    }
  } catch (err) {
    btn.disabled = false;
    btn.innerHTML = originalText;
    feedback.className = 'form-feedback error';
    feedback.textContent = 'A network error occurred. Please try again.';
    feedback.style.display = 'block';
  }
});
</script>
</body>
</html>
