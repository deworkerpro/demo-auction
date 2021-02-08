import React from 'react'
import { render } from '@testing-library/react'
import Home from './Home'
import { FeaturesProvider } from '../FeatureToggle'
import { MemoryRouter } from 'react-router-dom'

test('renders home', () => {
  const { getByText, queryByText } = render(
    <FeaturesProvider features={[]}>
      <MemoryRouter>
        <Home />
      </MemoryRouter>
    </FeaturesProvider>
  )

  expect(getByText(/We will be here/i)).toBeInTheDocument()
  expect(queryByText(/We are here/i)).not.toBeInTheDocument()
})

test('renders new home', () => {
  const { getByText, queryByText } = render(
    <FeaturesProvider features={['JOIN_TO_US']}>
      <MemoryRouter>
        <Home />
      </MemoryRouter>
    </FeaturesProvider>
  )

  expect(queryByText(/We will be here/i)).not.toBeInTheDocument()
  expect(getByText(/We are here/i)).toBeInTheDocument()
})
