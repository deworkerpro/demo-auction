import isJsonResponse from './isJsonResponse'

type Method = 'GET' | 'POST'
type Data = object
type Headers = Record<string, string>

function request(url: string, method: Method, data: Data | null, headers: Headers) {
  const common = {
    method,
    headers: {
      Accept: 'application/json',
      ...headers,
    },
  }

  const body =
    data !== null
      ? {
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify(data),
        }
      : { headers: {} }

  return fetch('/api' + url, {
    ...common,
    ...body,
    headers: {
      ...common.headers,
      ...(body.headers as Headers),
    },
  })
    .then((response) => {
      if (response.ok) {
        return response
      }
      throw response
    })
    .then((response) => {
      if (isJsonResponse(response)) {
        return response.json()
      }
      return response.text()
    })
}

const api = {
  get: (url: string, headers: Headers = {}) => request(url, 'GET', null, headers),
  post: (url: string, data: Data | null = null, headers: Headers = {}) =>
    request(url, 'POST', data, headers),
}

export default api
