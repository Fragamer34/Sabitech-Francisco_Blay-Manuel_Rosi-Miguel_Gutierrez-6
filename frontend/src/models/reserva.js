export class Reserva {
  constructor({
    idReserva = null,
    idUsuario,
    idCharla,
    fechaReserva,
    estadoReserva,
  }) {
    this.idReserva = idReserva;
    this.idUsuario = idUsuario;
    this.idCharla = idCharla;
    this.fechaReserva = fechaReserva;
    this.estadoReserva = estadoReserva;
  }

  static fromJSON(json) {
    return new Reserva({
      idReserva: json.idReserva,
      idUsuario: json.idUsuario,
      idCharla: json.idCharla,
      fechaReserva: json.fechaReserva,
      estadoReserva: json.estadoReserva,
    });
  }

  toJSON() {
    return {
      idReserva: this.idReserva,
      idUsuario: this.idUsuario,
      idCharla: this.idCharla,
      fechaReserva: this.fechaReserva,
      estadoReserva: this.estadoReserva,
    };
  }
}
