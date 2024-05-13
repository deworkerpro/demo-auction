import React, { useEffect, useState } from 'react'
import { Link, Navigate, useLocation } from 'react-router-dom'
import System from '../../Layout/System'
import { AlertError } from '../../Alert'
import api, { parseError } from '../../Api'

function useQuery() {
  return new URLSearchParams(useLocation().search)
}

export default function Confirm(): React.JSX.Element {
  const [success, setSuccess] = useState<true | null>(null)
  const [error, setError] = useState<string | null>(null)

  const query = useQuery()
  const token = query.get('token')

  useEffect(() => {
    if (token && success === null && error === null) {
      api
        .post('/v1/auth/join/confirm', { token })
        .then(() => setSuccess(true))
        .catch(async (error) => setError(await parseError(error)))
    }
  }, [success, error, token])

  if (success) {
    return <Navigate to="/join/success" replace />
  }

  if (!token) {
    return <Navigate to="/" replace />
  }

  return (
    <System>
      <div data-testid="join-confirm">
        <h1>Join</h1>
        <AlertError message={error} />
        <p>
          <Link to="/">Back to Home</Link>
        </p>
      </div>
    </System>
  )
}
