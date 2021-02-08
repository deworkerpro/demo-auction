import React from 'react'
import { render } from '@testing-library/react'
import FeaturesProvider from './FeaturesProvider'
import FeaturesContext from './FeaturesContext'

test('passes features', () => {
  const features = ['ONE', 'TWO']

  const { getByTestId } = render(
    <FeaturesProvider features={features}>
      <FeaturesContext.Consumer>
        {(features) => <div data-testid="features">{features.toString()}</div>}
      </FeaturesContext.Consumer>
    </FeaturesProvider>
  )

  expect(getByTestId('features')).toHaveTextContent('ONE,TWO')
})
