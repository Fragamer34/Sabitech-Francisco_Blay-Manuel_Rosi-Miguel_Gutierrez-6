import { Link } from 'react-router-dom';

export default function Inicio() {
  return (
    <main className="card form-card portada">
      <h1>Reserva de charlas</h1>
      <p className="muted">
        Consulta el calendario de charlas y reserva tu plaza. Para reservar, inicia sesión o crea una cuenta.
      </p>

      <div className="acciones">
        <Link className="btn" to="/login">Iniciar sesión</Link>
        <Link className="btn btn-secundario" to="/registro">Crear cuenta</Link>
        {/* Acceso público: ve las charlas pero no puede reservar */}
        <Link className="btn btn-invitado" to="/reservas">Entrar como invitado</Link>
      </div>
    </main>
  );
}
