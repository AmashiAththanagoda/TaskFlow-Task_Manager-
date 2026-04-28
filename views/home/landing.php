<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="TaskFlow - The elegant, minimalist task manager built for focused people.">
    <title>TaskFlow - Elegant Task Management</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant:ital,wght@0,300;0,400;0,500;1,300;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --bg:      #141420;
            --surface: #1E1E2E;
            --raised:  #28283C;
            --border:  rgba(255,255,255,0.09);
            --text:    #EEEEF5;
            --muted:   rgba(238,238,245,0.50);
            --dim:     rgba(238,238,245,0.28);
            --accent:  #9D8FF5;
        }
        html { font-family: 'DM Sans', sans-serif; background: var(--bg); color: var(--text); scroll-behavior: smooth; }
        body { line-height: 1.6; -webkit-font-smoothing: antialiased; overflow-x: hidden; }
        h1, h2, h3 { font-family: 'Cormorant', Georgia, serif; font-weight: 400; letter-spacing: -.01em; }
        a { text-decoration: none; }

        .container { max-width: 1100px; margin: 0 auto; padding: 0 32px; }
        .btn { display: inline-flex; align-items: center; gap: 8px; padding: 12px 28px; border-radius: 10px; font-size: 14px; font-weight: 500; font-family: 'DM Sans', sans-serif; cursor: pointer; border: none; transition: all .25s; }
        .btn-primary { background: var(--accent); color: #fff; }
        .btn-primary:hover { background: #8B7DE8; box-shadow: 0 6px 24px rgba(157,143,245,.4); transform: translateY(-1px); }
        .btn-outline { background: transparent; color: var(--text); border: 1px solid var(--border); }
        .btn-outline:hover { border-color: rgba(157,143,245,.4); background: rgba(157,143,245,.08); color: var(--accent); }

        @keyframes fadeUp { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        .fade-up { animation: fadeUp .6s ease both; }
        .d1 { animation-delay: .1s; } .d2 { animation-delay: .22s; } .d3 { animation-delay: .34s; } .d4 { animation-delay: .46s; }

        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(255,255,255,.1); border-radius: 3px; }

        .nav { position: fixed; top: 0; left: 0; right: 0; z-index: 100; border-bottom: 1px solid transparent; transition: all .3s; }
        .nav.scrolled { background: rgba(20,20,32,.92); backdrop-filter: blur(20px); border-color: var(--border); }
        .nav-inner { display: flex; align-items: center; justify-content: space-between; height: 64px; }
        .logo { display: flex; align-items: center; gap: 10px; }
        .logo-icon { width: 30px; height: 30px; background: var(--accent); border-radius: 8px; display: grid; place-items: center; }
        .logo-text { font-family: 'DM Sans', sans-serif; font-weight: 600; font-size: 16px; color: var(--text); letter-spacing: -.02em; }
        .nav-links { display: flex; align-items: center; gap: 8px; }

        .hero { position: relative; min-height: 100vh; display: flex; align-items: center; overflow: hidden; }
        .hero-bg { position: absolute; inset: 0; }
        .hero-bg img { width: 100%; height: 100%; object-fit: cover; object-position: center 30%; opacity: .45; }
        .hero-bg::after { content: ''; position: absolute; inset: 0; background: linear-gradient(160deg, rgba(20,20,32,.96) 0%, rgba(20,20,32,.75) 50%, rgba(20,20,32,.88) 100%); }
        .hero-content { position: relative; z-index: 1; padding: 120px 0 80px; }
        .hero-eyebrow { font-size: 11px; letter-spacing: .14em; text-transform: uppercase; color: var(--accent); margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
        .hero-eyebrow::before { content: ''; width: 32px; height: 1px; background: var(--accent); }
        .hero h1 { font-size: clamp(52px, 7vw, 88px); line-height: 1.05; color: var(--text); margin-bottom: 24px; }
        .hero h1 em { font-style: italic; color: var(--accent); }
        .hero-sub { font-size: 18px; color: var(--muted); max-width: 480px; line-height: 1.75; margin-bottom: 40px; }
        .hero-actions { display: flex; gap: 12px; flex-wrap: wrap; }
        .hero-scroll { position: absolute; bottom: 40px; left: 50%; transform: translateX(-50%); z-index: 1; display: flex; flex-direction: column; align-items: center; gap: 8px; }
        .hero-scroll span { font-size: 11px; letter-spacing: .1em; text-transform: uppercase; color: var(--dim); }
        .scroll-line { width: 1px; height: 40px; background: linear-gradient(to bottom, var(--accent), transparent); animation: fadeIn 2s ease infinite alternate; }

        .stats { padding: 64px 0; border-bottom: 1px solid var(--border); }
        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 0; }
        .stat { text-align: center; padding: 24px; border-right: 1px solid var(--border); }
        .stat:last-child { border-right: none; }
        .stat-num { font-family: 'Cormorant', serif; font-size: 52px; font-weight: 300; color: var(--text); line-height: 1; }
        .stat-label { font-size: 12px; color: var(--dim); letter-spacing: .06em; text-transform: uppercase; margin-top: 6px; }

        .features { padding: 100px 0; }
        .section-label { font-size: 11px; letter-spacing: .14em; text-transform: uppercase; color: var(--accent); margin-bottom: 16px; }
        .section-title { font-size: clamp(36px, 4vw, 52px); color: var(--text); margin-bottom: 16px; line-height: 1.15; }
        .section-sub { font-size: 16px; color: var(--muted); max-width: 480px; line-height: 1.75; margin-bottom: 60px; }
        .features-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; }
        .feature-card { background: var(--surface); border: 1px solid var(--border); border-radius: 18px; padding: 32px 28px; transition: all .3s; }
        .feature-card:hover { border-color: rgba(157,143,245,.35); background: var(--raised); box-shadow: 0 8px 32px rgba(0,0,0,.3); transform: translateY(-3px); }
        .feature-icon { width: 44px; height: 44px; background: rgba(157,143,245,.12); border: 1px solid rgba(157,143,245,.2); border-radius: 12px; display: grid; place-items: center; margin-bottom: 20px; }
        .feature-icon svg { width: 20px; height: 20px; color: var(--accent); }
        .feature-title { font-family: 'Cormorant', serif; font-size: 22px; color: var(--text); margin-bottom: 10px; }
        .feature-desc { font-size: 14px; color: var(--muted); line-height: 1.7; }

        .showcase { padding: 80px 0 100px; }
        .showcase-inner { display: grid; grid-template-columns: 1fr 1fr; gap: 64px; align-items: center; }
        .showcase-img { position: relative; border-radius: 20px; overflow: hidden; }
        .showcase-img img { width: 100%; height: 420px; object-fit: cover; opacity: .7; }
        .showcase-img::after { content: ''; position: absolute; inset: 0; border: 1px solid var(--border); border-radius: 20px; pointer-events: none; }
        .showcase-steps { display: flex; flex-direction: column; gap: 32px; }
        .step { display: flex; gap: 20px; }
        .step-num { font-family: 'Cormorant', serif; font-size: 40px; font-weight: 300; color: rgba(157,143,245,.3); line-height: 1; flex-shrink: 0; width: 40px; }
        .step-title { font-family: 'Cormorant', serif; font-size: 20px; color: var(--text); margin-bottom: 6px; }
        .step-desc { font-size: 13px; color: var(--muted); line-height: 1.7; }

        .cta-section { padding: 100px 0; border-top: 1px solid var(--border); text-align: center; position: relative; overflow: hidden; }
        .cta-section::before { content: ''; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 600px; height: 600px; background: radial-gradient(circle, rgba(157,143,245,.08) 0%, transparent 70%); pointer-events: none; }
        .cta-section h2 { font-size: clamp(36px, 4.5vw, 60px); color: var(--text); margin-bottom: 16px; }
        .cta-section p { font-size: 16px; color: var(--muted); margin-bottom: 36px; }
        .cta-btns { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }

        .footer { border-top: 1px solid var(--border); padding: 32px 0; }
        .footer-inner { display: flex; align-items: center; justify-content: space-between; }
        .footer-links { display: flex; gap: 24px; }
        .footer-link { font-size: 13px; color: var(--dim); transition: color .2s; }
        .footer-link:hover { color: var(--accent); }
        .footer-copy { font-size: 12px; color: var(--dim); }

        @media (max-width: 1024px) {
            .features-grid { grid-template-columns: repeat(2, 1fr); }
            .showcase-inner { gap: 40px; }
        }

        @media (max-width: 768px) {
            .container { padding: 0 20px; }

            .nav-links .btn-outline { display: none; }
            .nav-links .btn-primary { padding: 8px 16px; font-size: 12px; }

            .hero-content { padding: 100px 0 60px; }
            .hero h1 { font-size: clamp(38px, 10vw, 60px); }
            .hero-sub { font-size: 15px; }
            .hero-scroll { display: none; }

            .stats-grid { grid-template-columns: 1fr; }
            .stat { border-right: none; border-bottom: 1px solid var(--border); }
            .stat:last-child { border-bottom: none; }
            .stat-num { font-size: 40px; }

            .features { padding: 60px 0; }
            .features-grid { grid-template-columns: 1fr; gap: 16px; }
            .section-title { font-size: clamp(28px, 6vw, 40px); }
            .section-sub { font-size: 14px; margin-bottom: 36px; }

            .showcase-inner { grid-template-columns: 1fr; gap: 32px; }
            .showcase-img { display: none; }
            .showcase { padding: 40px 0 60px; }

            .cta-section { padding: 60px 0; }
            .cta-section h2 { font-size: clamp(28px, 6vw, 44px); }
            .cta-section p { font-size: 14px; }
            .cta-btns { flex-direction: column; align-items: center; }
            .cta-btns .btn { width: 100%; justify-content: center; max-width: 300px; }

            .footer-inner { flex-direction: column; gap: 16px; text-align: center; }
            .footer-links { justify-content: center; }
        }

        @media (max-width: 480px) {
            .hero h1 { font-size: 34px; }
            .hero-actions { flex-direction: column; }
            .hero-actions .btn { width: 100%; justify-content: center; }
            .feature-card { padding: 24px 20px; }
        }

    </style>
</head>
<body>

<header class="nav" id="site-nav">
    <div class="container">
        <div class="nav-inner">
            <a href="/" class="logo">
                <div class="logo-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="width:15px;height:15px;color:#fff">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="logo-text">TaskFlow</span>
            </a>
            <div class="nav-links">
                <a href="/login"    class="btn btn-outline" style="padding:9px 20px;font-size:13px">Sign in</a>
                <a href="/register" class="btn btn-primary" style="padding:9px 20px;font-size:13px">Get started</a>
            </div>
        </div>
    </div>
</header>

<section class="hero">
    <div class="hero-bg">
        <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=1920&q=80" alt="">
    </div>
    <div class="container">
        <div class="hero-content">
            <p class="hero-eyebrow fade-up d1">Elegant Task Management</p>
            <h1 class="fade-up d2">Your work,<br><em>beautifully</em><br>organised.</h1>
            <p class="hero-sub fade-up d3">A minimalist task manager designed for people who value clarity, focus, and beautiful software.</p>
            <div class="hero-actions fade-up d4">
                <a href="/register" class="btn btn-primary">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Start for free
                </a>
                <a href="/login" class="btn btn-outline">Sign in</a>
            </div>
        </div>
    </div>
    <div class="hero-scroll">
        <span>Scroll</span>
        <div class="scroll-line"></div>
    </div>
</section>

<section class="stats">
    <div class="container">
        <div class="stats-grid">
            <div class="stat">
                <p class="stat-num">100%</p>
                <p class="stat-label">Free forever</p>
            </div>
            <div class="stat">
                <p class="stat-num">Zero</p>
                <p class="stat-label">Distractions</p>
            </div>
            <div class="stat">
                <p class="stat-num">Real-time</p>
                <p class="stat-label">Status updates</p>
            </div>
        </div>
    </div>
</section>

<section class="features">
    <div class="container">
        <p class="section-label">Why TaskFlow</p>
        <h2 class="section-title">Everything you need.<br>Nothing you don't.</h2>
        <p class="section-sub">TaskFlow strips away the noise and gives you a clear, elegant space to track what matters.</p>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="feature-title">Track everything</h3>
                <p class="feature-desc">Create tasks with titles, descriptions, and due dates. Filter by status. Never lose sight of what needs to be done.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="feature-title">Stay on schedule</h3>
                <p class="feature-desc">Set due dates and get clear overdue indicators. See exactly what is pending, completed, or past its deadline.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <h3 class="feature-title">Instant updates</h3>
                <p class="feature-desc">Toggle task status without page reloads. Powered by AJAX - your workflow stays smooth and uninterrupted.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z"/>
                    </svg>
                </div>
                <h3 class="feature-title">Powerful search</h3>
                <p class="feature-desc">Find any task instantly. Search by title, filter by status, and paginate through large task lists with ease.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <h3 class="feature-title">Secure by design</h3>
                <p class="feature-desc">CSRF protection on every form, bcrypt password hashing, session fixation defence. Your data is yours alone.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
                <h3 class="feature-title">A joy to use</h3>
                <p class="feature-desc">Thoughtful micro-animations, a refined colour palette, and generous whitespace make every interaction feel premium.</p>
            </div>
        </div>
    </div>
</section>

<section class="showcase">
    <div class="container">
        <div class="showcase-inner">
            <div class="showcase-img">
                <img src="https://images.unsplash.com/photo-1484480974693-6ca0a78fb36b?auto=format&fit=crop&w=800&q=80" alt="Notebook and pen on desk">
            </div>
            <div>
                <p class="section-label">How it works</p>
                <h2 class="section-title" style="margin-bottom:40px">Up and running<br>in minutes.</h2>
                <div class="showcase-steps">
                    <div class="step">
                        <span class="step-num">01</span>
                        <div>
                            <h3 class="step-title">Create your account</h3>
                            <p class="step-desc">Register in seconds. No credit card, no trials - TaskFlow is free forever.</p>
                        </div>
                    </div>
                    <div class="step">
                        <span class="step-num">02</span>
                        <div>
                            <h3 class="step-title">Add your first task</h3>
                            <p class="step-desc">Give it a title, an optional description, and a due date. It takes less than 10 seconds.</p>
                        </div>
                    </div>
                    <div class="step">
                        <span class="step-num">03</span>
                        <div>
                            <h3 class="step-title">Mark it complete</h3>
                            <p class="step-desc">Toggle status with one click. Watch your pending list shrink and your confidence grow.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="cta-section">
    <div class="container" style="position:relative;z-index:1">
        <h2>Ready to get organised?</h2>
        <p>Join today and take control of your work - beautifully.</p>
        <div class="cta-btns">
            <a href="/register" class="btn btn-primary" style="padding:14px 32px;font-size:15px">Create free account</a>
            <a href="/login"    class="btn btn-outline" style="padding:14px 32px;font-size:15px">Sign in</a>
        </div>
    </div>
</section>

<footer class="footer">
    <div class="container">
        <div class="footer-inner">
            <a href="/" class="logo">
                <div class="logo-icon" style="width:24px;height:24px;border-radius:6px">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="width:12px;height:12px;color:#fff">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="logo-text" style="font-size:14px">TaskFlow</span>
            </a>
            <nav class="footer-links">
                <a href="/login"    class="footer-link">Sign in</a>
                <a href="/register" class="footer-link">Register</a>
            </nav>
            <p class="footer-copy">Built with Core PHP and MySQL</p>
        </div>
    </div>
</footer>

<script>
const nav = document.getElementById('site-nav');
window.addEventListener('scroll', () => {
    nav.classList.toggle('scrolled', window.scrollY > 40);
}, { passive: true });

const observer = new IntersectionObserver((entries) => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.style.opacity = '1'; e.target.style.transform = 'translateY(0)'; } });
}, { threshold: 0.12 });

document.querySelectorAll('.feature-card, .step, .stat').forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(16px)';
    el.style.transition = 'opacity .5s ease, transform .5s ease';
    observer.observe(el);
});
</script>
</body>
</html>
