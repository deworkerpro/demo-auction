import React, { ReactNode } from 'react'

function ButtonRow({ children }: { children: ReactNode }): JSX.Element {
  return <div className="button-row">{children}</div>
}

export default ButtonRow
