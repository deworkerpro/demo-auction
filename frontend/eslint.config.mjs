import { defineConfig, globalIgnores } from 'eslint/config'
import globals from 'globals'
import js from '@eslint/js'
import tsEslint from 'typescript-eslint'
import pluginReact from 'eslint-plugin-react'
import pluginPrettierRecommended from 'eslint-plugin-prettier/recommended'

export default defineConfig([
  globalIgnores(['build/']),
  {
    files: ['**/*.ts', '**/*.tsx'],
    languageOptions: { globals: { ...globals.browser, ...globals.node } },
  },
  { files: ['**/*.ts', '**/*.tsx'], plugins: { js }, extends: ['js/recommended'] },
  tsEslint.configs.recommended,
  pluginReact.configs.flat.recommended,
  pluginReact.configs.flat['jsx-runtime'],
  pluginPrettierRecommended,
])
