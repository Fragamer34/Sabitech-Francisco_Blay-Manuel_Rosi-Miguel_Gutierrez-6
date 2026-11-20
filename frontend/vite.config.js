/* global process */
import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'

// El proxy reenvía /api al backend. En este caso localhost:8080 (puerto que publica Docker);
// dentro de Docker Compose se usa VITE_PROXY_TARGET=http://web:80 (ver Backend/compose.yaml).
const destinoApi = process.env.VITE_PROXY_TARGET || 'http://localhost:8080'

// https://vite.dev/config/
export default defineConfig({
  plugins: [react()],
  server: {
    proxy: {
      '/api': { target: destinoApi, changeOrigin: true },
    },
  },
})

