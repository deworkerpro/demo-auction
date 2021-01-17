import mergeFeatures from './mergeFeatures'

test('merges features structs and arrays', () => {
  const features1 = {
    first: true,
    second: false,
    third: false,
    fourth: false,
  }

  const features2 = ['second']

  const features3 = {
    fourth: true,
  }

  const features = mergeFeatures(features1, features2, features3)

  expect(features).toEqual(['first', 'fourth', 'second'])
})
