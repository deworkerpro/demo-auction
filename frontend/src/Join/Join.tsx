import React from 'react'
import System from '../Layout/System'
import { Link, Navigate } from 'react-router-dom'
import JoinForm from './JoinForm'
import { useAuth } from '../OAuth/Provider'

export default function Join(): React.JSX.Element {
  const { isAuthenticated } = useAuth()

  if (isAuthenticated) {
    return <Navigate to="/" replace />
  }

  return (
    <System>
      <h1>Join to Us</h1>
      <JoinForm />
      <p>
        <Link to="/">Back to Home</Link>
      </p>
    </System>
  )
}
