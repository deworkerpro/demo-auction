import { render } from '@testing-library/react'
import System from './System'
import { expect, test } from 'vitest'

test('renders system layout', () => {
  const { container } = render(<System>Content</System>)

  expect(container).toHaveTextContent('Content')
})
