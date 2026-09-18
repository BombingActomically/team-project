<!-- C:\xampp\htdocs\Project2\frontend\components\header.php -->
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EVENTRA | Multi-College Event Management Platform</title>
    
    <!-- Meta tags for SEO -->
    <meta name="description" content="Discover, connect, and compete in inter-college events, fests, hackathons, and seminars. The ultimate destination for college students to network and showcase talent.">
    <meta name="keywords" content="college events, hackathon 2026, tech fests, inter-college events, sports tournament, cultural fest, workshop, code storm, robot wars">
    <meta name="author" content="EVENTRA Team">
    
    <!-- Google Fonts: Outfit (headings) & Inter (body) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons (via CDN) -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- Tailwind CSS (via Play CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brandBg: '#FBF9E4',           /* Pearl Perfect (Light Mode BG) */
                        brandBgSec: '#E6EDF5',        /* Soft light Midnight-blue section backdrop */
                        brandCard: '#FFFFFF',         /* Solid White Cards */
                        brandPrimary: '#5B88B2',      /* Ocean Blue Accent */
                        brandSecondary: '#122C4F',    /* Midnight (Primary Dark Blue buttons/text) */
                        brandHighlight: '#225B8D',    /* Accent Blue */
                        brandText: '#122C4F',         /* Midnight Text */
                        brandMuted: '#57718A',         /* Medium slate text */
                        brandBorder: 'rgba(18, 44, 79, 0.12)' /* Light Midnight borders */
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        outfit: ['Outfit', 'sans-serif']
                    }
                }
            }
        }
    </script>
    
    <!-- Custom Style Overrides -->
    <link rel="stylesheet" href="./assets/css/style.css">
</head>
<body class="bg-brandBg text-brandText antialiased selection:bg-brandPrimary/30 selection:text-brandSecondary">
    
    <!-- Background Grid -->
    <div class="fixed top-0 left-0 w-full h-full pointer-events-none z-0 overflow-hidden">
        <!-- Tech grid background -->
        <div class="absolute inset-0 grid-bg opacity-15"></div>
    </div>
