import React, { useState } from 'react'
import styles from './JoinForm.module.css'

function JoinForm() {
  const [formData, setFormData] = useState({
    email: '',
    password: '',
    agree: false,
  })

  const handleChange = (event) => {
    const input = event.target
    setFormData({
      ...formData,
      [input.name]: input.type === 'checkbox' ? input.checked : input.value,
    })
  }

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
            value={formData.email}
            onChange={handleChange}
            required
          />
        </div>
        <div className="input-row">
          <label htmlFor="password" className="input-label">
            Password
          </label>
          <input
            id="password"
            name="password"
            type="password"
            value={formData.password}
            onChange={handleChange}
            required
          />
        </div>
        <div className="input-row">
          <label>
            <input
              name="agree"
              type="checkbox"
              checked={formData.agree}
              onChange={handleChange}
              required
            />
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
