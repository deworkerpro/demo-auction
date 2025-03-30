import { type ReactNode } from 'react'
import styles from './System.module.css'

export default function System({ children }: { children: ReactNode }) {
  return (
    <div className={styles.layout}>
      <div className={styles.content}>{children}</div>
    </div>
  )
}
