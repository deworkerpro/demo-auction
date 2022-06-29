import FeaturesContext from './FeaturesContext'
import { useContext } from 'react'

export default function useFeatures(): string[] {
  return useContext(FeaturesContext)
}
