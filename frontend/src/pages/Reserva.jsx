import { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';

/* ---- DATOS DE EJEMPLO ----
   TODO: sustitúyelos por las charlas de tu base de datos, por ejemplo con
   useEffect + fetch (o la función de src/api/charlaApi.js) y useState([]). */
const CHARLAS = [
  { idCharla: 1, nombreCharla: 'Introducción a Laravel', descripcionCharla: 'Primeros pasos con rutas, controladores y Blade.',
    fechaCharla: '2026-11-18', horaInicioCharla: '10:00:00', horaFinCharla: '11:30:00', estadoCharla: 'PROGRAMADA', idSala: 1 },
  { idCharla: 2, nombreCharla: 'MySQL avanzado', descripcionCharla: 'Índices, joins y optimización de consultas.',
    fechaCharla: '2026-11-18', horaInicioCharla: '12:00:00', horaFinCharla: '13:00:00', estadoCharla: 'ACTUALIZADA', idSala: 2 },
  { idCharla: 3, nombreCharla: 'Git en equipo', descripcionCharla: 'Ramas, pull requests y resolución de conflictos.',
    fechaCharla: '2026-11-25', horaInicioCharla: '09:30:00', horaFinCharla: '10:30:00', estadoCharla: 'CANCELADA', idSala: 1 },
  { idCharla: 4, nombreCharla: 'Seguridad web', descripcionCharla: 'XSS, CSRF e inyección SQL.',
    fechaCharla: '2026-12-02', horaInicioCharla: '16:00:00', horaFinCharla: '17:30:00', estadoCharla: 'PROGRAMADA', idSala: 3 },
];

const DIAS_SEMANA = ['L', 'M', 'X', 'J', 'V', 'S', 'D'];
const hhmm = (t) => t.slice(0, 5);
const pad = (n) => String(n).padStart(2, '0');

export default function Reserva() {
  const navigate = useNavigate();

  const [charlas] = useState(CHARLAS);
  const [vista, setVista] = useState(() => new Date(new Date().getFullYear(), new Date().getMonth(), 1));
  const [seleccionada, setSeleccionada] = useState(null); // objeto charla o null
  const [mensaje, setMensaje] = useState('');

  // ¿Hay un usuario con sesión iniciada? (si no, es invitado)
  const [logueado, setLogueado] = useState(() => Boolean(localStorage.getItem('token')));

  // Reservas del usuario: { idCharla: estado de asistencia }
  // Estados: 'ASISTENCIA NO REVISADA' (recién reservada) | 'HA ASISTIDO' (confirmada)
  // TODO: al cargar la página, rellénalo con las reservas del usuario que devuelva el backend.
  const [reservadas, setReservadas] = useState({});

  const anio = vista.getFullYear();
  const mes = vista.getMonth();
  const huecos = (new Date(anio, mes, 1).getDay() + 6) % 7; // lunes = 0
  const totalDias = new Date(anio, mes + 1, 0).getDate();
  const hoy = new Date();
  const hoyStr = `${hoy.getFullYear()}-${pad(hoy.getMonth() + 1)}-${pad(hoy.getDate())}`;

  const cambiarMes = (delta) => setVista(new Date(anio, mes + delta, 1));

  const charlasDelDia = (fecha) =>
    charlas
      .filter((c) => c.fechaCharla === fecha)
      .sort((a, b) => a.horaInicioCharla.localeCompare(b.horaInicioCharla));

  // Estado de la charla seleccionada para este usuario (undefined = no reservada)
  const estadoReserva = seleccionada ? reservadas[seleccionada.idCharla] : undefined;

  const reservar = (e) => {
    e.preventDefault();
    if (!logueado || !seleccionada || estadoReserva) return;

    // TODO: aquí va el POST a tu backend con { idCharla: seleccionada.idCharla }
    setReservadas({ ...reservadas, [seleccionada.idCharla]: 'ASISTENCIA NO REVISADA' });
    setMensaje('');
  };

  const confirmarAsistencia = () => {
    // TODO: aquí va la llamada a tu backend para marcar la asistencia como 'HA ASISTIDO'
    setReservadas({ ...reservadas, [seleccionada.idCharla]: 'HA ASISTIDO' });
    setMensaje('');
  };

  const salir = () => {
    localStorage.removeItem('token'); // TODO: o lo que uses para guardar la sesión
    setLogueado(false);
    setReservadas({});
    navigate('/');
  };

  return (
    <>
      {/* ---------- Barra superior ---------- */}
      <div className="barra">
        <Link to="/">← Inicio</Link>
        {logueado ? (
          <button type="button" className="btn btn-secundario" onClick={salir}>Cerrar sesión</button>
        ) : (
          <Link className="btn" to="/login">Iniciar sesión</Link>
        )}
      </div>

      <div className="wrap">
        {/* ---------- Calendario ---------- */}
        <section className="card">
          <div className="cal-head">
            <button type="button" onClick={() => cambiarMes(-1)} aria-label="Mes anterior">‹</button>
            <h2>{vista.toLocaleDateString('es-ES', { month: 'long', year: 'numeric' })}</h2>
            <button type="button" onClick={() => cambiarMes(1)} aria-label="Mes siguiente">›</button>
          </div>

          <div className="grid">
            {DIAS_SEMANA.map((d) => <div key={d} className="dow">{d}</div>)}
          </div>

          <div className="grid">
            {Array.from({ length: huecos }, (_, i) => <div key={`v${i}`} className="day empty" />)}

            {Array.from({ length: totalDias }, (_, i) => {
              const dia = i + 1;
              const fecha = `${anio}-${pad(mes + 1)}-${pad(dia)}`;
              return (
                <div key={fecha} className={`day${fecha === hoyStr ? ' today' : ''}`}>
                  <div className="num">{dia}</div>

                  {charlasDelDia(fecha).map((c) => (
                    <button
                      key={c.idCharla}
                      type="button"
                      className={`charla ${c.estadoCharla}${seleccionada?.idCharla === c.idCharla ? ' selected' : ''}`}
                      disabled={c.estadoCharla === 'CANCELADA'}
                      onClick={() => { setSeleccionada(c); setMensaje(''); }}
                    >
                      {reservadas[c.idCharla] ? '✓ ' : ''}{c.nombreCharla}
                      <small>{hhmm(c.horaInicioCharla)} – {hhmm(c.horaFinCharla)}</small>
                    </button>
                  ))}
                </div>
              );
            })}
          </div>
        </section>

        {/* ---------- Detalle y reserva ---------- */}
        <form className="card" onSubmit={reservar}>
          <h3>Reservar charla</h3>

          {!logueado && (
            <p className="aviso">Estás viendo las charlas como invitado. Inicia sesión para reservar.</p>
          )}

          {!seleccionada ? (
            <p className="muted">Selecciona una charla en el calendario.</p>
          ) : (
            <dl>
              <dt>Charla</dt><dd>{seleccionada.nombreCharla}</dd>
              <dt>Descripción</dt><dd>{seleccionada.descripcionCharla}</dd>
              <dt>Fecha</dt>
              <dd>
                {new Date(seleccionada.fechaCharla + 'T00:00').toLocaleDateString('es-ES', {
                  weekday: 'long', day: 'numeric', month: 'long', year: 'numeric',
                })}
              </dd>
              <dt>Horario</dt>
              <dd>{hhmm(seleccionada.horaInicioCharla)} – {hhmm(seleccionada.horaFinCharla)}</dd>
              <dt>Sala</dt><dd>Sala {seleccionada.idSala}</dd>
            </dl>
          )}

          {mensaje && <p className="muted">{mensaje}</p>}

          {/* Botones según el estado del usuario */}
          {!logueado ? (
            // Invitado: no puede reservar
            <Link className="btn" to="/login">Iniciar sesión para reservar</Link>
          ) : !estadoReserva ? (
            // Con sesión y sin reserva en esta charla
            <button className="btn" type="submit" disabled={!seleccionada}>Reservar plaza</button>
          ) : (
            // Ya reservada: aparece el botón de asistencia
            <>
              <p className="ok">✓ Plaza reservada</p>
              {estadoReserva === 'HA ASISTIDO' ? (
                <p className="ok">✓ Asistencia confirmada</p>
              ) : (
                <button className="btn" type="button" onClick={confirmarAsistencia}>
                  Confirmar asistencia
                </button>
              )}
            </>
          )}
        </form>
      </div>
    </>
  );
}
