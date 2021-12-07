import React from 'react'
import { createMemoryHistory } from 'history'
import { render, waitFor, screen } from '@testing-library/react'
import Confirm from './Confirm'
import { Router, MemoryRouter } from 'react-router-dom'
import api from '../../Api'

test('confirms without token', async () => {
  jest.spyOn(api, 'post')

  const history = createMemoryHistory({
    initialEntries: ['/join/confirm'],
  })

  render(
    <Router location={history.location} navigator={history}>
      <Confirm />
    </Router>
  )

  expect(history.location.pathname).toBe('/')

  expect(api.post).not.toHaveBeenCalled()
})

test('confirms successfully', async () => {
  jest.spyOn(api, 'post').mockResolvedValue({
    ok: true,
    status: 201,
    headers: new Headers(),
    text: () => Promise.resolve(''),
  })

  const history = createMemoryHistory({
    initialEntries: ['/join/confirm?token=01'],
  })

  render(
    <Router location={history.location} navigator={history}>
      <Confirm />
    </Router>
  )

  await waitFor(() => {
    expect(api.post).toHaveBeenCalled()
  })

  expect(history.location.pathname).toBe('/join/success')

  expect(api.post).toHaveBeenCalledWith('/v1/auth/join/confirm', {
    token: '01',
  })
})

test('shows error', async () => {
  jest.spyOn(api, 'post').mockRejectedValue({
    ok: false,
    status: 409,
    headers: new Headers({ 'content-type': 'application/json' }),
    json: () => Promise.resolve({ message: 'Incorrect token.' }),
  })

  render(
    <MemoryRouter initialEntries={['/join/confirm?token=01']}>
      <Confirm />
    </MemoryRouter>
  )

  const alert = await screen.findByTestId('alert-error')

  expect(alert).toHaveTextContent('Incorrect token.')
})
