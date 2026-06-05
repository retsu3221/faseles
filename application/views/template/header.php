<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FASE Les</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap"/>
    <link rel="stylesheet" href="<?= base_url('assets/css/style1.css'); ?>">
    <link rel="icon" type="image/x-icon" href="<?= base_url('assets/img/Logo.png'); ?>">

    <style>
        /* ---- Load animations (navbar & hero) ---- */
        .navbar {
            animation: slideDown 0.5s ease both;
        }
        .hero-section h1 {
            animation: fadeInDown 0.9s ease both 0.3s;
        }
        .hero-section .lead {
            animation: fadeInUp 0.8s ease both 0.6s;
        }
        .hero-section .btn-warning {
            animation: zoomIn 0.7s ease both 0.9s;
        }

        /* ---- Scroll animation base ---- */
        .will-anim {
            opacity: 0;
            transform: translateY(40px);
            transition: opacity 0.65s ease, transform 0.65s ease;
        }
        .will-anim.from-left  { transform: translateX(-50px); }
        .will-anim.from-right { transform: translateX(50px); }
        .will-anim.appeared {
            opacity: 1;
            transform: none;
        }

        /* ---- Keyframes ---- */
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-100%); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-35px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(35px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes zoomIn {
            from { opacity: 0; transform: scale(0.8); }
            to   { opacity: 1; transform: scale(1); }
        }
    </style>
</head>

<body>
