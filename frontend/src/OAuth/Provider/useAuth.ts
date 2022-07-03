import AuthContext, { AuthContextValue } from './AuthContext'
import { useContext } from 'react'

export default function useAuth(): AuthContextValue {
  const ctx = useContext(AuthContext)

  if (ctx === null) {
    throw new Error('Unable to use auth outside of provider.')
  }

  return ctx
}
