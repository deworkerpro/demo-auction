import '@testing-library/jest-dom'
import 'isomorphic-fetch'
import { TextEncoder, TextDecoder } from 'node:util'

Object.assign(global, { TextDecoder, TextEncoder })
