import React from 'react'
import PropTypes from 'prop-types'
import './App.css'
import Welcome from '../Welcome'

function App({ features }) {
  return (
    <div className="app">
      <Welcome features={features} />
    </div>
  )
}

App.propTypes = {
  features: PropTypes.array.isRequired,
}

export default App
