<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Geist:wght@100..900&display=swap');

        /* AMOLED Black Theme Overrides */
        body, .bg-dark {
            background-color: #000000 !important; /* Pure black */
            color: #E0E0E0 !important; /* Off-white text */
            font-family: "Geist", sans-serif !important;
        }
        
        .card, .bg-dark-subtle {
            background-color: #0A0A0A !important; /* Very dark grey for cards */
            border-color: #333333 !important; /* Lighter grey borders */
        }
        
        .navbar, .card-header {
            background-color: #111111 !important; /* Slightly elevated black */
            border-color: #333333 !important;
        }

        .form-control, .form-control:focus {
            background-color: #1A1A1A !important;
            border-color: #444444 !important;
            color: #FFFFFF !important;
        }
        
        /* Remove Bootstrap's default blue glow on inputs */
        .form-control:focus, .btn:focus {
            box-shadow: 0 0 0 0.25rem rgba(255, 255, 255, 0.1) !important;
            border-color: #666666 !important;
        }

        /* Override text selection highlight */
        ::selection {
            background-color: #444444 !important;
            color: #FFFFFF !important;
        }
        
        /* Subdued Links */
        a {
            color: #A0A0A0;
            text-decoration: none;
            transition: color 0.2s ease;
        }
        a:hover {
            color: #FFFFFF;
            text-decoration: underline;
        }

        /* Essential Accents */
        .text-primary, .text-info { color: #888888 !important; }
        .text-danger { color: #777777 !important; }
        
        .greentext {
            color: #789922 !important;
        }
        
        .btn-primary {
            background-color: #333333;
            border-color: #555555;
            color: #FFFFFF;
        }
        .btn-primary:hover, .btn-primary:focus, .btn-primary:active, .btn-primary.active {
            background-color: #444444 !important;
            border-color: #666666 !important;
            color: #FFFFFF !important;
        }

        .btn-outline-primary {
            color: #CCCCCC;
            border-color: #555555;
        }
        .btn-outline-primary:hover, .btn-outline-primary:focus, .btn-outline-primary:active, .btn-outline-primary.active {
            background-color: #222222 !important;
            color: #FFFFFF !important;
            border-color: #777777 !important;
        }
        
        hr.border-secondary {
            border-color: #333333 !important;
        }
    </style>

    <title><?php echo $title ?? 'BORD'; ?></title>
</head>
<body class="bg-dark text-light">

<!-- Global Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark border-bottom border-secondary mb-4 shadow-sm">
    <div class="container">
        <!-- Brand / Home Link -->
        <a class="navbar-brand fw-bold" href="/">BORD</a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="/">[Home]</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="javascript:history.back()">[Back]</a>
                </li>
            </ul>
            
            <!-- Auth Status -->
            <span class="navbar-text">
                <?php if (isset($_SESSION['userid'])): ?>
                    Welcome, <strong class="text-white"><?php echo htmlspecialchars($_SESSION['username']); ?></strong> | 
                    <a href="/logout" class="text-decoration-none text-danger fw-bold">[Logout]</a>
                <?php else: ?>
                    <a href="/login" class="text-decoration-none">[Login]</a> | 
                    <a href="/register" class="text-decoration-none">[Register]</a>
                <?php endif; ?>
            </span>
        </div>
    </div>
</nav>

<!-- Main Container for View Content -->
<main class="container">
