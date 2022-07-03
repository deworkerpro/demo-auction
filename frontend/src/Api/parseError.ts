import isJsonResponse from './isJsonResponse'

async function parseError(error: Error | Response) {
  if (error instanceof Error) {
    return error.message
  }

  if (error.status === 422) {
    return null
  }

  if (isJsonResponse(error)) {
    const data = await error.json()
    if (data.message) {
      return data.message
    }
  }

  return error.statusText
}

export default parseError
