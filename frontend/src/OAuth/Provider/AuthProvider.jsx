import React, { useCallback, useMemo } from 'react'
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

  const contextValue = useMemo(
    () => ({
      isAuthenticated: false,
      login,
      logout: () => {},
    }),
    [login]
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
