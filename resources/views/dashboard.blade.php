{{-- Módulo 2: Pantalla principal (Panel de Tickets) --}}
@extends('layouts.app')

@section('title', 'Panel de Inicio')

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endpush

@section('content')
  {{-- Barra superior --}}
  <header class="navbar">
    <a href="{{ route('dashboard') }}" class="nav-brand">
      @include('partials.logo', ['color' => '#0047ba', 'class' => 'nav-brand-icon', 'stroke' => 4])
      <span class="nav-brand-title">TicketPro</span>
    </a>

    <div class="nav-user">
      <span class="user-greeting">Hola, <span class="user-name">{{ $usuario->username }}</span></span>
      {{-- El cierre de sesión se envía por POST para que esté protegido con CSRF --}}
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn-logout">Cerrar sesión</button>
      </form>
    </div>
  </header>

  <main class="main-container">
    {{-- Título y botón principal --}}
    <div class="dashboard-header">
      <div class="dashboard-title">
        <h2>Panel de Tickets</h2>
        <p>Gestiona y da seguimiento a tus solicitudes de soporte</p>
      </div>
      <button class="btn-new-ticket" id="btnOpenModal" type="button">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <line x1="12" y1="5" x2="12" y2="19"></line>
          <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
        Nuevo Ticket
      </button>
    </div>

    {{-- Mensaje de confirmación (flash) --}}
    @if (session('exito'))
      <div class="alert-success">{{ session('exito') }}</div>
    @endif

    {{-- Tarjetas de resumen --}}
    <section class="stats-grid">
      <div class="stat-card">
        <div class="stat-info">
          <span>Total de Tickets</span>
          <h3 id="countTotal">{{ $contadores['total'] }}</h3>
        </div>
        <div class="stat-icon stat-total">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
          </svg>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-info">
          <span>Tickets Abiertos</span>
          <h3 id="countOpen">{{ $contadores['abiertos'] }}</h3>
        </div>
        <div class="stat-icon stat-open">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"></circle>
            <polyline points="12 6 12 12 16 14"></polyline>
          </svg>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-info">
          <span>Tickets Resueltos</span>
          <h3 id="countClosed">{{ $contadores['resueltos'] }}</h3>
        </div>
        <div class="stat-icon stat-closed">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
            <polyline points="22 4 12 14.01 9 11.01"></polyline>
          </svg>
        </div>
      </div>
    </section>

    {{-- Lista de tickets --}}
    <section class="tickets-card">
      <div class="tickets-toolbar">
        <div class="filter-group">
          @foreach (['todos' => 'Todos', 'abiertos' => 'Abiertos / En proceso', 'resueltos' => 'Resueltos'] as $clave => $texto)
            <a href="{{ route('dashboard', ['filtro' => $clave]) }}"
               class="filter-btn {{ $filtro === $clave ? 'active' : '' }}">{{ $texto }}</a>
          @endforeach
        </div>
      </div>

      @if ($tickets->isEmpty())
        {{-- Estado vacío --}}
        <div class="empty-state">
          <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="8" y1="12" x2="16" y2="12"></line>
          </svg>
          <p>No se encontraron tickets en esta categoría.</p>
        </div>
      @else
        <div class="table-wrapper">
          <table class="ticket-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Asunto</th>
                <th>Prioridad</th>
                <th>Estado</th>
                <th>Fecha</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($tickets as $ticket)
                <tr>
                  <td class="ticket-id">TK-{{ $ticket->id }}</td>
                  <td>
                    {{-- Blade escapa el texto con {{ }} para prevenir ataques XSS --}}
                    <div class="ticket-title">{{ $ticket->titulo }}</div>
                    <div class="ticket-desc">{{ $ticket->descripcion }}</div>
                  </td>
                  <td><span class="badge {{ $ticket->clasePrioridad() }}">{{ $ticket->prioridad }}</span></td>
                  <td><span class="badge {{ $ticket->claseEstado() }}">{{ $ticket->estado }}</span></td>
                  <td>{{ $ticket->created_at->format('d/m/Y') }}</td>
                  <td>
                    <div class="table-actions">
                      <form method="POST" action="{{ route('tickets.estado', $ticket) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn-action" title="Cambiar estado">
                          {{ $ticket->estado === 'Resuelto' ? 'Reabrir' : 'Resolver' }}
                        </button>
                      </form>
                      <form method="POST" action="{{ route('tickets.destroy', $ticket) }}" class="form-eliminar">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-action btn-delete" title="Eliminar ticket">Eliminar</button>
                      </form>
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </section>
  </main>

  {{-- Modal para crear un ticket. Se abre solo si hubo errores de validación. --}}
  <div class="modal-overlay {{ $errors->any() ? 'open' : '' }}" id="ticketModal">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Crear nuevo ticket</h3>
        <button type="button" class="btn-close-modal" id="btnCloseModal">&times;</button>
      </div>
      <form id="newTicketForm" method="POST" action="{{ route('tickets.store') }}">
        @csrf
        <div class="form-group-simple">
          <label for="ticketTitle">Título del problema</label>
          <input type="text" id="ticketTitle" name="titulo" class="form-control-simple"
                 placeholder="Ej: No funciona el acceso al correo" value="{{ old('titulo') }}" required maxlength="150">
          @error('titulo')
            <span class="form-error">{{ $message }}</span>
          @enderror
        </div>
        <div class="form-group-simple">
          <label for="ticketPriority">Prioridad</label>
          <select id="ticketPriority" name="prioridad" class="form-control-simple">
            @foreach (\App\Models\Ticket::PRIORIDADES as $prioridad)
              <option value="{{ $prioridad }}" @selected(old('prioridad', 'Media') === $prioridad)>{{ $prioridad }}</option>
            @endforeach
          </select>
          @error('prioridad')
            <span class="form-error">{{ $message }}</span>
          @enderror
        </div>
        <div class="form-group-simple">
          <label for="ticketDesc">Descripción detallada</label>
          <textarea id="ticketDesc" name="descripcion" class="form-control-simple"
                    placeholder="Explica brevemente qué ocurre...">{{ old('descripcion') }}</textarea>
        </div>
        <div class="modal-actions">
          <button type="button" class="btn-secondary" id="btnCancelModal">Cancelar</button>
          <button type="submit" class="btn-new-ticket">Guardar Ticket</button>
        </div>
      </form>
    </div>
  </div>

  <footer class="footer-simple">
    TicketPro &copy; {{ date('Y') }} - Sistema de gestión de tickets
  </footer>
@endsection

@push('scripts')
  <script src="{{ asset('js/dashboard.js') }}"></script>
@endpush
