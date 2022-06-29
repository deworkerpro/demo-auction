import React, { ReactNode } from 'react'

type Props = {
  error: string | null
  children: ReactNode
}

function InputRow({ children, error }: Props): JSX.Element {
  return <div className={'input-row' + (error ? ' has-error' : '')}>{children}</div>
}

export default InputRow
