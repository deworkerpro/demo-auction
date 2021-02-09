import React from 'react'
import { render } from '@testing-library/react'
import AlertError from './AlertError'

test('renders error', () => {
  const { getByTestId } = render(<AlertError message="Error!" />)

  const alert = getByTestId('alert-error')
  expect(alert).toBeInTheDocument()
  expect(alert).toHaveTextContent('Error!')
})

test('renders empty', () => {
  const { queryByTestId } = render(<AlertError message="" />)

  expect(queryByTestId('alert-error')).not.toBeInTheDocument()
})
