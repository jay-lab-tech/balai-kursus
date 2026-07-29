<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Balai Kursus - Admin'))</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        html {
            height: 100%;
        }

        body {
            min-height: 100%;
            font-family: 'Inter', sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }

        .admin-shell {
            background:
                radial-gradient(circle at top left, rgba(14, 165, 233, 0.16), transparent 24%),
                radial-gradient(circle at bottom right, rgba(250, 204, 21, 0.12), transparent 20%),
                linear-gradient(180deg, #020617 0%, #0f172a 48%, #111827 100%);
        }

        .admin-layout {
            min-height: 100vh;
        }

        .admin-sidebar {
            background:
                linear-gradient(180deg, rgba(3, 7, 18, 0.98) 0%, rgba(15, 23, 42, 0.96) 100%);
            box-shadow: 24px 0 60px rgba(0, 0, 0, 0.28);
            overscroll-behavior: contain;
        }

        .admin-sidebar-shell {
            width: min(18.5rem, calc(100vw - 1.5rem));
        }

        .admin-panel {
            background: rgba(15, 23, 42, 0.72);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 24px 60px rgba(2, 6, 23, 0.34);
            backdrop-filter: blur(14px);
        }

        .admin-panel-soft {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.06);
        }

        .admin-panel__header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            padding: 1.5rem 1.5rem 0;
        }

        .admin-panel__title {
            font-size: 1.125rem;
            line-height: 1.75rem;
            font-weight: 700;
            color: #fff;
        }

        .admin-panel__subtitle {
            margin-top: 0.35rem;
            max-width: 42rem;
            font-size: 0.875rem;
            line-height: 1.6;
            color: rgb(148 163 184);
        }

        .admin-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 0.625rem;
            border-radius: 999px;
            border: 1px solid rgba(250, 204, 21, 0.18);
            background: rgba(250, 204, 21, 0.08);
            padding: 0.55rem 0.95rem;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.24em;
            text-transform: uppercase;
            color: rgb(253 224 71);
        }

        .admin-alert {
            display: flex;
            gap: 0.875rem;
            align-items: flex-start;
            border-radius: 1.25rem;
            padding: 1rem 1.1rem;
            border: 1px solid transparent;
        }

        .admin-alert-success {
            background: rgba(16, 185, 129, 0.12);
            border-color: rgba(16, 185, 129, 0.28);
            color: rgb(209 250 229);
        }

        .admin-alert-danger {
            background: rgba(244, 63, 94, 0.12);
            border-color: rgba(244, 63, 94, 0.28);
            color: rgb(255 228 230);
        }

        .admin-stat-card {
            border-radius: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(15, 23, 42, 0.8);
            box-shadow: 0 18px 40px rgba(2, 6, 23, 0.28);
            padding: 1.35rem 1.25rem;
        }

        .admin-stat-card__label {
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            color: rgb(148 163 184);
        }

        .admin-stat-card__value {
            margin-top: 0.75rem;
            font-size: clamp(1.8rem, 4vw, 2.4rem);
            line-height: 1;
            font-weight: 800;
            color: #fff;
        }

        .admin-stat-card__hint {
            margin-top: 0.8rem;
            font-size: 0.875rem;
            line-height: 1.6;
            color: rgb(148 163 184);
        }

        .admin-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.625rem;
            border-radius: 1rem;
            padding: 0.85rem 1.1rem;
            font-size: 0.875rem;
            font-weight: 700;
            transition: all 0.2s ease;
        }

        .admin-btn:hover {
            transform: translateY(-1px);
        }

        .admin-btn-primary {
            background: linear-gradient(135deg, #0284c7, #0369a1);
            color: #fff;
            box-shadow: 0 16px 30px rgba(14, 165, 233, 0.22);
        }

        .admin-btn-secondary {
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.05);
            color: rgb(226 232 240);
        }

        .admin-btn-ghost {
            border: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(255, 255, 255, 0.03);
            color: rgb(226 232 240);
        }

        .admin-btn-danger {
            background: rgba(190, 24, 93, 0.14);
            border: 1px solid rgba(244, 63, 94, 0.2);
            color: rgb(254 205 211);
        }

        .admin-btn-sm {
            padding: 0.7rem 0.95rem;
            font-size: 0.8rem;
        }

        .admin-label {
            display: block;
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: rgb(203 213 225);
        }

        .admin-input {
            width: 100%;
            border-radius: 1rem;
            border: 1px solid rgba(148, 163, 184, 0.28);
            background: #fff !important;
            color: #0f172a !important;
            padding: 0.9rem 1rem;
            outline: none;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08);
            color-scheme: light;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }

        .admin-input::placeholder {
            color: rgb(100 116 139);
        }

        .admin-input option,
        .admin-input optgroup {
            color: #0f172a;
            background: #fff;
        }

        .admin-input:focus {
            border-color: rgba(56, 189, 248, 0.75);
            box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.14), 0 12px 28px rgba(15, 23, 42, 0.1);
            background: #fff !important;
        }

        .admin-input::-webkit-calendar-picker-indicator,
        .admin-input::-webkit-clear-button {
            opacity: 0.78;
        }

        .admin-content form :is(
            input[type="text"],
            input[type="email"],
            input[type="password"],
            input[type="number"],
            input[type="date"],
            input[type="datetime-local"],
            input[type="month"],
            input[type="search"],
            input[type="tel"],
            input[type="time"],
            input[type="url"],
            input[type="week"],
            select,
            textarea
        ) {
            border-color: rgba(148, 163, 184, 0.28) !important;
            background: #fff !important;
            color: #0f172a !important;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08);
            color-scheme: light;
        }

        .admin-content form :is(
            input[type="text"],
            input[type="email"],
            input[type="password"],
            input[type="number"],
            input[type="date"],
            input[type="datetime-local"],
            input[type="month"],
            input[type="search"],
            input[type="tel"],
            input[type="time"],
            input[type="url"],
            input[type="week"],
            textarea
        )::placeholder {
            color: rgb(100 116 139) !important;
        }

        .admin-content form :is(select) option,
        .admin-content form :is(select) optgroup {
            color: #0f172a;
            background: #fff;
        }

        .admin-content form :is(
            input[type="text"],
            input[type="email"],
            input[type="password"],
            input[type="number"],
            input[type="date"],
            input[type="datetime-local"],
            input[type="month"],
            input[type="search"],
            input[type="tel"],
            input[type="time"],
            input[type="url"],
            input[type="week"],
            select,
            textarea
        ):focus {
            border-color: rgba(56, 189, 248, 0.75) !important;
            background: #fff !important;
            color: #0f172a !important;
            box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.14), 0 12px 28px rgba(15, 23, 42, 0.1) !important;
        }

        .admin-content form :is(
            input[type="date"],
            input[type="datetime-local"],
            input[type="month"],
            input[type="time"],
            input[type="week"]
        )::-webkit-calendar-picker-indicator,
        .admin-content form :is(
            input[type="search"]
        )::-webkit-search-cancel-button,
        .admin-content form :is(
            input[type="date"],
            input[type="datetime-local"],
            input[type="month"],
            input[type="time"],
            input[type="week"]
        )::-webkit-clear-button {
            opacity: 0.78;
        }

        .admin-field-error {
            font-size: 0.85rem;
            color: rgb(253 164 175);
        }

        .admin-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            padding: 0.45rem 0.8rem;
            font-size: 0.75rem;
            font-weight: 700;
            line-height: 1;
        }

        .admin-badge-warning {
            background: rgba(250, 204, 21, 0.12);
            color: rgb(253 224 71);
        }

        .admin-badge-info {
            background: rgba(59, 130, 246, 0.12);
            color: rgb(147 197 253);
        }

        .admin-badge-muted {
            background: rgba(148, 163, 184, 0.16);
            color: rgb(226 232 240);
        }

        .admin-empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 0.9rem;
            padding: 3rem 1.5rem;
        }

        .admin-empty-state__icon {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 4rem;
            width: 4rem;
            border-radius: 1.5rem;
            background: rgba(250, 204, 21, 0.1);
            color: rgb(253 224 71);
            font-size: 1.35rem;
        }

        .admin-empty-state h3 {
            font-size: 1.2rem;
            font-weight: 700;
            color: #fff;
        }

        .admin-empty-state p {
            max-width: 34rem;
            font-size: 0.95rem;
            line-height: 1.7;
            color: rgb(148 163 184);
        }

        .admin-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .admin-table thead th {
            padding: 1rem 1.2rem;
            text-align: left;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: rgb(148 163 184);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(255, 255, 255, 0.02);
            white-space: nowrap;
        }

        .admin-table tbody td {
            padding: 1rem 1.2rem;
            vertical-align: top;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            color: rgb(226 232 240);
        }

        .admin-table tbody tr:hover td {
            background: rgba(255, 255, 255, 0.02);
        }

        .admin-nav-link {
            position: relative;
            display: flex;
            align-items: center;
            gap: 0.875rem;
            padding: 0.85rem 1rem;
            border-radius: 1rem;
            color: rgb(203 213 225);
            transition: all 0.2s ease;
        }

        .admin-nav-link:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.05);
            transform: translateX(2px);
        }

        .admin-nav-link.active {
            color: #fff;
            background: linear-gradient(90deg, rgba(14, 165, 233, 0.2), rgba(234, 179, 8, 0.14));
            border: 1px solid rgba(56, 189, 248, 0.16);
            box-shadow: inset 0 0 0 1px rgba(250, 204, 21, 0.08);
        }

        .admin-nav-link.active::before {
            content: "";
            position: absolute;
            left: -0.85rem;
            top: 0.7rem;
            bottom: 0.7rem;
            width: 4px;
            border-radius: 999px;
            background: linear-gradient(180deg, #facc15, #0ea5e9);
        }

        .admin-nav-group-title {
            color: rgb(148 163 184);
            letter-spacing: 0.18em;
        }

        .admin-main {
            background: rgba(255, 255, 255, 0.02);
            min-width: 0;
            min-height: 0;
        }

        .admin-content {
            min-height: calc(100vh - 5.5rem);
            min-width: 0;
            overscroll-behavior: contain;
        }

        .admin-topbar-actions {
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .admin-topbar-date {
            max-width: 100%;
        }

        .admin-mobile-only {
            display: inline-flex;
        }

        @media (min-width: 960px) {
            .admin-layout {
                display: flex;
                height: 100vh;
                overflow: hidden;
            }

            .admin-sidebar {
                position: sticky;
                top: 0;
                left: auto;
                inset: auto;
                z-index: 20;
                height: 100vh;
                flex: 0 0 min(18.5rem, calc(100vw - 1.5rem));
                transform: translateX(0) !important;
            }

            .admin-main {
                display: flex;
                flex: 1 1 auto;
                flex-direction: column;
            }

            .admin-content {
                min-height: 0;
                flex: 1 1 auto;
            }

            .admin-mobile-only,
            .admin-sidebar-overlay {
                display: none !important;
            }
        }

        @media (max-width: 959.98px) {
            .admin-shell-mobile-lock {
                overflow: hidden;
                height: 100vh;
            }

            .admin-content {
                min-height: calc(100vh - 7.5rem);
            }
        }

        @media (max-width: 639.98px) {
            .admin-mobile-stack {
                align-items: flex-start;
            }

            .admin-topbar-actions {
                width: 100%;
                justify-content: flex-start;
            }

            .admin-topbar-actions form,
            .admin-topbar-actions button,
            .admin-topbar-actions a {
                width: 100%;
            }

            .admin-panel__header {
                padding: 1.2rem 1.2rem 0;
            }

            .admin-panel {
                border-radius: 1.35rem;
            }

            .admin-btn,
            .admin-btn-sm {
                width: 100%;
                padding-left: 1rem;
                padding-right: 1rem;
            }

            .admin-empty-state {
                padding: 2.4rem 1.1rem;
            }

            .admin-table {
                min-width: 100%;
            }

            .admin-table thead {
                display: none;
            }

            .admin-table tbody {
                display: grid;
                gap: 1rem;
                padding: 1rem;
            }

            .admin-table tbody tr {
                display: block;
                overflow: hidden;
                border-radius: 1.15rem;
                border: 1px solid rgba(255, 255, 255, 0.08);
                background: rgba(2, 6, 23, 0.35);
            }

            .admin-table tbody td {
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                gap: 1rem;
                padding: 0.95rem 1rem;
                white-space: normal;
            }

            .admin-table tbody td::before {
                content: attr(data-label);
                flex: 0 0 38%;
                min-width: 5.5rem;
                font-size: 0.7rem;
                font-weight: 700;
                letter-spacing: 0.16em;
                text-transform: uppercase;
                color: rgb(148 163 184);
            }

            .admin-table tbody td > * {
                min-width: 0;
                flex: 1;
            }

            .admin-table tbody td:last-child {
                border-bottom: 0;
            }

            .admin-table tbody td:has(.admin-btn),
            .admin-table tbody td:has(form) {
                flex-direction: column;
                align-items: stretch;
            }

            .admin-table tbody td:has(.admin-btn)::before,
            .admin-table tbody td:has(form)::before {
                flex-basis: auto;
            }
        }

        .admin-logo-mark {
            box-shadow: 0 18px 30px rgba(14, 165, 233, 0.18);
        }

        .admin-logo-mark img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .admin-scrollbar::-webkit-scrollbar {
            width: 8px;
        }

        .admin-scrollbar::-webkit-scrollbar-track {
            background: rgba(15, 23, 42, 0.4);
        }

        .admin-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(14, 165, 233, 0.75);
            border-radius: 999px;
        }

        .admin-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(2, 132, 199, 0.95);
        }

        /* Admin visual system: a light operations console, not a generic dark dashboard. */
        .admin-shell {
            background: #f7f8f6;
            color: #173f5f;
        }

        .admin-sidebar {
            background: #ffffff;
            border-color: #dce7e5 !important;
            box-shadow: 12px 0 32px rgba(23, 63, 95, 0.06);
        }

        .admin-main,
        .admin-content {
            background: #f7f8f6;
        }

        .admin-panel,
        .admin-panel-soft,
        .admin-stat-card {
            background: #ffffff;
            border-color: #dce7e5;
            box-shadow: 0 14px 35px rgba(23, 63, 95, 0.08);
            backdrop-filter: none;
        }

        .admin-panel__title,
        .admin-empty-state h3,
        .admin-stat-card__value {
            color: #173f5f;
        }

        .admin-panel__subtitle,
        .admin-stat-card__hint,
        .admin-empty-state p,
        .admin-nav-group-title {
            color: #718596;
        }

        .admin-nav-link {
            color: #40627d;
        }

        .admin-nav-link:hover {
            color: #0f766e;
            background: #e8f7f4;
            transform: none;
        }

        .admin-nav-link.active {
            color: #0f766e;
            background: #e8f7f4;
            border-color: #b8e5df;
            box-shadow: none;
        }

        .admin-nav-link.active::before {
            background: #d97706;
        }

        .admin-sidebar [class~="text-white"],
        .admin-sidebar [class~="text-slate-200"] {
            color: #173f5f !important;
        }

        .admin-sidebar [class~="text-slate-300"],
        .admin-sidebar [class~="text-slate-400"] {
            color: #718596 !important;
        }

        .admin-sidebar [class~="bg-white"],
        .admin-sidebar [class~="bg-white/5"] {
            background: #ffffff !important;
        }

        .admin-sidebar [class~="border-white/10"] {
            border-color: #dce7e5 !important;
        }

        .admin-content [class~="text-white"] {
            color: #173f5f !important;
        }

        .admin-content [class~="text-slate-200"],
        .admin-content [class~="text-slate-300"] {
            color: #40627d !important;
        }

        .admin-content [class~="text-slate-400"],
        .admin-content [class~="text-gray-400"] {
            color: #718596 !important;
        }

        .admin-content [class~="bg-white/5"],
        .admin-content [class~="bg-black/20"],
        .admin-content [class~="bg-slate-950/45"] {
            background: #f3f7f6 !important;
        }

        .admin-content [class~="border-white/10"],
        .admin-content [class~="border-gray-700"] {
            border-color: #dce7e5 !important;
        }

        .admin-content [class*="bg-gradient"] {
            background-image: none !important;
            background-color: #ffffff !important;
            color: #173f5f !important;
            border: 1px solid #dce7e5;
            box-shadow: 0 14px 35px rgba(23, 63, 95, 0.08);
        }

        .admin-content [class~="text-yellow-300"],
        .admin-content [class~="text-yellow-400"] {
            color: #b45309 !important;
        }

        .admin-content [class~="bg-yellow-400"],
        .admin-content [class~="bg-yellow-500"] {
            background: #fef3c7 !important;
        }

        .admin-content [class~="text-sky-300"],
        .admin-content [class~="text-sky-400"] {
            color: #0f766e !important;
        }

        .admin-content [class~="bg-sky-600"],
        .admin-content [class~="bg-sky-700"] {
            background: #0d9488 !important;
        }

        .admin-content [class~="bg-[#173f5f]"],
        .admin-content a[class*="bg-gradient"],
        .admin-content button[class*="bg-gradient"] {
            background-image: none !important;
            background: #0d9488 !important;
            color: #ffffff !important;
            border-color: #0d9488 !important;
        }

        .admin-table thead th {
            color: #718596;
            background: #f3f7f6;
            border-color: #dce7e5;
        }

        .admin-table tbody td {
            color: #40627d;
            border-color: #e8efed;
        }

        .admin-table tbody tr:hover td {
            background: #f3f7f6;
        }

        .admin-content h1,
        .admin-content h2,
        .admin-content h3,
        .admin-content h4 {
            letter-spacing: -0.025em;
        }

        /* Institutional operations workspace */
        body.admin-shell { background: #f5f2ea; color: #1e2d36; }
        .admin-layout { background: #f5f2ea; }
        .admin-sidebar { background: #173f5f; border-color: rgba(255,255,255,.15); box-shadow: 12px 0 30px rgba(23,63,95,.12); }
        .admin-sidebar .admin-panel { background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.14); box-shadow: none; }
        .admin-sidebar .admin-panel-soft { background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.12); }
        .admin-sidebar .admin-nav-group-title { color: #9ddbd4; letter-spacing: .16em; }
        .admin-sidebar .admin-nav-link { color: #d9e6ec; border-left: 3px solid transparent; border-radius: 0; }
        .admin-sidebar .admin-nav-link:hover { color: #fff; background: rgba(255,255,255,.08); }
        .admin-sidebar .admin-nav-link.active { color: #fff; background: rgba(13,148,136,.32); border-left-color: #f0a36c; box-shadow: none; }
        .admin-sidebar .admin-nav-link i { color: #9ddbd4; }
        .admin-main { background: #f5f2ea; }
        .admin-main > header { background: #fffefa; border-color: #cfc8bb; box-shadow: 0 3px 12px rgba(30,45,54,.04); }
        .admin-main > header p.text-white { color: #173f5f !important; font-family: 'Source Serif 4', Georgia, serif; }
        .admin-main > header p.text-slate-400 { color: #6c7c82 !important; }
        .admin-topbar-date { background: #f5f2ea !important; border-color: #cfc8bb !important; color: #526875 !important; }
        .admin-topbar-actions form button { background: #a84a2a !important; border-radius: 0 !important; }
        .admin-content { color: #1e2d36; }
        .admin-content .admin-panel, .admin-content .admin-stat-card { background: #fffefa; border: 1px solid #cfc8bb; box-shadow: 0 8px 22px rgba(30,45,54,.07); border-radius: 8px; }
        .admin-content .admin-panel__title, .admin-content .admin-stat-card__value, .admin-content h1, .admin-content h2, .admin-content h3, .admin-content h4 { color: #173f5f; }
        .admin-content .admin-panel__subtitle, .admin-content .admin-stat-card__hint { color: #6c7c82; }
        .admin-content .admin-stat-card__label, .admin-content .admin-label { color: #526875; }
        .admin-content .admin-table thead th { color: #526875; background: #f5f2ea; border-color: #cfc8bb; }
        .admin-content .admin-table tbody td { color: #1e2d36; border-color: #e5e0d6; }
        .admin-content .admin-table tbody tr:hover td { background: #f5f2ea; }
        .admin-content .admin-btn-primary { background: #0d9488; border-radius: 0; box-shadow: none; }
        .admin-content .admin-btn-secondary, .admin-content .admin-btn-ghost { color: #173f5f; background: transparent; border-color: #cfc8bb; border-radius: 0; }
        .admin-content .admin-badge-warning { background: #f8e5d8; color: #8a3b22; }
        .admin-content .admin-badge-info { background: #dff2ef; color: #0f766e; }
        .admin-content .admin-badge-muted { background: #e5e0d6; color: #526875; }
    </style>

    @yield('styles')
</head>
<body class="admin-shell text-slate-100">
    @php
        $navGroups = [
            'Utama' => [
                ['label' => 'Dashboard', 'route' => route('admin.dashboard'), 'active' => request()->routeIs('admin.dashboard'), 'icon' => 'bi-speedometer2'],
            ],
            'Akademik' => [
                ['label' => 'Program', 'route' => route('admin.program.index'), 'active' => request()->routeIs('admin.program.*'), 'icon' => 'bi-diagram-3-fill'],
                ['label' => 'Level', 'route' => route('admin.level.index'), 'active' => request()->routeIs('admin.level.*'), 'icon' => 'bi-layers-fill'],
                ['label' => 'Kelas Program', 'route' => route('admin.kursus.index'), 'active' => request()->routeIs('admin.kursus.*'), 'icon' => 'bi-book-half'],
                ['label' => 'Jadwal', 'route' => route('admin.jadwal.all'), 'active' => request()->routeIs('admin.jadwal.*'), 'icon' => 'bi-calendar-week-fill'],
                ['label' => 'Hari', 'route' => route('admin.hari.index'), 'active' => request()->routeIs('admin.hari.*'), 'icon' => 'bi-calendar3'],
                ['label' => 'Lokasi', 'route' => route('admin.lokasi.index'), 'active' => request()->routeIs('admin.lokasi.*'), 'icon' => 'bi-geo-alt-fill'],
                ['label' => 'Kelas', 'route' => route('admin.kelas.index'), 'active' => request()->routeIs('admin.kelas.*'), 'icon' => 'bi-door-open-fill'],
            ],
            'Pengguna' => [
                ['label' => 'Peserta', 'route' => route('admin.peserta.index'), 'active' => request()->routeIs('admin.peserta.*'), 'icon' => 'bi-people-fill'],
                ['label' => 'Instruktur', 'route' => route('admin.instruktur.index'), 'active' => request()->routeIs('admin.instruktur.*'), 'icon' => 'bi-person-badge-fill'],
            ],
            'Operasional' => [
                ['label' => 'Risalah & Absensi', 'route' => route('admin.risalah.all'), 'active' => request()->routeIs('admin.risalah.*') || request()->routeIs('admin.absensi.*'), 'icon' => 'bi-journal-richtext'],
                ['label' => 'Tes Penempatan', 'route' => route('admin.score.index'), 'active' => request()->routeIs('admin.score.*'), 'icon' => 'bi-clipboard-data-fill'],
                ['label' => 'Sertifikat', 'route' => route('admin.certificates.index'), 'active' => request()->routeIs('admin.certificates.*') || request()->routeIs('admin.templates.*'), 'icon' => 'bi-patch-check-fill'],
            ],
        ];
    @endphp

    <div
        x-data="{
            sidebarOpen: false,
            desktopBreakpoint: 960,
            isDesktop: window.innerWidth >= 960,
            syncLayout() {
                this.isDesktop = window.innerWidth >= this.desktopBreakpoint;
                if (this.isDesktop) {
                    this.sidebarOpen = true;
                    return;
                }

                this.sidebarOpen = false;
            }
        }"
        x-init="syncLayout(); window.addEventListener('resize', () => syncLayout())"
        :class="{ 'admin-shell-mobile-lock': sidebarOpen && !isDesktop }"
        class="admin-layout min-h-screen">
        <aside
            class="admin-sidebar admin-sidebar-shell admin-scrollbar fixed inset-y-0 left-0 z-50 flex max-w-full flex-col overflow-y-auto border-r border-white/10 px-4 py-5 transition-transform duration-300 ease-out sm:px-5"
            :class="sidebarOpen || isDesktop ? 'translate-x-0' : '-translate-x-full'">
            <div class="flex items-center justify-between gap-3">
                <a href="{{ route('admin.dashboard') }}" class="flex min-w-0 items-center gap-4">
                    <div class="admin-logo-mark h-14 w-14 rounded-2xl bg-white p-2.5">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo Balai Kursus">
                    </div>
                    <div class="min-w-0">
                        <p class="truncate text-lg font-bold text-white">Balai Kursus</p>
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-yellow-300">Admin Panel</p>
                    </div>
                </a>
                <button type="button" class="admin-mobile-only rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-slate-300" @click="sidebarOpen = false">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <div class="admin-panel mt-6 rounded-[1.5rem] p-4">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Akun Aktif</p>
                <div class="mt-3 flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-sky-500 to-sky-700 text-base font-bold text-white">
                        {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="truncate text-sm font-semibold text-white">{{ Auth::user()->name }}</p>
                        <p class="truncate text-xs text-slate-400">{{ Auth::user()->email }}</p>
                    </div>
                </div>
            </div>

            <nav class="mt-8 space-y-7">
                @foreach ($navGroups as $groupLabel => $items)
                    <div>
                        <p class="admin-nav-group-title mb-3 px-4 text-[11px] font-semibold uppercase">{{ $groupLabel }}</p>
                        <div class="space-y-2">
                            @foreach ($items as $item)
                                <a href="{{ $item['route'] }}" class="admin-nav-link {{ $item['active'] ? 'active' : '' }}" @click="if (!isDesktop) sidebarOpen = false">
                                    <i class="bi {{ $item['icon'] }} text-lg"></i>
                                    <span class="text-sm font-medium">{{ $item['label'] }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </nav>

            <div class="mt-8 space-y-3">
                <a href="{{ url('/papan-informasi') }}" class="admin-panel-soft flex items-center justify-between rounded-2xl px-4 py-3 text-sm text-slate-200 transition hover:bg-white/10" @click="if (!isDesktop) sidebarOpen = false">
                    <span class="flex items-center gap-3">
                        <i class="bi bi-display"></i>
                        Papan Informasi
                    </span>
                    <i class="bi bi-arrow-up-right"></i>
                </a>
                <a href="{{ url('/') }}" class="admin-panel-soft flex items-center justify-between rounded-2xl px-4 py-3 text-sm text-slate-200 transition hover:bg-white/10" @click="if (!isDesktop) sidebarOpen = false">
                    <span class="flex items-center gap-3">
                        <i class="bi bi-globe2"></i>
                        Lihat Situs
                    </span>
                    <i class="bi bi-arrow-up-right"></i>
                </a>
            </div>
        </aside>

        <div x-cloak x-show="sidebarOpen && !isDesktop" x-transition.opacity class="admin-sidebar-overlay fixed inset-0 z-40 bg-slate-950/70" @click="sidebarOpen = false"></div>

        <div class="admin-main flex min-h-screen flex-1 flex-col">
            <header class="sticky top-0 z-30 border-b border-white/10 bg-slate-950/72 backdrop-blur-xl">
                <div class="admin-mobile-stack flex flex-wrap items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
                    <div class="flex min-w-0 flex-1 items-center gap-3">
                        <button type="button" class="admin-mobile-only h-11 w-11 items-center justify-center rounded-2xl border border-white/10 bg-white/5 text-slate-200" @click="sidebarOpen = true">
                            <i class="bi bi-list text-xl"></i>
                        </button>
                        <div class="min-w-0">
                            <p class="truncate text-lg font-semibold text-white">@yield('page-title', 'Dashboard')</p>
                            <p class="truncate text-sm text-slate-400">@yield('page-description', 'Kelola operasional Balai Kursus dari satu panel admin yang terpusat.')</p>
                        </div>
                    </div>

                    <div class="admin-topbar-actions flex items-center gap-3">
                        <div class="hidden rounded-2xl border border-yellow-400/20 bg-yellow-400/10 px-4 py-2 text-sm font-semibold text-yellow-300 md:inline-flex md:items-center md:gap-2">
                            <i class="bi bi-shield-lock-fill"></i>
                            Admin
                        </div>
                        <div class="admin-topbar-date hidden rounded-2xl border border-white/10 bg-white/5 px-4 py-2 text-sm text-slate-300 sm:block">
                            {{ now()->translatedFormat('l, j F Y') }}
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-sky-600 to-sky-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:from-sky-500 hover:to-sky-600">
                                <i class="bi bi-box-arrow-right"></i>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <main class="admin-content admin-scrollbar flex-1 overflow-y-auto px-4 py-6 sm:px-6 lg:px-8">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.admin-table').forEach(function (table) {
                const headers = Array.from(table.querySelectorAll('thead th')).map(function (header) {
                    return header.textContent.trim();
                });

                table.querySelectorAll('tbody tr').forEach(function (row) {
                    Array.from(row.children).forEach(function (cell, index) {
                        if (!cell.dataset.label) {
                            cell.dataset.label = headers[index] || 'Data';
                        }
                    });
                });
            });
        });
    </script>

    @yield('scripts')
</body>
</html>
