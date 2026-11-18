export class Notificacion {
  constructor({
    idNotificacion = null,
    tituloNotificacion,
    mensajeNotificacion,
    tipoNotificacion,
    fechaEnvioNotificacion,
    leidaNotificacion,
    idUsuario,
  }) {
    this.idNotificacion = idNotificacion;
    this.tituloNotificacion = tituloNotificacion;
    this.mensajeNotificacion = mensajeNotificacion;
    this.tipoNotificacion = tipoNotificacion;
    this.fechaEnvioNotificacion = fechaEnvioNotificacion;
    this.leidaNotificacion = leidaNotificacion;
    this.idUsuario = idUsuario;
  }

  static fromJSON(json) {
    return new Notificacion({
      idNotificacion: json.idNotificacion,
      tituloNotificacion: json.tituloNotificacion,
      mensajeNotificacion: json.mensajeNotificacion,
      tipoNotificacion: json.tipoNotificacion,
      fechaEnvioNotificacion: json.fechaEnvioNotificacion,
      leidaNotificacion: json.leidaNotificacion,
      idUsuario: json.idUsuario,
    });
  }

  toJSON() {
    return {
      idReserva: this.idReserva,
      tituloNotificacion: this.tituloNotificacion,
      mensajeNotificacion: this.mensajeNotificacion,
      tipoNotificacion: this.tipoNotificacion,
      fechaEnvioNotificacion: this.fechaEnvioNotificacion,
      leidaNotificacion: this.leidaNotificacion,
      idUsuario: this.idUsuario,
    };
  }
}
