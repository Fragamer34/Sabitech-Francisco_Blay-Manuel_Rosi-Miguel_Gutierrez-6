import { useEffect, useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { cerrarSesion, obtenerUsuario } from '../api/authApi';
import {
  obtenerCharlasDelMes,
  obtenerMisReservas,
  reservarCharla,
  confirmarAsistenciaReserva,
} from '../api/reservaApi';

const DIAS_SEMANA = ['L', 'M', 'X', 'J', 'V', 'S', 'D'];
const hhmm = (t) => t.slice(0, 5);
const pad = (n) => String(n).padStart(2, '0');

export default function Reserva() {
  const navigate = useNavigate();

  const [vista, setVista] = useState(() => new Date(new Date().getFullYear(), new Date().getMonth(), 1));
  const [seleccionadaId, setSeleccionadaId] = useState(null);
  const [mensaje, setMensaje] = useState(null); // { texto, error } o null
  const [enviando, setEnviando] = useState(false);

  // Usuario con sesión iniciada (null = invitado)
  const [usuario, setUsuario] = useState(() => obtenerUsuario());
  const logueado = usuario !== null;

  const anio = vista.getFullYear();
  const mes = vista.getMonth();
  const claveMes = `${anio}-${pad(mes + 1)}`;

  // ---------- Charlas del mes visible (tabla Charla) ----------
  const [resultado, setResultado] = useState({ clave: null, charlas: [], error: '' });
  const [recarga, setRecarga] = useState(0); // al subirlo, se vuelven a pedir las charlas

  useEffect(() => {
    let cancelado = false;
    obtenerCharlasDelMes(anio, mes)
      .then((datos) => { if (!cancelado) setResultado({ clave: claveMes, charlas: datos, error: '' }); })
      .catch((err) => { if (!cancelado) setResultado({ clave: claveMes, charlas: [], error: err.message }); });
    return () => { cancelado = true; };
  }, [anio, mes, claveMes, recarga]);

  const cargando = resultado.clave !== claveMes;
  const charlas = cargando ? [] : resultado.charlas;

  // ---------- Reservas del usuario (tabla Reserva y Asistencia) ----------
  // { idCharla: { idReserva, asistida } }
  const [reservadas, setReservadas] = useState({});
  const idUsuario = usuario?.idUsuario ?? null;

  useEffect(() => {
    if (!idUsuario) return undefined;
    let cancelado = false;
    obtenerMisReservas(idUsuario)
      .then((lista) => {
        if (cancelado) return;
        const mapa = {};
        lista.forEach((r) => { mapa[r.idCharla] = { idReserva: r.idReserva, asistida: r.asistenciaConfirmada }; });
        setReservadas(mapa);
      })
      .catch((err) => { if (!cancelado) setMensaje({ texto: err.message, error: true }); });
    return () => { cancelado = true; };
  }, [idUsuario]);

  const huecos = (new Date(anio, mes, 1).getDay() + 6) % 7; // lunes = 0
  const totalDias = new Date(anio, mes + 1, 0).getDate();
  const hoy = new Date();
  const hoyStr = `${hoy.getFullYear()}-${pad(hoy.getMonth() + 1)}-${pad(hoy.getDate())}`;

  const cambiarMes = (delta) => setVista(new Date(anio, mes + delta, 1));

  const charlasDelDia = (fecha) =>
    charlas
      .filter((c) => c.fechaCharla === fecha)
      .sort((a, b) => a.horaInicioCharla.localeCompare(b.horaInicioCharla));

  const seleccionada = charlas.find((c) => c.idCharla === seleccionadaId) ?? null;
  const reserva = seleccionada ? reservadas[seleccionada.idCharla] : undefined;
  const sinPlazas = seleccionada !== null && seleccionada.plazasLibres <= 0;

  const reservar = async (e) => {
    e.preventDefault();
    if (!logueado || !seleccionada || reserva || enviando) return;

    setEnviando(true);
    setMensaje(null);
    try {
      const nueva = await reservarCharla(usuario.idUsuario, seleccionada.idCharla);
      setReservadas({ ...reservadas, [seleccionada.idCharla]: { idReserva: nueva.idReserva, asistida: false } });
      setRecarga((n) => n + 1); // actualiza las plazas libres
      setMensaje({ texto: `Plaza reservada. Referencia: ${nueva.referenciaReserva}`, error: false });
    } catch (err) {
      setMensaje({ texto: err.message, error: true });
    } finally {
      setEnviando(false);
    }
  };

  const confirmarAsistencia = async () => {
    if (!logueado || !seleccionada || !reserva || enviando) return;

    setEnviando(true);
    setMensaje(null);
    try {
      await confirmarAsistenciaReserva(usuario.idUsuario, reserva.idReserva);
      setReservadas({ ...reservadas, [seleccionada.idCharla]: { ...reserva, asistida: true } });
    } catch (err) {
      setMensaje({ texto: err.message, error: true });
    } finally {
      setEnviando(false);
    }
  };

  const salir = () => {
    cerrarSesion();
    setUsuario(null);
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
              const delDia = charlasDelDia(fecha);
              return (
                <div key={fecha} className={`day${fecha === hoyStr ? ' today' : ''}${delDia.length === 0 ? ' sin-charlas' : ''}`}>
                  <div className="num">{dia}</div>

                  {delDia.map((c) => (
                    <button
                      key={c.idCharla}
                      type="button"
                      className={`charla ${c.estadoCharla.toUpperCase()}${seleccionadaId === c.idCharla ? ' selected' : ''}`}
                      disabled={c.estadoCharla === 'cancelada'}
                      onClick={() => { setSeleccionadaId(c.idCharla); setMensaje(null); }}
                    >
                      {reservadas[c.idCharla] ? '✓ ' : ''}{c.tituloCharla}
                      <small>{hhmm(c.horaInicioCharla)} – {hhmm(c.horaFinCharla)}</small>
                    </button>
                  ))}
                </div>
              );
            })}
          </div>

          {cargando && <p className="muted vacio">Cargando charlas…</p>}
          {!cargando && resultado.error && <p className="error vacio">{resultado.error}</p>}
          {!cargando && !resultado.error && charlas.length === 0 && (
            <p className="muted vacio">No hay charlas este mes.</p>
          )}
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
              <dt>Charla</dt><dd>{seleccionada.tituloCharla}</dd>
              <dt>Descripción</dt><dd>{seleccionada.contenidoCharla || 'Sin descripción'}</dd>
              <dt>Fecha</dt>
              <dd>
                {new Date(seleccionada.fechaCharla + 'T00:00').toLocaleDateString('es-ES', {
                  weekday: 'long', day: 'numeric', month: 'long', year: 'numeric',
                })}
              </dd>
              <dt>Horario</dt>
              <dd>{hhmm(seleccionada.horaInicioCharla)} – {hhmm(seleccionada.horaFinCharla)}</dd>
              <dt>Sala</dt><dd>{seleccionada.sala?.nombreSala ?? `Sala ${seleccionada.idSala}`}</dd>
              {seleccionada.ponente && (
                <>
                  <dt>Ponente</dt>
                  <dd>{seleccionada.ponente.nombrePonente} {seleccionada.ponente.apellidosPonente}</dd>
                </>
              )}
              <dt>Plazas libres</dt><dd>{seleccionada.plazasLibres} de {seleccionada.aforoCharla}</dd>
            </dl>
          )}

          {mensaje && <p className={mensaje.error ? 'error' : 'ok'}>{mensaje.texto}</p>}

          {/* Botones según el estado del usuario */}
          {!logueado ? (
            // Invitado: no puede reservar
            <Link className="btn" to="/login">Iniciar sesión para reservar</Link>
          ) : !reserva ? (
            // Con sesión y sin reserva en esta charla
            <button className="btn" type="submit" disabled={!seleccionada || sinPlazas || enviando}>
              {sinPlazas ? 'Sin plazas libres' : 'Reservar plaza'}
            </button>
          ) : (
            // Ya reservada: aparece el botón de asistencia
            <>
              <p className="ok">✓ Plaza reservada</p>
              {reserva.asistida ? (
                <p className="ok">✓ Asistencia confirmada</p>
              ) : (
                <button className="btn" type="button" onClick={confirmarAsistencia} disabled={enviando}>
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
