import React from 'react'
import PropTypes from 'prop-types'

function ButtonRow({ children }) {
  return <div className="button-row">{children}</div>
}

ButtonRow.propTypes = {
  children: PropTypes.any,
}

export default ButtonRow
