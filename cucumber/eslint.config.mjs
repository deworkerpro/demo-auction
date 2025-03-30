import { defineConfig } from "eslint/config";
import globals from "globals";
import js from "@eslint/js";
import tsEslint from "typescript-eslint";

export default defineConfig([
  { files: ["**/*.{js,mjs,cjs,ts}"] },
  { files: ["**/*.{js,mjs,cjs,ts}"], languageOptions: { globals: {...globals.browser, ...globals.node} } },
  { files: ["**/*.{js,mjs,cjs,ts}"], plugins: { js }, extends: ["js/recommended"] },
  tsEslint.configs.recommended,
]);
