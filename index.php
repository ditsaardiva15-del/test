<?php
session_start();
require_once 'config/database.php';
$settings = getSettings();
$contact = getContactInfo();
$stats = getStats();
$menuItems = getMenuItems();
$testimonials = getTestimonials();
$promos = getPromos();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($settings['site_name']) ?> — Premium Coffee Experience</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400&family=DM+Sans:wght@300;400;500;600&family=Cormorant+Garamond:ital,wght@0,300;0,600;1,300&display=swap" rel="stylesheet">
<style>
  :root {
    --cream: #F5EDD8;
    --espresso: #2C1A0E;
    --caramel: #C07B3A;
    --latte: #D4A96A;
    --dark: #1A0F06;
    --mid: #4A2E17;
    --light-brown: #8B5E3C;
    --off-white: #FBF6EE;
    --gold: #D4A017;
    --text-muted: #7A5C3E;
    --green-accent: #2D5016;
    --shadow: rgba(44,26,14,0.15);
  }

  * { margin: 0; padding: 0; box-sizing: border-box; }
  html { scroll-behavior: smooth; }
  body {
    font-family: 'DM Sans', sans-serif;
    background: var(--dark);
    color: var(--cream);
    overflow-x: hidden;
  }

  /* ═══════════════ NAV ═══════════════ */
  nav {
    position: fixed; top: 0; width: 100%; z-index: 1000;
    padding: 1.2rem 3rem;
    display: flex; align-items: center; justify-content: space-between;
    background: rgba(26,15,6,0.92);
    backdrop-filter: blur(16px);
    border-bottom: 1px solid rgba(212,169,106,0.2);
    transition: all 0.3s;
  }
  .nav-logo {
    font-family: 'Playfair Display', serif;
    font-size: 1.6rem; font-weight: 900;
    color: var(--latte); letter-spacing: -0.5px;
  }
  .nav-logo span { color: var(--caramel); font-style: italic; }
  .nav-links { display: flex; gap: 2.5rem; list-style: none; }
  .nav-links a {
    color: var(--cream); text-decoration: none;
    font-size: 0.85rem; letter-spacing: 0.1em; text-transform: uppercase;
    font-weight: 500; opacity: 0.8; transition: opacity 0.2s;
  }
  .nav-links a:hover { opacity: 1; color: var(--latte); }
  .nav-right { display: flex; gap: 1rem; align-items: center; }
  .btn-admin {
    background: var(--caramel); color: white;
    border: none; padding: 0.55rem 1.3rem;
    border-radius: 30px; font-family: 'DM Sans', sans-serif;
    font-size: 0.8rem; font-weight: 600; cursor: pointer;
    text-transform: uppercase; letter-spacing: 0.08em;
    transition: background 0.2s; white-space: nowrap;
  }
  .btn-admin:hover { background: var(--latte); }

  /* Hamburger */
  .hamburger {
    display: none; flex-direction: column; gap: 5px;
    background: none; border: none; cursor: pointer; padding: 4px;
  }
  .hamburger span {
    display: block; width: 24px; height: 2px;
    background: var(--cream); border-radius: 2px;
    transition: all 0.3s;
  }
  .hamburger.open span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
  .hamburger.open span:nth-child(2) { opacity: 0; }
  .hamburger.open span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }

  /* Mobile nav dropdown */
  .nav-mobile {
    display: none;
    position: fixed; top: 64px; left: 0; right: 0; z-index: 999;
    background: rgba(26,15,6,0.98);
    backdrop-filter: blur(16px);
    border-bottom: 1px solid rgba(212,169,106,0.2);
    padding: 1.5rem 2rem 2rem;
    flex-direction: column; gap: 0;
    transform: translateY(-10px); opacity: 0;
    transition: all 0.3s;
  }
  .nav-mobile.open {
    display: flex; opacity: 1; transform: translateY(0);
  }
  .nav-mobile a {
    color: var(--cream); text-decoration: none;
    font-size: 1rem; font-weight: 500; padding: 0.85rem 0;
    border-bottom: 1px solid rgba(245,237,216,0.07);
    display: block; transition: color 0.2s;
  }
  .nav-mobile a:hover { color: var(--latte); }
  .nav-mobile .btn-admin {
    margin-top: 1.2rem; width: 100%; padding: 0.8rem;
    font-size: 0.9rem; text-align: center;
  }

  /* ═══════════════ HERO ═══════════════ */
  #hero {
    min-height: 100vh;
    background: radial-gradient(ellipse at 60% 40%, #3D2010 0%, var(--dark) 65%);
    display: flex; align-items: center;
    position: relative; overflow: hidden;
    padding: 8rem 3rem 4rem;
  }
  .hero-bg-text {
    position: absolute; right: -2rem; top: 50%; transform: translateY(-50%);
    font-family: 'Playfair Display', serif;
    font-size: clamp(8rem, 15vw, 18rem);
    font-weight: 900; color: rgba(192,123,58,0.06);
    line-height: 1; pointer-events: none; user-select: none;
    white-space: nowrap;
  }
  .hero-content { max-width: 650px; position: relative; z-index: 1; }
  .hero-badge {
    display: inline-block;
    background: rgba(192,123,58,0.15);
    border: 1px solid rgba(192,123,58,0.4);
    color: var(--latte); font-size: 0.75rem;
    padding: 0.4rem 1rem; border-radius: 30px;
    letter-spacing: 0.15em; text-transform: uppercase;
    margin-bottom: 1.5rem;
  }
  .hero-title {
    font-family: 'Playfair Display', serif;
    font-size: clamp(2.8rem, 6vw, 6rem);
    font-weight: 900; line-height: 1.05;
    margin-bottom: 1rem;
  }
  .hero-title .italic { font-style: italic; color: var(--caramel); }
  .hero-subtitle {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.25rem; font-weight: 300; font-style: italic;
    color: var(--latte); opacity: 0.85;
    margin-bottom: 2rem; line-height: 1.6;
  }
  .hero-desc {
    font-size: 0.95rem; color: rgba(245,237,216,0.65);
    line-height: 1.8; margin-bottom: 2.5rem; max-width: 480px;
  }
  .hero-btns { display: flex; gap: 1rem; align-items: center; flex-wrap: wrap; }
  .btn-primary {
    background: var(--caramel);
    color: white; border: none;
    padding: 0.9rem 2.2rem; border-radius: 50px;
    font-family: 'DM Sans', sans-serif;
    font-size: 0.9rem; font-weight: 600;
    cursor: pointer; text-decoration: none;
    display: inline-block; transition: all 0.25s;
    letter-spacing: 0.03em;
  }
  .btn-primary:hover { background: var(--latte); transform: translateY(-2px); }
  .btn-outline {
    background: transparent;
    color: var(--cream); border: 1px solid rgba(245,237,216,0.3);
    padding: 0.9rem 2.2rem; border-radius: 50px;
    font-family: 'DM Sans', sans-serif;
    font-size: 0.9rem; font-weight: 500;
    cursor: pointer; text-decoration: none;
    display: inline-block; transition: all 0.25s;
  }
  .btn-outline:hover { border-color: var(--latte); color: var(--latte); }
  .hero-image {
    position: absolute; right: 5%; top: 50%; transform: translateY(-50%);
    width: 420px; height: 500px;
  }
  .coffee-cup-svg { width: 100%; height: 100%; }
  .hero-stats {
    display: flex; gap: 2rem; margin-top: 3rem;
    padding-top: 2rem; border-top: 1px solid rgba(245,237,216,0.1);
    flex-wrap: wrap;
  }
  .stat-num {
    font-family: 'Playfair Display', serif;
    font-size: 2rem; font-weight: 700; color: var(--latte);
  }
  .stat-label { font-size: 0.8rem; color: rgba(245,237,216,0.5); margin-top: 2px; }

  /* ═══════════════ SECTIONS ═══════════════ */
  section { padding: 6rem 3rem; }
  .section-label {
    font-size: 0.7rem; letter-spacing: 0.25em; text-transform: uppercase;
    color: var(--caramel); margin-bottom: 0.8rem; font-weight: 600;
  }
  .section-title {
    font-family: 'Playfair Display', serif;
    font-size: clamp(2rem, 3.5vw, 3.2rem);
    font-weight: 700; line-height: 1.15; margin-bottom: 1rem;
  }
  .section-title em { font-style: italic; color: var(--caramel); }
  .section-sub {
    color: rgba(245,237,216,0.55); font-size: 1rem;
    line-height: 1.7; max-width: 500px;
  }

  /* ═══════════════ MENU ═══════════════ */
  #menu { background: var(--espresso); }
  .menu-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 3.5rem; flex-wrap: wrap; gap: 1.5rem; }
  .menu-tabs { display: flex; gap: 0.5rem; background: rgba(255,255,255,0.05); border-radius: 50px; padding: 0.3rem; flex-wrap: wrap; }
  .menu-tab {
    padding: 0.55rem 1.3rem; border-radius: 50px; border: none;
    background: transparent; color: rgba(245,237,216,0.5);
    cursor: pointer; font-family: 'DM Sans', sans-serif;
    font-size: 0.85rem; font-weight: 500; transition: all 0.2s;
  }
  .menu-tab.active { background: var(--caramel); color: white; }
  .menu-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 1.5rem; }
  .menu-card {
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(245,237,216,0.08);
    border-radius: 16px; padding: 1.5rem;
    transition: all 0.3s; cursor: pointer;
    position: relative; overflow: hidden;
  }
  .menu-card::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px;
    background: linear-gradient(90deg, var(--caramel), transparent);
    opacity: 0; transition: opacity 0.3s;
  }
  .menu-card:hover { border-color: rgba(192,123,58,0.3); transform: translateY(-4px); }
  .menu-card:hover::before { opacity: 1; }
  .menu-card-emoji { font-size: 2.5rem; margin-bottom: 1rem; }
  .menu-card-name {
    font-family: 'Playfair Display', serif;
    font-size: 1.15rem; font-weight: 700; margin-bottom: 0.4rem;
  }
  .menu-card-desc { font-size: 0.85rem; color: rgba(245,237,216,0.5); line-height: 1.6; margin-bottom: 1rem; }
  .menu-card-footer { display: flex; justify-content: space-between; align-items: center; }
  .menu-price {
    font-family: 'Playfair Display', serif;
    font-size: 1.2rem; font-weight: 700; color: var(--latte);
  }
  .menu-badge { font-size: 0.7rem; padding: 0.25rem 0.7rem; border-radius: 20px; font-weight: 600; letter-spacing: 0.05em; }
  .badge-hot { background: rgba(180,60,30,0.2); color: #E8855A; border: 1px solid rgba(180,60,30,0.3); }
  .badge-new { background: rgba(45,80,22,0.25); color: #7EC850; border: 1px solid rgba(45,80,22,0.4); }
  .badge-popular { background: rgba(192,123,58,0.2); color: var(--latte); border: 1px solid rgba(192,123,58,0.35); }

  /* ═══════════════ ABOUT ═══════════════ */
  #about { background: var(--dark); }
  .about-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 5rem; align-items: center; }
  .about-visual {
    background: radial-gradient(ellipse, #3D2010 20%, #1A0F06 80%);
    border-radius: 20px; padding: 3rem;
    border: 1px solid rgba(192,123,58,0.15);
    display: flex; align-items: center; justify-content: center;
    min-height: 350px; position: relative; overflow: hidden;
  }
  .about-bg-circle {
    position: absolute; border-radius: 50%; opacity: 0.12;
    background: var(--caramel);
  }
  .about-center-text {
    font-family: 'Playfair Display', serif;
    font-size: 5rem; font-weight: 900; font-style: italic;
    color: var(--latte); text-align: center; line-height: 1;
    position: relative; z-index: 1;
  }
  .about-features { display: grid; grid-template-columns: 1fr 1fr; gap: 1.2rem; margin-top: 2.5rem; }
  .about-feature {
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(245,237,216,0.08);
    border-radius: 12px; padding: 1.2rem;
  }
  .feature-icon { font-size: 1.5rem; margin-bottom: 0.5rem; }
  .feature-title { font-weight: 600; font-size: 0.9rem; margin-bottom: 0.3rem; }
  .feature-text { font-size: 0.8rem; color: rgba(245,237,216,0.5); line-height: 1.5; }

  /* ═══════════════ TESTIMONIALS ═══════════════ */
  #testimonials { background: var(--espresso); }
  .testi-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem; margin-top: 3rem; }
  .testi-card {
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(245,237,216,0.08);
    border-radius: 16px; padding: 2rem; position: relative;
  }
  .testi-quote { font-size: 3rem; color: var(--caramel); opacity: 0.3; line-height: 1; margin-bottom: 0.5rem; font-family: serif; }
  .testi-text { font-size: 0.95rem; line-height: 1.75; color: rgba(245,237,216,0.8); margin-bottom: 1.5rem; font-style: italic; }
  .testi-author { display: flex; align-items: center; gap: 0.8rem; }
  .testi-avatar {
    width: 42px; height: 42px; border-radius: 50%;
    background: var(--caramel); display: flex; align-items: center;
    justify-content: center; font-weight: 700; font-size: 1rem; color: white;
    flex-shrink: 0;
  }
  .testi-name { font-weight: 600; font-size: 0.9rem; }
  .testi-role { font-size: 0.8rem; color: rgba(245,237,216,0.45); }
  .stars { color: var(--gold); font-size: 0.85rem; margin-bottom: 1rem; }

  /* ═══════════════ GALLERY ═══════════════ */
  #gallery { background: var(--dark); }
  .gallery-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    grid-template-rows: repeat(2, 200px);
    gap: 1rem; margin-top: 3rem;
  }
  .gallery-item {
    border-radius: 12px; overflow: hidden;
    position: relative; cursor: pointer;
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(245,237,216,0.08);
    display: flex; align-items: center; justify-content: center;
    font-size: 3rem; transition: all 0.3s;
  }
  .gallery-item:hover { border-color: rgba(192,123,58,0.4); transform: scale(1.01); }
  .gallery-item:nth-child(1) { grid-column: span 2; grid-row: span 2; font-size: 5rem; }
  .gallery-item:nth-child(4) { grid-column: span 2; }

  /* ═══════════════ CONTACT ═══════════════ */
  #contact { background: var(--espresso); }
  .contact-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; margin-top: 3rem; }
  .contact-info-item { display: flex; gap: 1rem; margin-bottom: 1.5rem; align-items: flex-start; }
  .contact-icon { font-size: 1.5rem; color: var(--caramel); flex-shrink: 0; margin-top: 0.1rem; }
  .contact-label { font-size: 0.75rem; color: var(--caramel); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.3rem; font-weight: 600; }
  .contact-value { font-size: 0.95rem; color: rgba(245,237,216,0.8); line-height: 1.5; }
  .contact-form { display: flex; flex-direction: column; gap: 1rem; }
  .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
  .form-group { display: flex; flex-direction: column; gap: 0.4rem; }
  .form-label { font-size: 0.8rem; color: rgba(245,237,216,0.6); font-weight: 500; }
  .form-input, .form-textarea, .form-select {
    background: rgba(255,255,255,0.06);
    border: 1px solid rgba(245,237,216,0.12);
    border-radius: 10px; padding: 0.8rem 1rem;
    color: var(--cream); font-family: 'DM Sans', sans-serif;
    font-size: 0.9rem; outline: none; transition: border 0.2s;
    width: 100%;
  }
  .form-input:focus, .form-textarea:focus, .form-select:focus { border-color: var(--caramel); }
  .form-textarea { resize: vertical; min-height: 100px; }
  .form-select option { background: var(--espresso); }

  /* ═══════════════ FOOTER ═══════════════ */
  footer {
    background: var(--dark);
    border-top: 1px solid rgba(192,123,58,0.15);
    padding: 3rem;
    display: flex; justify-content: space-between; align-items: center;
    flex-wrap: wrap; gap: 1.5rem;
  }
  .footer-logo {
    font-family: 'Playfair Display', serif;
    font-size: 1.3rem; font-weight: 900; color: var(--latte);
  }
  .footer-copy { font-size: 0.8rem; color: rgba(245,237,216,0.35); }
  .footer-links { display: flex; gap: 1.5rem; flex-wrap: wrap; }
  .footer-links a { font-size: 0.8rem; color: rgba(245,237,216,0.45); text-decoration: none; }
  .footer-links a:hover { color: var(--latte); }

  /* Scrollbar */
  ::-webkit-scrollbar { width: 6px; }
  ::-webkit-scrollbar-track { background: transparent; }
  ::-webkit-scrollbar-thumb { background: rgba(192,123,58,0.3); border-radius: 3px; }

  /* Animations */
  .fade-up {
    opacity: 0; transform: translateY(30px);
    animation: fadeUp 0.8s ease forwards;
  }
  .fade-up:nth-child(1) { animation-delay: 0.1s; }
  .fade-up:nth-child(2) { animation-delay: 0.25s; }
  .fade-up:nth-child(3) { animation-delay: 0.4s; }
  .fade-up:nth-child(4) { animation-delay: 0.55s; }
  .fade-up:nth-child(5) { animation-delay: 0.7s; }
  @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }

  /* ═══════════════ RESPONSIVE ═══════════════ */

  /* Tablet (768px - 1024px) */
  @media (max-width: 1024px) {
    nav { padding: 1rem 2rem; }
    .nav-links { gap: 1.5rem; }
    #hero { padding: 8rem 2rem 4rem; }
    .hero-image { width: 300px; height: 360px; right: 2%; }
    .hero-content { max-width: 520px; }
    .about-grid { grid-template-columns: 1fr; gap: 3rem; }
    .about-visual { min-height: 250px; }
    section { padding: 4rem 2rem; }
    .contact-grid { gap: 2.5rem; }
  }

  /* Mobile (max 768px) */
  @media (max-width: 768px) {
    nav { padding: 1rem 1.25rem; }
    .nav-links { display: none; }
    .nav-right .btn-admin { display: none; }
    .hamburger { display: flex; }

    #hero {
      padding: 7rem 1.25rem 4rem;
      align-items: flex-start;
      min-height: 100svh;
    }
    .hero-bg-text { font-size: 7rem; right: -1rem; opacity: 0.04; }
    .hero-image {
      position: relative; right: auto; top: auto;
      transform: none; width: 100%; height: 220px;
      margin: 2rem 0 0;
    }
    .hero-content { max-width: 100%; }
    .hero-title { font-size: clamp(2.5rem, 11vw, 3.5rem); }
    .hero-subtitle { font-size: 1.1rem; }
    .hero-desc { font-size: 0.9rem; max-width: 100%; }
    .hero-btns { gap: 0.75rem; }
    .hero-btns .btn-primary,
    .hero-btns .btn-outline {
      flex: 1; text-align: center; padding: 0.85rem 1.5rem;
      font-size: 0.85rem;
    }
    .hero-stats {
      gap: 1.5rem; flex-wrap: nowrap; justify-content: space-between;
    }
    .hero-stats > div { flex: 1; }
    .stat-num { font-size: 1.5rem; }
    .stat-label { font-size: 0.7rem; }

    section { padding: 3.5rem 1.25rem; }

    /* Menu */
    .menu-header { flex-direction: column; align-items: flex-start; margin-bottom: 2rem; gap: 1.2rem; }
    .menu-tabs { width: 100%; justify-content: space-between; }
    .menu-tab { flex: 1; text-align: center; padding: 0.55rem 0.5rem; font-size: 0.78rem; }
    .menu-grid { grid-template-columns: 1fr 1fr; gap: 1rem; }
    .menu-card { padding: 1.2rem; }
    .menu-card-emoji { font-size: 2rem; margin-bottom: 0.75rem; }
    .menu-card-name { font-size: 1rem; }
    .menu-card-desc { font-size: 0.8rem; }
    .menu-price { font-size: 1rem; }

    /* About */
    .about-grid { grid-template-columns: 1fr; gap: 2rem; }
    .about-visual { min-height: 200px; padding: 2rem; }
    .about-center-text { font-size: 3.5rem; }
    .about-features { grid-template-columns: 1fr 1fr; gap: 0.75rem; }
    .about-feature { padding: 1rem; }

    /* Testimonials */
    .testi-grid { grid-template-columns: 1fr; gap: 1rem; margin-top: 2rem; }

    /* Gallery */
    .gallery-grid {
      grid-template-columns: repeat(2, 1fr);
      grid-template-rows: auto;
      gap: 0.75rem;
    }
    .gallery-item:nth-child(1) { grid-column: span 2; grid-row: span 1; height: 150px; font-size: 3.5rem; }
    .gallery-item:nth-child(4) { grid-column: span 2; }
    .gallery-item { height: 100px; font-size: 2.2rem; }

    /* Contact */
    .contact-grid { grid-template-columns: 1fr; gap: 2.5rem; margin-top: 2rem; }
    .form-row { grid-template-columns: 1fr; }

    /* Footer */
    footer {
      padding: 2rem 1.25rem;
      flex-direction: column; align-items: flex-start; gap: 1.2rem;
    }
    .footer-links { gap: 1rem; }
  }

  /* Very small phones (max 420px) */
  @media (max-width: 420px) {
    .menu-grid { grid-template-columns: 1fr; }
    .hero-stats { justify-content: flex-start; gap: 1.5rem; flex-wrap: wrap; }
    .hero-stats > div { min-width: 80px; }
    .about-features { grid-template-columns: 1fr; }
    nav { padding: 0.9rem 1rem; }
    .nav-logo { font-size: 1.3rem; }
  }
</style>
</head>
<body>

<!-- TOAST NOTIFICATION -->
<div class="notification" id="toast" style="position:fixed;bottom:2rem;right:2rem;z-index:99999;background:var(--caramel);color:white;padding:0.9rem 1.5rem;border-radius:10px;font-size:0.875rem;font-weight:600;box-shadow:0 8px 30px rgba(0,0,0,0.3);transform:translateY(100px);opacity:0;transition:all 0.3s;pointer-events:none;max-wi
