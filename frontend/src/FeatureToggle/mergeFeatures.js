function transform(features) {
  if (!Array.isArray(features)) {
    return features
  }

  return Object.fromEntries(
    features.map((value) => {
      if (value.startsWith('!')) {
        return [value.substr(1), false]
      }
      return [value, true]
    })
  )
}

export default function mergeFeatures(...lists) {
  const features = lists.map(transform).reduce((previous, current) => ({ ...previous, ...current }))

  return Object.entries(features)
    .filter(([, value]) => value)
    .map(([name]) => name)
    .sort()
}
