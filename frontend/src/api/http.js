import { ApiError } from "./apiError.js";

// En desarrollo, Vite reenvía "/api" al backend (ver vite.config.js).
// En producción, Nginx hace lo mismo (ver docker/nginx/default.conf).
const API_BASE_URL = import.meta.env.VITE_API_URL ?? "/api";

/** Hace una petición al backend y devuelve el JSON. Lanza ApiError si algo falla. */
export async function peticion(ruta, { method = "GET", body } = {}) {
  const headers = { Accept: "application/json" };
  if (body) headers["Content-Type"] = "application/json";

  let respuesta;
  try {
    respuesta = await fetch(`${API_BASE_URL}${ruta}`, {
      method,
      headers,
      body: body ? JSON.stringify(body) : undefined,
    });
  } catch {
    throw new ApiError(0, "No se pudo conectar con el servidor. Inténtalo de nuevo más tarde.");
  }

  const texto = await respuesta.text();
  let json = null;
  try {
    json = JSON.parse(texto);
  } catch {
    // La respuesta no es JSON
  }

  if (!respuesta.ok) {
    throw new ApiError(respuesta.status, json?.message ?? "Ha ocurrido un error inesperado.", json);
  }

  // Éxito (2xx) pero sin JSON: la petición no ha llegado a Laravel o este ha respondido otra cosa
  if (json === null) {
    console.error(
      `Respuesta inesperada de ${method} ${API_BASE_URL}${ruta}: HTTP ${respuesta.status}, ` +
      `${respuesta.headers.get("content-type")}`,
      texto.slice(0, 300),
    );
    throw new ApiError(
      respuesta.status,
      "El servidor no ha devuelto datos válidos. Comprueba que el backend está en marcha (revisa la consola del navegador).",
    );
  }
  return json;
}
