import React from 'react'
import System from '../Layout/System'
import { Link } from 'react-router-dom'

function NotFound() {
  return (
    <System>
      <h1>Error</h1>
      <p>Page is not found</p>
      <p>
        <Link to="/">Back to Home</Link>
      </p>
    </System>
  )
}

export default NotFound
