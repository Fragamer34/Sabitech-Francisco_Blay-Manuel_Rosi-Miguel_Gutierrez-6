import { useState } from 'react';
import { Link, useLocation, useNavigate } from 'react-router-dom';
import { iniciarSesion } from '../api/authApi';

export default function Login() {
  const navigate = useNavigate();
  const location = useLocation();
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const [cargando, setCargando] = useState(false);

  // Viene de /registro con la cuenta recién creada
  const recienRegistrado = location.state?.registrado === true;

  const enviar = async (e) => {
    e.preventDefault();
    setError('');

    if (!email || !password) {
      setError('Introduce tu correo electrónico y tu contraseña.');
      return;
    }

    setCargando(true);
    try {
      await iniciarSesion(email, password);
      navigate('/reservas');
    } catch (err) {
      setError(err.status === 422 ? 'Introduce un correo electrónico válido.' : err.message);
    } finally {
      setCargando(false);
    }
  };

  return (
    <form className="card form-card" onSubmit={enviar} noValidate>
      <h1>Iniciar sesión</h1>

      {recienRegistrado && (
        <div className="exito">Cuenta creada correctamente. Ya puedes iniciar sesión.</div>
      )}

      <label htmlFor="email">Correo electrónico</label>
      <input type="email" id="email" value={email}
             onChange={(e) => setEmail(e.target.value)} autoComplete="email" required />

      <label htmlFor="password">Contraseña</label>
      <input type="password" id="password" value={password}
             onChange={(e) => setPassword(e.target.value)} autoComplete="current-password" required />

      <div className="error">{error}</div>

      <button className="btn" type="submit" disabled={cargando}>
        {cargando ? 'Entrando…' : 'Entrar'}
      </button>

      <p className="pie">¿No tienes cuenta? <Link to="/registro">Crear cuenta</Link></p>
      <p className="pie"><Link to="/reservas">Ver charlas como invitado</Link></p>
      <p className="pie"><Link to="/">← Volver al inicio</Link></p>
    </form>
  );
}
