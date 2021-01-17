import React from 'react'
import { render } from '@testing-library/react'
import Welcome from './Welcome'
import { FeaturesContext } from '../FeatureToggle'

test('renders old welcome', () => {
  const { getByText, queryByText } = render(
    <FeaturesContext.Provider value={[]}>
      <Welcome />
    </FeaturesContext.Provider>
  )

  expect(getByText(/We will be here/i)).toBeInTheDocument()
  expect(queryByText(/We are here/i)).toBeNull()
})

test('renders new welcome', () => {
  const { getByText, queryByText } = render(
    <FeaturesContext.Provider value={['WE_ARE_HERE']}>
      <Welcome />
    </FeaturesContext.Provider>
  )

  expect(queryByText(/We will be here/i)).toBeNull()
  expect(getByText(/We are here/i)).toBeInTheDocument()
})
