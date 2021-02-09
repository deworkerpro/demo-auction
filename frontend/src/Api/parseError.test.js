import parseError from './parseError'

test('response with violations', async () => {
  const response = {
    ok: false,
    status: 422,
    headers: new Headers({ 'content-type': 'application/json' }),
    json: () => Promise.resolve({ errors: { email: 'Wrong' } }),
  }
  const result = await parseError(response)
  expect(result).toBe(null)
})

test('response with error', async () => {
  const response = {
    ok: false,
    status: 409,
    headers: new Headers({ 'content-type': 'application/json' }),
    json: () => Promise.resolve({ message: 'Domain Error' }),
  }
  const result = await parseError(response)
  expect(result).toBe('Domain Error')
})

test('html response with error', async () => {
  const response = {
    ok: false,
    status: 500,
    statusText: 'Server Error',
    headers: new Headers({ 'content-type': 'text/plain' }),
    text: () => Promise.resolve('Error'),
  }
  const result = await parseError(response)
  expect(result).toBe('Server Error')
})

test('JS error', async () => {
  const error = new Error('JS error')
  const result = await parseError(error)
  expect(result).toBe('JS error')
})
