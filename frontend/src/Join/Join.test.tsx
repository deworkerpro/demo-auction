import { render, screen } from '@testing-library/react'
import Join from './Join'
import { MemoryRouter } from 'react-router'
import FakeAuthProvider from '../OAuth/Provider/FakeAuthProvider'
import { expect, test } from 'vitest'

test('renders join page', () => {
  render(
    <FakeAuthProvider isAuthenticated={false}>
      <MemoryRouter>
        <Join />
      </MemoryRouter>
    </FakeAuthProvider>,
  )

  expect(screen.getByTestId('join-form')).toBeInTheDocument()
})
