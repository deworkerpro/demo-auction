import FeaturesContext from './FeaturesContext'
import { useContext } from 'react'

export default function useFeatures() {
  return useContext(FeaturesContext)
}
