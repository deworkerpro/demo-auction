import React from 'react'
import { render, screen } from '@testing-library/react'
import InputError from './InputError'

test('renders violation', () => {
  render(<InputError error="Error!" />)

  const violation = screen.getByTestId('violation')
  expect(violation).toBeInTheDocument()
  expect(violation).toHaveTextContent('Error!')
})

test('renders empty', () => {
  render(<InputError error={null} />)

  expect(screen.queryByTestId('violation')).not.toBeInTheDocument()
})

test('renders undefined', () => {
  render(<InputError error={undefined} />)

  expect(screen.queryByTestId('violation')).not.toBeInTheDocument()
})
