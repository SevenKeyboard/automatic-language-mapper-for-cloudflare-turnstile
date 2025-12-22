<?php
/**
 * Plugin Name: Automatic Language Mapper for Simple Cloudflare Turnstile
 * Description: Forces Turnstile to use the current site language instead of the saved plugin setting. Supports WPML or the native determine_locale() function.
 * Version: 1.0.0
 * Author: SevenKeyboard
 * Author URI: https://sevenkeyboard.com
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Requires at least: 6.9
 * Tested up to: 6.9
 * Requires Plugins: simple-cloudflare-turnstile
 **/

add_action('plugins_loaded', function () {
   add_filter('pre_option_cfturnstile_language', function ($default) {
        if (
            is_admin() &&
            isset($_GET['page']) &&
            $_GET['page'] === 'cfturnstile'
        ) {
            return $default;
        }
        $locale = sctlf_get_current_locale();
        $turnstile_lang = sctlf_map_locale_to_turnstile_lang( $locale );
        return $turnstile_lang;
    }, 99);
}, 10);

function sctlf_get_current_locale() {
    if ( function_exists( 'apply_filters' ) && has_filter( 'wpml_current_language' ) ) {
        $lang_code = apply_filters( 'wpml_current_language', null );
        $languages = apply_filters( 'wpml_active_languages', null, ['skip_missing' => 0] );
        if ( ! empty( $lang_code ) && ! empty( $languages ) && ! empty( $languages[ $lang_code ]['default_locale'] ) ) {
            return $languages[ $lang_code ]['default_locale'];
        }
    }
    return determine_locale();
}
function sctlf_map_locale_to_turnstile_lang( $locale ) {
    // https://wp-kama.com/note/wp-locales-fill-list
    // https://developers.cloudflare.com/turnstile/reference/supported-languages/
    static $supported_languages = [
        'ar', 'bg', 'zh', 'hr', 'cs', 'da', 'nl', 'en', 'fa', 'fi',
        'fr', 'de', 'el', 'he', 'hi', 'hu', 'id', 'it', 'ja', 'ko',
        'lt', 'ms', 'nb', 'pl', 'pt', 'ro', 'ru', 'sr', 'sk', 'sl',
        'es', 'sv', 'tl', 'th', 'tr', 'uk', 'vi'
    ];
    $locale = strtolower($locale);
    if ( $locale === 'zh_hk' ) return 'zh_tw'; // 香港中文版
    if ( $locale === 'zh_tw' ) return 'zh-tw'; // 繁體中文
    $parts = explode('_', $locale);
    if ( ! isset($parts[0]) || $parts[0] === '' ) return 'auto';
    $lang = $parts[0];
    return in_array($lang, $supported_languages, true) ? $lang : 'auto';
}

/*
add_action('wp_footer', function () {
    echo "<div style='text-align:center; color:#888;'>[Turnstile Lang Override] ";
    echo "<strong>" . esc_html( get_option('cfturnstile_language') ) . "</strong>";
    echo " (" . esc_html( sctlf_get_current_locale() ) . ")</div>";
});
*/