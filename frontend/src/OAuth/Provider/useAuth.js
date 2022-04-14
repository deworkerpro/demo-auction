import AuthContext from './AuthContext'
import { useContext } from 'react'

export default function useAuth() {
  return useContext(AuthContext)
}
