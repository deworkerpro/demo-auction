import React, { Fragment } from 'react'
import { render, waitFor, screen } from '@testing-library/react'
import Confirm from './Confirm'
import { createMemoryRouter, RouterProvider } from 'react-router-dom'
import api from '../../Api'

test('confirms without token', async () => {
  jest.spyOn(api, 'post')

  const router = createMemoryRouter(
    [
      {
        path: '/',
        element: <Fragment />,
      },
      {
        path: '/join/confirm',
        element: <Confirm />,
      },
    ],
    {
      initialEntries: ['/join/confirm'],
    },
  )

  render(<RouterProvider router={router} />)

  expect(router.state.location.pathname).toBe('/')

  expect(api.post).not.toHaveBeenCalled()
})

test('confirms successfully', async () => {
  jest.spyOn(api, 'post').mockResolvedValue(
    new Response('', {
      status: 201,
      headers: new Headers(),
    }),
  )

  const router = createMemoryRouter(
    [
      {
        path: '/join/confirm',
        element: <Confirm />,
      },
      {
        path: '/join/success',
        element: <Fragment />,
      },
    ],
    {
      initialEntries: ['/join/confirm?token=01'],
    },
  )

  render(<RouterProvider router={router} />)

  await waitFor(() => {
    expect(api.post).toHaveBeenCalled()
  })

  await waitFor(() => {
    expect(router.state.location.pathname).toBe('/join/success')
  })

  expect(api.post).toHaveBeenCalledWith('/v1/auth/join/confirm', {
    token: '01',
  })
})

test('shows error', async () => {
  jest.spyOn(api, 'post').mockRejectedValue(
    new Response(JSON.stringify({ message: 'Incorrect token.' }), {
      status: 409,
      headers: new Headers({ 'content-type': 'application/json' }),
    }),
  )

  const router = createMemoryRouter(
    [
      {
        path: '/join/confirm',
        element: <Confirm />,
      },
    ],
    {
      initialEntries: ['/join/confirm?token=01'],
    },
  )

  render(<RouterProvider router={router} />)

  const alert = await screen.findByTestId('alert-error')

  expect(alert).toHaveTextContent('Incorrect token.')
})
