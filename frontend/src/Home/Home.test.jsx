import React from 'react'
import { render } from '@testing-library/react'
import Home from './Home'
import { FeaturesProvider } from '../FeatureToggle'

test('renders home', () => {
  const { getByText, queryByText } = render(
    <FeaturesProvider features={[]}>
      <Home />
    </FeaturesProvider>
  )

  expect(getByText(/We will be here/i)).toBeInTheDocument()
  expect(queryByText(/We are here/i)).not.toBeInTheDocument()
})

test('renders new home', () => {
  const { getByText, queryByText } = render(
    <FeaturesProvider features={['WE_ARE_HERE']}>
      <Home />
    </FeaturesProvider>
  )

  expect(queryByText(/We will be here/i)).not.toBeInTheDocument()
  expect(getByText(/We are here/i)).toBeInTheDocument()
})
