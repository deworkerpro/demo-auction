import React from 'react'
import { render, screen } from '@testing-library/react'
import AlertError from './AlertError'

test('renders error', () => {
  render(<AlertError message="Error!" />)

  const alert = screen.getByTestId('alert-error')
  expect(alert).toBeInTheDocument()
  expect(alert).toHaveTextContent('Error!')
})

test('renders empty', () => {
  render(<AlertError message="" />)

  expect(screen.queryByTestId('alert-error')).not.toBeInTheDocument()
})
