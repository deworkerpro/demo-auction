type FeaturesHash = Record<string, boolean>
type FeaturesArray = string[]

type Features = FeaturesHash | FeaturesArray

function transform(features: Features): FeaturesHash {
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

export default function mergeFeatures(...lists: Features[]): string[] {
  const features = lists.map(transform).reduce((previous, current) => ({ ...previous, ...current }))

  return Object.entries(features)
    .filter(([, value]) => value)
    .map(([name]) => name)
    .sort()
}
