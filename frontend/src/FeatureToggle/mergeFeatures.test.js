import mergeFeatures from './mergeFeatures'

test('merges features structs and arrays', () => {
  const features1 = {
    first: true,
    second: false,
    third: true,
    fourth: false,
    fifth: true,
  }

  const features2 = ['second', '!third']

  const features3 = {
    fourth: true,
    fifth: false,
  }

  const features = mergeFeatures(features1, features2, features3)

  expect(features).toEqual(['first', 'fourth', 'second'])
})
