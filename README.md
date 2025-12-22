# Automatic Language Mapper for Simple Cloudflare Turnstile
Contributors: sevenkeyboard  
Tags: cloudflare, turnstile, captcha, language, multilingual  
Requires at least: 6.9  
Tested up to: 6.9  
Stable tag: 1.0.0  
License: GPLv2 or later  
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Automatically maps the Cloudflare Turnstile widget language to the current site locale.

## Description

This plugin forces Cloudflare Turnstile to use the current site language instead of the saved plugin setting.

It is especially useful for multilingual sites using WPML, but it also works on single-language sites by relying on WordPress core locale detection via `determine_locale()`.

Features:

- Dynamically maps the current locale to a supported Turnstile language.
- Respects the Simple Cloudflare Turnstile settings screen (does not override values there).
- Works automatically with no additional settings.
- Falls back to WordPress core locale when WPML is not available.

Requirements:

- The plugin "[Simple Cloudflare Turnstile](https://wordpress.org/plugins/simple-cloudflare-turnstile/)" must be installed and active.
- Optionally, WPML for advanced multilingual setups.

## Installation

1. Install and activate the "[Simple Cloudflare Turnstile](https://wordpress.org/plugins/simple-cloudflare-turnstile/)" plugin.
2. Upload this plugin to the `/wp-content/plugins/` directory, or install it via the WordPress Plugins screen.
3. Activate **Automatic Language Mapper for Simple Cloudflare Turnstile** through the 'Plugins' screen.
4. No further configuration is required — Turnstile will automatically adapt to the current site language.

## Frequently Asked Questions

### Does this plugin require WPML?

No. If WPML is not installed, the plugin falls back to WordPress core `determine_locale()` to pick the current locale.

### Does this change any settings in Simple Cloudflare Turnstile?

No. It does not modify saved settings. It only filters the effective language when Turnstile reads the `cfturnstile_language` option.

### Are there any settings for this plugin?

No. The plugin has no settings screen. Once activated, it works automatically in the background.

### What happens if the current locale is not supported by Turnstile?

If the mapped language is not in the Turnstile supported list, the plugin returns `auto`, which lets Turnstile decide the best language.

## Changelog

### 1.0.0
- Initial release.

## Upgrade Notice

### 1.0.0
Initial release.
