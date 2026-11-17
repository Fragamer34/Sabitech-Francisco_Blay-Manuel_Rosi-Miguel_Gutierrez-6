export class Ponente {
  constructor({
    idPonente = null,
    nombrePonente,
    apellidosPonente,
    emailPonente,
    especialidadPonente,
    biografiaPonente,
  }) {
    this.idPonente = idPonente;
    this.nombrePonente = nombrePonente;
    this.apellidosPonente = apellidosPonente;
    this.emailPonente = emailPonente;
    this.especialidadPonente = especialidadPonente;
    this.biografiaPonente = biografiaPonente;
  }

  static fromJSON(json) {
    return new Ponente({
      idPonente: json.idPonente,
      nombrePonente: json.nombrePonente,
      apellidosPonente: json.apellidosPonente,
      emailPonente: json.emailPonente,
      especialidadPonente: json.especialidadPonente,
      biografiaPonente: json.biografiaPonente,
    });
  }

  toJSON() {
    return {
      idPonente: this.idPonente,
      nombrePonente: this.nombrePonente,
      apellidosPonente: this.apellidosPonente,
      emailPonente: this.emailPonente,
      especialidadPonente: this.especialidadPonente,
      biografiaPonente: this.biografiaPonente,
    };
  }
}
