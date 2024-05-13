import React from 'react'

function InputError({ error }: { error: string | null }): React.JSX.Element {
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

export default InputError
