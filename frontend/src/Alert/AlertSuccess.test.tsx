import React from 'react'
import { render, screen } from '@testing-library/react'
import AlertSuccess from './AlertSuccess'

test('renders success', () => {
  render(<AlertSuccess message="Success!" />)

  const alert = screen.getByTestId('alert-success')
  expect(alert).toBeInTheDocument()
  expect(alert).toHaveTextContent('Success!')
})

test('renders empty', () => {
  render(<AlertSuccess message="" />)

  expect(screen.queryByTestId('alert-success')).not.toBeInTheDocument()
})

test('renders null', () => {
  render(<AlertSuccess message={null} />)

  expect(screen.queryByTestId('alert-success')).not.toBeInTheDocument()
})

test('renders undefined', () => {
  render(<AlertSuccess message={undefined} />)

  expect(screen.queryByTestId('alert-success')).not.toBeInTheDocument()
})
