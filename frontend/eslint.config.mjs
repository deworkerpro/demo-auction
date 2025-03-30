import { defineConfig } from 'eslint/config'
import globals from 'globals'
import js from '@eslint/js'
import tsEslint from 'typescript-eslint'
import pluginReact from 'eslint-plugin-react'
import pluginPrettierRecommended from 'eslint-plugin-prettier/recommended'

export default defineConfig([
  { ignores: ['build/'] },
  { files: ['**/*.{js,mjs,cjs,ts,jsx,tsx}'] },
  {
    files: ['**/*.{js,mjs,cjs,ts,jsx,tsx}'],
    languageOptions: { globals: { ...globals.browser, ...globals.node } },
  },
  { files: ['**/*.{js,mjs,cjs,ts,jsx,tsx}'], plugins: { js }, extends: ['js/recommended'] },
  tsEslint.configs.recommended,
  pluginReact.configs.flat.recommended,
  pluginPrettierRecommended,
])
