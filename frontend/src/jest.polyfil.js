// TODO: remove after jest fix
import { TextEncoder, TextDecoder, ReadableStream } from 'node:util'

Object.assign(global, { TextDecoder, TextEncoder, ReadableStream })
