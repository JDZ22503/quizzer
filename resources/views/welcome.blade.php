@extends('layouts.public')

@section('title', 'Welcome to Quizzer')



@section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container text-center">
            <div class="row justify-content-center">
                <div class="col-lg-8 animate-fadeInUp">
                    <span class="badge bg-soft-primary text-primary px-3 py-2 rounded-pill mb-3"
                        style="background: rgba(99, 102, 241, 0.1);">
                        <i class="fas fa-sparkles me-2"></i>AI-Powered Learning
                    </span>
                    <h1 class="display-3 fw-bold mb-4">Smart Quizzes, <span class="text-primary"
                            style="background: var(--primary-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Smarter
                            Learning.</span></h1>
                    <p class="lead text-secondary mb-5">Transform your educational content into interactive quizzes in
                        seconds. Empower teachers, engage students, and track progress with AI.</p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('student.register') }}" class="btn btn-primary-custom btn-lg px-5">Start for
                            Free</a>
                        <a href="#demo" class="btn btn-outline-custom btn-lg px-5">Watch Demo</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Platform Demo Image -->
    <section id="demo" class="pb-5">
        <div class="container">
            <div class="platform-demo animate-fadeInUp">
                <div class="p-2 bg-dark rounded-top-4 d-flex gap-2 px-3">
                    <div style="width: 12px; height: 12px; background: #ff5f56; border-radius: 50%;"></div>
                    <div style="width: 12px; height: 12px; background: #ffbd2e; border-radius: 50%;"></div>
                    <div style="width: 12px; height: 12px; background: #27c93f; border-radius: 50%;"></div>
                </div>
                <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&q=80"
                    alt="Platform Demo" class="img-fluid">
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5 bg-white">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold mb-3">Everything you need to succeed</h2>
                <p class="text-secondary">Explore the powerful modules designed for both teachers and students.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fas fa-magic"></i></div>
                        <h4>AI Question Generation</h4>
                        <p class="text-secondary">Automatically generate MCQs and study material from PDFs and text in
                            seconds.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fas fa-gamepad"></i></div>
                        <h4>Gamified Learning</h4>
                        <p class="text-secondary">Engage students with XP, badges, streaks, and real-time leaderboards.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fas fa-chart-line"></i></div>
                        <h4>Deep Analytics</h4>
                        <p class="text-secondary">Track performance at a granular level. Identify weak topics and monitor
                            progress.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works (Teacher Workflow) -->
    <section class="py-5">
        <div class="container py-5">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <h2 class="display-5 fw-bold mb-4">Teacher's Workflow</h2>
                    <div class="workflow-step">
                        <div class="step-number">1</div>
                        <h5>Upload Content</h5>
                        <p class="text-secondary">Upload your book PDFs or paste manual text content for AI processing.</p>
                    </div>
                    <div class="workflow-step">
                        <div class="step-number">2</div>
                        <h5>AI Processing</h5>
                        <p class="text-secondary">Our AI creates chapters, topics, and MCQs with detailed explanations.</p>
                    </div>
                    <div class="workflow-step">
                        <div class="step-number">3</div>
                        <h5>Review & Publish</h5>
                        <p class="text-secondary">Adjust questions, add difficulty levels, and publish to your students.</p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <img src="https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80"
                        alt="Teacher Workflow" class="img-fluid rounded-4 shadow-lg">
                </div>
            </div>
        </div>
    </section>

    <!-- Student Workflow -->
    <section class="py-5 bg-white">
        <div class="container py-5">
            <div class="row align-items-center flex-row-reverse">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <h2 class="display-5 fw-bold mb-4">Student's Workflow</h2>
                    <div class="workflow-step">
                        <div class="step-number">1</div>
                        <h5>Discovery</h5>
                        <p class="text-secondary">Browse subjects and chapters curated by your teachers.</p>
                    </div>
                    <div class="workflow-step">
                        <div class="step-number">2</div>
                        <h5>Interactive Learning</h5>
                        <p class="text-secondary">Switch between Quiz Mode, Study Mode, or Passive MCQ Reading.</p>
                    </div>
                    <div class="workflow-step">
                        <div class="step-number">3</div>
                        <h5>Level Up</h5>
                        <p class="text-secondary">Earn XP for every correct answer. Compete on the leaderboard and earn
                            badges.</p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80"
                        alt="Student Workflow" class="img-fluid rounded-4 shadow-lg">
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-5 mb-4">
        <div class="container py-5 text-center">
            <h2 class="display-5 fw-bold mb-5">Hear from our community</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="testimonial-card text-start">
                        <div class="d-flex mb-3">
                            @for ($i = 0; $i < 5; $i++)
                                <i class="fas fa-star text-warning"></i>
                            @endfor
                        </div>
                        <p class="mb-4">"The AI question generation has saved me hours of work. I can now focus on
                            teaching while Quizzer handles the assessment."</p>
                        <div class="d-flex align-items-center">
                            <img src="https://ui-avatars.com/api/?name=John+Doe&background=random" alt="User"
                                class="testimonial-avatar">
                            <div>
                                <h6 class="mb-0">John Doe</h6>
                                <small class="text-secondary">Physics Teacher</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimonial-card text-start">
                        <div class="d-flex mb-3">
                            @for ($i = 0; $i < 5; $i++)
                                <i class="fas fa-star text-warning"></i>
                            @endfor
                        </div>
                        <p class="mb-4">"I love the leaderboard! Competing with my classmates makes studying actually
                            fun. I've finished 3 chapters today!"</p>
                        <div class="d-flex align-items-center">
                            <img src="https://ui-avatars.com/api/?name=Sarah+Smith&background=random" alt="User"
                                class="testimonial-avatar">
                            <div>
                                <h6 class="mb-0">Sarah Smith</h6>
                                <small class="text-secondary">10th Standard Student</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimonial-card text-start">
                        <div class="d-flex mb-3">
                            @for ($i = 0; $i < 5; $i++)
                                <i class="fas fa-star text-warning"></i>
                            @endfor
                        </div>
                        <p class="mb-4">"The passive MCQ reader is perfect for my commute. I can review 50+ questions
                            quickly before reaching school."</p>
                        <div class="d-flex align-items-center">
                            <img src="https://ui-avatars.com/api/?name=Mike+Ross&background=random" alt="User"
                                class="testimonial-avatar">
                            <div>
                                <h6 class="mb-0">Mike Ross</h6>
                                <small class="text-secondary">Graduation Student</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing -->
    <section class="py-5 bg-white">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold mb-3">Transparent Pricing</h2>
                <p class="text-secondary">Choose the plan that fits your needs.</p>
            </div>
            <div class="row g-4 justify-content-center">
                <div class="col-md-4">
                    <div class="pricing-card h-100">
                        <h4 class="mb-3">Free</h4>
                        <div class="display-6 fw-bold mb-3">$0<small class="text-secondary fs-6">/mo</small></div>
                        <ul class="list-unstyled mb-5">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Unlimited Practice</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Daily Challenges</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Basic Analytics</li>
                        </ul>
                        <a href="{{ route('student.register') }}" class="btn btn-outline-custom w-100">Get Started</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="pricing-card popular h-100">
                        <h4 class="mb-3">Teacher</h4>
                        <div class="display-6 fw-bold mb-3">$19<small class="text-secondary fs-6">/mo</small></div>
                        <ul class="list-unstyled mb-5">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>AI Creation (10k tokens)</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>500 Students</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Advanced Analytics</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Question Export (PDF)</li>
                        </ul>
                        <a href="{{ route('teacher.register') }}" class="btn btn-primary-custom w-100">Start 14-day Free
                            Trial</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="pricing-card h-100">
                        <h4 class="mb-3">Institution</h4>
                        <div class="display-6 fw-bold">Custom</div>
                        <p class="text-secondary mb-5">For schools & universities</p>
                        <ul class="list-unstyled mb-5">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Unlimited AI Creation</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>White Label App</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Priority Support</li>
                        </ul>
                        <a href="#" class="btn btn-outline-custom w-100">Contact Sales</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ -->
    <section class="py-5">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold mb-3">Frequently Asked Questions</h2>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="accordion accordion-flush" id="faqAccordion">
                        <div class="accordion-item faq-item mb-3">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed rounded-3 fw-bold" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#faq1">
                                    How accurate is the AI Question Generation?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Our AI is 95%+ accurate on factual content. We always recommend teachers to quickly
                                    review and approve the generated content before publishing.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item faq-item mb-3">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed rounded-3 fw-bold" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#faq2">
                                    Can I use Quizzer for any subject?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Yes! While it works exceptionally well with text-heavy subjects like History, Geography,
                                    and Language, it also supports Science and Math MCQ layouts.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-5">
        <div class="container py-5">
            <div class="bg-primary p-5 rounded-4 text-center text-white shadow-lg"
                style="background: var(--primary-gradient) !important;">
                <h2 class="display-5 fw-bold mb-4">Ready to transform your classroom?</h2>
                <p class="lead mb-5">Join thousands of teachers and students who are already using Quizzer to learn faster
                    and better.</p>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="{{ route('student.register') }}" class="btn btn-light btn-lg px-5 text-primary fw-bold">I'm a
                        Student</a>
                    <a href="{{ route('teacher.register') }}" class="btn btn-outline-light btn-lg px-5">I'm a Teacher</a>
                </div>
            </div>
        </div>
    </section>
@endsection
