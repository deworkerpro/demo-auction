import React from 'react'
import System from '../Layout/System'
import { Link } from 'react-router'

export default function NotFound(): React.JSX.Element {
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
