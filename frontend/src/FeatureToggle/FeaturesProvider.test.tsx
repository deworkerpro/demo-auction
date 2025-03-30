import { render, screen } from '@testing-library/react'
import FeaturesProvider from './FeaturesProvider'
import FeaturesContext from './FeaturesContext'
import { expect, test } from 'vitest'

test('passes features', () => {
  const features = ['ONE', 'TWO']

  render(
    <FeaturesProvider features={features}>
      <FeaturesContext.Consumer>
        {(features) => <div data-testid="features">{features.toString()}</div>}
      </FeaturesContext.Consumer>
    </FeaturesProvider>,
  )

  expect(screen.getByTestId('features')).toHaveTextContent('ONE,TWO')
})
