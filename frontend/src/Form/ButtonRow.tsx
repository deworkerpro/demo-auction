import React, { ReactNode } from 'react'

function ButtonRow({ children }: { children: ReactNode }): React.JSX.Element {
  return <div className="button-row">{children}</div>
}

export default ButtonRow
