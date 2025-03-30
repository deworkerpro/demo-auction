import React from 'react'
import { jest } from '@jest/globals'
import { render, screen } from '@testing-library/react'
import { FeaturesProvider } from '../FeatureToggle'
import App from './App'

jest.mock('../FeatureToggle', () => ({ FeaturesProvider: jest.fn(() => null) }))

test('renders app', () => {
  render(<App features={['ONE']} />)

  const context = expect.any(Object)
  const children = expect.any(Object)
  const props = { children, features: ['ONE'] }

  expect(FeaturesProvider).toHaveBeenCalledWith(props, context)

  expect(screen.queryByText(/We are here/i)).not.toBeInTheDocument()
})
