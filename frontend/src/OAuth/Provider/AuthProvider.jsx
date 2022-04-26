import React, { useCallback, useEffect, useMemo, useState } from 'react'
import PropTypes from 'prop-types'
import AuthContext from './AuthContext'
import {
  generateCodeChallenge,
  generateCodeVerifier,
  generateState,
} from './crypt'

function AuthProvider({
  authorizeUrl,
  tokenUrl,
  clientId,
  scope,
  redirectPath,
  children,
}) {
  const [isAuthenticated, setIsAuthenticated] = useState(
    window.localStorage.getItem('auth.tokens') !== null
  )

  const [loading, setLoading] = useState(false)

  const isAuthRedirect = window.location.pathname === redirectPath

  const query = new URLSearchParams(window.location.search)

  const getStateError = () => {
    if (!query.get('state')) {
      return 'Empty state.'
    }
    if (query.get('state') !== window.localStorage.getItem('auth.state')) {
      return 'Invalid state.'
    }
    return null
  }

  const getAuthRedirectError = () => {
    return (
      query.get('hint') || query.get('error_description') || query.get('error')
    )
  }

  const [error, setError] = useState(
    isAuthRedirect ? getStateError() || getAuthRedirectError() : null
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

    fetch(tokenUrl, {
      method: 'POST',
      headers: {
        Accept: 'application/json',
        'Content-type': 'application/x-www-form-urlencoded',
      },
      body: new URLSearchParams({
        client_id: clientId,
        code_verifier: window.localStorage.getItem('auth.code_verifier'),
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
        const data = await response.json()

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
          setError(
            data.hint || data.error_description || data.error || data.message
          )
          return
        }

        setError(error.statusText)
      })
  }, [])

  useEffect(() => {
    const listener = (e) => {
      if (e.key === 'auth.tokens') {
        setIsAuthenticated(JSON.parse(e.newValue) !== null)
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

  const refreshPromises = useMemo(() => ({}), [])

  const getToken = useCallback(async () => {
    const tokens = JSON.parse(window.localStorage.getItem('auth.tokens'))

    if (tokens === null) {
      return null
    }

    if (tokens.expires > new Date().getTime()) {
      return tokens.accessToken
    }

    setLoading(true)

    if (refreshPromises[tokens.refreshToken]) {
      return await refreshPromises[tokens.refreshToken]
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
      .catch(() => {
        setLoading(false)
        window.localStorage.removeItem('auth.tokens')
        setIsAuthenticated(false)
        return null
      })

    return await refreshPromises[tokens.refreshToken]
  }, [])

  const buildTokens = useCallback(
    (data) => ({
      accessToken: data.token_type + ' ' + data.access_token,
      expires: (new Date().getTime() + (data.expires_in - 5) * 1000).toString(),
      refreshToken: data.refresh_token,
    }),
    []
  )

  const contextValue = useMemo(
    () => ({
      isAuthenticated,
      getToken,
      login,
      logout,
      loading,
      error,
    }),
    [isAuthenticated, getToken, login, logout, loading, error]
  )

  return (
    <AuthContext.Provider value={contextValue}>{children}</AuthContext.Provider>
  )
}

AuthProvider.propTypes = {
  authorizeUrl: PropTypes.string.isRequired,
  tokenUrl: PropTypes.string.isRequired,
  clientId: PropTypes.string.isRequired,
  scope: PropTypes.string.isRequired,
  redirectPath: PropTypes.string.isRequired,
  children: PropTypes.object,
}

export default AuthProvider
