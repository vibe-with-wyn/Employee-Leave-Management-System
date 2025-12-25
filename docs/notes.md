# Notes

## Deployment / Base Path
- The deployed app runs under:
  - `/employee-leave-management-system`
- When hardcoding links or redirects, ensure they include that base path.

## Hosting Behavior (InfinityFree)
- Caching can cause stale pages/assets.
  - Use a hard refresh (Ctrl+F5) when testing UI changes.

## Security/Access
- Manager pages should be accessed only when logged in as a manager.
- Logout endpoint:
  - `/employee-leave-management-system/backend/controllers/LogoutController.php?action=logout`

## Troubleshooting Quick List
- 404 for pages/assets: confirm the `/employee-leave-management-system` prefix is present.
- DB connection errors: verify DB credentials used by the backend connection code and that the schema is imported.
- CSRF token errors: refresh the page and retry the submission.
