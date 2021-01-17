import React from 'react'
import ReactDOM from 'react-dom'
import './index.css'
import App from './App'
import * as serviceWorker from './serviceWorker'
import cookie from 'cookie'
import { mergeFeatures } from './FeatureToggle'

const defaultFeatures = {
  WE_ARE_HERE: false,
}

const cookies = cookie.parse(document.cookie)
const cookieFeatures = (cookies.features || '').split(/\s*,\s*/g)

const features = mergeFeatures(defaultFeatures, cookieFeatures)

ReactDOM.render(
  <React.StrictMode>
    <App features={features} />
  </React.StrictMode>,
  document.getElementById('root')
)

serviceWorker.unregister()
