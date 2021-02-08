import React from 'react'
import styles from './JoinForm.module.css'

function JoinForm() {
  return (
    <div data-testid="join-form" className={styles.joinForm}>
      <form className="form" method="post">
        <div className="input-row">
          <label htmlFor="email" className="input-label">
            Email
          </label>
          <input
            id="email"
            name="email"
            type="email"
            value="mail@app.test"
            required
          />
        </div>
        <div className="input-row has-error">
          <label htmlFor="password" className="input-label">
            Password
          </label>
          <input
            id="password"
            name="password"
            type="password"
            value="pas"
            required
          />
          <div className="input-error">The value is too short.</div>
        </div>
        <div className="input-row">
          <label>
            <input name="agree" type="checkbox" required />
            <small>I agree with privacy policy</small>
          </label>
        </div>
        <div className="button-row">
          <button type="submit">Join to Us</button>
        </div>
      </form>
    </div>
  )
}

export default JoinForm
