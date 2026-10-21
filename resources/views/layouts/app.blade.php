<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Laporan Rumah Tahfidz') - MAN 1 Probolinggo</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:ital,wght@0,400;0,700;1,400&family=Fraunces:ital,opsz,wght@0,9..144,600;0,9..144,700;1,9..144,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    @php
        $appLogo = \App\Models\Setting::get('app_logo');
        $isAdminRoute = request()->routeIs('admin.*');
    @endphp

    <header class="app-header">
        <div class="header-container">
            <div class="brand-wrapper">
                @if ($appLogo && file_exists(public_path($appLogo)))
                    <a href="{{ route('petugas.index') }}" style="display: flex; align-items: center; text-decoration: none; color: inherit;">
                        <img src="{{ asset($appLogo) }}" alt="Logo Laporan Rumah Tahfidz" class="brand-logo-img">
                    </a>
                @else
                    <a href="{{ route('petugas.index') }}" class="brand-icon-box" aria-hidden="true" style="text-decoration: none;">
                        <svg class="svg-icon-lg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z" fill="rgba(255,255,255,0.08)" stroke="#dfc888"/>
                            <path d="M6 6h10" stroke="#dfc888"/>
                            <path d="M6 10h10" stroke="#dfc888"/>
                            <path d="M6 14h7" stroke="#dfc888"/>
                        </svg>
                    </a>
                @endif
                <div class="brand-info">
                    <small>
                        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M12 2L14.5 9.5L22 12L14.5 14.5L12 22L9.5 14.5L2 12L9.5 9.5L12 2Z"/>
                        </svg>
                        MAN 1 PROBOLINGGO &middot; RUMAH TAHFIDZ
                    </small>
                    <h1><a href="{{ route('petugas.index') }}" style="color: inherit; text-decoration: none;">Laporan Rumah Tahfidz</a></h1>
                </div>
            </div>

            <div class="header-status" id="header-nav-menu">
                <div class="header-nav-actions">
                    <!-- Tampilan Tanggal, Jam, dan Hari Live di Header -->
                    <div class="admin-live-datetime" id="admin-live-datetime" title="Tanggal, Hari dan Jam Nyata Sistem">
                        <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                        <span id="live-header-day">{{ now()->locale('id')->isoFormat('dddd') }}</span>,
                        <span id="live-header-date">{{ now()->locale('id')->isoFormat('D MMMM Y') }}</span>
                        <span class="live-divider">&bull;</span>
                        <span class="live-time" id="live-header-time">{{ now()->format('H:i:s') }} WIB</span>
                    </div>

                    @if (request()->routeIs('login'))
                        <a href="{{ route('petugas.index') }}" class="header-action-btn header-btn-ghost">
                            <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="19" y1="12" x2="5" y2="12"/>
                                <polyline points="12 19 5 12 12 5"/>
                            </svg>
                            <span>Halaman Pendamping</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </header>

    <main class="main-content" id="main-content">
        @if (session('success'))
            <div class="alert-box alert-success" role="alert">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <svg class="svg-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                        <polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" style="background:none;border:none;cursor:pointer;color:inherit;font-weight:700;font-size:18px;line-height:1;" aria-label="Tutup pesan">&times;</button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert-box alert-danger" role="alert">
                <div style="display: flex; align-items: flex-start; gap: 10px;">
                    <svg class="svg-icon" style="margin-top: 2px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="12"/>
                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    <ul style="margin: 0; padding-left: 16px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" onclick="this.parentElement.remove()" style="background:none;border:none;cursor:pointer;color:inherit;font-weight:700;font-size:18px;line-height:1;" aria-label="Tutup pesan">&times;</button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Modal Konfirmasi Umum (Accessible & Escape-closable) -->
    <div class="modal-overlay" id="confirm-modal" role="dialog" aria-modal="true" aria-labelledby="confirm-modal-title">
        <div class="modal-container">
            <h2 class="modal-title" id="confirm-modal-title">Konfirmasi Tindakan</h2>
            <p id="confirm-modal-message" class="card-desc">Apakah Anda yakin ingin melanjutkan tindakan ini?</p>
            
            <form id="confirm-modal-form" method="POST" action="">
                @csrf
                <input type="hidden" name="_method" id="confirm-modal-method" value="POST">
                
                <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 22px;">
                    <button type="button" class="btn-secondary" id="btn-confirm-cancel">Batal</button>
                    <button type="submit" class="btn-danger" id="btn-confirm-ok">Ya, Lanjutkan</button>
                </div>
            </form>
        </div>
    </div>

    <div id="global-toast" role="status" aria-live="polite"></div>

    <script>
        // Global Toast Notification Helper
        window.showToast = function(message) {
            const toast = document.getElementById('global-toast');
            if (!toast) return;
            toast.textContent = message;
            toast.style.display = 'block';
            setTimeout(() => {
                toast.style.display = 'none';
            }, 2600);
        };

        // Generic Accessible Confirmation Modal
        window.openConfirmModal = function(options) {
            const modal = document.getElementById('confirm-modal');
            const titleEl = document.getElementById('confirm-modal-title');
            const msgEl = document.getElementById('confirm-modal-message');
            const formEl = document.getElementById('confirm-modal-form');
            const methodEl = document.getElementById('confirm-modal-method');
            const okBtn = document.getElementById('btn-confirm-ok');

            if (!modal) return;

            titleEl.textContent = options.title || 'Konfirmasi';
            msgEl.textContent = options.message || 'Apakah Anda yakin ingin melanjutkan?';
            formEl.action = options.actionUrl;
            methodEl.value = options.method || 'POST';
            okBtn.textContent = options.confirmButtonText || 'Ya, Lanjutkan';

            modal.classList.add('active');
            okBtn.focus();
        };

        const closeConfirmBtn = document.getElementById('btn-confirm-cancel');
        if (closeConfirmBtn) {
            closeConfirmBtn.addEventListener('click', () => {
                document.getElementById('confirm-modal').classList.remove('active');
            });
        }

        // Mobile Hamburger Menu for Header
        const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
        const headerNavMenu = document.getElementById('header-nav-menu');

        if (mobileMenuToggle && headerNavMenu) {
            mobileMenuToggle.addEventListener('click', function(e) {
                e.stopPropagation();
                const isOpen = headerNavMenu.classList.toggle('is-open');
                mobileMenuToggle.classList.toggle('is-active', isOpen);
                mobileMenuToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            });

            document.addEventListener('click', function(e) {
                if (!headerNavMenu.contains(e.target) && !mobileMenuToggle.contains(e.target)) {
                    if (headerNavMenu.classList.contains('is-open')) {
                        headerNavMenu.classList.remove('is-open');
                        mobileMenuToggle.classList.remove('is-active');
                        mobileMenuToggle.setAttribute('aria-expanded', 'false');
                    }
                }
            });
        }

        // Escape Key Global Listener for Modal & Menu Dismissal (AntiSlop R-32)
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                if (mobileMenuToggle && headerNavMenu && headerNavMenu.classList.contains('is-open')) {
                    headerNavMenu.classList.remove('is-open');
                    mobileMenuToggle.classList.remove('is-active');
                    mobileMenuToggle.setAttribute('aria-expanded', 'false');
                    mobileMenuToggle.focus();
                }
                const confirmModal = document.getElementById('confirm-modal');
                if (confirmModal && confirmModal.classList.contains('active')) {
                    confirmModal.classList.remove('active');
                }
                const customEditModal = document.getElementById('edit-modal');
                if (customEditModal && customEditModal.classList.contains('active')) {
                    customEditModal.classList.remove('active');
                }
                const customEditPetugasModal = document.getElementById('edit-petugas-modal');
                if (customEditPetugasModal && customEditPetugasModal.classList.contains('active')) {
                    customEditPetugasModal.classList.remove('active');
                }
            }
        });
    </script>
    @yield('scripts')
</body>
</html>
