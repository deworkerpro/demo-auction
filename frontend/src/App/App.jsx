import React from 'react'
import PropTypes from 'prop-types'
import './App.css'
import Home from '../Home'
import { FeaturesProvider } from '../FeatureToggle'

function App({ features }) {
  return (
    <FeaturesProvider features={features}>
      <div className="app">
        <Home />
      </div>
    </FeaturesProvider>
  )
}

App.propTypes = {
  features: PropTypes.array.isRequired,
}

export default App
