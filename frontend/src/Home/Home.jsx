import React from 'react'
import styles from './Home.module.css'
import System from '../Layout/System'
import { Link } from 'react-router-dom'
import FeatureFlag from '../FeatureToggle'

function Home() {
  return (
    <System>
      <h1>Auction</h1>
      <p>We are here</p>
      <p className={styles.links}>
        <Link to="/join" data-testid="join-link">
          Join
        </Link>

        <FeatureFlag name="OAUTH">
          <button type="button" data-testid="login-button">
            Log In
          </button>
        </FeatureFlag>
      </p>
    </System>
  )
}

export default Home
