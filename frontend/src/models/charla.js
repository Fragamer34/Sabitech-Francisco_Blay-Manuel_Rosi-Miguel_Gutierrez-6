export class Charla {
  constructor({
    idCharla = null,
    nombreCharla,
    descripcionCharla,
    fechaCharla,
    horaInicioCharla,
    horaFinCharla,
    estadoCharla,
    asistenciaCharla,
    idUsuario,
    idSala,
  }) {
    this.idCharla = idCharla;
    this.nombreCharla = nombreCharla;
    this.descripcionCharla = descripcionCharla;
    this.fechaCharla = fechaCharla;
    this.horaInicioCharla = horaInicioCharla;
    this.horaFinCharla = horaFinCharla;
    this.estadoCharla = estadoCharla;
    this.asistenciaCharla = asistenciaCharla;
    this.idUsuario = idUsuario;
    this.idSala = idSala;
  }

  static fromJSON(json) {
    return new Charla({
      idCharla: json.idCharla,
      nombreCharla: json.nombreCharla,
      descripcionCharla: json.descripcionCharla,
      fechaCharla: json.fechaCharla,
      horaInicioCharla: json.horaInicioCharla,
      horaFinCharla: json.horaFinCharla,
      estadoCharla: json.estadoCharla,
      asistenciaCharla: json.asistenciaCharla,
      idUsuario: json.idUsuario,
      idSala: json.idSala,
    });
  }

  toJSON() {
    return {
      idCharla: this.idCharla,
      nombreCharla: this.nombreCharla,
      descripcionCharla: this.descripcionCharla,
      fechaCharla: this.fechaCharla,
      horaInicioCharla: this.horaInicioCharla,
      horaFinCharla: this.horaFinCharla,
      estadoCharla: this.estadoCharla,
      asistenciaCharla: this.asistenciaCharla,
      idUsuario: this.idUsuario,
      idSala: this.idSala,
    };
  }
}
