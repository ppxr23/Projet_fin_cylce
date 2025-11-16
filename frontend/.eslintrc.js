module.exports = {
  root: true,
  env: {
    browser: true,
    node: true,
    es2021: true,
  },
  parser: "vue-eslint-parser",
  parserOptions: {
    parser: "@babel/eslint-parser",
    requireConfigFile: false,
    ecmaVersion: 2021,
    sourceType: "module",
  },
  extends: [
    "eslint:recommended",
    "plugin:vue/vue3-recommended"
  ],
  rules: {
    // tes règles ici
  },
  ignorePatterns: ["dist/", "node_modules/"]
};
