import React from 'react'
import System from '../Layout/System'
import { Link } from 'react-router-dom'

function Home() {
  return (
    <System>
      <h1>Auction</h1>
      <p>We are here</p>
      <p>
        <Link to="/join" data-testid="join-link">
          Join to Us
        </Link>
      </p>
    </System>
  )
}

export default Home
