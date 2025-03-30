import { render } from '@testing-library/react'
import FeaturesProvider from './FeaturesProvider'
import useFeatures from './useFeatures'
import { expect, test } from 'vitest'

test('read features', () => {
  const Component = () => {
    const features = useFeatures()
    return <>{features.toString()}</>
  }

  const { container } = render(
    <FeaturesProvider features={['ONE', 'TWO']}>
      <Component />
    </FeaturesProvider>,
  )

  expect(container).toHaveTextContent('ONE,TWO')
})
