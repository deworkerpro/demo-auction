import React from 'react'
import { render } from '@testing-library/react'
import Join from './Join'
import { MemoryRouter } from 'react-router-dom'

test('renders join page', () => {
  const { getByText } = render(
    <MemoryRouter>
      <Join />
    </MemoryRouter>
  )

  expect(getByText(/Join to Us/i)).toBeInTheDocument()
})
