# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.0.0] - 2025-03-30

### Added

- Role resource with full CRUD and searchable, bulk-toggleable permission checkbox matrix
- Permission resource with list view, role badge column, and role filter
- Manage Permissions overview page with sync button and role/permission summary cards
- `permissions:sync` artisan command for syncing enum definitions to database
- Roles and Permissions relation managers for user resources
- Configurable access control via closure or automatic first-role detection
- Configurable navigation group, sort order, and icon
- Publishable config, views, and translations
- Hungarian (hu) and English (en) translations
- Multi-tenancy support (resources are excluded from tenant scoping)
- Contracts (`PermissionEnum`, `RoleEnum`) for type-safe enum integration
