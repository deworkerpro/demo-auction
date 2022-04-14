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

  expect(screen.queryByText(/We will be here/i)).not.toBeInTheDocument()
  expect(screen.getByText(/We are here/i)).toBeInTheDocument()
})
