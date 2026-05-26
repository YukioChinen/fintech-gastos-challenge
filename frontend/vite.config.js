import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

export default defineConfig({
  // Render serves SPA entry from / while built assets are copied to /public/build/assets.
  base: '/build/',
  plugins: [vue()],
  server: { port: 5173 }
})
