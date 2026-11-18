export class Page {
  constructor({ items }) {
    this.items = items;
  }

  static fromJSON(json, listKey, mapFn) {
    const rawItems = Array.isArray(json[listKey]) ? json[listKey] : [];
    return rawItems.map(mapFn);
  }
}
