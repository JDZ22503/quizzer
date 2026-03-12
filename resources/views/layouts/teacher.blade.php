<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'QuizPlatform') Teacher</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    @stack('styles')
</head>

<body>
    <nav class="main-nav">
        <a class="nav-brand" href="{{ route('teacher.dashboard') }}">
            Quiz<span>Platform</span>
            <span class="nav-badge">Teacher</span>
        </a>
        @auth('teacher')
            <div class="nav-links">
                <a href="{{ route('teacher.dashboard') }}"><i class="bi bi-grid-1x2"></i> Dashboard</a>
                <a href="{{ route('teacher.ai-monitor') }}"><i class="bi bi-cpu"></i> AI Monitor</a>
                <a href="{{ route('teacher.analytics') }}"><i class="bi bi-graph-up"></i> Analytics</a>
                <a href="{{ route('teacher.settings') }}"><i class="bi bi-gear"></i> Settings</a>
                <form method="POST" action="{{ route('teacher.logout') }}">
                    @csrf
                    <button type="submit" class="btn-logout">Logout</button>
                </form>
            </div>
        @endauth
    </nav>

    <main class="p-3">
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

    <script>
        function convertKrutiDevToUnicode(input) {

            if (!input) return "";

            var text = input;

            // If already Unicode Hindi, don't convert
            if (/[\u0900-\u097F]/.test(text)) {
                return text;
            }

            // KrutiDev → Unicode dictionary
            var dict = [
                ['Q+Z', 'QZ+'],
                ['sas', 'sa'],
                ['aa', 'a'],
                [')Z', 'र्द्ध'],
                ['ZZ', 'Z'],
                ['=kk', '=k'],
                ['f=k', 'f='],

                ['vks', 'ओ'],
                ['vkS', 'औ'],
                ['vk', 'आ'],
                ['v', 'अ'],
                ['bZ', 'ई'],
                ['b', 'इ'],
                ['m', 'उ'],
                [',', 'ए'],
                [',s', 'ऐ'],

                ['pkS', 'चै'],
                ['ks', 'ो'],
                ['kS', 'ौ'],
                ['k', 'ा'],
                ['h', 'ी'],
                ['q', 'ु'],
                ['w', 'ू'],
                ['s', 'े'],
                ['S', 'ै'],

                ['d', 'क'],
                ['[', 'ख'],
                ['x', 'ग'],
                ['?', 'घ'],
                ['³', 'ङ'],
                ['p', 'च'],
                ['N', 'छ'],
                ['t', 'ज'],
                ['>', 'झ'],
                ['¥', 'ञ'],

                ['V', 'ट'],
                ['B', 'ठ'],
                ['M', 'ड'],
                ['<', 'ढ'],
                ['.', 'ण'],
                ['r', 'त'],
                ['F', 'थ'],
                ['n', 'द'],
                ['/', 'ध'],
                ['u', 'न'],

                ['i', 'प'],
                ['Q', 'फ'],
                ['c', 'ब'],
                ['H', 'भ'],
                ['e', 'म'],

                [';', 'य'],
                ['j', 'र'],
                ['y', 'ल'],
                ['G', 'ळ'],
                ['o', 'व'],

                ["'", "श"],
                ['"', 'ष'],
                ['l', 'स'],
                ['g', 'ह'],

                ['z', '्र'],
                ['~', '्'],
                ['+', '़'],

                ['A', '।'],
                ['-', '.'],
                ['@', '/'],
                [']', ',']
            ];

            // Sort dictionary by longest key first
            dict.sort(function(a, b) {
                return b[0].length - a[0].length;
            });

            // Apply dictionary replacements
            for (var i = 0; i < dict.length; i++) {
                var regex = new RegExp(dict[i][0].replace(/[.*+?^${}()|[\]\\]/g, '\\$&'), 'g');
                text = text.replace(regex, dict[i][1]);
            }

            // Fix "ि" matra placement
            text = text.replace(/f(.)/g, "$1ि");

            // Fix "िं"
            text = text.replace(/fa(.)/g, "$1िं");

            // Fix Reph (Z → र्)
            text = text.replace(/(.)Z/g, "र्$1");

            // Clean double halant
            text = text.replace(/््/g, '्');

            return text.trim();
        }
    </script>
    <script>
        function convert() {
            var text = document.getElementById("input").value;
            document.getElementById("output").value = convertKrutiDevToUnicode(text);
        }
    </script>
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
