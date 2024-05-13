import React, { ReactNode } from 'react'

export default function ButtonRow({ children }: { children: ReactNode }): React.JSX.Element {
  return <div className="button-row">{children}</div>
}
