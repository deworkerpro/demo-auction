import { render, screen } from '@testing-library/react'
import Home from './Home'
import { FeaturesProvider } from '../FeatureToggle'
import { MemoryRouter } from 'react-router'
import FakeAuthProvider from '../OAuth/Provider/FakeAuthProvider'
import { expect, test } from 'vitest'

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
