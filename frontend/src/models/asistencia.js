export class Asistencia {
  constructor({
    idAsistencia = null,
    idReserva,
    idUsuarioValidador,
    fechaHoraEntrada,
    idUsuario,
  }) {
    this.idAsistencia = idAsistencia;
    this.idReserva = idReserva;
    this.idUsuarioValidador = idUsuarioValidador;
    this.fechaHoraEntrada = fechaHoraEntrada;
    this.idUsuario = idUsuario;
  }

  static fromJSON(json) {
    return new Asistencia({
      idAsistencia: json.idAsistencia,
      idReserva: json.idReserva,
      idUsuarioValidador: json.idUsuarioValidador,
      fechaHoraEntrada: json.fechaHoraEntrada,
      idUsuario: json.idUsuario,
    });
  }

  toJSON() {
    return {
      idAsistencia: this.idAsistencia,
      idReserva: this.idReserva,
      idUsuarioValidador: this.idUsuarioValidador,
      fechaHoraEntrada: this.fechaHoraEntrada,
      idUsuario: this.idUsuario,
    };
  }
}
