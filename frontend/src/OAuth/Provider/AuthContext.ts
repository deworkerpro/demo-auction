import { createContext } from 'react'

export type AuthContextValue = {
  isAuthenticated: boolean
  getToken(): Promise<string>
  login(extra: Record<string, string>): void
  logout(): void
  loading: boolean
  error: string | null
}

const AuthContext = createContext<AuthContextValue | null>(null)

export default AuthContext
