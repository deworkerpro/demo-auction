import React from 'react'
import { render, fireEvent, screen } from '@testing-library/react'
import JoinForm from './JoinForm'

test('allows the user to join successfully', async () => {
  const fetch = jest.spyOn(global, 'fetch').mockResolvedValue({
    ok: true,
    status: 201
  })

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

  expect(fetch).toHaveBeenCalledWith('/api/v1/auth/join', {
    method: 'POST',
    headers: {
      Accept: 'application/json',
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({
      email: 'mail@app.test',
      password: 'password',
    }),
  })
})

test('shows conflict error', async () => {
  jest.spyOn(global, 'fetch').mockResolvedValue({
    ok: false,
    status: 409,
    headers: new Headers({ 'Content-Type': 'application/json' }),
    json: () => Promise.resolve({ message: 'User already exists.' }),
  })

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
  jest.spyOn(global, 'fetch').mockResolvedValue({
    ok: false,
    status: 422,
    headers: new Headers({ 'Content-Type': 'application/json' }),
    json: () =>
      Promise.resolve({
        errors: {
          email: 'Incorrect email',
          password: 'Incorrect password',
        },
      }),
  })

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
  jest.spyOn(global, 'fetch').mockResolvedValue({
    ok: false,
    status: 502,
    statusText: 'Bad Gateway',
    headers: new Headers(),
    text: () => Promise.resolve(''),
  })

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
