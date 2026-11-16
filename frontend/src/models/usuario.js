export class Usuario {
  constructor({
    idUsuario = null,
    nombreUsuario,
    apellidosUsuario,
    emailUsuario,
    contrasenaUsuario_has,
    fechaDeRegistroUsuario,
  }) {
    this.idUsuario = idUsuario;
    this.nombreUsuario = nombreUsuario;
    this.apellidosUsuario = apellidosUsuario;
    this.emailUsuario = emailUsuario;
    this.contrasenaUsuario_has = contrasenaUsuario_has;
    this.fechaDeRegistroUsuario = fechaDeRegistroUsuario;
  }

  static fromJSON(json) {
    return new Usuario({
      idUsuario: json.idUsuario,
      nombreUsuario: json.nombreUsuario,
      apellidosUsuario: json.apellidosUsuario,
      emailUsuario: json.emailUsuario,
      contrasenaUsuario_has: json.contrasenaUsuario_has,
      fechaDeRegistroUsuario: json.fechaDeRegistroUsuario,
    });
  }

  toJSON() {
    return {
      idUsuario: this.idUsuario,
      nombreUsuario: this.nombreUsuario,
      apellidosUsuario: this.apellidosUsuario,
      emailUsuario: this.emailUsuario,
      contrasenaUsuario_has: this.contrasenaUsuario_has,
      fechaDeRegistroUsuario: this.fechaDeRegistroUsuario,
    };
  }
}
