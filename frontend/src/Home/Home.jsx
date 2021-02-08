import React from 'react'
import FeatureFlag from '../FeatureToggle'
import System from '../Layout/System'
import { Link } from 'react-router-dom'

function Home() {
  return (
    <System>
      <h1>Auction</h1>

      <FeatureFlag not name="JOIN_TO_US">
        <p>We will be here soon</p>
      </FeatureFlag>

      <FeatureFlag name="JOIN_TO_US">
        <p>We are here</p>
        <p>
          <Link to="/join" data-testid="join-link">
            Join to Us
          </Link>
        </p>
      </FeatureFlag>
    </System>
  )
}

export default Home
