import React, { ReactNode } from 'react'
import AuthContext from './AuthContext'

type Props = {
  isAuthenticated: boolean
  children: ReactNode
}

function FakeAuthProvider({ isAuthenticated, children }: Props) {
  const contextValue = {
    isAuthenticated,
    getToken: () =>
      isAuthenticated ? Promise.resolve('token') : Promise.reject(new Error('Error')),
    login: () => null,
    logout: () => null,
    loading: false,
    error: null,
  }
  return <AuthContext.Provider value={contextValue}>{children}</AuthContext.Provider>
}

export default FakeAuthProvider
