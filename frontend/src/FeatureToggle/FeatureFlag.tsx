import React, { ReactNode, useContext } from 'react'
import FeaturesContext from './FeaturesContext'

type Props = {
  name: string
  not?: boolean
  children: ReactNode
}

export default function FeatureFlag({ name, not = false, children }: Props): React.JSX.Element {
  const features = useContext(FeaturesContext)
  const isActive = features.includes(name)

  return <>{(not ? !isActive : isActive) ? children : null}</>
}
