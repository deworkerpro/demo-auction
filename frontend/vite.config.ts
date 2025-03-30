import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'

export default defineConfig({
  plugins: [react()],
  server: {
    host: true,
    port: 3000,
    hmr: {
      host: 'localhost',
      port: 0,
      path: '/hmr',
    },
  },
})
