@extends('layouts.public')

@section('title', 'Documentation')

@section('content')
    <section class="py-5 bg-dark text-white text-center">
        <div class="container py-5">
            <h1 class="display-4 fw-bold">Documentation</h1>
            <p class="lead text-secondary">Everything you need to know about using Quizzer.</p>
        </div>
    </section>

    <section class="py-5">
        <div class="container py-5">
            <div class="row">
                <div class="col-lg-3">
                    <div class="sticky-top" style="top: 100px;">
                        <h6 class="text-uppercase fw-bold mb-3 small">Getting Started</h6>
                        <nav class="nav flex-column mb-4">
                            <a class="nav-link ps-0 active" href="#intro">Introduction</a>
                            <a class="nav-link ps-0" href="#setup">Platform Setup</a>
                        </nav>
                        <h6 class="text-uppercase fw-bold mb-3 small">Teacher Tools</h6>
                        <nav class="nav flex-column mb-4">
                            <a class="nav-link ps-0" href="#ai-gen">AI generation</a>
                            <a class="nav-link ps-0" href="#manual">Manual Content</a>
                        </nav>
                    </div>
                </div>
                <div class="col-lg-9">
                    <article id="intro" class="mb-5">
                        <h2 class="fw-bold mb-4">Introduction</h2>
                        <p>Quizzer is a comprehensive educational platform designed to bridge the gap between static content
                            and active learning. Using state-of-the-art AI, we help educators create engaging materials
                            instantly.</p>
                    </article>
                    <article id="ai-gen" class="mb-5">
                        <h2 class="fw-bold mb-4">How AI Generation Works</h2>
                        <p>Our AI analyzes the structure of your document to identify natural breaks, forming chapters and
                            topics. It then generates multiple-choice questions (MCQs) for each topic.</p>
                        <div class="bg-light p-4 rounded-3 border">
                            <h6 class="fw-bold"><i class="fas fa-lightbulb me-2 text-warning"></i>Pro Tip</h6>
                            <p class="mb-0 small">For best results, upload clean PDF text rather than scanned images of
                                handwritten notes.</p>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </section>
@endsection
