<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sydney Events for Newcomers</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        @keyframes float {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        .glass-card {
            backdrop-filter: blur(12px);
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>

<body class="min-h-screen bg-gradient-to-b from-gray-900 via-purple-900 to-black text-white">
    <!-- Ambient Background -->
    <div class="fixed inset-0 pointer-events-none">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-500/10 via-purple-500/10 to-pink-500/10"></div>
        <div class="absolute inset-0 backdrop-blur-3xl"></div>
    </div>

    <!-- Main Content Container -->
    <main class="relative min-h-screen">
        <?php
        $page = $_GET['page'] ?? 'welcome';
        $validPages = [
            'welcome' => 'components/events/welcome-flow/welcome.php',
            'duration' => 'components/events/welcome-flow/duration-select.php',
            'interests' => 'components/events/welcome-flow/interests-select.php',
            'location' => 'components/events/welcome-flow/location-select.php',
            'events' => 'components/events/event-feed.php'
        ];

        if (isset($validPages[$page])) {
            include $validPages[$page];
        } else {
            // 404 handling
            include 'components/404.php';
        }
        ?>
    </main>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();
    </script>
</body>

</html>