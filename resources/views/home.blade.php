<!DOCTYPE html>
<html lang="en" dir="ltr" id="htmlRoot">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="description" content="AJEER is Jordan's first flexible jobs platform connecting businesses with job seekers for hourly and part-time work.">
  <meta name="keywords" content="jobs in Jordan, part-time jobs, flexible work, hiring platform, AJEER">
  <meta name="author" content="AJEER">

  <title>AJEER - Flexible Jobs Platform in Jordan</title>

  <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" id="bootstrapCSS">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&family=Inter:wght@300;400;500;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/home.css') }}">
</head>
<body>

  <!-- ═══════════════════════════════════════════════ -->
  <!-- Navbar — always visible, no reveal             -->
  <!-- ═══════════════════════════════════════════════ -->
  <nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
      <a class="navbar-brand logo" href="{{ route('home') }}">
        <i class="bi bi-briefcase-fill me-2"></i>AJEER
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0" id="navLinks">
          <li class="nav-item"><a class="nav-link active" href="{{ route('home') }}" data-en="Home" data-ar="الرئيسية">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="#how-it-works" data-en="How It Works" data-ar="كيف يعمل">How It Works</a></li>
          <li class="nav-item"><a class="nav-link" href="#services" data-en="Services" data-ar="الخدمات">Services</a></li>
          <li class="nav-item"><a class="nav-link" href="#features" data-en="Features" data-ar="المميزات">Features</a></li>
        </ul>
        <div class="d-flex gap-2 align-items-center">
          <button class="lang-toggle" onclick="toggleLanguage()" id="langBtn">
            <i class="bi bi-translate"></i>
            <span id="langBtnText">عربي</span>
          </button>
          <a href="{{ route('login') }}" class="btn btn-outline-primary" data-en="Login" data-ar="تسجيل الدخول">Login</a>
          <a href="{{ route('register') }}" class="btn btn-primary" data-en="Get Started Free" data-ar="ابدأ مجاناً">Get Started Free</a>
        </div>
      </div>
    </div>
  </nav>

  <!-- ═══════════════════════════════════════════════ -->
  <!-- Hero — has its own CSS animations, no reveal   -->
  <!-- ═══════════════════════════════════════════════ -->
  <header id="home" class="hero-section">
    <div class="hero-bg-animated"></div>
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6">
          <div class="hero-badge mb-3">
            <i class="bi bi-star-fill text-warning"></i>
            <span data-en="Jordan's #1 Flexible Jobs Platform" data-ar="منصة #1 للوظائف المرنة في الأردن">Jordan's #1 Flexible Jobs Platform</span>
          </div>
          <h1 class="hero-title mb-4">
            <span data-en="Hire the Right Talent." data-ar="وظف الموهبة المناسبة.">Hire the Right Talent.</span>
            <span class="highlight" data-en=" At the Right Time." data-ar=" في الوقت المناسب."> At the Right Time.</span>
          </h1>
          <p class="hero-text mb-4" data-en="AJEER connects businesses with flexible job seekers. Hourly work, part-time jobs, or short-term projects — all in one place." data-ar="منصة AJEER تربط أصحاب الأعمال بالباحثين عن عمل مرن. وظائف بالساعة، توظيف جزئي، أو مشاريع قصيرة المدى - كل ذلك في مكان واحد.">
            AJEER connects businesses with flexible job seekers. Hourly work, part-time jobs, or short-term projects — all in one place.
          </p>

          <div class="hero-stats mb-4">
            <div class="stat-item">
              <div class="stat-number">500+</div>
              <div class="stat-label" data-en="Businesses" data-ar="شركة ومتجر">Businesses</div>
            </div>
            <div class="stat-item">
              <div class="stat-number">2000+</div>
              <div class="stat-label" data-en="Job Seekers" data-ar="باحث عن عمل">Job Seekers</div>
            </div>
            <div class="stat-item">
              <div class="stat-number">1500+</div>
              <div class="stat-label" data-en="Jobs Posted" data-ar="وظيفة منشورة">Jobs Posted</div>
            </div>
          </div>

          <div class="hero-buttons">
            <a href="{{ route('register') }}" class="btn btn-hero btn-primary me-3" data-en="Join as Employer" data-ar="انضم كصاحب عمل">
              <i class="bi bi-person-plus me-2"></i>Join as Employer
            </a>
            <a href="{{ route('register') }}" class="btn btn-hero btn-outline-light" data-en="Find a Job" data-ar="ابحث عن وظيفة">
              <i class="bi bi-search me-2"></i>Find a Job
            </a>
          </div>
        </div>

        <div class="col-lg-6 text-center d-none d-lg-block">
          <div class="hero-image-container">
            <div class="floating-card card-1">
              <i class="bi bi-briefcase-fill"></i>
              <div data-en="Various Jobs" data-ar="وظائف متنوعة">Various Jobs</div>
            </div>
            <div class="floating-card card-2">
              <i class="bi bi-clock-fill"></i>
              <div data-en="Flexible Hours" data-ar="ساعات مرنة">Flexible Hours</div>
            </div>
            <div class="floating-card card-3">
              <i class="bi bi-cash-stack"></i>
              <div data-en="Quick Pay" data-ar="دفع سريع">Quick Pay</div>
            </div>
            <div class="icon-hero-display mx-auto">
              <i class="bi bi-people-fill"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="hero-wave">
      <svg viewBox="0 0 1200 120" preserveAspectRatio="none">
        <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" fill="#f4f7fa"></path>
      </svg>
    </div>
  </header>

  <!-- ═══════════════════════════════════════════════ -->
  <!-- How It Works                                   -->
  <!-- ═══════════════════════════════════════════════ -->
  <section id="how-it-works" class="py-5 bg-light">
    <div class="container">

      {{-- Section heading slides up --}}
      <div class="text-center mb-5 reveal from-bottom">
        <h2 class="section-title" data-en="How Does AJEER Work?" data-ar="كيف يعمل AJEER؟">How Does AJEER Work?</h2>
        <p class="section-subtitle" data-en="Three simple steps to get started" data-ar="ثلاث خطوات بسيطة للبدء">Three simple steps to get started</p>
      </div>

      <div class="row g-4">

        {{-- Employers column slides in from left --}}
        <div class="col-lg-6">
          <h3 class="text-center mb-4 text-primary reveal from-left">
            <i class="bi bi-building me-2"></i>
            <span data-en="For Employers" data-ar="لأصحاب الأعمال">For Employers</span>
          </h3>
          <div class="timeline">
            <div class="timeline-item reveal from-left reveal-delay-1">
              <div class="timeline-icon">1</div>
              <div class="timeline-content">
                <h5 data-en="Create Your Account" data-ar="أنشئ حسابك">Create Your Account</h5>
                <p data-en="Register your company or store once and get started immediately" data-ar="سجّل بيانات شركتك أو متجرك مرة واحدة فقط">Register your company or store once and get started immediately</p>
              </div>
            </div>
            <div class="timeline-item reveal from-left reveal-delay-2">
              <div class="timeline-icon">2</div>
              <div class="timeline-content">
                <h5 data-en="Post a Job" data-ar="انشر الوظيفة">Post a Job</h5>
                <p data-en="Specify required skills, working hours, and pay rate" data-ar="حدد المهارات، الساعات، والأجر المطلوب">Specify required skills, working hours, and pay rate</p>
              </div>
            </div>
            <div class="timeline-item reveal from-left reveal-delay-3">
              <div class="timeline-icon">3</div>
              <div class="timeline-content">
                <h5 data-en="Choose the Best" data-ar="اختر الأفضل">Choose the Best</h5>
                <p data-en="Receive applications from candidates and pick the right one" data-ar="استقبل طلبات من المرشحين واختر الأنسب لك">Receive applications from candidates and pick the right one</p>
              </div>
            </div>
          </div>
        </div>

        {{-- Job Seekers column slides in from right --}}
        <div class="col-lg-6">
          <h3 class="text-center mb-4 text-success reveal from-right">
            <i class="bi bi-person-workspace me-2"></i>
            <span data-en="For Job Seekers" data-ar="للباحثين عن عمل">For Job Seekers</span>
          </h3>
          <div class="timeline">
            <div class="timeline-item reveal from-right reveal-delay-1">
              <div class="timeline-icon green">1</div>
              <div class="timeline-content">
                <h5 data-en="Build Your Profile" data-ar="أنشئ ملفك الشخصي">Build Your Profile</h5>
                <p data-en="Add your skills, experience, and availability" data-ar="أضف مهاراتك، خبراتك، وأوقات توفرك">Add your skills, experience, and availability</p>
              </div>
            </div>
            <div class="timeline-item reveal from-right reveal-delay-2">
              <div class="timeline-icon green">2</div>
              <div class="timeline-content">
                <h5 data-en="Browse Jobs" data-ar="تصفح الوظائف">Browse Jobs</h5>
                <p data-en="Search for jobs that match your skills and schedule" data-ar="ابحث عن وظائف تناسب مهاراتك ووقتك">Search for jobs that match your skills and schedule</p>
              </div>
            </div>
            <div class="timeline-item reveal from-right reveal-delay-3">
              <div class="timeline-icon green">3</div>
              <div class="timeline-content">
                <h5 data-en="Apply and Work" data-ar="قدّم واعمل">Apply and Work</h5>
                <p data-en="Apply, get hired, and receive your payment" data-ar="قدّم على الوظيفة، اعمل، واحصل على أجرك">Apply, get hired, and receive your payment</p>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- ═══════════════════════════════════════════════ -->
  <!-- Services                                       -->
  <!-- ═══════════════════════════════════════════════ -->
  <section id="services" class="py-5">
    <div class="container">

      {{-- Section heading --}}
      <div class="text-center mb-5 reveal from-bottom">
        <h2 class="section-title" data-en="Our Core Services" data-ar="خدماتنا الرئيسية">Our Core Services</h2>
        <p class="section-subtitle" data-en="Everything you need to manage your flexible workforce" data-ar="نوفر لك كل ما تحتاجه لتوظيف وإدارة فريقك المرن">Everything you need to manage your flexible workforce</p>
      </div>

      {{-- Each card staggers up one after the other --}}
      <div class="row text-center g-4">

        <div class="col-md-4 reveal from-bottom reveal-delay-1">
          <div class="card service-card p-4 h-100">
            <div class="icon-circle mx-auto">
              <i class="bi bi-person-workspace"></i>
            </div>
            <h5 class="card-title fw-bold mt-3" data-en="Hourly Flexible Hiring" data-ar="توظيف مرن بالساعة">Hourly Flexible Hiring</h5>
            <p class="card-text text-muted" data-en="Hire workers by the hour or day. Pay only for actual hours worked — no fixed salaries, no long-term commitments." data-ar="وظف موظفين بالساعة أو اليوم. ادفع فقط مقابل الوقت الفعلي للعمل. لا رواتب ثابتة، لا التزامات طويلة.">Hire workers by the hour or day. Pay only for actual hours worked — no fixed salaries, no long-term commitments.</p>
          </div>
        </div>

        <div class="col-md-4 reveal from-bottom reveal-delay-2">
          <div class="card service-card p-4 h-100">
            <div class="icon-circle mx-auto orange">
              <i class="bi bi-calendar-range"></i>
            </div>
            <h5 class="card-title fw-bold mt-3" data-en="Schedule Management" data-ar="إدارة الجداول الزمنية">Schedule Management</h5>
            <p class="card-text text-muted" data-en="Smart system for shift scheduling and tracking work hours with automatic notifications for all parties." data-ar="نظام ذكي لجدولة المناوبات وتتبع ساعات العمل. تنبيهات تلقائية للموظفين والمديرين.">Smart system for shift scheduling and tracking work hours with automatic notifications for all parties.</p>
          </div>
        </div>

        <div class="col-md-4 reveal from-bottom reveal-delay-3">
          <div class="card service-card p-4 h-100">
            <div class="icon-circle mx-auto red">
              <i class="bi bi-shield-check"></i>
            </div>
            <h5 class="card-title fw-bold mt-3" data-en="Verification & Trust" data-ar="التحقق والموثوقية">Verification & Trust</h5>
            <p class="card-text text-muted" data-en="All job seekers are identity-verified. Ratings and review systems ensure consistent quality." data-ar="جميع الباحثين عن عمل يتم التحقق من هوياتهم. نظام تقييمات ومراجعات لضمان الجودة.">All job seekers are identity-verified. Ratings and review systems ensure consistent quality.</p>
          </div>
        </div>

        <div class="col-md-4 reveal from-bottom reveal-delay-4">
          <div class="card service-card p-4 h-100">
            <div class="icon-circle mx-auto purple">
              <i class="bi bi-cpu"></i>
            </div>
            <h5 class="card-title fw-bold mt-3" data-en="AI Smart Matching" data-ar="مطابقة ذكية بالذكاء الاصطناعي">AI Smart Matching</h5>
            <p class="card-text text-muted" data-en="Advanced AI algorithm matches skills with job requirements automatically, giving you the best candidates." data-ar="خوارزمية متطورة لمطابقة المهارات مع متطلبات الوظائف. احصل على أفضل المرشحين تلقائياً.">Advanced AI algorithm matches skills with job requirements automatically, giving you the best candidates.</p>
          </div>
        </div>

        <div class="col-md-4 reveal from-bottom reveal-delay-5">
          <div class="card service-card p-4 h-100">
            <div class="icon-circle mx-auto teal">
              <i class="bi bi-chat-dots"></i>
            </div>
            <h5 class="card-title fw-bold mt-3" data-en="Direct Communication" data-ar="تواصل مباشر">Direct Communication</h5>
            <p class="card-text text-muted" data-en="Communicate directly with candidates through the built-in messaging system. Agree on every detail easily." data-ar="تواصل مع المرشحين مباشرة عبر نظام الرسائل المدمج. ناقش التفاصيل واتفق على كل شيء.">Communicate directly with candidates through the built-in messaging system.</p>
          </div>
        </div>

        <div class="col-md-4 reveal from-bottom reveal-delay-6">
          <div class="card service-card p-4 h-100">
            <div class="icon-circle mx-auto indigo">
              <i class="bi bi-graph-up"></i>
            </div>
            <h5 class="card-title fw-bold mt-3" data-en="Reports & Analytics" data-ar="تقارير وتحليلات">Reports & Analytics</h5>
            <p class="card-text text-muted" data-en="Comprehensive dashboard with statistics on hiring, costs, and temporary employee performance." data-ar="لوحة تحكم شاملة مع إحصائيات عن التوظيف، التكاليف، وأداء الموظفين المؤقتين.">Comprehensive dashboard with statistics on hiring, costs, and temporary employee performance.</p>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- ═══════════════════════════════════════════════ -->
  <!-- Features                                       -->
  <!-- ═══════════════════════════════════════════════ -->
  <section id="features" class="py-5 bg-light">
    <div class="container">

      {{-- Section heading --}}
      <div class="text-center mb-5 reveal from-bottom">
        <h2 class="section-title" data-en="Why Choose AJEER?" data-ar="لماذا تختار AJEER؟">Why Choose AJEER?</h2>
        <p class="section-subtitle" data-en="Features that make us the best choice for flexible hiring" data-ar="مميزات تجعلنا الخيار الأفضل للتوظيف المرن">Features that make us the best choice for flexible hiring</p>
      </div>

      <div class="row g-4 align-items-center">

        {{-- Feature list items stagger in from the left --}}
        <div class="col-lg-6">
          <div class="feature-list">

            <div class="feature-item reveal from-left reveal-delay-1">
              <div class="feature-icon"><i class="bi bi-lightning-charge-fill"></i></div>
              <div class="feature-content">
                <h5 data-en="Fast Hiring" data-ar="توظيف سريع">Fast Hiring</h5>
                <p data-en="Post your job and receive applicants in minutes, not hours or days" data-ar="انشر وظيفتك واحصل على متقدمين في دقائق، لا ساعات أو أيام">Post your job and receive applicants in minutes, not hours or days</p>
              </div>
            </div>

            <div class="feature-item reveal from-left reveal-delay-2">
              <div class="feature-icon"><i class="bi bi-cash-coin"></i></div>
              <div class="feature-content">
                <h5 data-en="Save Costs" data-ar="وفّر التكاليف">Save Costs</h5>
                <p data-en="Pay only for hours needed. No monthly salaries, no benefits overhead." data-ar="ادفع فقط مقابل الساعات المطلوبة. لا رواتب شهرية، لا تأمينات">Pay only for hours needed. No monthly salaries, no benefits overhead.</p>
              </div>
            </div>

            <div class="feature-item reveal from-left reveal-delay-3">
              <div class="feature-icon"><i class="bi bi-patch-check-fill"></i></div>
              <div class="feature-content">
                <h5 data-en="High Reliability" data-ar="موثوقية عالية">High Reliability</h5>
                <p data-en="Transparent rating system and identity verification for all users" data-ar="نظام تقييمات شفاف والتحقق من الهوية لجميع المستخدمين">Transparent rating system and identity verification for all users</p>
              </div>
            </div>

            <div class="feature-item reveal from-left reveal-delay-4">
              <div class="feature-icon"><i class="bi bi-gear-fill"></i></div>
              <div class="feature-content">
                <h5 data-en="Easy to Use" data-ar="سهولة الاستخدام">Easy to Use</h5>
                <p data-en="Simple, intuitive interface — no complex training required" data-ar="واجهة بسيطة وسهلة، لا حاجة لتدريب معقد">Simple, intuitive interface — no complex training required</p>
              </div>
            </div>

          </div>
        </div>

        {{-- Illustration scales in from the right --}}
        <div class="col-lg-6 text-center reveal scale-in">
          <div class="features-illustration">
            <div class="illustration-circle"><i class="bi bi-trophy-fill"></i></div>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- ═══════════════════════════════════════════════ -->
  <!-- CTA                                            -->
  <!-- ═══════════════════════════════════════════════ -->
  <section id="cta-section" class="cta-section text-center">
    <div class="container">

      {{-- Heading drops in from top --}}
      <h2 class="mb-3 fw-bold reveal from-top"
          data-en="Ready to Start Your Journey with AJEER?"
          data-ar="جاهز لبدء رحلتك مع AJEER؟">
        Ready to Start Your Journey with AJEER?
      </h2>

      {{-- Subtext rises from bottom with slight delay --}}
      <p class="lead mb-5 reveal from-bottom reveal-delay-1"
         data-en="Join hundreds of businesses and thousands of job seekers who trust us"
         data-ar="انضم إلى مئات الشركات والآلاف من الباحثين عن عمل الذين يثقون بنا">
        Join hundreds of businesses and thousands of job seekers who trust us
      </p>

      {{-- Buttons appear last --}}
      <div class="cta-buttons reveal from-bottom reveal-delay-2">
        <a href="{{ route('register') }}" class="btn btn-hero btn-light me-3"
           data-en="Register as Employer" data-ar="سجّل كصاحب عمل">
          <i class="bi bi-building me-2"></i>Register as Employer
        </a>
        <a href="{{ route('register') }}" class="btn btn-hero btn-outline-light"
           data-en="Register as Job Seeker" data-ar="سجّل كباحث عن عمل">
          <i class="bi bi-person me-2"></i>Register as Job Seeker
        </a>
      </div>

    </div>
  </section>

  <!-- ═══════════════════════════════════════════════ -->
  <!-- Footer                                         -->
  <!-- ═══════════════════════════════════════════════ -->
  <footer class="footer py-5">
    <div class="container">
      <div class="row g-4">

        {{-- Brand block slides in from left --}}
        <div class="col-lg-4 reveal from-left reveal-delay-1">
          <h5 class="logo mb-3"><i class="bi bi-briefcase-fill me-2"></i>AJEER</h5>
          <p class="text-muted"
             data-en="Jordan's first flexible jobs platform, connecting employers with flexible and part-time job seekers."
             data-ar="منصة الوظائف المرنة الأولى في الأردن. نربط أصحاب الأعمال بالباحثين عن فرص عمل مرنة وجزئية.">
            Jordan's first flexible jobs platform, connecting employers with flexible and part-time job seekers.
          </p>
          <div class="social-links mt-3">
            <a href="#" class="social-link"><i class="bi bi-facebook"></i></a>
            <a href="#" class="social-link"><i class="bi bi-twitter"></i></a>
            <a href="#" class="social-link"><i class="bi bi-instagram"></i></a>
            <a href="#" class="social-link"><i class="bi bi-linkedin"></i></a>
          </div>
        </div>

        {{-- Link columns rise from bottom staggered --}}
        <div class="col-lg-2 col-md-4 reveal from-bottom reveal-delay-2">
          <h6 class="fw-bold mb-3" data-en="Company" data-ar="الشركة">Company</h6>
          <ul class="list-unstyled footer-links">
            <li><a href="#" data-en="About Us" data-ar="من نحن">About Us</a></li>
            <li><a href="#" data-en="Our Team" data-ar="فريق العمل">Our Team</a></li>
            <li><a href="#" data-en="Blog" data-ar="المدونة">Blog</a></li>
          </ul>
        </div>

        <div class="col-lg-2 col-md-4 reveal from-bottom reveal-delay-3">
          <h6 class="fw-bold mb-3" data-en="Services" data-ar="الخدمات">Services</h6>
          <ul class="list-unstyled footer-links">
            <li><a href="#" data-en="For Businesses" data-ar="للشركات">For Businesses</a></li>
            <li><a href="#" data-en="For Seekers" data-ar="للباحثين">For Seekers</a></li>
            <li><a href="#" data-en="FAQ" data-ar="الأسئلة الشائعة">FAQ</a></li>
          </ul>
        </div>

        <div class="col-lg-2 col-md-4 reveal from-bottom reveal-delay-4">
          <h6 class="fw-bold mb-3" data-en="Support" data-ar="الدعم">Support</h6>
          <ul class="list-unstyled footer-links">
            <li><a href="#" data-en="Help Center" data-ar="مركز المساعدة">Help Center</a></li>
            <li><a href="#" data-en="Contact Us" data-ar="تواصل معنا">Contact Us</a></li>
            <li><a href="#" data-en="Terms & Conditions" data-ar="الشروط والأحكام">Terms & Conditions</a></li>
            <li><a href="#" data-en="Privacy Policy" data-ar="سياسة الخصوصية">Privacy Policy</a></li>
          </ul>
        </div>

        {{-- App badges slide in from right --}}
        <div class="col-lg-2 col-md-12 reveal from-right reveal-delay-5">
          <h6 class="fw-bold mb-3" data-en="App" data-ar="التطبيق">App</h6>
          <p class="text-muted small mb-2" data-en="Coming soon on:" data-ar="قريباً على:">Coming soon on:</p>
          <a href="#" class="d-block mb-2">
            <img src="{{ asset('images/app-store-badge.svg') }}" alt="App Store" style="height: 40px;">
          </a>
          <a href="#" class="d-block">
            <img src="{{ asset('images/google-play-badge.svg') }}" alt="Google Play" style="height: 40px;">
          </a>
        </div>

      </div>

      <hr class="my-4">
      <div class="row reveal fade-only">
        <div class="col-md-6 text-center text-md-start">
          <p class="mb-0 text-muted small"
             data-en="© {{ date('Y') }} AJEER. All rights reserved."
             data-ar="© {{ date('Y') }} AJEER. جميع الحقوق محفوظة.">
            © {{ date('Y') }} AJEER. All rights reserved.
          </p>
        </div>
        <div class="col-md-6 text-center text-md-end">
          <p class="mb-0 text-muted small"
             data-en="Made with ❤️ in Jordan 🇯🇴"
             data-ar="صُنع بـ ❤️ في الأردن 🇯🇴">
            Made with ❤️ in Jordan 🇯🇴
          </p>
        </div>
      </div>

    </div>
  </footer>

  <button id="backToTop" class="back-to-top"><i class="bi bi-arrow-up"></i></button>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('js/home.js') }}"></script>
</body>
</html>