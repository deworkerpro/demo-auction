import React from 'react'

type Props = {
  label: string
  htmlFor: string | null
}

export default function InputLabel({ label, htmlFor = null, ...rest }: Props): React.JSX.Element {
  return (
    <label className="input-label" htmlFor={htmlFor || undefined} {...rest}>
      {label}
    </label>
  )
}
