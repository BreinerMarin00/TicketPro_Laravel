{{-- Módulo 1: Inicio de sesión --}}
@extends('layouts.app')

@section('title', 'Iniciar sesión')

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('content')
  <div class="auth-wrapper">
    <div class="auth-card">

      {{-- Panel izquierdo azul: marca e ilustración --}}
      <div class="auth-side">
        <div class="auth-side-inner">
          <div class="brand">
            <div class="brand-icon-wrap">
              @include('partials.logo', ['color' => '#ffffff', 'class' => 'brand-icon'])
            </div>
            <h2 class="brand-title">TicketPro</h2>
            <p class="brand-slogan">Tu soporte, nuestra prioridad</p>
          </div>

          {{-- Ilustración de soporte (monitor, agente y chat) --}}
          <div class="side-illustration-wrap">
            <svg class="side-illustration" viewBox="0 0 280 230" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M30 110C20 60 70 25 120 20C180 15 240 40 255 90C270 140 240 200 180 210C120 220 40 210 25 160C20 145 25 125 30 110Z" fill="#00358e" opacity="0.6"/>
              <g transform="translate(42, 65)">
                <path d="M30 100H85" stroke="#93c5fd" stroke-width="4" stroke-linecap="round"/>
                <rect x="52" y="85" width="12" height="16" fill="#93c5fd" rx="2"/>
                <rect x="10" y="5" width="95" height="80" rx="8" fill="#0f172a" stroke="#60a5fa" stroke-width="2.5"/>
                <rect x="16" y="11" width="83" height="68" rx="4" fill="#1e3a8a"/>
                <rect x="25" y="22" width="65" height="15" rx="3" fill="#ffffff"/>
                <rect x="25" y="44" width="48" height="6" rx="3" fill="#60a5fa"/>
                <rect x="25" y="55" width="58" height="6" rx="3" fill="#93c5fd"/>
                <rect x="25" y="66" width="35" height="6" rx="3" fill="#60a5fa"/>
              </g>
              <g transform="translate(160, 60)">
                <rect x="0" y="0" width="38" height="26" rx="6" fill="#2563eb"/>
                <path d="M10 26L6 32L16 26H10Z" fill="#2563eb"/>
                <circle cx="11" cy="13" r="2.2" fill="#ffffff"/>
                <circle cx="19" cy="13" r="2.2" fill="#ffffff"/>
                <circle cx="27" cy="13" r="2.2" fill="#ffffff"/>
              </g>
              <g transform="translate(130, 95)">
                <path d="M15 105C15 88 28 80 50 80C72 80 85 88 85 105V110H15V105Z" fill="#ffffff"/>
                <path d="M42 80C42 86 46 90 50 90C54 90 58 86 58 80H42Z" fill="#2563eb"/>
                <circle cx="50" cy="56" r="21" fill="#f8fafc"/>
                <circle cx="50" cy="54" r="19" fill="#ffedd5"/>
                <path d="M31 52C31 38 40 34 50 34C60 34 69 38 69 52C69 44 63 40 50 40C37 40 31 44 31 52Z" fill="#1e293b"/>
                <circle cx="44" cy="53" r="2" fill="#1e293b"/>
                <circle cx="56" cy="53" r="2" fill="#1e293b"/>
                <path d="M46 61C48 64 52 64 54 61" stroke="#1e293b" stroke-width="1.8" stroke-linecap="round"/>
                <path d="M29 55C29 38 38 30 50 30C62 30 71 38 71 55" stroke="#2563eb" stroke-width="4.5" stroke-linecap="round" fill="none"/>
                <rect x="25" y="47" width="8" height="16" rx="4" fill="#1d4ed8"/>
                <rect x="67" y="47" width="8" height="16" rx="4" fill="#1d4ed8"/>
                <path d="M30 57L44 65" stroke="#1d4ed8" stroke-width="2.5" stroke-linecap="round"/>
                <circle cx="45" cy="65.5" r="2.5" fill="#1d4ed8"/>
              </g>
            </svg>
          </div>
        </div>
      </div>

      {{-- Panel derecho blanco: formulario --}}
      <div class="auth-main">
        <div class="auth-form-inner">
          <div class="auth-header">
            <h1>Iniciar sesión</h1>
            <p>Accede a tu cuenta para gestionar tus tickets</p>
          </div>

          {{-- Errores de validación o credenciales incorrectas enviados por el controlador --}}
          <div id="authAlert" class="alert-box {{ $errors->any() ? 'visible' : '' }}">
            {{ $errors->first() }}
          </div>

          <form id="loginForm" method="POST" action="{{ route('login.store') }}">
            {{-- Protección contra ataques CSRF --}}
            @csrf

            {{-- Campo usuario o correo --}}
            <div class="form-group">
              <div class="input-container">
                <span class="input-icon-left">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                  </svg>
                </span>
                <input type="text" id="usuario" name="usuario" class="input-field" placeholder="Usuario o correo"
                       value="{{ old('usuario') }}" required autofocus autocomplete="username">
              </div>
            </div>

            {{-- Campo contraseña con botón para mostrar u ocultar --}}
            <div class="form-group">
              <div class="input-container has-toggle">
                <span class="input-icon-left">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                  </svg>
                </span>
                <input type="password" id="password" name="password" class="input-field" placeholder="Contraseña"
                       required autocomplete="current-password">
                <button type="button" class="toggle-password-btn" data-target="password" title="Mostrar/Ocultar contraseña" aria-label="Mostrar u ocultar contraseña">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                  </svg>
                </button>
              </div>
            </div>

            {{-- Opciones adicionales --}}
            <div class="form-options">
              <label class="checkbox-label">
                <input type="checkbox" id="recordarme" name="recordarme" value="1" @checked(old('recordarme'))>
                <span>Recordarme</span>
              </label>
              <a href="#" class="link-forgot" data-mensaje="La recuperación de contraseña se implementará en un próximo módulo.">¿Olvidaste tu contraseña?</a>
            </div>

            <button type="submit" class="btn-submit">Iniciar sesión</button>
          </form>

          <div class="auth-footer">
            ¿No tienes una cuenta?
            <a href="#" class="link-pendiente" data-mensaje="El módulo de registro se implementará en un próximo sprint.">Regístrate</a>
          </div>
        </div>
      </div>

    </div>
  </div>
@endsection

@push('scripts')
  <script src="{{ asset('js/auth.js') }}"></script>
@endpush
