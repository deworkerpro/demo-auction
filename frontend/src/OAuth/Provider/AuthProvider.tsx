import React, { ReactNode, useCallback, useEffect, useMemo, useState } from 'react'
import AuthContext, { AuthContextValue } from './AuthContext'
import { generateCodeChallenge, generateCodeVerifier, generateState } from './crypt'

type Props = {
  authorizeUrl: string
  tokenUrl: string
  clientId: string
  scope: string
  redirectPath: string
  children: ReactNode
}

interface TokenResponse {
  token_type: string
  access_token: string
  expires_in: number
  refresh_token: string
}

type Tokens = {
  accessToken: string
  expires: number
  refreshToken: string
}

function AuthProvider({ authorizeUrl, tokenUrl, clientId, scope, redirectPath, children }: Props) {
  const [isAuthenticated, setIsAuthenticated] = useState<boolean>(
    window.localStorage.getItem('auth.tokens') !== null,
  )

  const [loading, setLoading] = useState<boolean>(false)

  const isAuthRedirect = window.location.pathname === redirectPath

  const query = new URLSearchParams(window.location.search)

  const getStateError = (): string | null => {
    if (!query.get('state')) {
      return 'Empty state.'
    }
    if (query.get('state') !== window.localStorage.getItem('auth.state')) {
      return 'Invalid state.'
    }
    return null
  }

  const getAuthRedirectError = (): string | null => {
    return query.get('hint') || query.get('error_description') || query.get('error')
  }

  const [error, setError] = useState<string | null>(
    isAuthRedirect ? getStateError() || getAuthRedirectError() : null,
  )

  useEffect(() => {
    if (!isAuthRedirect) {
      return
    }

    if (error) {
      return
    }

    const authCode = query.get('code')

    if (!authCode) {
      return
    }

    setError(null)
    setLoading(true)

    const codeVerifier = window.localStorage.getItem('auth.code_verifier')

    if (!codeVerifier) {
      setError('Empty verifier.')
      return
    }

    fetch(tokenUrl, {
      method: 'POST',
      headers: {
        Accept: 'application/json',
        'Content-type': 'application/x-www-form-urlencoded',
      },
      body: new URLSearchParams({
        client_id: clientId,
        code_verifier: codeVerifier,
        grant_type: 'authorization_code',
        redirect_uri: window.location.origin + redirectPath,
        access_type: 'offline',
        code: authCode,
      }),
    })
      .then((response) => {
        if (response.ok) {
          return response
        }
        throw response
      })
      .then(async (response) => {
        const data = (await response.json()) as TokenResponse

        setLoading(false)

        const tokens = buildTokens(data)

        window.localStorage.setItem('auth.tokens', JSON.stringify(tokens))

        setIsAuthenticated(true)

        window.localStorage.removeItem('auth.state')
        window.localStorage.removeItem('auth.code_verifier')

        const location = window.localStorage.getItem('auth.location')
        window.localStorage.removeItem('auth.location')

        window.location.replace(location || '/')
      })
      .catch(async (error) => {
        setLoading(false)

        if (!(error instanceof Response)) {
          setError(error.message)
          return
        }

        const headers = error.headers.get('content-type')
        if (headers && headers.includes('application/json')) {
          const data = await error.json()
          setError(data.hint || data.error_description || data.error || data.message)
          return
        }

        setError(error.statusText)
      })
  }, [])

  useEffect(() => {
    const listener = (e: StorageEvent) => {
      if (e.key === 'auth.tokens') {
        setIsAuthenticated(e.newValue ? JSON.parse(e.newValue) !== null : false)
      }
    }
    window.addEventListener('storage', listener)
    return () => window.removeEventListener('storage', listener)
  }, [])

  const login = useCallback(async () => {
    const currentLocation = window.location.pathname
    const codeVerifier = generateCodeVerifier()
    const codeChallenge = await generateCodeChallenge(codeVerifier)
    const state = generateState()

    window.localStorage.setItem('auth.location', currentLocation)
    window.localStorage.setItem('auth.code_verifier', codeVerifier)
    window.localStorage.setItem('auth.state', state)

    const args = new URLSearchParams({
      response_type: 'code',
      client_id: clientId,
      code_challenge_method: 'S256',
      code_challenge: codeChallenge,
      redirect_uri: window.location.origin + redirectPath,
      scope,
      state,
    })

    window.location.assign(authorizeUrl + '?' + args)
  }, [])

  const logout = useCallback(() => {
    window.localStorage.removeItem('auth.tokens')
    setIsAuthenticated(false)
  }, [])

  const refreshPromises: Record<string, Promise<string>> = useMemo(() => ({}), [])

  const getToken = useCallback(() => {
    const storageTokens = window.localStorage.getItem('auth.tokens')

    if (storageTokens === null) {
      return Promise.reject(new Error())
    }

    const tokens = JSON.parse(storageTokens) as Tokens

    if (tokens === null) {
      return Promise.reject(new Error())
    }

    if (tokens.expires > new Date().getTime()) {
      return Promise.resolve(tokens.accessToken)
    }

    setLoading(true)

    if (Object.prototype.hasOwnProperty.call(refreshPromises, tokens.refreshToken)) {
      return refreshPromises[tokens.refreshToken]
    }

    refreshPromises[tokens.refreshToken] = fetch(tokenUrl, {
      method: 'POST',
      headers: {
        Accept: 'application/json',
        'Content-type': 'application/x-www-form-urlencoded',
      },
      body: new URLSearchParams({
        client_id: clientId,
        grant_type: 'refresh_token',
        redirect_uri: window.location.origin + redirectPath,
        access_type: 'offline',
        refresh_token: tokens.refreshToken,
      }),
    })
      .then((response) => {
        if (!response.ok) {
          throw response
        }
        return response
      })
      .then(async (response) => {
        const data = await response.json()
        setLoading(false)
        const tokens = buildTokens(data)
        window.localStorage.setItem('auth.tokens', JSON.stringify(tokens))
        setIsAuthenticated(true)
        return tokens.accessToken
      })
      .catch((error) => {
        setLoading(false)
        window.localStorage.removeItem('auth.tokens')
        setIsAuthenticated(false)
        throw error
      })

    return refreshPromises[tokens.refreshToken]
  }, [])

  const buildTokens = useCallback(
    (data: TokenResponse): Tokens => ({
      accessToken: data.token_type + ' ' + data.access_token,
      expires: new Date().getTime() + (data.expires_in - 5) * 1000,
      refreshToken: data.refresh_token,
    }),
    [],
  )

  const contextValue = useMemo(
    (): AuthContextValue => ({
      isAuthenticated,
      getToken,
      login,
      logout,
      loading,
      error,
    }),
    [isAuthenticated, getToken, login, logout, loading, error],
  )

  return <AuthContext.Provider value={contextValue}>{children}</AuthContext.Provider>
}

export default AuthProvider
