import React from 'react'
import PropTypes from 'prop-types'
import './App.css'
import Welcome from '../Welcome'
import { FeaturesProvider } from '../FeatureToggle'

function App({ features }) {
  return (
    <FeaturesProvider features={features}>
      <div className="app">
        <Welcome />
      </div>
    </FeaturesProvider>
  )
}

App.propTypes = {
  features: PropTypes.array.isRequired,
}

export default App
