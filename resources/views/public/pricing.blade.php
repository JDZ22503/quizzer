@extends('layouts.public')

@section('title', 'Pricing')

@section('content')
    <section class="py-5 bg-primary text-white text-center" style="background: var(--primary-gradient) !important;">
        <div class="container py-5">
            <h1 class="display-4 fw-bold mb-4">Simple, Transparent Pricing</h1>
            <p class="lead">Start for free, then upgrade as you grow.</p>
        </div>
    </section>

    <section class="py-5">
        <div class="container py-5">
            <div class="row g-4 justify-content-center">
                <!-- Reuse pricing cards from home but with more detail -->
                <div class="col-md-4">
                    <div class="p-5 border rounded-4 h-100">
                        <h3 class="fw-bold">Free</h3>
                        <p class="text-secondary">Perfect for individual practice.</p>
                        <div class="h2 mb-4">$0 <small class="fs-6 text-secondary">/ forever</small></div>
                        <ul class="list-unstyled mb-5">
                            <li class="mb-3"><i class="fas fa-check text-success me-2"></i>Unlimited Practice</li>
                            <li class="mb-3"><i class="fas fa-check text-success me-2"></i>Daily Challenges</li>
                            <li class="mb-3"><i class="fas fa-check text-success me-2"></i>Public Leaderboard</li>
                            <li class="mb-3"><i class="fas fa-check text-success me-2"></i>Standard Subjects</li>
                        </ul>
                        <a href="{{ route('student.register') }}" class="btn btn-outline-custom w-100">Sign Up Free</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-5 border border-primary border-2 rounded-4 h-100 bg-white shadow-lg">
                        <div class="badge bg-primary mb-3">MOST POPULAR</div>
                        <h3 class="fw-bold">Teacher</h3>
                        <p class="text-secondary">Best for tutors and school teachers.</p>
                        <div class="h2 mb-4">$19 <small class="fs-6 text-secondary">/ month</small></div>
                        <ul class="list-unstyled mb-5">
                            <li class="mb-3"><i class="fas fa-check text-success me-2"></i>AI Creation (10k tokens)</li>
                            <li class="mb-3"><i class="fas fa-check text-success me-2"></i>Up to 500 Students</li>
                            <li class="mb-3"><i class="fas fa-check text-success me-2"></i>Private Classrooms</li>
                            <li class="mb-3"><i class="fas fa-check text-success me-2"></i>Export Questions (PDF/CSV)</li>
                            <li class="mb-3"><i class="fas fa-check text-success me-2"></i>Advanced Student Analytics</li>
                        </ul>
                        <a href="{{ route('teacher.register') }}" class="btn btn-primary-custom w-100">Start Free Trial</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
