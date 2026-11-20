export class Sala {
  constructor({ idSala = null, nombreSala, ubicacionSala, capacidadSala }) {
    this.idSala = idSala;
    this.nombreSala = nombreSala;
    this.ubicacionSala = ubicacionSala;
    this.capacidadSala = capacidadSala;
  }

  static fromJSON(json) {
    return new Sala({
      idSala: json.idSala,
      nombreSala: json.nombreSala,
      ubicacionSala: json.ubicacionSala,
      capacidadSala: json.capacidadSala,
    });
  }

  toJSON() {
    return {
      idSala: this.idSala,
      nombreSala: this.nombreSala,
      ubicacionSala: this.ubicacionSala,
      capacidadSala: this.capacidadSala,
    };
  }
}
