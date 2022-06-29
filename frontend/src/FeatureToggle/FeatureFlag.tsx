import React, { ReactNode, useContext } from 'react'
import FeaturesContext from './FeaturesContext'

type Props = {
  name: string
  not?: boolean
  children: ReactNode
}

function FeatureFlag({ name, not = false, children }: Props): JSX.Element {
  const features = useContext(FeaturesContext)
  const isActive = features.includes(name)

  return <>{(not ? !isActive : isActive) ? children : null}</>
}

export default FeatureFlag
