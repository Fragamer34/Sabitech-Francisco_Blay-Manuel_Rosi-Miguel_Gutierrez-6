export class Token {
  constructor({
    idToken = null,
    idReserva,
    idUsuarioValidador,
    fechaCreacionToken,
    fechaExpiracionToken,
    usadoToken,
  }) {
    this.idToken = idToken;
    this.idReserva = idReserva;
    this.idUsuarioValidador = idUsuarioValidador;
    this.fechaCreacionToken = fechaCreacionToken;
    this.fechaExpiracionToken = fechaExpiracionToken;
    this.usadoToken = usadoToken;
  }

  static fromJSON(json) {
    return new Token({
      idToken: json.idToken,
      idReserva: json.idReserva,
      idUsuarioValidador: json.idUsuarioValidador,
      fechaCreacionToken: json.fechaCreacionToken,
      fechaExpiracionToken: json.fechaExpiracionToken,
      usadoToken: json.usadoToken,
    });
  }

  toJSON() {
    return {
      idToken: this.idToken,
      idReserva: this.idReserva,
      idUsuarioValidador: this.idUsuarioValidador,
      fechaCreacionToken: this.fechaCreacionToken,
      fechaExpiracionToken: this.fechaExpiracionToken,
      usadoToken: this.usadoToken,
    };
  }
}
