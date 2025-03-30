import { ReactNode } from 'react'

type Props = {
  error: string | null
  children: ReactNode
}

export default function InputRow({ children, error }: Props) {
  return <div className={'input-row' + (error ? ' has-error' : '')}>{children}</div>
}
