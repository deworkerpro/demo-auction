import React, { useMemo } from 'react'
import PropTypes from 'prop-types'
import AuthContext from './AuthContext'

function AuthProvider({
  authorizeUrl,
  tokenUrl,
  clientId,
  scope,
  redirectPath,
  children,
}) {
  const contextValue = useMemo(
    () => ({
      isAuthenticated: false,
      login: () => {},
      logout: () => {},
    }),
    []
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
