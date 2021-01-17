import React, { useContext } from 'react'
import styles from './Welcome.module.css'
import { FeaturesContext } from '../FeatureToggle'

function Welcome() {
  const features = useContext(FeaturesContext)

  return (
    <div data-testid="welcome" className={styles.welcome}>
      <h1>Auction</h1>
      {features.includes('WE_ARE_HERE') ? (
        <p>We are here</p>
      ) : (
        <p>We will be here soon</p>
      )}
    </div>
  )
}

export default Welcome
