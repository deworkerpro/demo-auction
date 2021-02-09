import React from 'react'
import PropTypes from 'prop-types'

function InputError({ error }) {
  return error ? (
    <div className="input-error" data-testid="violation">
      {error}
    </div>
  ) : null
}

InputError.propTypes = {
  error: PropTypes.string,
}

export default InputError
