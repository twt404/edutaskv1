<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $mode = isset($mode) ? $mode : 'login'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= html_escape($title ?? 'Login — EduTask') ?></title>
<!-- Self-hosted fonts (offline LAN) -->
<link rel="stylesheet" href="<?= base_url('assets/fonts/fonts.css') ?>">
<style>
  :root {
    --sky: #38BDF8;
    --sky-deep: #0EA5E9;
    --sky-soft: #E0F5FF;
    --mint: #6EE7B7;
    --mint-deep: #10B981;
    --mint-soft: #E8FFF6;
    --ink: #0F2A43;
    --muted: #5B7185;
    --line: #D9ECF5;
    --white: #FFFFFF;
    --shadow: 0 30px 80px -30px rgba(14, 116, 144, 0.35);
  }

  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  html, body { min-height: 100%; }

  body {
    font-family: 'Inter', sans-serif;
    color: var(--ink);
    background:
      radial-gradient(circle at 8% 10%, rgba(56, 189, 248, 0.20), transparent 32%),
      radial-gradient(circle at 92% 88%, rgba(110, 231, 183, 0.24), transparent 34%),
      linear-gradient(135deg, #F4FCFF 0%, #EDF9FF 48%, #F2FFFA 100%);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 28px;
    overflow-x: hidden;
  }

  .decor {
    position: fixed;
    inset: 0;
    pointer-events: none;
    overflow: hidden;
    z-index: 0;
  }

  .decor svg {
    position: absolute;
    opacity: 0.55;
    filter: drop-shadow(0 18px 20px rgba(14, 116, 144, 0.08));
    animation: drift 16s ease-in-out infinite;
  }

  .decor .d1 { width: 74px; top: 8%; left: 6%; animation-delay: -2s; }
  .decor .d2 { width: 58px; top: 18%; right: 7%; animation-delay: -6s; animation-duration: 19s; }
  .decor .d3 { width: 52px; bottom: 12%; left: 10%; animation-delay: -9s; animation-duration: 21s; }
  .decor .d4 { width: 84px; bottom: 7%; right: 4%; animation-delay: -4s; }
  .decor .d5 { width: 38px; top: 46%; left: 3%; animation-delay: -12s; animation-duration: 23s; }
  .decor .d6 { width: 46px; top: 58%; right: 3%; animation-delay: -7s; animation-duration: 18s; }

  @keyframes drift {
    0%, 100% { transform: translate3d(0, 0, 0) rotate(0deg); }
    50% { transform: translate3d(0, -14px, 0) rotate(3deg); }
  }

  .page {
    position: relative;
    z-index: 1;
    width: 100%;
    max-width: 1120px;
    display: grid;
    grid-template-columns: minmax(0, 1.08fr) minmax(0, 0.92fr);
    background: rgba(255, 255, 255, 0.82);
    border: 1px solid rgba(255, 255, 255, 0.9);
    border-radius: 32px;
    padding: 16px;
    box-shadow: var(--shadow);
    backdrop-filter: blur(18px);
  }

  /* ── Left showcase ───────────────────────────────────────────── */
  .showcase {
    position: relative;
    overflow: hidden;
    min-height: 620px;
    border-radius: 24px;
    padding: 38px 40px;
    background:
      radial-gradient(circle at 82% 18%, rgba(255, 255, 255, 0.32), transparent 30%),
      linear-gradient(145deg, #22B8F2 0%, #38BDF8 46%, #34D6C4 100%);
    color: #FFFFFF;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }

  .showcase::before,
  .showcase::after {
    content: "";
    position: absolute;
    border-radius: 50%;
    pointer-events: none;
  }

  .showcase::before {
    width: 320px;
    height: 320px;
    right: -120px;
    top: -130px;
    background: rgba(255, 255, 255, 0.14);
  }

  .showcase::after {
    width: 180px;
    height: 180px;
    left: -80px;
    bottom: 40px;
    background: rgba(110, 231, 183, 0.28);
  }

  .brand {
    position: relative;
    z-index: 1;
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .brand-mark {
    width: 46px;
    height: 46px;
    border-radius: 14px;
    background: rgba(255, 255, 255, 0.96);
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 10px 24px -10px rgba(6, 78, 108, 0.55);
  }

  .brand-mark svg { width: 27px; height: 27px; }

  .brand-name {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 1.35rem;
    font-weight: 800;
    letter-spacing: -0.02em;
  }

  .brand-tag {
    display: block;
    font-size: 0.72rem;
    font-weight: 500;
    color: rgba(255, 255, 255, 0.82);
    margin-top: 1px;
  }

  .hero-copy {
    position: relative;
    z-index: 1;
    max-width: 470px;
    margin-top: 20px;
  }

  .hero-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    padding: 7px 12px;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.18);
    border: 1px solid rgba(255, 255, 255, 0.28);
    margin-bottom: 18px;
  }

  .hero-copy h1 {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: clamp(2rem, 3.2vw, 3rem);
    line-height: 1.08;
    letter-spacing: -0.035em;
    font-weight: 800;
    margin-bottom: 16px;
  }

  .hero-copy h1 span {
    color: #DFFFF4;
    position: relative;
    z-index: 0;
    white-space: nowrap;
  }

  .hero-copy h1 span::after {
    content: "";
    position: absolute;
    left: 2px;
    right: 2px;
    bottom: 2px;
    height: 8px;
    background: rgba(110, 231, 183, 0.52);
    border-radius: 99px;
    z-index: -1;
  }

  .hero-copy p {
    font-size: 0.96rem;
    line-height: 1.65;
    color: rgba(255, 255, 255, 0.88);
    max-width: 410px;
  }

  .illustration {
    position: relative;
    z-index: 1;
    height: 230px;
    margin: 24px 0 10px;
  }

  .illu-board {
    position: absolute;
    left: 50%;
    top: 46%;
    width: 210px;
    height: 132px;
    transform: translate(-50%, -50%) rotate(-3deg);
    background: #FFFFFF;
    border-radius: 20px;
    box-shadow: 0 24px 45px -22px rgba(6, 78, 108, 0.55);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 13px;
    padding: 22px;
  }

  .illu-board::before {
    content: "";
    position: absolute;
    inset: 10px;
    border: 2px dashed #BFEFFF;
    border-radius: 13px;
  }

  .illu-shape {
    position: absolute;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 16px 32px -18px rgba(6, 78, 108, 0.6);
    animation: float 6.5s ease-in-out infinite;
  }

  .illu-cap {
    width: 82px;
    height: 82px;
    border-radius: 24px;
    background: #0F2A43;
    left: 16px;
    top: 18px;
    animation-delay: -1.5s;
  }

  .illu-book {
    width: 92px;
    height: 92px;
    border-radius: 28px;
    background: #E8FFF6;
    right: 14px;
    top: 6px;
    animation-delay: -3.5s;
  }

  .illu-pencil {
    width: 70px;
    height: 70px;
    border-radius: 20px;
    background: #FFFFFF;
    right: 44px;
    bottom: 0;
    animation-delay: -5s;
  }

  .illu-spark {
    position: absolute;
    width: 13px;
    height: 13px;
    border-radius: 50%;
    background: #FFFFFF;
    opacity: 0.8;
    animation: twinkle 3.5s ease-in-out infinite;
  }

  .illu-spark.s1 { left: 8%; top: 8%; }
  .illu-spark.s2 { right: 6%; top: 42%; animation-delay: -1.2s; }
  .illu-spark.s3 { left: 28%; bottom: 4%; animation-delay: -2.1s; width: 8px; height: 8px; }

  @keyframes float {
    0%, 100% { transform: translateY(0) rotate(0deg); }
    50% { transform: translateY(-9px) rotate(2deg); }
  }

  @keyframes twinkle {
    0%, 100% { opacity: 0.35; transform: scale(0.85); }
    50% { opacity: 0.95; transform: scale(1.2); }
  }

  .feature-row {
    position: relative;
    z-index: 1;
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 10px;
  }

  .feature {
    display: flex;
    align-items: center;
    gap: 9px;
    padding: 11px 12px;
    border-radius: 13px;
    background: rgba(255, 255, 255, 0.14);
    border: 1px solid rgba(255, 255, 255, 0.22);
    backdrop-filter: blur(6px);
    font-size: 0.76rem;
    font-weight: 600;
    line-height: 1.3;
    color: rgba(255, 255, 255, 0.92);
  }

  .feature svg { flex-shrink: 0; }

  /* ── Right login ─────────────────────────────────────────────── */
  .login-panel {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 28px 34px;
  }

  .login-card {
    width: 100%;
    max-width: 380px;
  }

  .login-head {
    margin-bottom: 26px;
  }

  .login-head .mini-brand {
    display: none;
    align-items: center;
    gap: 9px;
    margin-bottom: 20px;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-weight: 800;
    font-size: 1.08rem;
    color: var(--ink);
  }

  .mini-brand .mini-mark {
    width: 38px;
    height: 38px;
    border-radius: 11px;
    background: var(--sky-soft);
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .login-head h2 {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 1.8rem;
    font-weight: 800;
    letter-spacing: -0.03em;
    color: var(--ink);
    margin-bottom: 8px;
  }

  .login-head p {
    color: var(--muted);
    font-size: 0.9rem;
    line-height: 1.55;
  }

  .auth-switch {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 5px;
    padding: 5px;
    margin-bottom: 22px;
    border-radius: 14px;
    background: #EFF9FF;
    border: 1px solid #D9F1FB;
  }

  .auth-switch a {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    padding: 9px 10px;
    border-radius: 10px;
    font-size: 0.8rem;
    font-weight: 700;
    color: #5B7185;
    transition: background 0.2s ease, color 0.2s ease, box-shadow 0.2s ease;
  }

  .auth-switch a:hover { color: var(--sky-deep); }

  .auth-switch a.active {
    background: #FFFFFF;
    color: var(--ink);
    box-shadow: 0 8px 18px -12px rgba(14, 116, 144, 0.55);
  }

  .alert-success {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    background: #E8FFF6;
    border: 1px solid #A7F3D0;
    color: #047857;
    border-radius: 12px;
    padding: 12px 14px;
    font-size: 0.82rem;
    line-height: 1.5;
    margin-bottom: 18px;
  }

  .alert-success svg { flex-shrink: 0; margin-top: 1px; }

  .form-note {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    margin: 4px 0 18px;
    padding: 10px 12px;
    border-radius: 11px;
    background: #F1FBFF;
    border: 1px solid #D9F1FB;
    color: #5B7185;
    font-size: 0.73rem;
    line-height: 1.5;
  }

  .form-note svg { flex-shrink: 0; margin-top: 1px; color: var(--sky-deep); }

  .field-hint {
    margin-top: 6px;
    font-size: 0.71rem;
    line-height: 1.45;
    color: #7D97AA;
  }

  .alert-error {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    background: #FFF1F2;
    border: 1px solid #FECDD3;
    color: #BE123C;
    border-radius: 12px;
    padding: 12px 14px;
    font-size: 0.82rem;
    line-height: 1.5;
    margin-bottom: 18px;
    animation: shake 0.45s ease;
  }

  .alert-error svg { flex-shrink: 0; margin-top: 1px; }
  .alert-error p { margin: 0; }
  .alert-error p + p { margin-top: 4px; }

  @keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-4px); }
    75% { transform: translateX(4px); }
  }

  .field {
    margin-bottom: 16px;
  }

  .field label {
    display: block;
    font-size: 0.79rem;
    font-weight: 700;
    color: #27445C;
    margin-bottom: 7px;
  }

  .input-wrap {
    position: relative;
    display: flex;
    align-items: center;
  }

  .input-wrap .field-icon {
    position: absolute;
    left: 15px;
    color: #7DA3BC;
    display: flex;
    pointer-events: none;
    transition: color 0.2s ease;
  }

  .input-wrap input,
  .input-wrap select {
    width: 100%;
    height: 52px;
    border: 1.5px solid var(--line);
    background: #F7FCFF;
    border-radius: 14px;
    padding: 0 47px 0 46px;
    font-family: inherit;
    font-size: 0.9rem;
    color: var(--ink);
    outline: none;
    transition: background 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
  }

  .input-wrap select {
    padding-right: 44px;
    cursor: pointer;
    appearance: none;
    -webkit-appearance: none;
  }

  .input-wrap select:invalid { color: #9BB4C5; }

  .input-wrap .select-caret {
    position: absolute;
    right: 15px;
    color: #7DA3BC;
    display: flex;
    pointer-events: none;
  }

  .input-wrap input::placeholder { color: #9BB4C5; }

  .input-wrap input:focus,
  .input-wrap select:focus {
    background: #FFFFFF;
    border-color: var(--sky);
    box-shadow: 0 0 0 4px rgba(56, 189, 248, 0.16);
  }

  .input-wrap:focus-within .field-icon { color: var(--sky-deep); }

  .toggle-password {
    position: absolute;
    right: 12px;
    width: 32px;
    height: 32px;
    border: 0;
    border-radius: 9px;
    background: transparent;
    color: #7DA3BC;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background 0.2s ease, color 0.2s ease;
  }

  .toggle-password:hover { background: var(--sky-soft); color: var(--sky-deep); }

  .login-submit {
    width: 100%;
    height: 52px;
    border: 0;
    border-radius: 14px;
    background: linear-gradient(135deg, #38BDF8 0%, #22C1C3 55%, #10B981 100%);
    color: #FFFFFF;
    font-family: inherit;
    font-size: 0.94rem;
    font-weight: 700;
    letter-spacing: 0.01em;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    cursor: pointer;
    box-shadow: 0 18px 28px -16px rgba(14, 165, 233, 0.85);
    transition: transform 0.15s ease, box-shadow 0.2s ease, filter 0.2s ease;
    margin-top: 4px;
  }

  .login-submit:hover {
    transform: translateY(-1px);
    box-shadow: 0 22px 34px -16px rgba(14, 165, 233, 0.9);
    filter: saturate(1.06);
  }

  .login-submit:active { transform: translateY(1px); }

  .login-submit:disabled {
    cursor: wait;
    opacity: 0.88;
    transform: none;
  }

  .spinner {
    width: 17px;
    height: 17px;
    border: 2.5px solid rgba(255, 255, 255, 0.35);
    border-top-color: #FFFFFF;
    border-radius: 50%;
    animation: spin 0.7s linear infinite;
    display: none;
  }

  .login-submit.is-loading .spinner { display: block; }
  .login-submit.is-loading .submit-icon { display: none; }

  @keyframes spin { to { transform: rotate(360deg); } }

  .login-footer {
    margin-top: 22px;
    text-align: center;
    font-size: 0.72rem;
    color: #8AA2B3;
  }

  .login-footer strong { color: #5B7185; }

  /* ── Responsive ──────────────────────────────────────────────── */
  @media (max-width: 940px) {
    .page {
      grid-template-columns: 1fr;
      max-width: 500px;
      padding: 12px;
    }

    .showcase {
      min-height: auto;
      padding: 26px 26px 24px;
    }

    .hero-copy { max-width: none; }
    .hero-copy h1 { font-size: 1.8rem; }
    .hero-copy p { font-size: 0.87rem; }
    .illustration { height: 170px; margin: 14px 0 8px; }
    .illu-board { width: 160px; height: 96px; }
    .illu-cap { width: 55px; height: 55px; border-radius: 16px; left: 8px; top: 8px; }
    .illu-book { width: 62px; height: 62px; border-radius: 18px; right: 8px; top: 0; }
    .illu-pencil { width: 48px; height: 48px; border-radius: 14px; right: 28px; bottom: -4px; }
    .feature-row { display: none; }

    .login-panel { padding: 28px 22px 30px; }
    .login-head .mini-brand { display: flex; }
  }

  @media (max-width: 480px) {
    body { padding: 14px; }
    .page { border-radius: 24px; }
    .showcase { padding: 22px 20px 20px; border-radius: 18px; }
    .hero-eyebrow { margin-bottom: 12px; }
    .hero-copy h1 { font-size: 1.55rem; }
    .illustration { height: 140px; }
    .login-panel { padding: 24px 16px 26px; }
    .login-head h2 { font-size: 1.5rem; }
  }

  @media (prefers-reduced-motion: reduce) {
    *, *::before, *::after {
      animation-duration: 0.001ms !important;
      animation-iteration-count: 1 !important;
      transition-duration: 0.001ms !important;
    }
  }
</style>
</head>
<body>

<!-- Dekorasi latar belakang flat illustration -->
<div class="decor" aria-hidden="true">
  <svg class="d1" viewBox="0 0 80 80" fill="none">
    <rect x="10" y="18" width="60" height="46" rx="12" fill="#BAE6FD"/>
    <path d="M18 29h18M18 38h28M18 47h14" stroke="#0EA5E9" stroke-width="4" stroke-linecap="round"/>
    <path d="M52 29h10" stroke="#6EE7B7" stroke-width="4" stroke-linecap="round"/>
  </svg>
  <svg class="d2" viewBox="0 0 80 80" fill="none">
    <path d="M8 30L40 16l32 14-32 14L8 30z" fill="#6EE7B7"/>
    <path d="M20 36v14c0 5 9 10 20 10s20-5 20-10V36" stroke="#10B981" stroke-width="5" stroke-linecap="round"/>
    <path d="M72 30v20" stroke="#0F2A43" stroke-width="4" stroke-linecap="round"/>
    <circle cx="72" cy="53" r="4" fill="#FBBF24"/>
  </svg>
  <svg class="d3" viewBox="0 0 80 80" fill="none">
    <rect x="14" y="42" width="52" height="16" rx="8" transform="rotate(-40 14 42)" fill="#38BDF8"/>
    <path d="M24 54l14-14 12 12-14 14z" fill="#E0F2FE"/>
    <path d="M52 20l10 10-8 8-10-10z" fill="#FBBF24"/>
    <path d="M60 12l8 8" stroke="#0F2A43" stroke-width="4" stroke-linecap="round"/>
  </svg>
  <svg class="d4" viewBox="0 0 80 80" fill="none">
    <rect x="12" y="24" width="56" height="42" rx="10" fill="#FFFFFF" stroke="#BAE6FD" stroke-width="3"/>
    <path d="M27 24v-6a8 8 0 0116 0v6" stroke="#10B981" stroke-width="5" stroke-linecap="round"/>
    <circle cx="40" cy="45" r="8" fill="#E8FFF6"/>
    <path d="M36 45l3 3 5-6" stroke="#10B981" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
  </svg>
  <svg class="d5" viewBox="0 0 80 80" fill="none">
    <circle cx="40" cy="40" r="26" fill="#E0F5FF"/>
    <path d="M28 44l8 8 17-20" stroke="#38BDF8" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/>
  </svg>
  <svg class="d6" viewBox="0 0 80 80" fill="none">
    <path d="M40 10l7 20 21 2-16 14 5 21-17-12-17 12 5-21-16-14 21-2 7-20z" fill="#FDE68A"/>
  </svg>
</div>

<div class="page">
  <!-- Panel ilustrasi -->
  <section class="showcase">
    <div class="brand">
      <div class="brand-mark">
        <svg viewBox="0 0 32 32" fill="none" aria-hidden="true">
          <path d="M16 4L3 10.5 16 17l13-6.5L16 4z" fill="#0EA5E9"/>
          <path d="M3 10.5v8L16 25l13-6.5v-8" stroke="#0EA5E9" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
          <path d="M7 13v7" stroke="#10B981" stroke-width="2" stroke-linecap="round"/>
          <circle cx="7" cy="22" r="1.8" fill="#10B981"/>
        </svg>
      </div>
      <div>
        <div class="brand-name">EduTask</div>
        <span class="brand-tag">Platform Pembelajaran Digital</span>
      </div>
    </div>

    <div class="hero-copy">
      <div class="hero-eyebrow">
        <svg width="13" height="13" viewBox="0 0 16 16" fill="none" aria-hidden="true">
          <path d="M8 1.5l2 4.2 4.6.6-3.4 3.2.9 4.5L8 11.8 3.9 14l.9-4.5L1.4 6.3 6 5.7 8 1.5z" fill="#DFFFF4"/>
        </svg>
        Belajar tanpa batas
      </div>
      <h1>Belajar jadi lebih <span>menyenangkan.</span></h1>
      <p>Satu tempat untuk kelas, materi, tugas, dan penilaian. Mudah dipakai siswa, guru, dan sekolah.</p>

      <div class="illustration" aria-hidden="true">
        <div class="illu-board">
          <svg width="128" height="82" viewBox="0 0 128 82" fill="none">
            <rect x="2" y="4" width="124" height="74" rx="14" fill="#F0FBFF"/>
            <path d="M18 24h44M18 36h72M18 48h58" stroke="#BAE6FD" stroke-width="5" stroke-linecap="round"/>
            <rect x="18" y="58" width="36" height="8" rx="4" fill="#6EE7B7"/>
          </svg>
        </div>
        <div class="illu-shape illu-cap">
          <svg width="42" height="42" viewBox="0 0 48 48" fill="none">
            <path d="M4 18L24 9l20 9-20 9L4 18z" fill="#6EE7B7"/>
            <path d="M12 23v9c0 4 5.4 7 12 7s12-3 12-7v-9" stroke="#DFFFF4" stroke-width="3.5" stroke-linecap="round"/>
            <path d="M43 18v13" stroke="#DFFFF4" stroke-width="3" stroke-linecap="round"/>
            <circle cx="43" cy="34" r="2.5" fill="#FBBF24"/>
          </svg>
        </div>
        <div class="illu-shape illu-book">
          <svg width="46" height="46" viewBox="0 0 48 48" fill="none">
            <path d="M7 10h13a4 4 0 014 4v24a4 4 0 00-4-4H7V10z" fill="#38BDF8"/>
            <path d="M41 10H28a4 4 0 00-4 4v24a4 4 0 014-4h13V10z" fill="#0EA5E9"/>
            <path d="M13 18h6M13 24h6M29 18h6M29 24h6" stroke="#E0F5FF" stroke-width="2.5" stroke-linecap="round"/>
          </svg>
        </div>
        <div class="illu-shape illu-pencil">
          <svg width="34" height="34" viewBox="0 0 48 48" fill="none">
            <rect x="6" y="29" width="35" height="12" rx="6" transform="rotate(-40 6 29)" fill="#FBBF24"/>
            <path d="M14 38l8-8 7 7-8 8z" fill="#FEF3C7"/>
            <path d="M34 12l6 6-5 5-6-6z" fill="#0F2A43"/>
            <path d="M39 7l4 4" stroke="#0EA5E9" stroke-width="3" stroke-linecap="round"/>
          </svg>
        </div>
        <span class="illu-spark s1"></span>
        <span class="illu-spark s2"></span>
        <span class="illu-spark s3"></span>
      </div>
    </div>

    <div class="feature-row">
      <div class="feature">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
          <path d="M4 5.5A2.5 2.5 0 016.5 3H20v16H6.5A2.5 2.5 0 004 21.5V5.5z" stroke="#FFFFFF" stroke-width="1.8" stroke-linejoin="round"/>
          <path d="M8 7h8M8 11h8" stroke="#FFFFFF" stroke-width="1.8" stroke-linecap="round"/>
        </svg>
        Materi terstruktur
      </div>
      <div class="feature">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
          <rect x="3" y="4" width="18" height="17" rx="3" stroke="#FFFFFF" stroke-width="1.8"/>
          <path d="M8 2v4M16 2v4M3 9h18M8 14l2.5 2.5L16 11" stroke="#FFFFFF" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        Tugas terjadwal
      </div>
      <div class="feature">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
          <path d="M12 2l3 6 7 1-5 5 1 7-6-3-6 3 1-7-5-5 7-1 3-6z" stroke="#FFFFFF" stroke-width="1.8" stroke-linejoin="round"/>
        </svg>
        Penilaian cepat
      </div>
    </div>
  </section>

  <!-- Panel login -->
  <section class="login-panel">
    <div class="login-card">
      <div class="login-head">
        <div class="mini-brand">
          <span class="mini-mark">
            <svg width="22" height="22" viewBox="0 0 32 32" fill="none" aria-hidden="true">
              <path d="M16 4L3 10.5 16 17l13-6.5L16 4z" fill="#0EA5E9"/>
              <path d="M3 10.5v8L16 25l13-6.5v-8" stroke="#0EA5E9" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </span>
          EduTask
        </div>
        <?php if ($mode === 'register'): ?>
          <h2>Buat Akun Siswa</h2>
          <p>Daftarkan diri Anda untuk mengakses kelas, materi, dan tugas di EduTask.</p>
        <?php else: ?>
          <h2>Masuk ke Kelas</h2>
          <p>Selamat datang kembali. Gunakan akun sekolahmu untuk melanjutkan belajar.</p>
        <?php endif; ?>
      </div>

      <div class="auth-switch">
        <a href="<?= site_url('auth/login') ?>" class="<?= $mode === 'login' ? 'active' : '' ?>">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M10 17l5-5-5-5M15 12H3M14 3h5a2 2 0 012 2v14a2 2 0 01-2 2h-5" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          Masuk
        </a>
        <a href="<?= site_url('auth/register') ?>" class="<?= $mode === 'register' ? 'active' : '' ?>">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <circle cx="9" cy="8" r="3.2" stroke="currentColor" stroke-width="1.9"/>
            <path d="M3.5 20a5.5 5.5 0 0111 0M18 8v6M15 11h6" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"/>
          </svg>
          Daftar Siswa
        </a>
      </div>

      <?php if ($mode === 'register'): ?>
        <?php if (!empty($register_error)): ?>
          <div class="alert-error" role="alert">
            <svg width="17" height="17" viewBox="0 0 20 20" fill="none" aria-hidden="true">
              <circle cx="10" cy="10" r="8" stroke="currentColor" stroke-width="1.7"/>
              <path d="M10 6v5M10 14h.01" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"/>
            </svg>
            <div><?= $register_error ?></div>
          </div>
        <?php endif; ?>

        <?= form_open('auth/register', 'class="login-form" id="registerForm" data-loading="true" autocomplete="on"') ?>
          <div class="field">
            <label for="reg_nama">Nama Lengkap</label>
            <div class="input-wrap">
              <span class="field-icon">
                <svg width="19" height="19" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                  <circle cx="12" cy="8" r="3.6" stroke="currentColor" stroke-width="1.8"/>
                  <path d="M5 20a7 7 0 0114 0" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                </svg>
              </span>
              <input type="text" id="reg_nama" name="nama" placeholder="Nama lengkap siswa"
                     value="<?= html_escape(set_value('nama')) ?>" required minlength="3" maxlength="100" autocomplete="name">
            </div>
          </div>

          <div class="field">
            <label for="reg_email">Alamat Email</label>
            <div class="input-wrap">
              <span class="field-icon">
                <svg width="19" height="19" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                  <rect x="3" y="5" width="18" height="14" rx="3" stroke="currentColor" stroke-width="1.8"/>
                  <path d="M4 7l8 6 8-6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </span>
              <input type="email" id="reg_email" name="email" placeholder="nama@sekolah.id"
                     value="<?= html_escape(set_value('email')) ?>" required maxlength="100" autocomplete="email">
            </div>
          </div>

          <div class="field">
            <label for="reg_tingkat">Tingkat</label>
            <div class="input-wrap">
              <span class="field-icon">
                <svg width="19" height="19" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                  <path d="M12 3l9 4.5-9 4.5-9-4.5L12 3z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                  <path d="M6 10v5.2c0 1.9 2.7 3.3 6 3.3s6-1.4 6-3.3V10" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                </svg>
              </span>
              <?php $selected_tingkat = set_value('tingkat'); ?>
              <select id="reg_tingkat" name="tingkat" required>
                <option value="" <?= ($selected_tingkat === '' || $selected_tingkat === NULL) ? 'selected' : '' ?> disabled>Pilih tingkat</option>
                <option value="X" <?= $selected_tingkat === 'X' ? 'selected' : '' ?>>Kelas X</option>
                <option value="XI" <?= $selected_tingkat === 'XI' ? 'selected' : '' ?>>Kelas XI</option>
                <option value="XII" <?= $selected_tingkat === 'XII' ? 'selected' : '' ?>>Kelas XII</option>
              </select>
              <span class="select-caret">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                  <path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </span>
            </div>
          </div>

          <div class="field">
            <label for="reg_jurusan">Jurusan</label>
            <div class="input-wrap">
              <span class="field-icon">
                <svg width="19" height="19" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                  <rect x="3" y="7" width="18" height="13" rx="3" stroke="currentColor" stroke-width="1.8"/>
                  <path d="M9 7V5.5A1.5 1.5 0 0110.5 4h3A1.5 1.5 0 0115 5.5V7M3 12h18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                </svg>
              </span>
              <input type="text" id="reg_jurusan" name="jurusan" placeholder="Contoh: PPLG"
                     value="<?= html_escape(set_value('jurusan')) ?>" required maxlength="50" list="jurusan-options" autocomplete="off">
              <datalist id="jurusan-options">
                <option value="PPLG"></option>
                <option value="TJKT"></option>
                <option value="AKL"></option>
                <option value="MPLB"></option>
                <option value="DKV"></option>
              </datalist>
            </div>
            <div class="field-hint">Gunakan singkatan jurusan sesuai data sekolah, contoh: PPLG, TJKT, atau AKL.</div>
          </div>

          <div class="field">
            <label for="reg_password">Kata Sandi</label>
            <div class="input-wrap">
              <span class="field-icon">
                <svg width="19" height="19" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                  <rect x="4" y="10" width="16" height="11" rx="3" stroke="currentColor" stroke-width="1.8"/>
                  <path d="M8 10V7a4 4 0 018 0v3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                  <path d="M12 14v3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                </svg>
              </span>
              <input type="password" id="reg_password" name="password" placeholder="Minimal 6 karakter"
                     required minlength="6" maxlength="72" autocomplete="new-password">
              <button type="button" class="toggle-password" data-target="reg_password" aria-label="Tampilkan kata sandi" aria-pressed="false">
                <svg class="eye-open" width="19" height="19" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                  <path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6z" stroke="currentColor" stroke-width="1.7"/>
                  <circle cx="12" cy="12" r="2.7" stroke="currentColor" stroke-width="1.7"/>
                </svg>
                <svg class="eye-closed" width="19" height="19" viewBox="0 0 24 24" fill="none" aria-hidden="true" style="display:none;">
                  <path d="M3 3l18 18M10.6 6.3A10.8 10.8 0 0112 6c6 0 9.5 6 9.5 6a16 16 0 01-3.2 3.7M6.1 6.1C3.8 7.8 2.5 12 2.5 12s3.5 6 9.5 6c1.4 0 2.6-.3 3.8-.8" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
                </svg>
              </button>
            </div>
          </div>

          <div class="field">
            <label for="reg_confirm">Konfirmasi Kata Sandi</label>
            <div class="input-wrap">
              <span class="field-icon">
                <svg width="19" height="19" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                  <rect x="4" y="10" width="16" height="11" rx="3" stroke="currentColor" stroke-width="1.8"/>
                  <path d="M8 10V7a4 4 0 018 0v3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                  <path d="M9.5 15.5l1.7 1.7 3.3-3.7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </span>
              <input type="password" id="reg_confirm" name="confirm_password" placeholder="Ulangi kata sandi"
                     required minlength="6" maxlength="72" autocomplete="new-password">
              <button type="button" class="toggle-password" data-target="reg_confirm" aria-label="Tampilkan konfirmasi kata sandi" aria-pressed="false">
                <svg class="eye-open" width="19" height="19" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                  <path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6z" stroke="currentColor" stroke-width="1.7"/>
                  <circle cx="12" cy="12" r="2.7" stroke="currentColor" stroke-width="1.7"/>
                </svg>
                <svg class="eye-closed" width="19" height="19" viewBox="0 0 24 24" fill="none" aria-hidden="true" style="display:none;">
                  <path d="M3 3l18 18M10.6 6.3A10.8 10.8 0 0112 6c6 0 9.5 6 9.5 6a16 16 0 01-3.2 3.7M6.1 6.1C3.8 7.8 2.5 12 2.5 12s3.5 6 9.5 6c1.4 0 2.6-.3 3.8-.8" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
                </svg>
              </button>
            </div>
          </div>

          <div class="form-note">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" aria-hidden="true">
              <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.8"/>
              <path d="M12 11v5M12 8h.01" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"/>
            </svg>
            <span>Pendaftaran hanya untuk akun siswa. Akun guru dan admin dibuat oleh sekolah.</span>
          </div>

          <button type="submit" class="login-submit">
            <span class="spinner" aria-hidden="true"></span>
            <span class="submit-icon">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"/>
              </svg>
            </span>
            <span class="submit-text">Buat Akun Siswa</span>
          </button>
        <?= form_close() ?>

      <?php else: ?>
        <?php if ($this->session->flashdata('success')): ?>
          <div class="alert-success" role="status">
            <svg width="17" height="17" viewBox="0 0 20 20" fill="none" aria-hidden="true">
              <circle cx="10" cy="10" r="8" stroke="currentColor" stroke-width="1.7"/>
              <path d="M6.5 10.2l2.3 2.3 4.7-5" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span><?= html_escape($this->session->flashdata('success')) ?></span>
          </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('error')): ?>
          <div class="alert-error" role="alert">
            <svg width="17" height="17" viewBox="0 0 20 20" fill="none" aria-hidden="true">
              <circle cx="10" cy="10" r="8" stroke="currentColor" stroke-width="1.7"/>
              <path d="M10 6v5M10 14h.01" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"/>
            </svg>
            <span><?= html_escape($this->session->flashdata('error')) ?></span>
          </div>
        <?php endif; ?>

        <?= form_open('auth/login', 'class="login-form" id="loginForm" data-loading="true" autocomplete="on"') ?>
          <div class="field">
            <label for="email">Alamat Email</label>
            <div class="input-wrap">
              <span class="field-icon">
                <svg width="19" height="19" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                  <rect x="3" y="5" width="18" height="14" rx="3" stroke="currentColor" stroke-width="1.8"/>
                  <path d="M4 7l8 6 8-6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </span>
              <input type="email" id="email" name="email" placeholder="nama@sekolah.id"
                     value="<?= html_escape(set_value('email')) ?>" required autocomplete="email">
            </div>
          </div>

          <div class="field">
            <label for="password">Kata Sandi</label>
            <div class="input-wrap">
              <span class="field-icon">
                <svg width="19" height="19" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                  <rect x="4" y="10" width="16" height="11" rx="3" stroke="currentColor" stroke-width="1.8"/>
                  <path d="M8 10V7a4 4 0 018 0v3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                  <path d="M12 14v3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                </svg>
              </span>
              <input type="password" id="password" name="password" placeholder="Masukkan kata sandi"
                     required autocomplete="current-password">
              <button type="button" class="toggle-password" data-target="password" aria-label="Tampilkan kata sandi" aria-pressed="false">
                <svg class="eye-open" width="19" height="19" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                  <path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6z" stroke="currentColor" stroke-width="1.7"/>
                  <circle cx="12" cy="12" r="2.7" stroke="currentColor" stroke-width="1.7"/>
                </svg>
                <svg class="eye-closed" width="19" height="19" viewBox="0 0 24 24" fill="none" aria-hidden="true" style="display:none;">
                  <path d="M3 3l18 18M10.6 6.3A10.8 10.8 0 0112 6c6 0 9.5 6 9.5 6a16 16 0 01-3.2 3.7M6.1 6.1C3.8 7.8 2.5 12 2.5 12s3.5 6 9.5 6c1.4 0 2.6-.3 3.8-.8" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
                </svg>
              </button>
            </div>
          </div>

          <button type="submit" class="login-submit">
            <span class="spinner" aria-hidden="true"></span>
            <span class="submit-icon">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </span>
            <span class="submit-text">Masuk ke EduTask</span>
          </button>
        <?= form_close() ?>
      <?php endif; ?>

      <div class="login-footer">
        &copy; <?= date('Y') ?> <strong>EduTask</strong> &mdash; Platform Pembelajaran Digital
      </div>
    </div>
  </section>
</div>

<script>
(function () {
  document.querySelectorAll('.toggle-password[data-target]').forEach(function (toggleButton) {
    var targetInput = document.getElementById(toggleButton.dataset.target);
    var eyeOpen = toggleButton.querySelector('.eye-open');
    var eyeClosed = toggleButton.querySelector('.eye-closed');

    if (!targetInput) return;

    toggleButton.addEventListener('click', function () {
      var isHidden = targetInput.type === 'password';
      targetInput.type = isHidden ? 'text' : 'password';
      toggleButton.setAttribute('aria-pressed', isHidden ? 'true' : 'false');
      toggleButton.setAttribute('aria-label', isHidden ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
      if (eyeOpen && eyeClosed) {
        eyeOpen.style.display = isHidden ? 'none' : 'block';
        eyeClosed.style.display = isHidden ? 'block' : 'none';
      }
      targetInput.focus();
    });
  });

  document.querySelectorAll('form[data-loading="true"]').forEach(function (form) {
    form.addEventListener('submit', function () {
      if (!form.checkValidity()) return;

      var submitButton = form.querySelector('button[type="submit"]');
      var submitText = submitButton ? submitButton.querySelector('.submit-text') : null;

      if (submitButton) {
        submitButton.disabled = true;
        submitButton.classList.add('is-loading');
      }
      if (submitText) submitText.textContent = 'Memproses...';
    });
  });

  var scene = document.querySelector('.showcase');
  var canHover = window.matchMedia('(hover: hover) and (pointer: fine)').matches;
  if (scene && canHover && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    scene.addEventListener('pointermove', function (event) {
      var rect = scene.getBoundingClientRect();
      var x = (event.clientX - rect.left) / rect.width - 0.5;
      var y = (event.clientY - rect.top) / rect.height - 0.5;
      scene.querySelectorAll('.illu-shape').forEach(function (shape, index) {
        var depth = (index + 1) * 5;
        shape.style.transform = 'translate3d(' + (x * depth) + 'px,' + (y * depth) + 'px,0)';
      });
    });

    scene.addEventListener('pointerleave', function () {
      scene.querySelectorAll('.illu-shape').forEach(function (shape) {
        shape.style.transform = '';
      });
    });
  }
})();
</script>
</body>
</html>
