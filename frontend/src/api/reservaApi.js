import { peticion } from "./http.js";

const pad = (n) => String(n).padStart(2, "0");

/** Charlas (programadas y canceladas) de un mes. "mes" va de 0 (enero) a 11 (diciembre). */
export function obtenerCharlasDelMes(anio, mes) {
  return peticion(`/calendario?mes=${anio}-${pad(mes + 1)}`);
}

/** Reservas confirmadas del usuario, con el dato asistenciaConfirmada. */
export function obtenerMisReservas(idUsuario) {
  return peticion(`/mis-reservas?idUsuario=${idUsuario}`);
}

/** Guarda una reserva en la base de datos (tabla Reserva). */
export function reservarCharla(idUsuario, idCharla) {
  return peticion("/mis-reservas", {
    method: "POST",
    body: { idUsuario, idCharla },
  });
}

/** Guarda la asistencia a una reserva (tabla Asistencia). */
export function confirmarAsistenciaReserva(idUsuario, idReserva) {
  return peticion(`/mis-reservas/${idReserva}/asistencia`, {
    method: "POST",
    body: { idUsuario },
  });
}

/**
 * Todas las charlas próximas (programadas y canceladas, desde hoy), ordenadas por fecha y hora.
 * El backend las devuelve paginadas: se piden páginas de 100 hasta tenerlas todas.
 */
export async function obtenerProximasCharlas() {
  const charlas = [];
  let pagina = 1;
  let ultimaPagina;

  do {
    const respuesta = await peticion(`/charlas?per_page=100&page=${pagina}`);
    charlas.push(...respuesta.data);
    ultimaPagina = respuesta.last_page;
    pagina += 1;
  } while (pagina <= ultimaPagina);

  return charlas;
}
