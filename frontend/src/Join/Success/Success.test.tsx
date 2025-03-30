import React from 'react'
import { render, screen } from '@testing-library/react'
import Success from './Success'
import { MemoryRouter } from 'react-router-dom'

test('renders join page', () => {
  render(
    <MemoryRouter future={{ v7_startTransition: true, v7_relativeSplatPath: true }}>
      <Success />
    </MemoryRouter>,
  )

  expect(screen.getByTestId('join-success')).toBeInTheDocument()
})
