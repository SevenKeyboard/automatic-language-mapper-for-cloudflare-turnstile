<?php
/**
 * Plugin Name: Automatic Language Mapper for Simple Cloudflare Turnstile
 * Description: Forces Turnstile to use the current site language instead of the saved plugin setting. Supports <strong>WPML</strong> or the native <code>determine_locale()</code> function.
 * Version: 1.0.0
 * Author: SevenKeyboard
 * Author URI: https://sevenkeyboard.com
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Requires at least: 6.9
 * Tested up to: 6.9
 * Requires Plugins: simple-cloudflare-turnstile
 **/

defined( 'ABSPATH' ) || exit;

add_action( 'plugins_loaded', function() {
    add_filter( 'pre_option_cfturnstile_language', function ( $default ) {
        $page = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : '';
        if ( is_admin() && 'cfturnstile' === $page ) {
            return false;
        }
        $locale         = almfct_get_current_locale();
        $turnstile_lang = almfct_map_locale_to_turnstile_lang( $locale );
        return $turnstile_lang;
    }, 99 );
}, 10 );

function almfct_get_current_locale() {
    if ( function_exists( 'apply_filters' ) && has_filter( 'wpml_current_language' ) ) {
        $lang_code = apply_filters( 'wpml_current_language', null );
        $languages = apply_filters( 'wpml_active_languages', null, [ 'skip_missing' => 0 ] );
        if ( ! empty( $lang_code )
            && ! empty( $languages )
            && ! empty( $languages[ $lang_code ]['default_locale'] )
        ) {
            return $languages[ $lang_code ]['default_locale'];
        }
    }
    return determine_locale();
}

function almfct_map_locale_to_turnstile_lang( $locale ) {
    // https://translate.wordpress.org/
    // https://wp-kama.com/note/wp-locales-fill-list
    // https://developers.cloudflare.com/turnstile/reference/supported-languages/
    static $supported_languages = [
        'ar', 'bg', 'zh', 'hr', 'cs', 'da', 'nl', 'en', 'fa', 'fi',
        'fr', 'de', 'el', 'he', 'hi', 'hu', 'id', 'it', 'ja', 'tlh',
        'ko', 'lt', 'ms', 'nb', 'pl', 'pt', 'ro', 'ru', 'sr', 'sk',
        'sl', 'es', 'sv', 'tl', 'th', 'tr', 'uk', 'vi'
    ];
    $locale = strtolower( (string) $locale );
    $locale = str_replace( '-', '_', $locale );
    
    // Traditional Chinese.
    if ( $locale === 'zh_hk' ) return 'zh-tw'; // 香港中文.
    if ( $locale === 'zh_mo' ) return 'zh-tw';
    if ( $locale === 'zh_tw' ) return 'zh-tw'; // 繁體中文.
    if ( str_starts_with( $locale, 'zh_hant' ) ) return 'zh-tw';

    $parts = explode( '_', $locale );
    if ( ! isset( $parts[0] ) || $parts[0] === '' ) return 'auto';
    $lang = $parts[0];
    return in_array( $lang, $supported_languages, true ) ? $lang : 'auto';
}

/*
add_action('wp_footer', function() {
    echo "<div style='text-align:center; color:#888;'>[Turnstile Lang Override] ";
    echo "<strong>" . esc_html( get_option('cfturnstile_language') ) . "</strong>";
    echo " (" . esc_html( almfct_get_current_locale() ) . ")</div>";
});
*/