import React from 'react'
import styles from './Alert.module.css'

export default function AlertError({
  message,
}: {
  message: string | null | undefined
}): React.JSX.Element {
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
