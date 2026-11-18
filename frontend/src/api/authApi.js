// Sesión sencilla: Login.jsx guarda un token en localStorage.
// Cuando tengas el backend, sustituye esto por tu lógica real.

export function estaLogueado() {
  try {
    return Boolean(localStorage.getItem("token"));
  } catch {
    return false;
  }
}

export function cerrarSesion() {
  try {
    localStorage.removeItem("token");
  } catch {
    /* sin acceso a localStorage: nada que limpiar */
  }
}
