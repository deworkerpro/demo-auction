import parseErrors from './parseErrors'

test('response with violations', async () => {
  const response = new Response(
    JSON.stringify({
      errors: {
        email: 'Wrong email',
        password: 'Wrong password',
      },
    }),
    {
      status: 422,
      headers: new Headers({ 'content-type': 'application/json' }),
    }
  )
  const errors = await parseErrors(response)
  expect(errors).toEqual({
    email: 'Wrong email',
    password: 'Wrong password',
  })
})

test('response with error', async () => {
  const response = new Response(JSON.stringify({ error: 'Domain Error' }), {
    status: 409,
    headers: new Headers({ 'content-type': 'application/json' }),
  })
  const errors = await parseErrors(response)
  expect(errors).toEqual({})
})

test('html response with error', async () => {
  const response = new Response('Error', {
    status: 500,
    statusText: 'Server Error',
    headers: new Headers({ 'content-type': 'text/plain' }),
  })
  const errors = await parseErrors(response)
  expect(errors).toEqual({})
})

test('JS error', async () => {
  const error = new Error('JS error')
  const errors = await parseErrors(error)
  expect(errors).toEqual({})
})
