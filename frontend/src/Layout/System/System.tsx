import React, { ReactNode } from 'react'
import styles from './System.module.css'

export default function System({ children }: { children: ReactNode }): React.JSX.Element {
  return (
    <div className={styles.layout}>
      <div className={styles.content}>{children}</div>
    </div>
  )
}
