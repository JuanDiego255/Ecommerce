<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mi Portal') — {{ $tenantTitle ?? config('app.name') }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --brand:    #5e72e4;
            --brand-dk: #4a5cd6;
            --green:    #2dce89;
            --gray1:    #1e293b;
            --gray2:    #64748b;
            --gray3:    #94a3b8;
            --gray4:    #e2e8f0;
            --surface:  #ffffff;
            --bg:       #f1f5f9;
        }
        * { box-sizing: border-box; }
        body {
            background: var(--bg);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            color: var(--gray1);
            min-height: 100vh;
        }

        /* ── Top nav ── */
        .portal-nav {
            background: var(--surface);
            border-bottom: 1px solid var(--gray4);
            padding: .75rem 1rem;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 1px 4px rgba(0,0,0,.06);
        }
        .portal-nav .brand {
            font-weight: 700;
            font-size: 1rem;
            color: var(--brand);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: .5rem;
        }
        .portal-nav .patient-chip {
            background: var(--bg);
            border-radius: 20px;
            padding: .25rem .75rem;
            font-size: .78rem;
            color: var(--gray2);
            display: flex;
            align-items: center;
            gap: .4rem;
        }
        .portal-nav .patient-chip img {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            object-fit: cover;
        }

        /* ── Cards ── */
        .p-card {
            background: var(--surface);
            border-radius: 14px;
            box-shadow: 0 1px 4px rgba(0,0,0,.06);
            padding: 1.25rem 1.5rem;
            margin-bottom: 1rem;
        }
        .p-section-label {
            font-size: .68rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--gray3);
            margin-bottom: .75rem;
        }

        /* ── Stat tiles ── */
        .stat-tile {
            background: var(--bg);
            border-radius: 12px;
            padding: .75rem 1rem;
            text-align: center;
        }
        .stat-tile .val { font-size: 1.6rem; font-weight: 700; color: var(--brand); line-height: 1; }
        .stat-tile .lbl { font-size: .72rem; color: var(--gray2); margin-top: .2rem; }

        /* ── Session item ── */
        .ses-item {
            border: 1px solid var(--gray4);
            border-radius: 12px;
            padding: .9rem 1rem;
            margin-bottom: .6rem;
            display: flex;
            gap: .9rem;
            align-items: flex-start;
            background: var(--surface);
            text-decoration: none;
            color: inherit;
            transition: border-color .15s, box-shadow .15s;
        }
        .ses-item:hover { border-color: var(--brand); box-shadow: 0 2px 8px rgba(94,114,228,.12); color: inherit; }
        .ses-date {
            flex-shrink: 0;
            width: 40px;
            text-align: center;
            background: var(--brand);
            border-radius: 10px;
            padding: .35rem .25rem;
            color: #fff;
        }
        .ses-date .d { font-size: 1.1rem; font-weight: 700; line-height: 1; }
        .ses-date .m { font-size: .6rem; text-transform: uppercase; opacity: .85; }

        /* ── Pills ── */
        .pp { display:inline-flex;align-items:center;gap:.2rem;padding:.18rem .55rem;border-radius:20px;font-size:.72rem;font-weight:600; }
        .pp-green  { background:#d1fae5;color:#065f46; }
        .pp-blue   { background:#dbeafe;color:#1e40af; }
        .pp-yellow { background:#fef3c7;color:#92400e; }
        .pp-gray   { background:#f1f5f9;color:#64748b; }

        /* ── Consent item ── */
        .consent-item {
            border: 1px solid var(--gray4);
            border-radius: 12px;
            padding: .85rem 1rem;
            margin-bottom: .5rem;
            display: flex;
            align-items: center;
            gap: .75rem;
            text-decoration: none;
            color: inherit;
            background: var(--surface);
            transition: border-color .15s;
        }
        .consent-item:hover { border-color: var(--brand); color: inherit; }
        .consent-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: #ede9fe;
            color: #7c3aed;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        /* ── Image grid ── */
        .img-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(110px, 1fr)); gap: .5rem; }
        .img-thumb {
            aspect-ratio: 1;
            border-radius: 10px;
            overflow: hidden;
            cursor: pointer;
            position: relative;
        }
        .img-thumb img { width: 100%; height: 100%; object-fit: cover; }
        .img-thumb .tipo-tag {
            position: absolute;
            bottom: 4px;
            left: 4px;
            background: rgba(0,0,0,.55);
            color: #fff;
            font-size: .58rem;
            padding: .1rem .35rem;
            border-radius: 6px;
        }

        /* ── Lightbox ── */
        .lightbox-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.88);
            z-index: 1100;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .lightbox-overlay img { max-width: 92vw; max-height: 88vh; border-radius: 8px; object-fit: contain; }
        .lightbox-close {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: rgba(255,255,255,.15);
            border: none;
            color: #fff;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            font-size: 1rem;
            cursor: pointer;
        }

        /* ── Footer ── */
        .portal-footer {
            text-align: center;
            padding: 2rem 1rem 3rem;
            font-size: .75rem;
            color: var(--gray3);
        }

        /* ── Next appointment banner ── */
        .next-appt {
            background: linear-gradient(135deg, #eff6ff 0%, #e0f2fe 100%);
            border: 1px solid #bfdbfe;
            border-radius: 14px;
            padding: .85rem 1.25rem;
            display: flex;
            align-items: center;
            gap: .75rem;
            margin-bottom: 1rem;
        }
        .next-appt .icon { font-size: 1.4rem; color: var(--brand); flex-shrink: 0; }
        .next-appt .label { font-size: .72rem; color: var(--gray2); }
        .next-appt .date  { font-size: .95rem; font-weight: 700; color: var(--gray1); }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="portal-nav">
        <div class="container-sm d-flex align-items-center justify-content-between">
            <a href="#" class="brand">
                <i class="fas fa-heartbeat"></i>
                {{ $tenantTitle ?? config('app.name') }}
            </a>
            @isset($paciente)
            <div class="patient-chip">
                <img src="{{ $paciente->foto_url }}" alt="">
                {{ $paciente->nombre }}
            </div>
            @endisset
        </div>
    </nav>

    <main class="container-sm py-4">
        @yield('content')
    </main>

    <footer class="portal-footer">
        {{ $tenantTitle ?? config('app.name') }} &middot; Portal de seguimiento
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
