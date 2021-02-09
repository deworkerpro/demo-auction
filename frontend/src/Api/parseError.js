async function parseError(error) {
  if (error.status === 422) {
    return null
  }

  if (error.status) {
    const type = error.headers.get('content-type')
    if (type && type.includes('application/json')) {
      const data = await error.json()
      if (data.message) {
        return data.message
      }
    }

    return error.statusText
  }

  return error.message
}

export default parseError
