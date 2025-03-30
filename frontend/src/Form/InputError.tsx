export default function InputError({ error }: { error: string | null | undefined }) {
  return (
    <>
      {error ? (
        <div className="input-error" data-testid="violation">
          {error}
        </div>
      ) : null}
    </>
  )
}
