@extends('layouts.login')

@section('title', 'AIP | Iniciar sesión')

@section('content')
  <div class="login-page">
    <section class="login-shell" aria-label="Inicio de sesión AIP">
      <div class="login-brand-panel">
        <a href="{{ route('login') }}" class="login-logo" aria-label="AIP">
          <img src="{{ asset('assets/img/logoaip.svg') }}" alt="AIP">
        </a>

        <div class="login-brand-copy">
          <span class="login-eyebrow">Control de acceso institucional</span>
          <h1>Automatización de ingreso peatonal</h1>
        </div>

        <div class="login-brand-metrics" aria-label="Funciones principales">
          <div>
            <i class="bi bi-qr-code-scan" aria-hidden="true"></i>
            <span>Validación QR</span>
          </div>
          <div>
            <i class="bi bi-shield-check" aria-hidden="true"></i>
            <span>Acceso seguro</span>
          </div>
          <div>
            <i class="bi bi-clock-history" aria-hidden="true"></i>
            <span>Historial activo</span>
          </div>
        </div>
      </div>

      <div class="login-card-wrap">
        <div class="login-card">
          <div class="login-card-header">
            <span class="login-card-icon">
              <i class="bi bi-person-lock" aria-hidden="true"></i>
            </span>
            <div>
              <h2>Bienvenido</h2>
              <p>Ingresa con tu correo y contraseña.</p>
            </div>
          </div>

          @if ($errors->any())
            <div class="login-alert" role="alert">
              <i class="bi bi-exclamation-triangle" aria-hidden="true"></i>
              <span>{{ $errors->first() }}</span>
            </div>
          @endif

          <form class="login-form needs-validation" method="POST" action="{{ route('login.attempt') }}" novalidate>
            @csrf

            <div class="login-field">
              <label for="email">Correo electrónico</label>
              <div class="login-input-group">
                <i class="bi bi-envelope" aria-hidden="true"></i>
                <input
                  type="email"
                  name="email"
                  class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                  id="email"
                  value="{{ old('email') }}"
                  placeholder="usuario@correo.com"
                  autocomplete="email"
                  required
                  autofocus
                >
              </div>
              <div class="invalid-feedback">Por favor ingrese un correo válido.</div>
            </div>

            <div class="login-field">
              <label for="password">Contraseña</label>
              <div class="login-input-group">
                <i class="bi bi-key" aria-hidden="true"></i>
                <input
                  type="password"
                  name="password"
                  class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                  id="password"
                  placeholder="Ingresa tu contraseña"
                  autocomplete="current-password"
                  required
                >
                <button class="login-password-toggle" type="button" id="togglePassword" aria-label="Mostrar contraseña">
                  <i class="bi bi-eye" aria-hidden="true"></i>
                </button>
              </div>
              <div class="invalid-feedback">Por favor ingrese la contraseña.</div>
            </div>

            <div class="login-options">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="rememberMe">
                <label class="form-check-label" for="rememberMe">Recordarme</label>
              </div>
            </div>

            <button class="login-submit" type="submit">
              <span>Ingresar</span>
              <i class="bi bi-arrow-right" aria-hidden="true"></i>
            </button>
          </form>

      
        </div>
      </div>
    </section>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const toggleButton = document.getElementById('togglePassword');
      const passwordInput = document.getElementById('password');

      if (toggleButton && passwordInput) {
        toggleButton.addEventListener('click', function () {
          const isPassword = passwordInput.type === 'password';
          passwordInput.type = isPassword ? 'text' : 'password';
          toggleButton.setAttribute('aria-label', isPassword ? 'Ocultar contraseña' : 'Mostrar contraseña');
          toggleButton.querySelector('i').className = isPassword ? 'bi bi-eye-slash' : 'bi bi-eye';
        });
      }
    });
  </script>

  @if(session('success'))
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Bienvenido',
        text: "{{ session('success') }}",
        showConfirmButton: false,
        timer: 2000
      });
    </script>
  @endif

  @if($errors->any())
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
          icon: 'error',
          title: 'Error de ingreso',
          text: "{{ $errors->first() }}",
          confirmButtonColor: '#008f45',
          confirmButtonText: 'Reintentar'
        });
      });
    </script>
  @endif
@endsection
