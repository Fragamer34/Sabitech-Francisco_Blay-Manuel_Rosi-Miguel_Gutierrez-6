import { Charla } from "../models/charla.js";
import { ApiError } from "../api/apiError.js";
import { Page } from "../models/page.js";

const API_BASE_URL = "http://localhost:8080";
const CHARLA_URL = `${API_BASE_URL}/charla`;

export async function list() {
  const response = await fetch(`${CHARLA_URL}`);
  const json = await response.json();
  if (!response.ok) {
    throw new ApiError(response.status, json.message);
  }

  const charla = Page.fromJSON(json, "data", Charla.fromJSON);

  return charla;
}
