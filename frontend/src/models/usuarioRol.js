export class UsuarioRol {
  constructor({ idUsuario, idRol }) {
    this.idUsuario = idUsuario;
    this.idRol = idRol;
  }

  static fromJSON(json) {
    return new UsuarioRol({
      idUsuario: json.idUsuario,
      idRol: json.idRol,
    });
  }

  toJSON() {
    return {
      idUsuario: this.idUsuario,
      idRol: this.idRol,
    };
  }
}
