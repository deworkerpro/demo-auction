import { expect, test, vi } from 'vitest'
import { render, screen } from '@testing-library/react'
import { FeaturesProvider } from '../FeatureToggle'
import App from './App'

vi.mock('../FeatureToggle', () => ({ FeaturesProvider: vi.fn(() => null) }))

test('renders app', () => {
  render(<App features={['ONE']} />)

  const context = undefined
  const children = expect.any(Object)
  const props = { children, features: ['ONE'] }

  expect(FeaturesProvider).toHaveBeenCalledWith(props, context)

  expect(screen.queryByText(/We are here/i)).not.toBeInTheDocument()
})
