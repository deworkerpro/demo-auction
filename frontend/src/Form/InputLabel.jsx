import React from 'react'
import PropTypes from 'prop-types'

function InputLabel({ label, htmlFor = null, ...rest }) {
  return (
    <label className="input-label" htmlFor={htmlFor} {...rest}>
      {label}
    </label>
  )
}

InputLabel.propTypes = {
  label: PropTypes.string.isRequired,
  htmlFor: PropTypes.string,
}

export default InputLabel
