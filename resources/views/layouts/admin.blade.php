<!DOCTYPE html>
<html lang="fr" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#111111">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — VoltRide</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @yield('head')
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --primary:       #00FF6A;
            --primary-dark:  #00CC55;
            --primary-glow:  rgba(0,255,106,.15);
            --primary-glow2: rgba(0,255,106,.08);
            --bg:            #0a0a0a;
            --bg2:           #111111;
            --surface:       #1a1a1a;
            --surface2:      #222222;
            --surface3:      #2a2a2a;
            --border:        rgba(255,255,255,.07);
            --border2:       rgba(255,255,255,.12);
            --border-green:  rgba(0,255,106,.2);
            --txt:           #ffffff;
            --txt2:          #aaaaaa;
            --txt3:          #666666;
            --danger:        #ff4d4d;
            --warning:       #ffaa00;
            --info:          #00aaff;
            --sidebar-w:     260px;
            --topbar-h:      60px;
            --bottom-nav-h:  64px;
            --safe-bottom:   env(safe-area-inset-bottom, 0px);
        }

        html { height: 100%; }
        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: var(--bg);
            color: var(--txt);
            display: flex;
            height: 100%;
            overflow: hidden;
            -webkit-font-smoothing: antialiased;
            -webkit-tap-highlight-color: transparent;
        }

        /* ── Scrollbar ────────────────────────────────── */
        ::-webkit-scrollbar { width: 4px; height: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--surface3); border-radius: 10px; }

        /* ── Sidebar ──────────────────────────────────── */
        .sidebar {
            width: var(--sidebar-w);
            height: 100%;
            background: var(--bg2);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0;
            z-index: 200;
            transition: transform .28s cubic-bezier(.4,0,.2,1);
            will-change: transform;
        }
        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 20px;
            border-bottom: 1px solid var(--border);
            flex-shrink: 0;
        }
        .sidebar-logo img {
            width: 38px; height: 38px;
            border-radius: 10px;
            object-fit: cover;
            border: 1px solid var(--border-green);
            box-shadow: 0 0 12px var(--primary-glow);
            flex-shrink: 0;
        }
        .sidebar-logo-fallback {
            width: 38px; height: 38px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            display: flex; align-items: center; justify-content: center;
            color: #000; font-size: 17px; font-weight: 900;
            box-shadow: 0 0 16px var(--primary-glow);
            flex-shrink: 0;
        }
        .sidebar-logo-text { font-size: 17px; font-weight: 800; letter-spacing: -.5px; }
        .sidebar-logo-text span { color: var(--primary); }
        .sidebar-badge {
            margin-left: auto;
            font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;
            color: var(--primary);
            background: var(--primary-glow2);
            border: 1px solid var(--border-green);
            padding: 2px 7px; border-radius: 20px;
            flex-shrink: 0;
        }
        .sidebar-close-btn {
            display: none;
            background: none;
            border: none;
            color: var(--txt3);
            font-size: 18px;
            cursor: pointer;
            padding: 4px;
            margin-left: auto;
        }

        .sidebar-nav { flex: 1; padding: 12px; overflow-y: auto; }
        .nav-section {
            font-size: 9.5px; font-weight: 700; text-transform: uppercase;
            letter-spacing: 1.2px; color: var(--txt3);
            padding: 10px 10px 5px; margin-top: 6px;
        }
        .nav-item {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 11px 12px;
            border-radius: 8px;
            text-decoration: none;
            color: var(--txt2);
            font-size: 13.5px; font-weight: 500;
            transition: background .12s, color .12s;
            margin-bottom: 2px;
            position: relative;
            min-height: 44px;
        }
        .nav-item:hover { background: var(--surface); color: var(--txt); }
        .nav-item.active {
            background: var(--primary-glow2);
            color: var(--primary);
            border: 1px solid var(--border-green);
        }
        .nav-item.active::before {
            content: '';
            position: absolute; left: 0; top: 20%; bottom: 20%;
            width: 3px;
            background: var(--primary);
            border-radius: 0 3px 3px 0;
            box-shadow: 0 0 8px var(--primary);
        }
        .nav-icon { width: 18px; text-align: center; font-size: 14px; flex-shrink: 0; }

        .sidebar-footer { padding: 12px; border-top: 1px solid var(--border); flex-shrink: 0; }
        .sidebar-user {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px; border-radius: 8px;
            background: var(--surface); border: 1px solid var(--border);
            margin-bottom: 8px;
        }
        .sidebar-avatar {
            width: 34px; height: 34px; border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            display: flex; align-items: center; justify-content: center;
            font-weight: 800; font-size: 13px; color: #000;
            flex-shrink: 0;
            box-shadow: 0 0 10px var(--primary-glow);
        }
        .sidebar-user-name { font-size: 13px; font-weight: 600; color: var(--txt); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .sidebar-user-role { font-size: 10px; color: var(--primary); font-weight: 600; text-transform: uppercase; letter-spacing: .5px; }

        /* ── Overlay ──────────────────────────────────── */
        .sidebar-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,.65);
            z-index: 199;
            backdrop-filter: blur(2px);
            -webkit-backdrop-filter: blur(2px);
        }
        .sidebar-overlay.active { display: block; }

        /* ── Main ─────────────────────────────────────── */
        .main-wrapper {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            height: 100%;
            overflow: hidden;
        }

        /* ── Topbar ───────────────────────────────────── */
        .topbar {
            height: var(--topbar-h);
            background: var(--bg2);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            padding: 0 24px;
            gap: 12px;
            flex-shrink: 0;
            position: relative;
            z-index: 100;
        }
        .mobile-menu-btn {
            display: none;
            width: 40px; height: 40px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--txt);
            font-size: 16px;
            cursor: pointer;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .topbar-title {
            flex: 1;
            font-size: 15px; font-weight: 700;
            color: var(--txt);
            overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
        }
        .topbar-breadcrumb {
            flex: 1;
            display: flex; align-items: center; gap: 8px;
            font-size: 13px;
        }
        .topbar-breadcrumb .bc-sep { color: var(--txt3); font-size: 10px; }
        .topbar-breadcrumb .bc-current { font-size: 14px; font-weight: 700; color: var(--txt); }
        .topbar-breadcrumb .bc-root { color: var(--txt3); }
        .topbar-actions { display: flex; align-items: center; gap: 8px; }
        .topbar-action-btn {
            width: 38px; height: 38px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            color: var(--txt2); font-size: 13px;
            text-decoration: none;
            transition: all .15s;
        }
        .topbar-action-btn:hover { border-color: var(--primary); color: var(--primary); }

        /* ── Page content ─────────────────────────────── */
        .page-content {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 24px;
            -webkit-overflow-scrolling: touch;
        }

        /* ── Bottom nav (mobile only) ─────────────────── */
        .bottom-nav {
            display: none;
            position: fixed;
            bottom: 0; left: 0; right: 0;
            height: calc(var(--bottom-nav-h) + var(--safe-bottom));
            padding-bottom: var(--safe-bottom);
            background: var(--bg2);
            border-top: 1px solid var(--border);
            z-index: 150;
            align-items: center;
            justify-content: space-around;
        }
        .bottom-nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 3px;
            text-decoration: none;
            color: var(--txt3);
            font-size: 11px; font-weight: 600;
            padding: 6px 16px;
            border-radius: 10px;
            transition: color .15s;
            min-width: 56px;
            -webkit-tap-highlight-color: transparent;
        }
        .bottom-nav-item i { font-size: 20px; }
        .bottom-nav-item.active { color: var(--primary); }
        .bottom-nav-item.active i { filter: drop-shadow(0 0 6px var(--primary)); }
        .bottom-nav-item span { font-size: 10px; }

        /* ── Page header ──────────────────────────────── */
        .page-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }
        .page-heading {
            font-size: 22px; font-weight: 800; letter-spacing: -.5px;
        }
        .page-heading .accent { color: var(--primary); }
        .page-subtitle { font-size: 13px; color: var(--txt2); margin-top: 2px; }

        /* ── Stat cards ───────────────────────────────── */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
            margin-bottom: 20px;
        }
        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 18px 16px;
            position: relative;
            overflow: hidden;
            transition: border-color .2s, box-shadow .2s;
        }
        .stat-card.c-green  { border-top: 2px solid var(--primary); }
        .stat-card.c-green:hover { box-shadow: 0 4px 20px var(--primary-glow); }
        .stat-card.c-blue   { border-top: 2px solid var(--info); }
        .stat-card.c-blue:hover { box-shadow: 0 4px 20px rgba(0,170,255,.15); }
        .stat-card.c-amber  { border-top: 2px solid var(--warning); }
        .stat-card.c-amber:hover { box-shadow: 0 4px 20px rgba(255,170,0,.15); }
        .stat-card.c-red    { border-top: 2px solid var(--danger); }
        .stat-card.c-red:hover { box-shadow: 0 4px 20px rgba(255,77,77,.15); }
        .stat-label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--txt3); margin-bottom: 8px; }
        .stat-value { font-size: 28px; font-weight: 900; letter-spacing: -1.5px; line-height: 1; }
        .stat-card.c-green .stat-value { color: var(--primary); }
        .stat-card.c-blue  .stat-value { color: var(--info); }
        .stat-card.c-amber .stat-value { color: var(--warning); }
        .stat-card.c-red   .stat-value { color: var(--danger); }
        .stat-meta { font-size: 11.5px; color: var(--txt3); margin-top: 6px; }
        .stat-icon {
            position: absolute; top: 14px; right: 14px;
            width: 34px; height: 34px; border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            font-size: 14px;
        }
        .stat-card.c-green .stat-icon { background: var(--primary-glow2); color: var(--primary); }
        .stat-card.c-blue  .stat-icon { background: rgba(0,170,255,.08); color: var(--info); }
        .stat-card.c-amber .stat-icon { background: rgba(255,170,0,.08); color: var(--warning); }
        .stat-card.c-red   .stat-icon { background: rgba(255,77,77,.08); color: var(--danger); }

        /* ── Cards ────────────────────────────────────── */
        .card { background: var(--surface); border: 1px solid var(--border); border-radius: 12px; overflow: hidden; }
        .card-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 14px 18px; border-bottom: 1px solid var(--border);
        }
        .card-title { font-size: 14px; font-weight: 700; }
        .card-subtitle { font-size: 11px; color: var(--txt3); margin-top: 1px; }
        .card-body { padding: 18px; }

        /* ── Table ────────────────────────────────────── */
        .table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        thead tr { border-bottom: 1px solid var(--border); }
        thead th {
            text-align: left; padding: 10px 14px;
            font-size: 10px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 1px;
            color: var(--txt3); white-space: nowrap;
        }
        tbody tr { border-bottom: 1px solid var(--border); transition: background .1s; }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: var(--surface2); }
        td { padding: 12px 14px; color: var(--txt2); vertical-align: middle; }
        td.td-main { color: var(--txt); font-weight: 600; }

        /* ── Mobile row cards ─────────────────────────── */
        .mobile-row-cards { display: none; }
        .mobile-row-card {
            display: flex; flex-direction: column; gap: 10px;
            padding: 14px 16px;
            border-bottom: 1px solid var(--border);
        }
        .mobile-row-card:last-child { border-bottom: none; }
        .mobile-row-card-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 10px; }
        .mobile-row-card-title { font-size: 14px; font-weight: 700; color: var(--txt); }
        .mobile-row-card-sub { font-size: 12px; color: var(--txt3); margin-top: 2px; }
        .mobile-row-card-meta { display: flex; flex-wrap: wrap; gap: 8px; align-items: center; }
        .mobile-row-card-actions { display: flex; gap: 8px; margin-top: 4px; }
        .mobile-row-card-actions .btn { flex: 1; justify-content: center; min-height: 40px; }

        /* ── Badges ───────────────────────────────────── */
        .badge {
            display: inline-flex; align-items: center; gap: 4px;
            font-size: 11px; font-weight: 700;
            padding: 3px 9px; border-radius: 20px;
            white-space: nowrap; border: 1px solid;
        }
        .badge-dot { width: 5px; height: 5px; border-radius: 50%; flex-shrink: 0; }
        .badge-green  { background: rgba(0,255,106,.08);   color: var(--primary); border-color: rgba(0,255,106,.2); }
        .badge-green  .badge-dot { background: var(--primary); box-shadow: 0 0 4px var(--primary); }
        .badge-amber  { background: rgba(255,170,0,.08);   color: var(--warning); border-color: rgba(255,170,0,.2); }
        .badge-amber  .badge-dot { background: var(--warning); }
        .badge-red    { background: rgba(255,77,77,.08);   color: var(--danger);  border-color: rgba(255,77,77,.2); }
        .badge-red    .badge-dot { background: var(--danger); }
        .badge-blue   { background: rgba(0,170,255,.08);   color: var(--info);    border-color: rgba(0,170,255,.2); }
        .badge-blue   .badge-dot { background: var(--info); }
        .badge-gray   { background: rgba(255,255,255,.04); color: var(--txt3);    border-color: var(--border); }
        .badge-gray   .badge-dot { background: var(--txt3); }
        .badge-purple { background: rgba(167,139,250,.08); color: #a78bfa;        border-color: rgba(167,139,250,.2); }
        .badge-purple .badge-dot { background: #a78bfa; }

        /* ── Buttons ──────────────────────────────────── */
        .btn {
            display: inline-flex; align-items: center; gap: 7px;
            font-size: 13px; font-weight: 700;
            padding: 9px 16px; border-radius: 8px;
            border: none; cursor: pointer; text-decoration: none;
            transition: all .15s ease; white-space: nowrap;
            font-family: inherit; min-height: 38px;
        }
        .btn-primary  { background: var(--primary); color: #000; }
        .btn-primary:hover { background: var(--primary-dark); box-shadow: 0 4px 16px var(--primary-glow); transform: translateY(-1px); }
        .btn-secondary { background: var(--surface2); color: var(--txt2); border: 1px solid var(--border); }
        .btn-secondary:hover { color: var(--txt); border-color: var(--border2); }
        .btn-danger   { background: rgba(255,77,77,.1);    color: var(--danger); border: 1px solid rgba(255,77,77,.2); }
        .btn-danger:hover { background: rgba(255,77,77,.2); }
        .btn-success  { background: rgba(0,255,106,.08);   color: var(--primary); border: 1px solid var(--border-green); }
        .btn-success:hover { background: rgba(0,255,106,.15); box-shadow: 0 0 12px var(--primary-glow); }
        .btn-info     { background: rgba(0,170,255,.08);   color: var(--info); border: 1px solid rgba(0,170,255,.2); }
        .btn-sm  { padding: 6px 11px; font-size: 12px; gap: 5px; border-radius: 6px; min-height: 34px; }
        .btn-icon { padding: 7px; min-height: 36px; width: 36px; justify-content: center; }

        /* ── Forms ────────────────────────────────────── */
        .form-group { margin-bottom: 16px; }
        .form-label {
            display: block; font-size: 11px; font-weight: 700;
            text-transform: uppercase; letter-spacing: .7px;
            color: var(--txt3); margin-bottom: 6px;
        }
        .form-control {
            width: 100%;
            background: var(--bg); border: 1px solid var(--border);
            border-radius: 8px; padding: 11px 12px;
            font-size: 14px; color: var(--txt);
            font-family: inherit;
            transition: border-color .15s, box-shadow .15s;
        }
        .form-control:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px var(--primary-glow2); }
        .form-control::placeholder { color: var(--txt3); }
        select.form-control { cursor: pointer; }
        select.form-control option { background: var(--surface); }
        textarea.form-control { resize: vertical; min-height: 90px; }
        .form-hint  { font-size: 11.5px; color: var(--txt3); margin-top: 4px; }
        .form-error { font-size: 11.5px; color: var(--danger); margin-top: 4px; }
        .form-row   { display: grid; grid-template-columns: 1fr 1fr;   gap: 14px; }
        .form-row-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 14px; }

        /* ── Filters bar ──────────────────────────────── */
        .filters-bar { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-bottom: 16px; }
        .filters-bar .form-control { max-width: 200px; padding: 8px 11px; font-size: 13px; }
        .filters-bar .search-input { max-width: 260px; }
        .filters-toggle {
            display: none;
            width: 100%; padding: 10px 14px;
            background: var(--surface2); border: 1px solid var(--border);
            border-radius: 8px; color: var(--txt2);
            font-size: 13px; font-weight: 600;
            cursor: pointer; font-family: inherit;
            align-items: center; gap: 8px;
            margin-bottom: 10px;
        }
        .filters-body { display: contents; }

        /* ── Alerts ───────────────────────────────────── */
        .alert {
            display: flex; align-items: flex-start; gap: 10px;
            padding: 12px 16px; border-radius: 8px;
            font-size: 13.5px; margin-bottom: 16px; border: 1px solid;
        }
        .alert-success { background: rgba(0,255,106,.06); border-color: rgba(0,255,106,.2); color: var(--primary); }
        .alert-error   { background: rgba(255,77,77,.06);  border-color: rgba(255,77,77,.2);  color: var(--danger); }
        .alert-warning { background: rgba(255,170,0,.06); border-color: rgba(255,170,0,.2); color: var(--warning); }
        .alert-info    { background: rgba(0,170,255,.06); border-color: rgba(0,170,255,.2); color: var(--info); }

        /* ── Pagination ───────────────────────────────── */
        .pagination-wrap {
            display: flex; align-items: center; justify-content: space-between;
            padding: 12px 16px; border-top: 1px solid var(--border);
            font-size: 13px; color: var(--txt2); gap: 8px; flex-wrap: wrap;
        }
        .pagination-wrap .pagination { display: flex; gap: 4px; }
        .pagination-wrap .page-item a,
        .pagination-wrap .page-item span {
            display: flex; align-items: center; justify-content: center;
            width: 36px; height: 36px;
            border-radius: 6px; font-size: 13px;
            text-decoration: none; color: var(--txt2);
            border: 1px solid var(--border);
            background: var(--surface); transition: all .15s;
        }
        .pagination-wrap .page-item.active span {
            background: var(--primary); border-color: var(--primary);
            color: #000; font-weight: 700;
            box-shadow: 0 0 12px var(--primary-glow);
        }
        .pagination-wrap .page-item a:hover { border-color: var(--primary); color: var(--primary); }
        .pagination-wrap .page-item.disabled span { opacity: .3; }

        /* ── Empty state ──────────────────────────────── */
        .empty-state { display: flex; flex-direction: column; align-items: center; padding: 48px 20px; color: var(--txt3); }
        .empty-state i { font-size: 32px; margin-bottom: 10px; opacity: .3; }
        .empty-state p { font-size: 14px; }

        /* ────────────────────────────────────────────────
           DESKTOP (≥ 1024px)
        ──────────────────────────────────────────────── */
        @media (min-width: 1024px) {
            .sidebar { transform: none !important; }
        }

        /* ────────────────────────────────────────────────
           TABLET / SMALL LAPTOP  (< 1024px)
        ──────────────────────────────────────────────── */
        @media (max-width: 1023px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); box-shadow: 24px 0 60px rgba(0,0,0,.6); }
            .sidebar-close-btn { display: block; }
            .main-wrapper { margin-left: 0; }
            .mobile-menu-btn { display: flex; }
            .topbar { padding: 0 16px; }
            .page-content { padding: 16px; }
            .form-row, .form-row-3 { grid-template-columns: 1fr; }
            .stat-grid { grid-template-columns: repeat(2, 1fr); }
        }

        /* ────────────────────────────────────────────────
           MOBILE  (< 768px)
        ──────────────────────────────────────────────── */
        @media (max-width: 767px) {
            /* Bottom nav */
            .bottom-nav { display: flex; }
            .page-content {
                padding: 14px 14px calc(var(--bottom-nav-h) + var(--safe-bottom) + 14px);
            }

            /* Topbar: hide breadcrumb root on tiny screens */
            .bc-root, .bc-sep { display: none; }

            /* Stat cards: 2 col compact */
            .stat-grid { grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 16px; }
            .stat-card { padding: 14px; }
            .stat-value { font-size: 22px; }
            .stat-icon { width: 28px; height: 28px; font-size: 12px; top: 12px; right: 12px; }

            /* Page header */
            .page-header { margin-bottom: 16px; }
            .page-heading { font-size: 18px; }

            /* Filters: collapsible */
            .filters-toggle { display: flex; }
            .filters-body { display: none; flex-direction: column; gap: 8px; }
            .filters-body.open { display: flex; }
            .filters-bar .form-control { max-width: 100%; }
            .filters-bar .search-input { max-width: 100%; }

            /* Table ↔ mobile cards swap */
            .desktop-table { display: none !important; }
            .mobile-row-cards { display: block; }

            /* Card body padding */
            .card-body { padding: 14px; }
            .card-header { padding: 12px 14px; }
        }

        /* Extra small (< 400px) */
        @media (max-width: 400px) {
            .stat-grid { grid-template-columns: 1fr 1fr; }
            .stat-value { font-size: 20px; }
            .bottom-nav-item { padding: 6px 10px; min-width: 48px; }
        }
    </style>
</head>
<body>

<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        @php $logoPath = public_path('logos/logo.jpeg'); @endphp
        @if(file_exists($logoPath))
            <img src="{{ asset('logos/logo.jpeg') }}" alt="VoltRide">
        @else
            <div class="sidebar-logo-fallback"><i class="fa-solid fa-bolt"></i></div>
        @endif
        <div class="sidebar-logo-text">Volt<span>Ride</span></div>
        <span class="sidebar-badge">Admin</span>
        <button class="sidebar-close-btn" onclick="closeSidebar()" aria-label="Fermer">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section">Principal</div>
        <a href="{{ route('admin.dashboard') }}"
           class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
           onclick="closeSidebar()">
            <i class="nav-icon fa-solid fa-chart-pie"></i> Tableau de bord
        </a>

        <div class="nav-section">Gestion</div>
        <a href="{{ route('admin.scooters.index') }}"
           class="nav-item {{ request()->routeIs('admin.scooters*') ? 'active' : '' }}"
           onclick="closeSidebar()">
            <i class="nav-icon fa-solid fa-motorcycle"></i> Trottinettes
        </a>
        <a href="{{ route('admin.reservations.index') }}"
           class="nav-item {{ request()->routeIs('admin.reservations*') ? 'active' : '' }}"
           onclick="closeSidebar()">
            <i class="nav-icon fa-solid fa-calendar-check"></i> Réservations
        </a>

        <div class="nav-section">Accès rapide</div>
        <a href="{{ route('welcome') }}" target="_blank" class="nav-item">
            <i class="nav-icon fa-solid fa-arrow-up-right-from-square"></i> Voir le site
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="sidebar-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
            <div style="min-width:0; flex:1;">
                <div class="sidebar-user-name">{{ auth()->user()->name }}</div>
                <div class="sidebar-user-role">Administrateur</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-secondary" style="width:100%;justify-content:center;font-size:12px;">
                <i class="fa-solid fa-right-from-bracket"></i> Déconnexion
            </button>
        </form>
    </div>
</aside>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<div class="main-wrapper">
    <header class="topbar">
        <button class="mobile-menu-btn" onclick="openSidebar()" aria-label="Menu">
            <i class="fa-solid fa-bars"></i>
        </button>
        <div class="topbar-breadcrumb">
            <span class="bc-root">Admin</span>
            <span class="bc-sep"><i class="fa-solid fa-chevron-right"></i></span>
            <span class="bc-current">@yield('breadcrumb', 'Dashboard')</span>
        </div>
        <div class="topbar-actions">
            <a href="{{ route('scooters.index') }}" class="topbar-action-btn" title="Voir le site" target="_blank">
                <i class="fa-solid fa-arrow-up-right-from-square"></i>
            </a>
        </div>
    </header>

    <main class="page-content" id="pageContent">
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fa-solid fa-circle-check"></i>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">
                <i class="fa-solid fa-circle-xmark"></i>
                {{ session('error') }}
            </div>
        @endif
        @yield('content')
    </main>
</div>

{{-- Bottom nav (mobile) --}}
<nav class="bottom-nav" aria-label="Navigation principale">
    <a href="{{ route('admin.dashboard') }}"
       class="bottom-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="fa-solid fa-chart-pie"></i>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('admin.scooters.index') }}"
       class="bottom-nav-item {{ request()->routeIs('admin.scooters*') ? 'active' : '' }}">
        <i class="fa-solid fa-motorcycle"></i>
        <span>Trottinettes</span>
    </a>
    <a href="{{ route('admin.reservations.index') }}"
       class="bottom-nav-item {{ request()->routeIs('admin.reservations*') ? 'active' : '' }}">
        <i class="fa-solid fa-calendar-check"></i>
        <span>Réservations</span>
    </a>
    <button class="bottom-nav-item" onclick="openSidebar()" style="background:none;border:none;cursor:pointer;">
        <i class="fa-solid fa-ellipsis"></i>
        <span>Plus</span>
    </button>
</nav>

<script>
(function() {
    var sidebar  = document.getElementById('sidebar');
    var overlay  = document.getElementById('sidebarOverlay');
    var startX   = 0;
    var startTime = 0;

    window.openSidebar = function() {
        sidebar.classList.add('open');
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    };
    window.closeSidebar = function() {
        sidebar.classList.remove('open');
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    };

    // Swipe left to close sidebar
    sidebar.addEventListener('touchstart', function(e) {
        startX = e.touches[0].clientX;
        startTime = Date.now();
    }, { passive: true });
    sidebar.addEventListener('touchend', function(e) {
        var dx = e.changedTouches[0].clientX - startX;
        var dt = Date.now() - startTime;
        if (dx < -50 && dt < 400) closeSidebar();
    }, { passive: true });

    // Swipe right from left edge to open sidebar
    document.addEventListener('touchstart', function(e) {
        if (e.touches[0].clientX < 20) startX = e.touches[0].clientX;
        startTime = Date.now();
    }, { passive: true });
    document.addEventListener('touchend', function(e) {
        var dx = e.changedTouches[0].clientX - startX;
        var dt = Date.now() - startTime;
        if (startX < 20 && dx > 60 && dt < 400) openSidebar();
    }, { passive: true });
})();

// Collapsible filters
function toggleFilters(btn) {
    var body = btn.closest('form').querySelector('.filters-body');
    var open = body.classList.toggle('open');
    btn.querySelector('.filters-toggle-icon').style.transform = open ? 'rotate(180deg)' : '';
    btn.querySelector('.filters-toggle-label').textContent = open ? 'Masquer les filtres' : 'Afficher les filtres';
}
</script>
@yield('scripts')
</body>
</html>
