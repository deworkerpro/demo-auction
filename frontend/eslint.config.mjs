import { defineConfig } from 'eslint/config'
import globals from 'globals'
import js from '@eslint/js'
import tsEslint from 'typescript-eslint'
import pluginReact from 'eslint-plugin-react'
import pluginPrettierRecommended from 'eslint-plugin-prettier/recommended'

export default defineConfig([
  { ignores: ['build/'] },
  {
    files: ['**/*.{js,mjs,cjs,ts,jsx,tsx}'],
    languageOptions: { globals: { ...globals.browser, ...globals.node } },
  },
  { files: ['**/*.{js,mjs,cjs,ts,jsx,tsx}'], plugins: { js }, extends: ['js/recommended'] },
  tsEslint.configs.recommended,
  {
    files: ['**/*.{js,ts,jsx,tsx}'],
    ...pluginReact.configs.flat.recommended,
    rules: {
      ...pluginReact.configs.flat.recommended.rules,
      'react/react-in-jsx-scope': 'off',
    },
  },
  pluginPrettierRecommended,
])
