import { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';

export default function Registro() {
  const navigate = useNavigate();
  const [datos, setDatos] = useState({
    nombre: '', apellidos: '', email: '', password: '', password_confirmation: '',
  });
  const [error, setError] = useState('');

  // Un solo manejador para todos los campos (usa el atributo name del input)
  const cambiar = (e) => setDatos({ ...datos, [e.target.name]: e.target.value });

  const enviar = (e) => {
    e.preventDefault();
    setError('');

    if (Object.values(datos).some((v) => !v.trim())) {
      setError('Todos los campos son obligatorios.');
      return;
    }
    if (datos.password.length < 8) {
      setError('La contraseña debe tener al menos 8 caracteres.');
      return;
    }
    if (datos.password !== datos.password_confirmation) {
      setError('Las contraseñas no coinciden.');
      return;
    }

    // TODO: aquí va la llamada a tu backend con "datos"
    // Si va bien: navigate('/login');
    console.log('Registro:', datos);
    navigate('/login');
  };

  return (
    <form className="card form-card" onSubmit={enviar} noValidate>
      <h1>Crear cuenta</h1>

      <label htmlFor="nombre">Nombre</label>
      <input type="text" id="nombre" name="nombre" value={datos.nombre}
             onChange={cambiar} maxLength={50} autoComplete="given-name" required />

      <label htmlFor="apellidos">Apellidos</label>
      <input type="text" id="apellidos" name="apellidos" value={datos.apellidos}
             onChange={cambiar} maxLength={100} autoComplete="family-name" required />

      <label htmlFor="email">Correo electrónico</label>
      <input type="email" id="email" name="email" value={datos.email}
             onChange={cambiar} autoComplete="email" required />

      <label htmlFor="password">Contraseña</label>
      <input type="password" id="password" name="password" value={datos.password}
             onChange={cambiar} minLength={8} autoComplete="new-password" required />

      <label htmlFor="password_confirmation">Confirmar contraseña</label>
      <input type="password" id="password_confirmation" name="password_confirmation"
             value={datos.password_confirmation} onChange={cambiar}
             autoComplete="new-password" required />

      <div className="error">{error}</div>

      <button className="btn" type="submit">Registrarme</button>

      <p className="pie">¿Ya tienes cuenta? <Link to="/login">Iniciar sesión</Link></p>
      <p className="pie"><Link to="/">← Volver al inicio</Link></p>
    </form>
  );
}
