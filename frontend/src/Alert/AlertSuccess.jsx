import React from 'react'
import PropTypes from 'prop-types'
import styles from './Alert.module.css'

function AlertSuccess({ message }) {
  return message ? (
    <div className={styles.alert + ' ' + styles.success} data-testid="alert-success">
      {message}
    </div>
  ) : null
}

AlertSuccess.propTypes = {
  message: PropTypes.string,
}

export default AlertSuccess
