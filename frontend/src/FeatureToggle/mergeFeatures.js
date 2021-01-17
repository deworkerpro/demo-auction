function transform(features) {
  if (Array.isArray(features)) {
    return features
  }

  return Object.entries(features)
    .filter(([, value]) => value)
    .map(([key]) => key)
}

export default function mergeFeatures(...lists) {
  return lists
    .map(transform)
    .reduce((previous, current) => [...previous, ...current])
    .sort()
}
