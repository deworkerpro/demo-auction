import isJsonResponse from './isJsonResponse'

async function parseError(error) {
  if (error.status === 422) {
    return null
  }

  if (error.status) {
    if (isJsonResponse(error)) {
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
