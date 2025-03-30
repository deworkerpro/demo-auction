import React from 'react'
import './index.css'
import App from './App'
import { parse } from 'cookie'
import { mergeFeatures } from './FeatureToggle'
import defaultFeatures from './features'
import { createRoot } from 'react-dom/client'

const cookies = parse(document.cookie)
const cookieFeatures = (cookies.features || '').split(/\s*,\s*/g).filter(Boolean)

const features = mergeFeatures(defaultFeatures, cookieFeatures)

const root = createRoot(document.getElementById('root') as HTMLElement)
root.render(
  <React.StrictMode>
    <App features={features} />
  </React.StrictMode>,
)
