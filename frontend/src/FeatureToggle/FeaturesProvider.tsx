import { type ReactNode } from 'react'
import FeaturesContext from './FeaturesContext'

type Props = {
  features: string[]
  children: ReactNode
}

export default function FeaturesProvider({ features, children }: Props) {
  return <FeaturesContext.Provider value={features}>{children}</FeaturesContext.Provider>
}
