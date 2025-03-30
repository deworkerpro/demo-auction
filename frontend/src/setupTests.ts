import '@testing-library/jest-dom'
import 'isomorphic-fetch'
import { TextEncoder, TextDecoder } from 'node:util'

// TODO: remove after fix
Object.assign(global, { TextDecoder, TextEncoder })
