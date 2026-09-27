/**
 * TicketPro - Interacciones de la pantalla principal.
 *
 * Los datos (tickets, contadores y filtros) los genera Laravel en el
 * servidor con Blade. Este script solo controla el modal y las confirmaciones.
 */
document.addEventListener('DOMContentLoaded', () => {
  const modal = document.getElementById('ticketModal');
  const form = document.getElementById('newTicketForm');

  // Abrir el modal de nuevo ticket
  const abrirModal = () => {
    modal.classList.add('open');
    document.getElementById('ticketTitle').focus();
  };

  // Cerrar el modal y limpiar el formulario
  const cerrarModal = () => {
    modal.classList.remove('open');
    form.reset();
  };

  document.getElementById('btnOpenModal').addEventListener('click', abrirModal);
  document.getElementById('btnCloseModal').addEventListener('click', cerrarModal);
  document.getElementById('btnCancelModal').addEventListener('click', cerrarModal);

  // Cerrar al hacer clic fuera del contenido o con la tecla Escape
  modal.addEventListener('click', (e) => {
    if (e.target === modal) cerrarModal();
  });
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') cerrarModal();
  });

  // Pedir confirmación antes de eliminar un ticket
  document.querySelectorAll('.form-eliminar').forEach((formEliminar) => {
    formEliminar.addEventListener('submit', (e) => {
      if (!confirm('¿Deseas eliminar este ticket?')) e.preventDefault();
    });
  });
});
