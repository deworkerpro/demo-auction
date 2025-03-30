import { defineConfig } from "eslint/config";
import globals from "globals";
import js from "@eslint/js";
import tsEslint from "typescript-eslint";

export default defineConfig([
  { ignores: ['var/'] },
  { files: ["**/*.{js,mjs,cjs,ts}"], languageOptions: { globals: {...globals.browser, ...globals.node} } },
  { files: ["**/*.{js,mjs,cjs,ts}"], plugins: { js }, extends: ["js/recommended"] },
  tsEslint.configs.recommended,
]);
