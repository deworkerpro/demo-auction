function request(url, method, data, headers) {
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
      ...body.headers,
    },
  })
    .then((response) => {
      if (response.ok) {
        return response
      }
      throw response
    })
    .then((response) => {
      const type = response.headers.get('content-type')
      if (type && type.includes('application/json')) {
        return response.json()
      }
      return response.text()
    })
}

const api = {
  get: (url, headers = {}) => request(url, 'GET', null, headers),
  post: (url, data = null, headers = {}) => request(url, 'POST', data, headers),
}

export default api
