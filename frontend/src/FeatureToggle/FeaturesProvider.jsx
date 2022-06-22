import React from 'react'
import PropTypes from 'prop-types'
import FeaturesContext from './FeaturesContext'

function FeaturesProvider({ features, children }) {
  return <FeaturesContext.Provider value={features}>{children}</FeaturesContext.Provider>
}

FeaturesProvider.propTypes = {
  features: PropTypes.array.isRequired,
  children: PropTypes.object,
}

export default FeaturesProvider
