<!DOCTYPE html>
<html lang="en">

<head>
    <title>ERP | Aspire</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Playfair+Display:wght@700&display=swap"
        rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Animate -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">

    <style>
        .hero-title {
            font-size: 30px;
            font-weight: 800;
            letter-spacing: 1px;
            animation: fadeDown 1.2s ease forwards;
        }

        .hero-main {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 3.5rem;
            margin-top: 10px;

            background: linear-gradient(90deg,
                    #0552f9,
                    #18975b,
                    #9917a0);
            background-size: 300% 300%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;

            animation:
                gradientMove 5s ease infinite,
                zoomFade 1.3s ease forwards;
        }

        /* SUBTLE SHADOW */
        .text-glow {
            text-shadow: 0 5px 20px rgba(255, 77, 77, .4);
        }

        /* KEYFRAMES */
        @keyframes gradientMove {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        @keyframes fadeDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes zoomFade {
            from {
                opacity: 0;
                transform: scale(.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* MOBILE */
        @media (max-width: 768px) {
            .hero-main {
                font-size: 2.3rem;
            }
        }

        body {
            font-family: 'Segoe UI', sans-serif;
        }

        /* HERO SECTION */
        .hero {
            background-image: linear-gradient(to right, #ed6ea0 0%, #ec8c69 100%);
            min-height: 100vh;
            padding-top: 110px;
            /* navbar space */
            padding-bottom: 40px;
            color: #fff;
        }

        /* NAVBAR */
        .navbar {
            background: rgba(255, 255, 255, .95);
            box-shadow: 0 2px 10px rgba(0, 0, 0, .1);
        }

        /* GLASS CARD */
        .aspire-card {
            background: rgba(255, 255, 255, .15);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 30px;
            transition: all .4s ease;
            border: 1px solid rgba(255, 255, 255, .2);
        }

        .aspire-card:hover {
            transform: translateY(-10px) scale(1.03);
            box-shadow: 0 20px 40px rgba(0, 0, 0, .4);
        }

        .aspire-card img {
            width: 80px;
        }

        .btn-aspire {
            background: #3567dc;
            color: #fff;
            border-radius: 30px;
            padding: 10px 30px;
            transition: .3s;
        }

        .btn-aspire:hover {
            background: #b02a37;
            transform: scale(1.05);
        }


        .card-icon {
            font-size: 42px;
            color: #ffffff;
            margin-bottom: 12px;
            animation: floatIcon 3s ease-in-out infinite;
        }

        footer {
            background: #111;
            color: #aaa;
        }

        @keyframes floatIcon {
            0% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-6px);
            }

            100% {
                transform: translateY(0);
            }
        }

        /* NAVBAR BASE */
        .navbar-custom {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, .12);
        }

        /* BRAND */
        .brand-text {
            font-family: 'Inter', sans-serif;
            font-weight: 700;
            letter-spacing: 1px;
            color: #0d4a6b;
            font-size: 1.1rem;
        }

        /* SUPPORT INFO */
        .support-info {
            font-family: 'Inter', sans-serif;
            font-size: .95rem;
        }

        /* SUPPORT ITEM */
        .support-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #222;
            text-decoration: none;
            transition: .3s ease;
        }

        /* ICON */
        .support-icon {
            font-size: 1.2rem;
            color: #dc3545;
            animation: pulseIcon 2s infinite;
        }

        /* HOVER EFFECT */
        .support-item:hover {
            color: #dc3545;
            transform: translateY(-2px);
        }

        /* ICON ANIMATION */
        @keyframes pulseIcon {
            0% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.15);
                opacity: .7;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        /* MOBILE CLEAN */
        @media (max-width: 991px) {
            .brand-text {
                display: none;
            }
        }

        /* Logo */
        .logo-wrapper {
            width: 90px;
            height: 90px;
            margin: 0 auto 18px;
            border-radius: 50%;
            background: #f8f9fa;

            display: flex;
            align-items: center;
            justify-content: center;

            transform: translateZ(40px);
        }

        .logo-wrapper img {
            width: 60px;
        }

        /* =======================
   MOBILE OPTIMIZATION
======================= */
        @media (max-width: 768px) {

            .hero-title {
                font-size: 20px;
                letter-spacing: 0.5px;
            }

            .hero-main {
                font-size: 2.2rem;
                line-height: 1.2;
            }

            .aspire-card {
                padding: 22px 18px;
            }

            .aspire-card h5 {
                font-size: 1.05rem;
            }

            .btn-aspire {
                padding: 8px 24px;
                font-size: 0.95rem;
            }

            .logo-wrapper {
                width: 70px;
                height: 70px;
            }

            .logo-wrapper img {
                width: 45px;
            }

            footer {
                font-size: 0.85rem;
            }
        }

        @media (max-width: 576px) {
            .row.g-4 {
                gap: 1.2rem;
            }
        }

        @media (max-width: 576px) {
            .navbar-brand img {
                height: 48px;
            }

            .navbar {
                padding: 6px 0;
            }
        }

        @media (hover: none) {
            .aspire-card:hover {
                transform: none;
                box-shadow: none;
            }

            .btn-aspire:hover {
                transform: none;
            }
        }
    </style>
    
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg fixed-top navbar-custom">
        <div class="container">

            <!-- LOGO -->
            <a class="navbar-brand d-flex align-items-center gap-2" href="#">
                <img src="img/logo1.png" height="65">
            </a>

            <!-- SUPPORT INFO -->
            <div class="ms-auto d-none d-lg-flex align-items-center gap-4 support-info">

                <div class="support-item fw-bold">

                    <span>For Support <i class="bi bi-headset support-icon"></i> :</span>
                </div>

                <a href="tel:+914471255000" class="support-item">
                    <i class="bi bi-telephone-fill support-icon"></i>
                    <span>+91-44-7125 5000</span>
                </a>

                <a href="mailto:aspire@jrkresearch.com" class="support-item">
                    <i class="bi bi-envelope-fill support-icon"></i>
                    <span>aspire@jrkresearch.com</span>
                </a>

            </div>
        </div>
    </nav>


    <!-- HERO -->
    <section class="hero d-flex align-items-center text-center">
        <div class="container">

            <h2 class="hero-title text-white">
                Welcome to Our ERP System
            </h2>

            <h1 class="hero-main text-glow">
                Choose Your Aspire
            </h1>


            <div class="row justify-content-center mt-5 g-4">

                <!-- OLD ASPIRE -->
                <div class="col-md-5 animate__animated animate__fadeInLeft">
                    <div class="aspire-card text-center">
                        <div class="logo-wrapper">
                            <img src="img/logo.png" alt="JRK Logo">
                        </div>

                        <p class="mb-1 text-uppercase fw-bold">Click here for</p>
                        <h5 class="fw-bold">JRK ASPIRE (2019–24)</h5>

                        <a href="https://jrkappstore.com/jrk_asperp_fy19_24" class="btn btn-aspire mt-3">
                            Enter System
                        </a>
                    </div>
                </div>

                <!-- NEW ASPIRE -->
                <div class="col-md-5 animate__animated animate__fadeInRight">
                    <div class="aspire-card text-center">
                        <div class="logo-wrapper">
                            <img src="img/logo.png" alt="JRK Logo">
                        </div>

                        <p class="mb-1 text-uppercase fw-bold">Click here for</p>
                        <h5 class="fw-bold">JRK ASPIRE (2024–26)</h5>

                        <a href="https://jrkresearch.net/login" class="btn btn-aspire mt-3">
                            Enter System
                        </a>
                    </div>
                </div>

            </div>


        </div>
    </section>
    <!-- FOOTER -->
    <footer class="py-1 text-center">
        <div class="container">
            <p class="mb-0">&copy; {{ date('Y') }} Dr.JRK's Research and Pharmaceuticals Pvt Ltd</p>
        </div>
    </footer>

</body>

</html>