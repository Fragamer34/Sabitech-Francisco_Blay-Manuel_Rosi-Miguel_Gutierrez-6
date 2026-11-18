import { Link, useNavigate } from 'react-router-dom';
import { CHARLAS } from './datosCharlas';

const ETIQUETAS_ESTADO = {
  PROGRAMADA: 'Programada',
  ACTUALIZADA: 'Actualizada',
  CANCELADA: 'Cancelada',
};

const hhmm = (t) => t.slice(0, 5);

const formatearFecha = (fecha) =>
  new Date(fecha + 'T00:00').toLocaleDateString('es-ES', {
    weekday: 'long', day: 'numeric', month: 'long', year: 'numeric',
  });

export default function Inicio() {
  const navigate = useNavigate();

  // Charlas ordenadas por fecha y hora de inicio
  const charlas = [...CHARLAS].sort((a, b) =>
    `${a.fechaCharla}${a.horaInicioCharla}`.localeCompare(`${b.fechaCharla}${b.horaInicioCharla}`));

  // Va a Reserva.jsx con la charla elegida para que aparezca seleccionada
  const irAReservar = (charla) => navigate('/reservas', { state: { idCharla: charla.idCharla } });

  return (
    <>
      {/* ---------- Cabecera ---------- */}
      <header className="cabecera">
        <Link to="/" className="cabecera-logo">Sabitech</Link>
        <nav className="cabecera-nav">
          <Link className="btn btn-secundario" to="/login">Iniciar sesión</Link>
          <Link className="btn" to="/registro">Registrarse</Link>
        </nav>
      </header>

      {/* ---------- Listado de charlas ---------- */}
      <main className="wrap-inicio">
        <h1>Charlas disponibles</h1>
        <p className="muted">
          Consulta el calendario de charlas y reserva tu plaza. Para reservar, inicia sesión o crea una cuenta.
        </p>

        {charlas.length === 0 && <p className="muted">No hay charlas programadas.</p>}

        <ul className="lista-charlas">
          {charlas.map((c) => {
            const cancelada = c.estadoCharla === 'CANCELADA';
            return (
              <li key={c.idCharla} className={`card charla-card ${c.estadoCharla}`}>
                <div className="charla-card-cabecera">
                  <h2>{c.nombreCharla}</h2>
                  <span className={`estado ${c.estadoCharla}`}>{ETIQUETAS_ESTADO[c.estadoCharla]}</span>
                </div>

                <p>{c.descripcionCharla}</p>

                <dl>
                  <dt>Fecha</dt><dd>{formatearFecha(c.fechaCharla)}</dd>
                  <dt>Horario</dt><dd>{hhmm(c.horaInicioCharla)} – {hhmm(c.horaFinCharla)}</dd>
                  <dt>Sala</dt><dd>Sala {c.idSala}</dd>
                </dl>

                <button type="button" className="btn" disabled={cancelada} onClick={() => irAReservar(c)}>
                  {cancelada ? 'Charla cancelada' : 'Reservar'}
                </button>
              </li>
            );
          })}
        </ul>
      </main>
    </>
  );
}