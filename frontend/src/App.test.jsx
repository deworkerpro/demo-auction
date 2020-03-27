import React from 'react';
import { render } from '@testing-library/react';
import App from './App';

test('renders app', () => {
  const { getByText } = render(<App />);
  const h1Element = getByText(/Auction/i);
  expect(h1Element).toBeInTheDocument();
});
