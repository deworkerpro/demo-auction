import React from 'react'
import styles from './Alert.module.css'

export default function AlertSuccess({
  message,
}: {
  message: string | null | undefined
}): React.JSX.Element {
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
