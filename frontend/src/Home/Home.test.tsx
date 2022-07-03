import React from 'react'
import { render, screen } from '@testing-library/react'
import Home from './Home'
import { FeaturesProvider } from '../FeatureToggle'
import { MemoryRouter } from 'react-router-dom'
import { AuthProvider } from '../OAuth/Provider'

test('renders home', () => {
  render(
    <AuthProvider
      authorizeUrl="/api/authorize"
      tokenUrl="/api/token"
      clientId="frontend"
      scope="common"
      redirectPath="/oauth"
    >
      <FeaturesProvider features={[]}>
        <MemoryRouter>
          <Home />
        </MemoryRouter>
      </FeaturesProvider>
    </AuthProvider>
  )

  expect(screen.queryByText(/We will be here/i)).not.toBeInTheDocument()
  expect(screen.getByText(/We are here/i)).toBeInTheDocument()
})
