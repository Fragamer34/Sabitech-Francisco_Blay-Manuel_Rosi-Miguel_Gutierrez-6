export class ApiError extends Error {
  constructor(status, message, body = null) {
    super(message);
    this.name = "ApiError";
    this.status = status; // código HTTP (0 = no se pudo conectar con el servidor)
    this.body = body;     // JSON completo de la respuesta, por si hay que leer "errors"
  }
}
