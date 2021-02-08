import api from './api'

test('get with custom header', async () => {
  const fetch = jest.spyOn(global, 'fetch').mockResolvedValue({
    ok: true,
    status: 200,
    headers: new Headers(),
    text: () => Promise.resolve(''),
  })

  const data = await api.get('/url', { 'Custom-Header': 'Value' })

  expect(data).toEqual('')

  expect(fetch).toHaveBeenCalledWith('/api/url', {
    method: 'GET',
    headers: {
      Accept: 'application/json',
      'Custom-Header': 'Value',
    },
  })
})

test('get with JSON response', async () => {
  const fetch = jest.spyOn(global, 'fetch').mockResolvedValue({
    ok: true,
    status: 200,
    headers: new Headers({ 'Content-Type': 'application/json' }),
    json: () => Promise.resolve({ name: 'value' }),
  })

  const data = await api.get('/url')

  expect(data).toEqual({ name: 'value' })

  expect(fetch).toHaveBeenCalledWith('/api/url', {
    method: 'GET',
    headers: {
      Accept: 'application/json',
    },
  })
})

test('get with text response', async () => {
  jest.spyOn(global, 'fetch').mockResolvedValue({
    ok: true,
    status: 200,
    headers: new Headers({ 'Content-Type': 'text/plain' }),
    text: () => Promise.resolve('value'),
  })

  const data = await api.get('/url')

  expect(data).toEqual('value')
})

test('get promise', async () => {
  jest.spyOn(global, 'fetch').mockResolvedValue({
    ok: true,
    status: 200,
    headers: new Headers({ 'Content-Type': 'text/plain' }),
    text: () => Promise.resolve('value'),
  })

  expect.assertions(1)

  await api.get('/url').then((data) => expect(data).toEqual('value'))
})

test('post without params', async () => {
  const fetch = jest.spyOn(global, 'fetch').mockResolvedValue({
    ok: true,
    status: 200,
    headers: new Headers({ 'Content-Type': 'application/json' }),
    json: () => Promise.resolve({ name: 'value' }),
  })

  const data = await api.post('/url')

  expect(data).toEqual({ name: 'value' })

  expect(fetch).toHaveBeenCalledWith('/api/url', {
    method: 'POST',
    headers: { Accept: 'application/json' },
  })
})

test('post with params and header', async () => {
  const fetch = jest.spyOn(global, 'fetch').mockResolvedValue({
    ok: true,
    status: 200,
    headers: new Headers({ 'Content-Type': 'application/json' }),
    json: () => Promise.resolve({ name: 'value' }),
  })

  const data = await api.post(
    '/url',
    { param: 'val' },
    { 'Custom-Header': 'Value' }
  )

  expect(data).toEqual({ name: 'value' })

  expect(fetch).toHaveBeenCalledWith('/api/url', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      Accept: 'application/json',
      'Custom-Header': 'Value',
    },
    body: JSON.stringify({ param: 'val' }),
  })
})

test('post with error response', async () => {
  const response = {
    ok: false,
    status: 409,
  }

  jest.spyOn(global, 'fetch').mockResolvedValue(response)

  expect.assertions(1)

  await api.post('/url').catch((result) => expect(result).toEqual(response))
})

test('post with JS error', async () => {
  const error = new Error('Message')

  jest.spyOn(global, 'fetch').mockRejectedValue(error)

  expect.assertions(1)

  await api.post('/url').catch((result) => expect(result).toEqual(error))
})
