function isJsonResponse(response: Response) {
  const type = response.headers.get('content-type')
  return type && type.includes('application/json')
}

export default isJsonResponse
