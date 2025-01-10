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

        .event-image-container {
            position: relative;
            width: 100%;
            padding-top: 56.25%;
            /* 16:9 Aspect Ratio */
            background: rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .event-image {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }

        .event-image.contain {
            object-fit: contain;
        }

        /* Add a subtle gradient overlay */
        .event-image-container::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(180deg, rgba(0, 0, 0, 0) 0%, rgba(0, 0, 0, 0.1) 100%);
            pointer-events: none;
        }

        /* Fallback for when image fails to load */
        .event-image-fallback {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            width: 100%;
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
            'events' => 'components/events/event-feed.php',
            'new-in-town' => 'components/events/new-in-town.php'  // Add this line
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