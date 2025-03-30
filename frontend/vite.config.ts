import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'

export default defineConfig({
  plugins: [react()],
  resolve: {
    mainFields: [],
  },
  server: {
    host: true,
    port: 80,
    hmr: {
      host: 'localhost',
      port: 0,
      path: '/ws',
    },
  },
})
