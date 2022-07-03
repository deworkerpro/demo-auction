import React from 'react'
import { render, screen } from '@testing-library/react'
import Success from './Success'
import { MemoryRouter } from 'react-router-dom'

test('renders join page', () => {
  render(
    <MemoryRouter>
      <Success />
    </MemoryRouter>
  )

  expect(screen.getByTestId('join-success')).toBeInTheDocument()
})
