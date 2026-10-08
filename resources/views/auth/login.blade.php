@extends('layouts.app')

@section('content')
<style>
/* Reset App Layout for Login */
.app-layout {
    display: block;
    height: auto;
}
.main-wrapper {
    overflow: visible;
}
.main-content {
    padding: 0;
}

/* Full Screen Background Image */
.login-page {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    background-color: #0f172a;
    background-image: url('{{ asset('assets/images/login-bg.png') }}');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    position: relative;
    padding: 2rem;
    box-sizing: border-box;
}

/* Dark overlay to ensure the login card stands out beautifully */
.login-page::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(15,23,42,0.7) 0%, rgba(30,41,59,0.4) 100%);
    z-index: 1;
}

/* Central Card - Premium Glassmorphism */
.login-container {
    width: 100%;
    max-width: 440px;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(255, 255, 255, 0.4);
    border-radius: 16px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    padding: 3rem;
    position: relative;
    z-index: 10;
    animation: fadeIn 0.8s ease forwards;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Branding Header */
.login-header {
    text-align: center;
    margin-bottom: 2.5rem;
}
.login-logo {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 54px;
    height: 54px;
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: #ffffff;
    border-radius: 14px;
    font-size: 1.75rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 10px 15px -3px rgba(59,130,246,0.3);
}
.login-header h1 {
    font-size: 1.65rem;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 0.5rem 0;
    letter-spacing: -0.5px;
}
.login-header p {
    font-size: 0.95rem;
    color: #64748b;
    margin: 0;
}

/* Form Styles */
.form-group {
    margin-bottom: 1.25rem;
}
.form-label {
    display: block;
    font-size: 0.85rem;
    font-weight: 600;
    color: #334155;
    margin-bottom: 0.5rem;
}
.form-control {
    width: 100%;
    padding: 0.85rem 1rem;
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    font-size: 0.95rem;
    color: #0f172a;
    transition: all 0.2s ease;
    box-sizing: border-box;
    font-family: inherit;
}
.form-control:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59,130,246,0.15);
}
.form-control::placeholder {
    color: #94a3b8;
}

/* Submit Button */
.btn-submit {
    width: 100%;
    padding: 0.9rem 1rem;
    margin-top: 1rem;
    background: #0f172a;
    color: #ffffff;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 0.5rem;
}
.btn-submit:hover {
    background: #3b82f6;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(59,130,246,0.3);
}

/* Error Alert */
.alert-error {
    background: #fef2f2;
    border-left: 4px solid #ef4444;
    color: #b91c1c;
    padding: 0.85rem 1rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    font-size: 0.85rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Divider */
.divider {
    display: flex;
    align-items: center;
    text-align: center;
    margin: 2rem 0 1.5rem 0;
    color: #94a3b8;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
}
.divider::before, .divider::after {
    content: '';
    flex: 1;
    border-bottom: 1px solid #e2e8f0;
}
.divider::not(:empty)::before { margin-right: 1em; }
.divider::not(:empty)::after { margin-left: 1em; }

/* Demo Roles Grid */
.demo-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.5rem;
}
.demo-grid .full-width {
    grid-column: span 2;
}
.btn-demo {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    color: #475569;
    padding: 0.6rem;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.4rem;
}
.btn-demo i { opacity: 0.8; color: #3b82f6; }
.btn-demo:hover {
    background: #eff6ff;
    border-color: #bfdbfe;
    color: #1d4ed8;
}

/* Footer Info */
.login-footer {
    margin-top: 2.5rem;
    text-align: center;
    color: rgba(255,255,255,0.7);
    font-size: 0.85rem;
    position: relative;
    z-index: 10;
    text-shadow: 0 1px 2px rgba(0,0,0,0.5);
}

/* Dark Mode Overrides */
[data-theme="dark"] .login-container {
    background: rgba(15, 23, 42, 0.85);
    border-color: rgba(255, 255, 255, 0.1);
}
[data-theme="dark"] .login-header h1 { color: #f8fafc; }
[data-theme="dark"] .login-header p { color: #94a3b8; }
[data-theme="dark"] .form-label { color: #cbd5e1; }
[data-theme="dark"] .form-control {
    background: rgba(15,23,42,0.6);
    border-color: #334155;
    color: #f8fafc;
}
[data-theme="dark"] .form-control:focus {
    border-color: #3b82f6;
    background: rgba(15,23,42,0.9);
}
[data-theme="dark"] .btn-submit {
    background: #3b82f6;
    color: #ffffff;
}
[data-theme="dark"] .btn-submit:hover {
    background: #60a5fa;
}
[data-theme="dark"] .divider::before, [data-theme="dark"] .divider::after { border-color: #334155; }
[data-theme="dark"] .btn-demo {
    background: rgba(30,41,59,0.5);
    border-color: #334155;
    color: #cbd5e1;
}
[data-theme="dark"] .btn-demo:hover {
    background: #334155;
    border-color: #475569;
    color: #ffffff;
}

</style>

<div class="login-page">
    
    <div class="login-container">
        
        <div class="login-header">
            <div class="login-logo">
                <i class="fa-solid fa-industry"></i>
            </div>
            <h1>PT. Sinar Alfa Omega</h1>
            <p>Smart Production Intelligence</p>
        </div>

        @if ($errors->any())
            <div class="alert-error">
                <i class="fa-solid fa-circle-exclamation"></i> {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            
            <div class="form-group">
                <label class="form-label" for="email">Alamat Email</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="nama@sinaralfaomega.com" required value="handoko.manajer@sinaralfaomega.com">
            </div>
            
            <div class="form-group">
                <label class="form-label" for="password">Kata Sandi</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required value="password">
            </div>

            <button type="submit" class="btn-submit">
                Masuk Sistem <i class="fa-solid fa-arrow-right-to-bracket"></i>
            </button>
        </form>

        <div class="divider">Akses Demo Cepat</div>
        
        <div class="demo-grid">
            <button type="button" class="btn-demo" onclick="fillLogin('operator.lini@sinaralfaomega.com')">
                <i class="fa-solid fa-helmet-safety"></i> Operator
            </button>
            <button type="button" class="btn-demo" onclick="fillLogin('analis.qc@sinaralfaomega.com')">
                <i class="fa-solid fa-flask"></i> Analis QC
            </button>
            <button type="button" class="btn-demo" onclick="fillLogin('budi.spv@sinaralfaomega.com')">
                <i class="fa-solid fa-user-tie"></i> Supervisor
            </button>
            <button type="button" class="btn-demo" onclick="fillLogin('handoko.manajer@sinaralfaomega.com')">
                <i class="fa-solid fa-briefcase"></i> Manajer
            </button>
            <button type="button" class="btn-demo full-width" onclick="fillLogin('admin@sinaralfaomega.com')">
                <i class="fa-solid fa-shield-halved"></i> Administrator
            </button>
        </div>

    </div>

    <div class="login-footer">
        &copy; {{ date('Y') }} Sistem Informasi Manajemen Produksi Maklon Kosmetik.
    </div>

</div>

<script>
function fillLogin(email) {
    const emailInput = document.getElementById('email');
    const pwdInput = document.getElementById('password');
    
    emailInput.value = email;
    pwdInput.value = 'password';
    
    // Quick flash effect on input to show it was auto-filled
    emailInput.style.transform = 'scale(0.98)';
    setTimeout(() => { emailInput.style.transform = 'scale(1)'; }, 150);
    
    document.querySelector('.btn-submit').focus();
}
</script>
@endsection

