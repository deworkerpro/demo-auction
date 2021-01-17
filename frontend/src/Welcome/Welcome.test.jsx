import React from 'react'
import { render } from '@testing-library/react'
import Welcome from './Welcome'

test('renders old welcome', () => {
  const { getByText, queryByText } = render(<Welcome features={[]} />)

  expect(getByText(/We will be here/i)).toBeInTheDocument()
  expect(queryByText(/We are here/i)).toBeNull()
})

test('renders new welcome', () => {
  const { getByText, queryByText } = render(
    <Welcome features={['WE_ARE_HERE']} />
  )

  expect(queryByText(/We will be here/i)).toBeNull()
  expect(getByText(/We are here/i)).toBeInTheDocument()
})
