import React from 'react'
import { render, screen } from '@testing-library/react'
import Join from './Join'
import { MemoryRouter } from 'react-router-dom'
import FakeAuthProvider from '../OAuth/Provider/FakeAuthProvider'

test('renders join page', () => {
  render(
    <FakeAuthProvider isAuthenticated={false}>
      <MemoryRouter future={{ v7_startTransition: true, v7_relativeSplatPath: true }}>
        <Join />
      </MemoryRouter>
    </FakeAuthProvider>,
  )

  expect(screen.getByTestId('join-form')).toBeInTheDocument()
})
