import React from 'react'
import PropTypes from 'prop-types'
import './App.css'
import { FeaturesProvider } from '../FeatureToggle'
import { BrowserRouter, Route, Switch } from 'react-router-dom'
import Home from '../Home'
import Join from '../Join'
import { NotFound } from '../Error'

function App({ features }) {
  return (
    <FeaturesProvider features={features}>
      <BrowserRouter>
        <div className="app">
          <Switch>
            <Route exact path="/">
              <Home />
            </Route>
            {features.includes('JOIN_TO_US') ? (
              <Route exact path="/join">
                <Join />
              </Route>
            ) : null}
            <Route path="*">
              <NotFound />
            </Route>
          </Switch>
        </div>
      </BrowserRouter>
    </FeaturesProvider>
  )
}

App.propTypes = {
  features: PropTypes.array.isRequired,
}

export default App
