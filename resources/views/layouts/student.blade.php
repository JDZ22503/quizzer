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
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    @stack('styles')
</head>

<body>
    <nav>
        <a class="nav-brand" href="{{ route('student.subjects') }}">
            Rev<span>izo</span>
            <span class="nav-badge">Student</span>
        </a>
        @auth('student')
            <div class="nav-links">
                <a href="{{ route('student.subjects') }}" class="nav-link"><i class="bi bi-book"></i> Subjects</a>
                <form method="POST" action="{{ route('student.logout') }}">
                    @csrf
                    <button type="submit" class="btn-logout">Logout</button>
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
        // Safety: Reveal all elements if animation fails to trigger
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                document.querySelectorAll('.animate-in').forEach(el => {
                    el.style.opacity = '1';
                    el.style.transform = 'none';
                    el.style.visibility = 'visible';
                });
            }, 1000);
        });
    </script>
</body>

</html>
