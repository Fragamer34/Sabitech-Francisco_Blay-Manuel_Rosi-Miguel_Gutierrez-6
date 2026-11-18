/* ---- DATOS DE EJEMPLO ----
   TODO: sustitúyelos por las charlas de tu base de datos, por ejemplo con
   useEffect + fetch (o la función de src/api/charlaApi.js) y useState([]).
   Lo usan Inicio.jsx (listado de charlas) y Reserva.jsx (calendario). */
export const CHARLAS = [
  { idCharla: 1, nombreCharla: 'Introducción a Laravel', descripcionCharla: 'Primeros pasos con rutas, controladores y Blade.',
    fechaCharla: '2026-11-18', horaInicioCharla: '10:00:00', horaFinCharla: '11:30:00', estadoCharla: 'PROGRAMADA', idSala: 1 },
  { idCharla: 2, nombreCharla: 'MySQL avanzado', descripcionCharla: 'Índices, joins y optimización de consultas.',
    fechaCharla: '2026-11-18', horaInicioCharla: '12:00:00', horaFinCharla: '13:00:00', estadoCharla: 'ACTUALIZADA', idSala: 2 },
  { idCharla: 3, nombreCharla: 'Git en equipo', descripcionCharla: 'Ramas, pull requests y resolución de conflictos.',
    fechaCharla: '2026-11-25', horaInicioCharla: '09:30:00', horaFinCharla: '10:30:00', estadoCharla: 'CANCELADA', idSala: 1 },
  { idCharla: 4, nombreCharla: 'Seguridad web', descripcionCharla: 'XSS, CSRF e inyección SQL.',
    fechaCharla: '2026-12-02', horaInicioCharla: '16:00:00', horaFinCharla: '17:30:00', estadoCharla: 'PROGRAMADA', idSala: 3 },
];
