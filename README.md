# The Salt Ship

A static PHP website scaffold built for local development on WAMP or any PHP-enabled web server.

## Overview

`The Salt Ship` is a small web project with:
- a PHP front controller (`index.php`) that routes to page templates
- modular shared layout files in `includes/`
- reusable UI components in `components/`
- CSS and JavaScript assets under `assets/`
- data files for navigation and product/category content

## Features

- Responsive navigation with desktop and mobile menus
- Dedicated pages for:
  - Home
  - Products
  - Categories
  - Contact
- Clean separation of markup, assets, and shared layout logic
- Local data-driven content in `assets/data/`

## Getting Started

### Requirements

- PHP 7.4+ (or compatible PHP build included with WAMP)
- A local web server such as Apache

### Local Setup

1. Place the project folder in your WAMP `www` directory.
2. Start the Apache server.
3. Open the site in your browser at:
   - `http://localhost/thesaltship/`

### Customizing Base URL

The app computes `BASE_URL` automatically from the server document root, but you can adjust the base path in `includes/config.php` if needed.

## Project Structure

- `index.php` — root entry point
- `includes/` — configuration, templates, common functions, and shared markup
- `components/` — isolated UI components and widget styles
- `pages/` — individual page templates for content routing
- `assets/css/` — stylesheets and theme assets
- `assets/js/` — client-side scripts
- `assets/data/` — JSON content for navigation, categories, and products
- `assets/images/` — image assets used across the site

## Notes

- `includes/navbar.php` contains main navigation links.
- `includes/config.php` sets site constants such as `BASE_URL` and `CANONICAL_ORIGIN`.
- `components/` holds reusable page fragments and component-specific CSS.

## License

This repository is released under the MIT License. See `LICENSE` for details.
