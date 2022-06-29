import React from 'react'
import styles from './Alert.module.css'

function AlertSuccess({ message }: { message: string | null }): JSX.Element {
  return (
    <>
      {message ? (
        <div className={styles.alert + ' ' + styles.success} data-testid="alert-success">
          {message}
        </div>
      ) : null}
    </>
  )
}

export default AlertSuccess
