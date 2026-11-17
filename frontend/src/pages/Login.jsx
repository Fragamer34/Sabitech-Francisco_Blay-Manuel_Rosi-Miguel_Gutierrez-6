import { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';

export default function Login() {
  const navigate = useNavigate();
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');

  const enviar = (e) => {
    e.preventDefault();
    setError('');

    if (!email || !password) {
      setError('Introduce tu correo electrónico y tu contraseña.');
      return;
    }

    // TODO: aquí va la llamada a tu backend con { email, password }.
    // Si va bien, guarda lo que te devuelva (token o usuario) en lugar de 'demo'.
    localStorage.setItem('token', 'demo');
    navigate('/reservas');
  };

  return (
    <form className="card form-card" onSubmit={enviar} noValidate>
      <h1>Iniciar sesión</h1>

      <label htmlFor="email">Correo electrónico</label>
      <input type="email" id="email" value={email}
             onChange={(e) => setEmail(e.target.value)} autoComplete="email" required />

      <label htmlFor="password">Contraseña</label>
      <input type="password" id="password" value={password}
             onChange={(e) => setPassword(e.target.value)} autoComplete="current-password" required />

      <div className="error">{error}</div>

      <button className="btn" type="submit">Entrar</button>

      <p className="pie">¿No tienes cuenta? <Link to="/registro">Crear cuenta</Link></p>
      <p className="pie"><Link to="/reservas">Ver charlas como invitado</Link></p>
      <p className="pie"><Link to="/">← Volver al inicio</Link></p>
    </form>
  );
}
