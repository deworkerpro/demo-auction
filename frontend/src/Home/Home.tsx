import React from 'react'
import styles from './Home.module.css'
import System from '../Layout/System'
import { Link } from 'react-router-dom'
import { useAuth } from '../OAuth/Provider'

export default function Home(): React.JSX.Element {
  const { isAuthenticated, login, logout } = useAuth()

  return (
    <System>
      <h1>Auction</h1>
      <p>We are here</p>
      <p className={styles.links}>
        {isAuthenticated ? (
          <button type="button" data-testid="logout-button" onClick={() => logout()}>
            Log Out
          </button>
        ) : (
          <>
            <Link to="/join" data-testid="join-link">
              Join
            </Link>
            <button type="button" data-testid="login-button" onClick={() => login()}>
              Log In
            </button>
          </>
        )}
      </p>
    </System>
  )
}
