import React from 'react'
import styles from './System.module.css'
import PropTypes from 'prop-types'

function System({ children }) {
  return (
    <div className={styles.layout}>
      <div className={styles.content}>{children}</div>
    </div>
  )
}

System.propTypes = {
  children: PropTypes.any,
}

export default System
