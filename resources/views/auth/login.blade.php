@extends('layouts.app')

@section('title', 'Masuk Administrator - Laporan Rumah Tahfidz')

@section('content')
<div class="login-page-container">
    <div class="ui-card gold-edge" style="box-shadow: 0 12px 32px rgba(6, 84, 67, 0.12); border-width: 2px;">
        <div style="text-align: center; margin-bottom: 22px;">
            <div style="width: 58px; height: 58px; margin: 0 auto 14px; background: linear-gradient(135deg, var(--soft-green) 0%, #dcf0e5 100%); color: var(--pri-green); border-radius: 16px; display: grid; place-items: center; border: 2px solid var(--gold-border); box-shadow: 0 4px 12px rgba(6, 84, 67, 0.15);">
                <svg class="svg-icon-lg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
            </div>
            <h2 style="font-family: 'Fraunces', Georgia, serif; font-size: 24px; margin: 0 0 6px; color: var(--pri-dark);">Masuk Administrator</h2>
            <p class="card-desc" style="margin: 0; font-size: 14px;">Akses khusus pengelolaan data master dan rekapitulasi setoran tahfidz.</p>
        </div>

        @if ($errors->has('auth'))
            <div class="alert-box alert-danger" style="margin-bottom: 18px;" role="alert">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="12"/>
                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    <span>{{ $errors->first('auth') }}</span>
                </div>
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST" id="form-login-admin">
            @csrf

            <div class="form-group">
                <label for="email" class="form-label">Alamat Email Administrator</label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       class="form-input" 
                       placeholder="admin@man1probolinggo.sch.id" 
                       value="{{ old('email') }}"
                       autocomplete="username"
                       required
                       autofocus>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Kata Sandi</label>
                <div class="input-password-wrapper">
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="form-input" 
                           placeholder="Masukkan kata sandi administrator"
                           autocomplete="current-password"
                           required>
                    <button type="button" 
                            class="btn-toggle-password" 
                            data-target="password" 
                            aria-label="Tampilkan atau sembunyikan kata sandi">
                        <svg class="svg-icon-sm icon-eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-primary btn-full" id="btn-submit-login" style="margin-top: 18px;">
                <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                    <polyline points="10 17 15 12 10 7"/>
                    <line x1="15" y1="12" x2="3" y2="12"/>
                </svg>
                <span>Masuk ke Panel Administrator</span>
            </button>
        </form>

        <div style="text-align: center; margin-top: 24px; padding-top: 18px; border-top: 1.5px solid var(--border-line);">
            <a href="{{ route('petugas.index') }}" style="color: var(--pri-green); text-decoration: none; font-size: 13.5px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"/>
                    <polyline points="12 19 5 12 12 5"/>
                </svg>
                <span>Kembali ke Halaman Pendamping (Setoran)</span>
            </a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle password visibility
        document.querySelectorAll('.btn-toggle-password').forEach(btn => {
            btn.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                if (!input) return;

                if (input.type === 'password') {
                    input.type = 'text';
                    this.innerHTML = `
                        <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                            <line x1="1" y1="1" x2="23" y2="23"/>
                        </svg>
                    `;
                } else {
                    input.type = 'password';
                    this.innerHTML = `
                        <svg class="svg-icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    `;
                }
            });
        });
    });
</script>
@endsection

