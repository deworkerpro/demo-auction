import React from 'react'
import FeatureFlag from '../FeatureToggle'
import System from '../Layout/System'

function Home() {
  return (
    <System>
      <h1>Auction</h1>

      <FeatureFlag not name="WE_ARE_HERE">
        <p>We will be here soon</p>
      </FeatureFlag>

      <FeatureFlag name="WE_ARE_HERE">
        <p>We are here</p>
      </FeatureFlag>
    </System>
  )
}

export default Home
