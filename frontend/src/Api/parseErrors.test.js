import parseErrors from './parseErrors'

test('response with violations', async () => {
  const response = {
    ok: false,
    status: 422,
    headers: new Headers({ 'content-type': 'application/json' }),
    json: () =>
      Promise.resolve({
        errors: {
          email: 'Wrong email',
          password: 'Wrong password',
        },
      }),
  }
  const errors = await parseErrors(response)
  expect(errors).toEqual({
    email: 'Wrong email',
    password: 'Wrong password',
  })
})

test('response with error', async () => {
  const response = {
    ok: false,
    status: 409,
    headers: new Headers({ 'content-type': 'application/json' }),
    json: () => Promise.resolve({ error: 'Domain Error' }),
  }
  const errors = await parseErrors(response)
  expect(errors).toEqual({})
})

test('html response with error', async () => {
  const response = {
    ok: false,
    status: 500,
    statusMessage: 'Server Error',
    headers: new Headers({ 'content-type': 'text/plain' }),
    text: () => Promise.resolve('Error'),
  }
  const errors = await parseErrors(response)
  expect(errors).toEqual({})
})

test('JS error', async () => {
  const error = new Error('JS error')
  const errors = await parseErrors(error)
  expect(errors).toEqual({})
})
