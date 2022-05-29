<?php

/**
 * Load plugin's translated strings (Localization).
 * @Hook plugins_loaded
 */
function learningaid_load_plugin_textdomain_localization()
{
    load_plugin_textdomain(LEARNINGAID_DOMAIN, false, LEARNINGAID_PLUGIN_BASE_NAME . '/languages');
}
add_action('plugins_loaded', 'learningaid_load_plugin_textdomain_localization');
