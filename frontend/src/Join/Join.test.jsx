import React from 'react'
import { render } from '@testing-library/react'
import Join from './Join'
import { MemoryRouter } from 'react-router-dom'

test('renders join page', () => {
  const { getByTestId } = render(
    <MemoryRouter>
      <Join />
    </MemoryRouter>
  )

  expect(getByTestId('join-form')).toBeInTheDocument()
})
