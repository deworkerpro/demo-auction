import { render, screen } from '@testing-library/react'
import Success from './Success'
import { MemoryRouter } from 'react-router'
import { expect, test } from 'vitest'

test('renders join page', () => {
  render(
    <MemoryRouter>
      <Success />
    </MemoryRouter>,
  )

  expect(screen.getByTestId('join-success')).toBeInTheDocument()
})
