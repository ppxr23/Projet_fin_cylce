import axios from "axios";

let baseURL;

if (window.location.hostname === "localhost") {
  baseURL = "http://localhost:8000/api";
} else if (window.location.hostname.startsWith("192.168.")) {
  baseURL = `http://${window.location.hostname}:8000/api`;
} else {
  baseURL = "http://backend:8000/api";
}

const api = axios.create({
  baseURL
});

api.interceptors.request.use(config => {
  const token = localStorage.getItem("token");
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

export default api;
