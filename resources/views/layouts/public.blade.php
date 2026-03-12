<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name', 'Quizzer')) - Smart Learning Platform</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom Style -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    @yield('styles')
</head>

<body class="public-body">
    <nav class="main-nav navbar public-navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-brain me-2"></i>Quizzer
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('features') }}">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('pricing') }}">Pricing</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('documentation') }}">Docs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('tutorials') }}">Tutorials</a>
                    </li>
                </ul>
                <div class="d-flex gap-2">
                    <a href="{{ route('student.login') }}" class="btn btn-link nav-link">Login</a>
                    <a href="{{ route('student.register') }}" class="btn btn-primary-custom">Get Started</a>
                </div>
            </div>
        </div>
    </nav>

    @yield('content')

    <footer class="public-footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h4 class="text-white mb-4"><i class="fas fa-brain me-2"></i>Quizzer</h4>
                    <p>Empowering teachers and students with AI-driven learning tools. Create, learn, and grow together.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-white"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-linkedin"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 mb-4">
                    <h6 class="text-white mb-4">Platform</h6>
                    <a href="{{ route('features') }}" class="footer-link">Features</a>
                    <a href="{{ route('pricing') }}" class="footer-link">Pricing</a>
                    <a href="{{ route('tutorials') }}" class="footer-link">Tutorials</a>
                </div>
                <div class="col-lg-2 col-md-4 mb-4">
                    <h6 class="text-white mb-4">Support</h6>
                    <a href="{{ route('documentation') }}" class="footer-link">Documentation</a>
                    <a href="#" class="footer-link">FAQ</a>
                    <a href="#" class="footer-link">Contact</a>
                </div>
                <div class="col-lg-4 col-md-4 mb-4">
                    <h6 class="text-white mb-4">Newsletter</h6>
                    <p>Stay updated with our latest news and features.</p>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control bg-dark border-secondary text-white"
                            placeholder="Email address">
                        <button class="btn btn-primary-custom" type="button">Subscribe</button>
                    </div>
                </div>
            </div>
            <hr class="mt-4 mb-3 border-secondary">
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0">&copy; {{ date('Y') }} Quizzer. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <a href="#" class="footer-link d-inline-block me-3">Privacy Policy</a>
                    <a href="#" class="footer-link d-inline-block">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>

</html>
