import React from 'react'
import { render } from '@testing-library/react'
import Success from './Success'
import { MemoryRouter } from 'react-router-dom'

test('renders join page', () => {
  const { getByTestId } = render(
    <MemoryRouter>
      <Success />
    </MemoryRouter>
  )

  expect(getByTestId('join-success')).toBeInTheDocument()
})
