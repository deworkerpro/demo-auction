import React from 'react'
import styles from './Alert.module.css'

function AlertError({ message }: { message: string | null }): JSX.Element {
  return (
    <>
      {message ? (
        <div className={styles.alert + ' ' + styles.error} data-testid="alert-error">
          {message}
        </div>
      ) : null}
    </>
  )
}

export default AlertError
