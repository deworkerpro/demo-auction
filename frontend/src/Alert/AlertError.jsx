import React from 'react'
import PropTypes from 'prop-types'
import styles from './Alert.module.css'

function AlertError({ message }) {
  return message ? (
    <div className={styles.alert + ' ' + styles.error} data-testid="alert-error">
      {message}
    </div>
  ) : null
}

AlertError.propTypes = {
  message: PropTypes.string,
}

export default AlertError
