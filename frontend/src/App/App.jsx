import React from 'react'
import PropTypes from 'prop-types'
import './App.css'
import Welcome from '../Welcome'
import { FeaturesContext } from '../FeatureToggle'

function App({ features }) {
  return (
    <FeaturesContext.Provider value={features}>
      <div className="app">
        <Welcome />
      </div>
    </FeaturesContext.Provider>
  )
}

App.propTypes = {
  features: PropTypes.array.isRequired,
}

export default App
