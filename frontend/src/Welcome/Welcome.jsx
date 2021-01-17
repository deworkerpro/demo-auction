import React from 'react'
import PropTypes from 'prop-types'
import styles from './Welcome.module.css'

function Welcome({ features }) {
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

Welcome.propTypes = {
  features: PropTypes.array.isRequired,
}

export default Welcome
