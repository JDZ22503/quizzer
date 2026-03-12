@extends('layouts.public')

@section('title', 'Tutorials')

@section('content')
    <section class="py-5">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h1 class="display-5 fw-bold mb-3">Tutorials & Video Guides</h1>
                <p class="text-secondary">Master Quizzer with our step-by-step video tutorials.</p>
            </div>

            <div class="row g-4 mb-5">
                <div class="col-md-12">
                    <h3 class="fw-bold mb-4 border-bottom pb-2">Teacher Tutorials</h3>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100 overflow-hidden">
                        <div class="position-relative">
                            <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixlib=rb-1.2.1&auto=format&fit=crop&w=400&q=80"
                                alt="Tutorial" class="card-img-top">
                            <div class="position-absolute top-50 start-50 translate-middle">
                                <i class="fas fa-play-circle text-white display-4" style="opacity: 0.8;"></i>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title fw-bold">Creating your first Book</h5>
                            <p class="card-text text-secondary small">Learn how to upload a PDF and generate your first AI
                                chapter.</p>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="badge bg-light text-dark">5:30 min</span>
                                <a href="#" class="btn btn-sm btn-link text-primary p-0">Watch Now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-12">
                    <h3 class="fw-bold mb-4 border-bottom pb-2">Student Tutorials</h3>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100 overflow-hidden">
                        <div class="position-relative">
                            <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?ixlib=rb-1.2.1&auto=format&fit=crop&w=400&q=80"
                                alt="Tutorial" class="card-img-top">
                            <div class="position-absolute top-50 start-50 translate-middle">
                                <i class="fas fa-play-circle text-white display-4" style="opacity: 0.8;"></i>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title fw-bold">Mastering Quiz Mode</h5>
                            <p class="card-text text-secondary small">Tips and tricks to maximize your XP and climbing the
                                leaderboard.</p>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="badge bg-light text-dark">3:45 min</span>
                                <a href="#" class="btn btn-sm btn-link text-primary p-0">Watch Now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
