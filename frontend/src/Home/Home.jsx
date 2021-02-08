import React from 'react'
import FeatureFlag from '../FeatureToggle'
import System from '../Layout/System'

function Home() {
  return (
    <System>
      <h1>Auction</h1>

      <FeatureFlag not name="JOIN_TO_US">
        <p>We will be here soon</p>
      </FeatureFlag>

      <FeatureFlag name="JOIN_TO_US">
        <p>We are here</p>
      </FeatureFlag>
    </System>
  )
}

export default Home
