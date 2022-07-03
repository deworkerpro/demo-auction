import parseError from './parseError'

test('response with violations', async () => {
  const response = new Response(JSON.stringify({ errors: { email: 'Wrong' } }), {
    status: 422,
    headers: new Headers({ 'content-type': 'application/json' }),
  })
  const result = await parseError(response)
  expect(result).toBe(null)
})

test('response with error', async () => {
  const response = new Response(JSON.stringify({ message: 'Domain Error' }), {
    status: 409,
    headers: new Headers({ 'content-type': 'application/json' }),
  })
  const result = await parseError(response)
  expect(result).toBe('Domain Error')
})

test('html response with error', async () => {
  const response = new Response('Error', {
    status: 500,
    statusText: 'Server Error',
    headers: new Headers({ 'content-type': 'text/plain' }),
  })
  const result = await parseError(response)
  expect(result).toBe('Server Error')
})

test('JS error', async () => {
  const error = new Error('JS error')
  const result = await parseError(error)
  expect(result).toBe('JS error')
})
