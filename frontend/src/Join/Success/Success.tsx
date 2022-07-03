import React from 'react'
import System from '../../Layout/System'
import { Link } from 'react-router-dom'

function Success(): JSX.Element {
  return (
    <System>
      <div data-testid="join-success">
        <h1>Success</h1>
        <p>You are successfully joined!</p>
        <p>
          <Link to="/">Back to Home</Link>
        </p>
      </div>
    </System>
  )
}

export default Success
