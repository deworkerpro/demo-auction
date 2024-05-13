export default async function parseErrors(error: Error | Response) {
  if (error instanceof Error) {
    return {}
  }

  if (error.status === 422) {
    const data = await error.json()
    return data.errors
  }

  return {}
}
