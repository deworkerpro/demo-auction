import { ReactNode } from 'react'

export default function ButtonRow({ children }: { children: ReactNode }) {
  return <div className="button-row">{children}</div>
}
