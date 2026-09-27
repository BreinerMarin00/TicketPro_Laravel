/**
 * TicketPro - Interacciones del formulario de inicio de sesión.
 *
 * En la versión Laravel el envío y la validación del formulario los hace
 * el servidor (AuthController). Este script solo maneja detalles visuales.
 */
document.addEventListener('DOMContentLoaded', () => {
  // Mostrar / ocultar la contraseña
  document.querySelectorAll('.toggle-password-btn').forEach((btn) => {
    btn.addEventListener('click', () => {
      const input = document.getElementById(btn.dataset.target);
      if (!input) return;

      const oculta = input.type === 'password';
      input.type = oculta ? 'text' : 'password';
      btn.innerHTML = oculta ? iconoOjoTachado() : iconoOjo();
    });
  });

  // Enlaces de funciones que todavía no hacen parte de este módulo
  document.querySelectorAll('[data-mensaje]').forEach((enlace) => {
    enlace.addEventListener('click', (e) => {
      e.preventDefault();
      alert(enlace.dataset.mensaje);
    });
  });

  // Validación rápida en el navegador antes de enviar
  const form = document.getElementById('loginForm');
  const alerta = document.getElementById('authAlert');
  if (form && alerta) {
    form.addEventListener('submit', (e) => {
      const usuario = document.getElementById('usuario').value.trim();
      const password = document.getElementById('password').value.trim();

      if (!usuario || !password) {
        e.preventDefault();
        alerta.textContent = 'Por favor ingresa usuario y contraseña.';
        alerta.classList.add('visible');
      }
    });
  }
});

/** Icono SVG de ojo abierto. */
function iconoOjo() {
  return `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
    <circle cx="12" cy="12" r="3"></circle>
  </svg>`;
}

/** Icono SVG de ojo tachado. */
function iconoOjoTachado() {
  return `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
    <line x1="1" y1="1" x2="23" y2="23"></line>
  </svg>`;
}
