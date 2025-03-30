/** @type {import('stylelint').Config} */
export default {
  extends: ['stylelint-config-standard'],
  plugins: ['stylelint-prettier'],
  rules: {
    'prettier/prettier': true,
    'selector-class-pattern': '^(([a-z][a-zA-Z0-9]+)|(([a-z][a-z0-9]*)(-[a-z0-9]+)*))$',
  },
}
