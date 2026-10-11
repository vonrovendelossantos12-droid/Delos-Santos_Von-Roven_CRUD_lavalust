# Product Desk

React + Vite interface for the LavaLust product API.

## Run locally

1. Start the LavaLust API and configure its database and JWT environment variables as described in the repository README.
2. Copy `.env.example` to `.env.local` and set `VITE_API_BASE_URL` to the API URL ending in `/api`.
3. Run `npm install`, then `npm run dev`.

The app uses bearer tokens returned by `POST /login` and stores the active session in `sessionStorage`. Product data is accessed only through the LavaLust API.

## Deploy

Build with `npm run build` and publish the `dist/` directory from a static-site host. Set `VITE_API_BASE_URL` to the deployed API URL ending in `/api`, then add the exact frontend origin to the API's `API_ALLOWED_ORIGINS` environment variable.
