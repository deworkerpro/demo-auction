import React, { ReactNode } from 'react'
import FeaturesContext from './FeaturesContext'

type Props = {
  features: string[]
  children: ReactNode
}

export default function FeaturesProvider({ features, children }: Props): React.JSX.Element {
  return <FeaturesContext.Provider value={features}>{children}</FeaturesContext.Provider>
}
