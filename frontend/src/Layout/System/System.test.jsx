import React from 'react'
import { render } from '@testing-library/react'
import System from './System'

test('renders system layout', () => {
  const { container } = render(<System>Content</System>)

  expect(container).toHaveTextContent('Content')
})
