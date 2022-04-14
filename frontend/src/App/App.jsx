import React from 'react'
import PropTypes from 'prop-types'
import './App.css'
import { FeaturesProvider } from '../FeatureToggle'
import { BrowserRouter, Route, Routes } from 'react-router-dom'
import Home from '../Home'
import Join from '../Join'
import { NotFound } from '../Error'
import Confirm from '../Join/Confirm'
import Success from '../Join/Success'

function App({ features }) {
  return (
    <FeaturesProvider features={features}>
      <BrowserRouter>
        <div className="app">
          <Routes>
            <Route exact path="/" element={<Home />} />
            <Route exact path="/join" element={<Join />} />
            <Route exact path="/join/confirm" element={<Confirm />} />
            <Route exact path="/join/success" element={<Success />} />
            <Route path="*" element={<NotFound />} />
          </Routes>
        </div>
      </BrowserRouter>
    </FeaturesProvider>
  )
}

App.propTypes = {
  features: PropTypes.array.isRequired,
}

export default App
