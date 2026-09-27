{{-- Plantilla base de TicketPro: todas las vistas la extienden con @extends('layouts.app') --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  {{-- Token CSRF disponible para JavaScript si se necesita --}}
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>TicketPro - @yield('title')</title>
  @stack('styles')
</head>
<body>
  @yield('content')

  @stack('scripts')
</body>
</html>
