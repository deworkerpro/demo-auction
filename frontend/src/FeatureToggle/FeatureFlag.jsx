import { useContext } from 'react'
import PropTypes from 'prop-types'
import FeaturesContext from './FeaturesContext'

function FeatureFlag({ name, not = false, children }) {
  const features = useContext(FeaturesContext)
  const isActive = features.includes(name)
  return (not ? !isActive : isActive) ? children : null
}

FeatureFlag.propTypes = {
  name: PropTypes.string.isRequired,
  not: PropTypes.bool,
  children: PropTypes.object,
}

export default FeatureFlag
