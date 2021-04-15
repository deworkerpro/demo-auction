import React from 'react'
import { render, screen } from '@testing-library/react'
import Home from './Home'
import { FeaturesProvider } from '../FeatureToggle'
import { MemoryRouter } from 'react-router-dom'

test('renders home', () => {
  render(
    <FeaturesProvider features={[]}>
      <MemoryRouter>
        <Home />
      </MemoryRouter>
    </FeaturesProvider>
  )

  expect(screen.getByText(/We will be here/i)).toBeInTheDocument()
  expect(screen.queryByText(/We are here/i)).not.toBeInTheDocument()
})

test('renders new home', () => {
  render(
    <FeaturesProvider features={['JOIN_TO_US']}>
      <MemoryRouter>
        <Home />
      </MemoryRouter>
    </FeaturesProvider>
  )

  expect(screen.queryByText(/We will be here/i)).not.toBeInTheDocument()
  expect(screen.getByText(/We are here/i)).toBeInTheDocument()
})
