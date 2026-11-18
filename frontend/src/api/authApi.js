import { peticion } from "./http.js";

const CLAVE_USUARIO = "usuario";

// ---------- Sesión guardada en el navegador ----------

/** Devuelve el usuario con sesión iniciada, o null si no hay ninguno. */
export function obtenerUsuario() {
  try {
    return JSON.parse(localStorage.getItem(CLAVE_USUARIO));
  } catch {
    return null;
  }
}

export function estaLogueado() {
  return obtenerUsuario() !== null;
}

// ---------- Llamadas al backend ----------

/** Crea la cuenta en la base de datos. No inicia sesión: eso se hace en /login. */
export async function registrar({ nombre, apellidos, email, password, password_confirmation }) {
  return peticion("/auth/registro", {
    method: "POST",
    body: {
      nombreUsuario: nombre,
      apellidosUsuario: apellidos,
      emailUsuario: email,
      contrasenaUsuario: password,
      contrasenaUsuario_confirmation: password_confirmation,
    },
  });
}

/** Comprueba email y contraseña en el servidor y recuerda al usuario en el navegador. */
export async function iniciarSesion(email, password) {
  const { usuario } = await peticion("/auth/login", {
    method: "POST",
    body: { emailUsuario: email, contrasenaUsuario: password },
  });
  localStorage.setItem(CLAVE_USUARIO, JSON.stringify(usuario));
  return usuario;
}

/** Cierra la sesión (solo en el navegador; el servidor no guarda sesiones). */
export function cerrarSesion() {
  localStorage.removeItem(CLAVE_USUARIO);
}
