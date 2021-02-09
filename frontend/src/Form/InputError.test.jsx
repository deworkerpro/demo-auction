import React from 'react'
import { render } from '@testing-library/react'
import InputError from './InputError'

test('renders violation', () => {
  const { getByTestId } = render(<InputError error="Error!" />)

  const violation = getByTestId('violation')
  expect(violation).toBeInTheDocument()
  expect(violation).toHaveTextContent('Error!')
})

test('renders empty', () => {
  const { queryByTestId } = render(<InputError error={null} />)

  expect(queryByTestId('violation')).not.toBeInTheDocument()
})
