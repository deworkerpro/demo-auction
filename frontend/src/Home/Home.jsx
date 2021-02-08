import React from 'react'
import styles from './Home.module.css'
import FeatureFlag from '../FeatureToggle'

function Home() {
  return (
    <div className={styles.home}>
      <h1>Auction</h1>

      <FeatureFlag not name="WE_ARE_HERE">
        <p>We will be here soon</p>
      </FeatureFlag>

      <FeatureFlag name="WE_ARE_HERE">
        <p>We are here</p>
      </FeatureFlag>
    </div>
  )
}

export default Home
