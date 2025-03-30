import React from 'react'
import { MemoryRouter } from 'react-router-dom'
import { render, screen } from '@testing-library/react'
import NotFound from './NotFound'

test('renders not found', () => {
  render(
    <MemoryRouter future={{ v7_startTransition: true, v7_relativeSplatPath: true }}>
      <NotFound />
    </MemoryRouter>,
  )

  expect(screen.getByText(/Page is not found/i)).toBeInTheDocument()
})
