import React from 'react'
import PropTypes from 'prop-types'

function InputRow({ children, error }) {
  return <div className={'input-row' + (error ? ' has-error' : '')}>{children}</div>
}

InputRow.propTypes = {
  children: PropTypes.any,
  error: PropTypes.string,
}

export default InputRow
