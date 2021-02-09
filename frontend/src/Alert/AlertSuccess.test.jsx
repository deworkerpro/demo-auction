import React from 'react'
import { render } from '@testing-library/react'
import AlertSuccess from './AlertSuccess'

test('renders success', () => {
  const { getByTestId } = render(<AlertSuccess message="Success!" />)

  const alert = getByTestId('alert-success')
  expect(alert).toBeInTheDocument()
  expect(alert).toHaveTextContent('Success!')
})

test('renders empty', () => {
  const { queryByTestId } = render(<AlertSuccess message="" />)

  expect(queryByTestId('alert-success')).not.toBeInTheDocument()
})
