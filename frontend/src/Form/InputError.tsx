import React from 'react'

export default function InputError({
  error,
}: {
  error: string | null | undefined
}): React.JSX.Element {
  return (
    <>
      {error ? (
        <div className="input-error" data-testid="violation">
          {error}
        </div>
      ) : null}
    </>
  )
}
