import isJsonResponse from './isJsonResponse'

test('json response', () => {
  const response = {
    headers: new Headers({
      'content-type': 'application/json; charset=utf-8',
    }),
  }

  expect(isJsonResponse(response)).toBe(true)
})

test('not json response', () => {
  const response = {
    headers: new Headers({
      'content-type': 'text/html; charset=utf-8',
    }),
  }

  expect(isJsonResponse(response)).toBe(false)
})
