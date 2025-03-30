import { defineConfig, globalIgnores } from "eslint/config";
import globals from "globals";
import js from "@eslint/js";
import tsEslint from "typescript-eslint";

export default defineConfig([
  globalIgnores(['var/']),
  { files: ["**/*.ts"], languageOptions: { globals: {...globals.browser, ...globals.node} } },
  { files: ["**/*.ts"], plugins: { js }, extends: ["js/recommended"] },
  tsEslint.configs.recommended,
]);
