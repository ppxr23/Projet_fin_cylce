// src/api.js
import axios from "axios";

const api = axios.create({
  baseURL: "http://localhost:8000/api" // ton backend Symfony par ex.
});

// Ajouter le token à chaque requête si dispo
api.interceptors.request.use(config => {
  const token = localStorage.getItem("token");
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});


export default api;
