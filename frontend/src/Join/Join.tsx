import React from 'react'
import System from '../Layout/System'
import { Link } from 'react-router-dom'
import JoinForm from './JoinForm'

function Join(): React.JSX.Element {
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

export default Join
