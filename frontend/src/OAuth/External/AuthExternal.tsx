import React from 'react'
import styles from './AuthExternal.module.css'
import { useAuth } from '../Provider'

export default function AuthExternal(): React.JSX.Element {
  const { login } = useAuth()

  return (
    <div className={styles.external}>
      <div className={styles.heading}>Or log in via:</div>
      <div className={styles.items} data-testid="auth-external">
        <div
          className={styles.item + ' ' + styles.yandex}
          onClick={() => login({ provider: 'yandex' })}
          data-testid="auth-external-yandex"
        >
          Yandex
        </div>
        <div
          className={styles.item + ' ' + styles.mailru}
          onClick={() => login({ provider: 'mailru' })}
          data-testid="auth-external-mailru"
        >
          MailRu
        </div>
      </div>
    </div>
  )
}
