@extends('layouts.public')

@section('title', 'Features')

@section('content')
    <section class="py-5 bg-light">
        <div class="container py-5 text-center">
            <h1 class="display-4 fw-bold mb-4">Powerful Features for Modern Education</h1>
            <p class="lead text-secondary mx-auto" style="max-width: 800px;">Quizzer brings the power of AI and gamification
                to your classroom, making learning more efficient and engaging.</p>
        </div>
    </section>

    <section class="py-5">
        <div class="container py-5">
            <div class="row align-items-center mb-5">
                <div class="col-lg-6">
                    <h2 class="fw-bold mb-4">AI Question Generation</h2>
                    <p class="text-secondary mb-4">Our advanced AI models analyze your educational content—whether it's a
                        PDF book, a set of notes, or raw text—and automatically extract key concepts to create high-quality
                        MCQs.</p>
                    <ul class="list-unstyled mb-4">
                        <li class="mb-2"><i class="fas fa-check-circle text-primary me-2"></i>PDF Book Analysis</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-primary me-2"></i>Automatic Topic Extraction
                        </li>
                        <li class="mb-2"><i class="fas fa-check-circle text-primary me-2"></i>Detailed Explanations for
                            each answer</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-primary me-2"></i>Multi-language support
                            (English, Hindi, Gujarati)</li>
                    </ul>
                </div>
                <div class="col-lg-6">
                    <img src="https://images.unsplash.com/photo-1677442136019-21780ecad995?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80"
                        alt="AI Features" class="img-fluid rounded-4 shadow-lg">
                </div>
            </div>

            <div class="row align-items-center flex-row-reverse mb-5">
                <div class="col-lg-6">
                    <h2 class="fw-bold mb-4">Gamified Learning System</h2>
                    <p class="text-secondary mb-4">Turn studying into an adventure. Our gamification engine keeps students
                        motivated through healthy competition and rewards.</p>
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="p-3 border rounded-3 text-center">
                                <i class="fas fa-bolt text-warning mb-2 h3"></i>
                                <p class="mb-0 fw-bold">Daily Streaks</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 border rounded-3 text-center">
                                <i class="fas fa-trophy text-primary mb-2 h3"></i>
                                <p class="mb-0 fw-bold">Leaderboards</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 border rounded-3 text-center">
                                <i class="fas fa-medal text-success mb-2 h3"></i>
                                <p class="mb-0 fw-bold">Badges</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 border rounded-3 text-center">
                                <i class="fas fa-star text-info mb-2 h3"></i>
                                <p class="mb-0 fw-bold">XP System</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <img src="https://images.unsplash.com/photo-1550745165-9bc0b252726f?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80"
                        alt="Gamification" class="img-fluid rounded-4 shadow-lg">
                </div>
            </div>
        </div>
    </section>
@endsection
