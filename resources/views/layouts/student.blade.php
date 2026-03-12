<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'QuizPlatform') — Student</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ time() }}">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    @stack('styles')
</head>

<body>
    <nav class="main-nav">
        <a class="nav-brand" href="{{ route('student.subjects') }}">
            Rev<span>izo</span>
            <span class="nav-badge">Student</span>
        </a>
        @auth('student')
            <button class="mobile-menu-btn d-md-none" id="mobileMenuBtn" aria-label="Toggle Navigation">
                <i class="bi bi-list"></i>
            </button>
            <div class="nav-links" id="navLinks">
                <a href="{{ route('student.dashboard') }}" class="nav-link"><i class="bi bi-grid"></i>
                    <span>Dashboard</span></a>
                <a href="{{ route('student.subjects') }}" class="nav-link"><i class="bi bi-book"></i>
                    <span>Subjects</span></a>
                <a href="{{ route('student.leaderboard') }}" class="nav-link"><i class="bi bi-trophy"></i>
                    <span>Leaderboard</span></a>
                <a href="{{ route('student.rewards') }}" class="nav-link"><i class="bi bi-star-fill"></i>
                    <span>Rewards</span></a>
                <a href="{{ route('student.profile') }}" class="nav-link"><i class="bi bi-person"></i>
                    <span>Profile</span></a>
                <form method="POST" action="{{ route('student.logout') }}">
                    @csrf
                    <button type="submit" class="btn-logout"><i class="bi bi-box-arrow-right"></i>
                        <span>Logout</span></button>
                </form>
            </div>
        @endauth
    </nav>

    <main class="p-5">
        @if (session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: "{{ session('success') }}",
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true,
                    });
                });
            </script>
        @endif
        @if (session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: "{{ session('error') }}",
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true,
                    });
                });
            </script>
        @endif

        @yield('content')
    </main>

    @stack('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Safety: Reveal all elements if animation fails to trigger
            setTimeout(() => {
                document.querySelectorAll('.animate-in').forEach(el => {
                    el.style.opacity = '1';
                    el.style.transform = 'none';
                    el.style.visibility = 'visible';
                });
            }, 1000);

            // Mobile Menu Toggle
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const navLinks = document.getElementById('navLinks');

            if (mobileMenuBtn && navLinks) {
                mobileMenuBtn.addEventListener('click', () => {
                    navLinks.classList.toggle('active');
                    const icon = mobileMenuBtn.querySelector('i');
                    if (navLinks.classList.contains('active')) {
                        icon.classList.remove('bi-list');
                        icon.classList.add('bi-x-lg');
                    } else {
                        icon.classList.remove('bi-x-lg');
                        icon.classList.add('bi-list');
                    }
                });
            }
        });
    </script>
</body>

</html>
