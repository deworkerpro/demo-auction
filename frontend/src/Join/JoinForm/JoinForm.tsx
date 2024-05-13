import React, { useState } from 'react'
import styles from './JoinForm.module.css'
import api, { parseError, parseErrors } from '../../Api'
import { AlertError, AlertSuccess } from '../../Alert'
import { ButtonRow, InputError, InputLabel, InputRow } from '../../Form'

function JoinForm(): React.JSX.Element {
  const [formData, setFormData] = useState({
    email: '',
    password: '',
    agree: false,
  })

  const [buttonActive, setButtonActive] = useState<boolean>(true)
  const [errors, setErrors] = useState<Record<string, string>>({})
  const [error, setError] = useState<string | null>(null)
  const [success, setSuccess] = useState<string | null>(null)

  const handleChange = (event: React.FormEvent<HTMLInputElement>) => {
    const input = event.currentTarget
    setFormData({
      ...formData,
      [input.name]: input.type === 'checkbox' ? input.checked : input.value,
    })
  }

  const handleSubmit = (event: React.SyntheticEvent) => {
    event.preventDefault()

    if (!formData.agree) {
      setErrors({ agree: 'Please agree with terms.' })
      return
    }

    setButtonActive(false)
    setErrors({})
    setError(null)
    setSuccess(null)

    api
      .post('/v1/auth/join', {
        email: formData.email,
        password: formData.password,
      })
      .then(() => {
        setSuccess('Confirm join by link in email.')
        setButtonActive(true)
      })
      .catch(async (error) => {
        setErrors(await parseErrors(error))
        setError(await parseError(error))
        setButtonActive(true)
      })
  }

  return (
    <div data-testid="join-form" className={styles.joinForm}>
      <AlertError message={error} />
      <AlertSuccess message={success} />

      {!success ? (
        <form className="form" method="post" onSubmit={handleSubmit}>
          <InputRow error={errors.email}>
            <InputLabel htmlFor="email" label="Email" />
            <input
              id="email"
              name="email"
              type="email"
              value={formData.email}
              onChange={handleChange}
              required
            />
            <InputError error={errors.email} />
          </InputRow>
          <InputRow error={errors.password}>
            <InputLabel htmlFor="password" label="Password" />
            <input
              id="password"
              name="password"
              type="password"
              value={formData.password}
              onChange={handleChange}
              required
            />
            <InputError error={errors.password} />
          </InputRow>
          <InputRow error={errors.agree}>
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
            <InputError error={errors.agree} />
          </InputRow>
          <ButtonRow>
            <button type="submit" data-testid="join-button" disabled={!buttonActive}>
              Join to Us
            </button>
          </ButtonRow>
        </form>
      ) : null}
    </div>
  )
}

export default JoinForm
