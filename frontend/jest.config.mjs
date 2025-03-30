/** @type {import('ts-jest').JestConfigWithTsJest} **/
export default {
  testEnvironment: 'jsdom',
  transform: {
    '\\.tsx?$': [
      'ts-jest',
      {
        tsconfig: 'tsconfig.app.json',
        astTransformers: {
          before: [
            {
              path: 'node_modules/ts-jest-mock-import-meta',
              options: {
                metaObjectReplacement: {
                  url: 'http://localhost',
                  env: {
                    VITE_AUTH_URL: process.env.VITE_AUTH_URL,
                  },
                },
              },
            },
          ],
        },
      },
    ],
  },
  moduleNameMapper: {
    '\\.css$': 'identity-obj-proxy',
  },
  setupFilesAfterEnv: ['<rootDir>/src/setupTests.ts'],
}
