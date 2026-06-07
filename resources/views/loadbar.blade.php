<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Loading</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --ink: #0f172a;
            --muted: #475569;
            --accent: #0ea5a4;
            --accent-2: #f97316;
            --panel: rgba(255, 255, 255, 0.8);
            --ring: rgba(15, 23, 42, 0.08);
            --shadow: 0 30px 60px rgba(15, 23, 42, 0.18);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Space Grotesk", sans-serif;
            color: var(--ink);
            background: radial-gradient(1100px 520px at 10% -10%, #fef3c7 0%, rgba(254, 243, 199, 0) 70%),
                radial-gradient(900px 600px at 90% 0%, #e0f2fe 0%, rgba(224, 242, 254, 0) 65%),
                linear-gradient(140deg, #fff7ed 0%, #f8fafc 45%, #ecfeff 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 16px;
        }

        body::before,
        body::after {
            content: "";
            position: fixed;
            inset: auto;
            width: 240px;
            height: 240px;
            border-radius: 40px;
            background: linear-gradient(145deg, rgba(14, 165, 164, 0.18), rgba(249, 115, 22, 0.15));
            filter: blur(0.5px);
            z-index: 0;
        }

        body::before {
            top: 80px;
            left: -80px;
            transform: rotate(12deg);
        }

        body::after {
            bottom: 60px;
            right: -60px;
            transform: rotate(-10deg);
        }

        .panel {
            position: relative;
            z-index: 1;
            width: min(560px, 92vw);
            padding: 40px 36px 32px;
            background: var(--panel);
            border: 1px solid var(--ring);
            border-radius: 28px;
            box-shadow: var(--shadow);
            backdrop-filter: blur(12px);
            animation: rise 600ms ease-out;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 14px;
            border-radius: 999px;
            background: rgba(14, 165, 164, 0.12);
            color: #0f766e;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        h1 {
            margin: 18px 0 12px;
            font-size: clamp(28px, 4vw, 36px);
            line-height: 1.15;
        }

        p {
            margin: 0 0 24px;
            color: var(--muted);
            font-size: 16px;
            line-height: 1.6;
        }

        .progress {
            position: relative;
            height: 12px;
            background: rgba(15, 23, 42, 0.08);
            border-radius: 999px;
            overflow: hidden;
            border: 1px solid rgba(15, 23, 42, 0.08);
            --duration: 3000ms;
        }

        .progress .bar {
            height: 100%;
            width: 0;
            border-radius: inherit;
            background: linear-gradient(90deg, var(--accent), var(--accent-2));
            animation: load var(--duration) linear forwards;
        }

        .meta {
            margin-top: 20px;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            font-size: 14px;
        }

        .meta span {
            color: var(--muted);
        }

        .meta a {
            color: var(--ink);
            text-decoration: none;
            font-weight: 600;
            border-bottom: 1px solid rgba(15, 23, 42, 0.2);
        }

        .meta a:hover {
            border-bottom-color: rgba(15, 23, 42, 0.6);
        }

        @keyframes load {
            to {
                width: 100%;
            }
        }

        @keyframes rise {
            from {
                opacity: 0;
                transform: translateY(16px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .panel {
                animation: none;
            }

            .progress .bar {
                animation: none;
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <main class="panel" style="--duration: {{ $delayMs }}ms">
        <span class="badge">{{ $message ? 'Succes' : 'Session Verified' }}</span>
        <h1>{{ $message ?: 'Preparing your dashboard' }}</h1>
        <p>{{ $message ? 'U wordt doorgestuurd naar de betreffende pagina.' : 'We are syncing your workspace and routing you to the right area. This should only take a moment.' }}</p>

        <div class="progress" style="--duration: {{ $delayMs }}ms">
            <span class="bar"></span>
        </div>

        <div class="meta">
            <span>Redirecting in {{ (int) ($delayMs / 1000) }} seconds</span>
            <a href="{{ $redirectTo }}">Go now</a>
        </div>
    </main>

    <script>
        setTimeout(function() {
            window.location.assign(@json($redirectTo));
        }, {{ $delayMs }});
    </script>
</body>

</html>
