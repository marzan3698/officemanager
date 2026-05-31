<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>{{ config('app.name', 'অফিস ম্যানেজার') }}</title>
    
    <!-- PWA Meta Tags -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#1A56DB">
    <link rel="apple-touch-icon" href="/icons/icon-192x192.png">
    
    <!-- Tiro Bangla Font -->
    <link href="https://fonts.maateen.me/tiro-bangla/font.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #1A56DB;
            --secondary: #0E9F6E;
            --accent: #FF5A1F;
            --bg: #F9FAFB;
            --surface: #FFFFFF;
            --text-primary: #111827;
            --text-secondary: #6B7280;
            --border: #E5E7EB;
            --success: #0E9F6E;
            --warning: #FF5A1F;
            --danger: #E02424;
            --radius-sm: 8px;
            --radius-md: 16px;
            --radius-lg: 24px;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.08);
            --shadow-md: 0 4px 16px rgba(0,0,0,0.10);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Tiro Bangla', sans-serif;
        }

        body {
            background-color: var(--bg);
            color: var(--text-primary);
        }

        .app-container {
            max-width: 430px;
            margin: 0 auto;
            min-height: 100vh;
            background-color: var(--bg);
            position: relative;
            padding-bottom: 70px; /* space for bottom nav */
            box-shadow: var(--shadow-md);
            overflow-x: hidden;
        }

        .header {
            padding: 16px;
            background: var(--surface);
            box-shadow: var(--shadow-sm);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .header h1 {
            font-size: 20px;
            color: var(--text-primary);
        }
        
        .content {
            padding: 16px;
        }

        a {
            text-decoration: none;
            color: var(--primary);
        }

        input, select, textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            font-size: 16px;
            margin-bottom: 12px;
            outline: none;
        }

        input:focus, select:focus, textarea:focus {
            border-color: var(--primary);
        }

        .btn {
            display: inline-block;
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: var(--radius-sm);
            font-size: 16px;
            font-weight: 600;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            opacity: 0.9;
        }

        .alert {
            padding: 12px;
            border-radius: var(--radius-sm);
            margin-bottom: 16px;
            font-weight: 500;
        }
        .alert-error {
            background-color: #FDE8E8;
            color: var(--danger);
        }
        .alert-success {
            background-color: #DEF7EC;
            color: var(--success);
        }
        
        .d-flex { display: flex; }
        .justify-between { justify-content: space-between; }
        .align-center { align-items: center; }
        .mb-2 { margin-bottom: 8px; }
        .mb-4 { margin-bottom: 16px; }
        .mt-4 { margin-top: 16px; }

        /* Dashboard Redesign Styles */
        .dashboard-header {
            background: linear-gradient(180deg, #9D1C5B 0%, #D42B6A 100%);
            color: white;
            padding: 24px 20px 48px 20px; /* Extra bottom padding for overlap */
            border-radius: 0 0 16px 16px;
        }
        .dashboard-header h1 {
            color: white;
            font-size: 22px;
            font-weight: 600;
        }
        .dashboard-overlap-card {
            background: var(--surface);
            border-radius: 24px 24px 0 0;
            margin-top: -32px;
            padding: 24px 16px;
            min-height: calc(100vh - 120px);
            box-shadow: 0 -4px 12px rgba(0,0,0,0.05);
        }
        .service-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px 8px;
            margin-bottom: 24px;
        }
        .service-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            text-decoration: none;
            color: var(--text-primary);
        }
        .service-icon {
            width: 56px;
            height: 56px;
            background: #F3F4F6;
            border: 1px solid #E5E7EB;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 24px;
            margin-bottom: 8px;
            transition: all 0.2s;
        }
        .service-item:hover .service-icon {
            background: #E5E7EB;
        }
        .service-label {
            font-size: 11px;
            line-height: 1.2;
            color: #374151;
            font-weight: 500;
        }
        .quick-features-scroll {
            display: flex;
            overflow-x: auto;
            gap: 12px;
            padding-bottom: 12px;
            scrollbar-width: none;
        }
        .quick-features-scroll::-webkit-scrollbar {
            display: none;
        }
        .quick-feature-card {
            flex: 0 0 auto;
            background: #F9FAFB;
            border: 1px solid #E5E7EB;
            border-radius: 12px;
            padding: 12px;
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 160px;
        }
        .section-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 12px;
            color: var(--text-primary);
        }
        
        /* PWA Install Modal Styles */
        #pwa-install-modal {
            display: none; /* Hidden by default */
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: var(--primary);
            z-index: 9999;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            padding: 24px;
            text-align: center;
        }
        #pwa-install-modal.show {
            display: flex;
        }
        .pwa-icon {
            width: 96px;
            height: 96px;
            border-radius: 24px;
            background: white;
            margin-bottom: 24px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.2);
            padding: 12px;
        }
        .pwa-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 12px;
        }
        .pwa-desc {
            font-size: 16px;
            opacity: 0.9;
            margin-bottom: 32px;
            line-height: 1.5;
            max-width: 300px;
        }
        .pwa-btn {
            background: white;
            color: var(--primary);
            border: none;
            padding: 16px 32px;
            border-radius: 30px;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            width: 100%;
            max-width: 300px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transition: transform 0.2s;
        }
        .pwa-btn:active {
            transform: scale(0.95);
        }
        #ios-instructions {
            display: none;
            background: rgba(255,255,255,0.1);
            padding: 16px;
            border-radius: 12px;
            margin-top: 20px;
            text-align: left;
            font-size: 14px;
            line-height: 1.6;
        }
    </style>
</head>
<body style="margin: 0; background-color: var(--surface);">
    <div class="app-container">
        @yield('content')
        
        @if(Auth::check())
            @include('components.bottom-nav')
        @endif
    </div>

    <!-- PWA Install Modal -->
    <div id="pwa-install-modal">
        <img src="/icons/icon-192x192.png" alt="App Icon" class="pwa-icon">
        <div class="pwa-title">Shantikotha Office</div>
        <div class="pwa-desc">সর্বোত্তম অভিজ্ঞতার জন্য আমাদের অ্যাপটি ইনস্টল করুন।</div>
        
        <button id="pwa-install-btn" class="pwa-btn">অ্যাপ ইনস্টল করুন</button>
        
        <div id="ios-instructions">
            <strong>iOS ব্যবহারকারীদের জন্য:</strong><br>
            ১. নিচের <strong>Share</strong> আইকনে ট্যাপ করুন।<br>
            ২. <strong>"Add to Home Screen"</strong> নির্বাচন করুন।
        </div>
    </div>

    <script>
        // Register Service Worker
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => console.log('SW Registered', reg))
                    .catch(err => console.error('SW Error', err));
            });
        }

        // PWA Install Logic
        let deferredPrompt;
        const installModal = document.getElementById('pwa-install-modal');
        const installBtn = document.getElementById('pwa-install-btn');
        const iosInstructions = document.getElementById('ios-instructions');

        // Check if already installed / standalone
        const isStandalone = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true;
        const isInstalledInStorage = localStorage.getItem('pwa_installed') === 'true';
        
        // Detect iOS
        const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;

        if (!isStandalone && !isInstalledInStorage) {
            // Show modal forcing installation
            installModal.classList.add('show');
            
            if (isIOS) {
                // iOS doesn't support the install prompt API
                installBtn.style.display = 'none';
                iosInstructions.style.display = 'block';
            }
        }

        window.addEventListener('beforeinstallprompt', (e) => {
            // Prevent Chrome 67 and earlier from automatically showing the prompt
            e.preventDefault();
            // Stash the event so it can be triggered later.
            deferredPrompt = e;
            
            installBtn.addEventListener('click', async () => {
                // Show the install prompt
                deferredPrompt.prompt();
                // Wait for the user to respond to the prompt
                const { outcome } = await deferredPrompt.userChoice;
                if (outcome === 'accepted') {
                    console.log('User accepted the A2HS prompt');
                    installModal.classList.remove('show');
                    localStorage.setItem('pwa_installed', 'true');
                }
                deferredPrompt = null;
            });
        });
        
        window.addEventListener('appinstalled', () => {
            // Hide modal when installed
            installModal.classList.remove('show');
            localStorage.setItem('pwa_installed', 'true');
            console.log('PWA was installed');
        });
    </script>
</body>
</html>
