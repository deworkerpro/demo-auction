import React from 'react'
import { render, screen } from '@testing-library/react'
import Home from './Home'
import { FeaturesProvider } from '../FeatureToggle'
import { MemoryRouter } from 'react-router'
import FakeAuthProvider from '../OAuth/Provider/FakeAuthProvider'

test('renders home', () => {
  render(
    <FakeAuthProvider isAuthenticated={false}>
      <FeaturesProvider features={[]}>
        <MemoryRouter>
          <Home />
        </MemoryRouter>
      </FeaturesProvider>
    </FakeAuthProvider>,
  )

  expect(screen.queryByText(/We will be here/i)).not.toBeInTheDocument()
  expect(screen.getByText(/We are here/i)).toBeInTheDocument()
})
