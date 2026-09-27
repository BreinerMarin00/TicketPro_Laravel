{{--
  Icono de ticket de TicketPro (componente reutilizable).
  Parámetros: $color (color del trazo) y $class (clase CSS del SVG).
  Uso: @include('partials.logo', ['color' => '#ffffff', 'class' => 'brand-icon'])
--}}
<svg class="{{ $class }}" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
  <rect x="7" y="13" width="34" height="22" rx="4" transform="rotate(-15 24 24)" stroke="{{ $color }}" stroke-width="{{ $stroke ?? 3.5 }}" fill="none"/>
  <path d="M12 18L18 34" stroke="{{ $color }}" stroke-width="3" stroke-linecap="round" stroke-dasharray="2 3"/>
  <circle cx="34" cy="21" r="2.5" fill="{{ $color }}"/>
</svg>
