import React from 'react'
import { fireEvent, render, screen } from '@testing-library/react'
import JoinForm from './JoinForm'
import api from '../../Api'

test('allows the user to join successfully', async () => {
  jest.spyOn(api, 'post').mockResolvedValue('')

  render(<JoinForm />)

  fireEvent.change(screen.getByLabelText('Email'), {
    target: { value: 'mail@app.test' },
  })
  fireEvent.change(screen.getByLabelText('Password'), {
    target: { value: 'password' },
  })
  fireEvent.click(screen.getByLabelText(/I agree/i))

  fireEvent.click(screen.getByTestId('join-button'))

  const alert = await screen.findByTestId('alert-success')

  expect(alert).toHaveTextContent('Confirm join by link in email')

  expect(api.post).toHaveBeenCalledWith('/v1/auth/join', {
    email: 'mail@app.test',
    password: 'password',
  })
})

test('shows conflict error', async () => {
  jest.spyOn(api, 'post').mockRejectedValue(
    new Response(JSON.stringify({ message: 'User already exists.' }), {
      status: 409,
      headers: new Headers({ 'Content-Type': 'application/json' }),
    }),
  )

  render(<JoinForm />)

  fireEvent.change(screen.getByLabelText('Email'), {
    target: { value: 'mail@app.test' },
  })
  fireEvent.change(screen.getByLabelText('Password'), {
    target: { value: 'password' },
  })
  fireEvent.click(screen.getByLabelText(/I agree/i))

  fireEvent.click(screen.getByTestId('join-button'))

  const alert = await screen.findByTestId('alert-error')

  expect(alert).toHaveTextContent('User already exists.')
})

test('shows validation errors', async () => {
  jest.spyOn(api, 'post').mockRejectedValue(
    new Response(
      JSON.stringify({
        errors: {
          email: 'Incorrect email',
          password: 'Incorrect password',
        },
      }),
      {
        status: 422,
        headers: new Headers({ 'Content-Type': 'application/json' }),
      },
    ),
  )

  render(<JoinForm />)

  fireEvent.change(screen.getByLabelText('Email'), {
    target: { value: 'incorrect@app.test' },
  })
  fireEvent.change(screen.getByLabelText('Password'), {
    target: { value: 'password' },
  })
  fireEvent.click(screen.getByLabelText(/I agree/i))

  fireEvent.click(screen.getByTestId('join-button'))

  await screen.findAllByTestId('violation')

  screen.getByText('Incorrect email')
  screen.getByText('Incorrect password')
})

test('shows server error', async () => {
  jest.spyOn(api, 'post').mockRejectedValue(
    new Response('', {
      status: 502,
      statusText: 'Bad Gateway',
    }),
  )

  render(<JoinForm />)

  fireEvent.change(screen.getByLabelText('Email'), {
    target: { value: 'mail@app.test' },
  })
  fireEvent.change(screen.getByLabelText('Password'), {
    target: { value: 'password' },
  })
  fireEvent.click(screen.getByLabelText(/I agree/i))

  fireEvent.click(screen.getByTestId('join-button'))

  const alert = await screen.findByTestId('alert-error')

  expect(alert).toHaveTextContent('Bad Gateway')
})
