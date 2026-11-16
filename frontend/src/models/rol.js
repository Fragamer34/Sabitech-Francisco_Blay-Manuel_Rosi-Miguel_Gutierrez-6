export class Rol {
  constructor({ idRoles = null, tipoRol, idUsuario }) {
    this.idRoles = idRoles;
    this.tipoRol = tipoRol;
    this.idUsuario = idUsuario;
  }

  static fromJSON(json) {
    return new Rol({
      idRoles: json.idRoles,
      tipoRol: json.tipoRol,
      idUsuario: json.idUsuario,
    });
  }

  toJSON() {
    return {
      idRoles: this.idRoles,
      tipoRol: this.tipoRol,
      idUsuario: this.idUsuario,
    };
  }
}
