<?php
/**
 * Plugin Name: Theme - Live Reload
 * Plugin URI: https://github.com/GotanDev/WordpressLiveReload
 * Description: Auto reload page in your browser when any active theme is modified. Activated only if WP_DEBUG mode is enabled.
 * Version: 0.1
 * Author: Gotan
 * Author URI: https://www.gotan.io/
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 **/
const LIVERELOAD_IP_OPTION_NAME = "livereload_allowed_ips";


/** Load script on front */
function isAllowedToLiveReload(): bool
{
    $ip = $_SERVER["REMOTE_ADDR"];
    if ($ip === "127.0.0.1") {
        return true;
    }
    $allowedIps = get_option(LIVERELOAD_IP_OPTION_NAME);
    foreach (explode(",", $allowedIps) as $i) {
        if(cidr_match($ip, $i)) {
            return true;
        }
    }
    return false;
}
/**
 * Validates subnet specified by CIDR notation.of the form IP address followed by
 * a '/' character and a decimal number specifying the length, in bits, of the subnet
 * mask or routing prefix (number from 0 to 32).
 *
 * @param $ip - IP address to check
 * @param $cidr - IP address range in CIDR notation for check
 * @return bool - true match found otherwise false
 */
function cidr_match($ip, $cidr): bool
{
    $outcome = false;
    $pattern = '/^(([01]?\d?\d|2[0-4]\d|25[0-5])\.){3}([01]?\d?\d|2[0-4]\d|25[0-5])\/(\d{1}|[0-2]{1}\d{1}|3[0-2])$/';
    if (preg_match($pattern, $cidr)){
        list($subnet, $mask) = explode('/', $cidr);
        if (ip2long($ip) >> (32 - $mask) == ip2long($subnet) >> (32 - $mask)) {
            $outcome = true;
        }
    }
    return $outcome;
}

if (WP_DEBUG) {
    if (!isAllowedToLiveReload()) {
        error_log(sprintf('[WARNING] Theme Live Reload Plugin: Your IP %s is not allowed to use livereload', $_SERVER["REMOTE_ADDR"]));

    } else {
        add_action('wp_enqueue_scripts', function () {
            wp_register_script('theme_live_reload', plugins_url('theme_livereload/livereload.js', 'theme_live_reload'));
            wp_enqueue_script('theme_live_reload');
        });
    }
}



/** IP Page Options */
function theme_livereload_settings() {
    register_setting("theme_livereload_settings", LIVERELOAD_IP_OPTION_NAME);
}

function theme_livereload_setting_page() {
    add_options_page('Live Reload Plugin', 'Live Reload ', 'manage_options', 'theme_livereload-setting-url', 'theme_livereload_html_form');
}
add_action('admin_init', "theme_livereload_settings");
add_action('admin_menu', 'theme_livereload_setting_page');


function theme_livereload_html_form() { ?>
    <div class="wrap" style="margin: 15px 0">

        <a style="display:block;float:right;margin-right: 15px;" href="https://github.com/GotanDev/WordpressLiveReload" target="blank"><img src="https://cdn3.iconfinder.com/data/icons/sociocons/128/github-sociocon.png" style="height: 64px;" alt="GitHub Project"/></a>
        <h2 style="font-size: 18pt;"><strong>Theme Live Reload</strong> - Settings</h2>

        <p style="border-left:3px solid #AAA;padding: 5px 10px">
            Live Reload plugin allows you to auto reload your website when your active theme is modified.<br />
            It will only activated when <code>WP_DEBUG</code> is set on <code>true</code>.
        </p>
        <form method="post" action="options.php">
            <?php settings_fields('theme_livereload_settings'); ?>
            <div class="form-field" style="margin-top: 10px;padding: 10px 15px; position:relative">
                <label style="display:block; font-weight:bold;" for="allowed_ips">Allowed IP addresses:</label>
                <input type = 'text' class="regular-text" id="allowed_ips" name="livereload_allowed_ips" value="<?php echo get_option(LIVERELOAD_IP_OPTION_NAME); ?>">
                <small style="margin-top: 15px; white-space: pre-line">
                    IPs addresses can be written
                    - either with full classical notation <code style="font-size: 8pt">X.X.X.X</code>
                    - or with the <a href="https://www.catalyst2.com/knowledgebase/networking/subnets-and-cidr/" target="_blank">CIDR notation</a> <code style="font-size: 8pt">X.X.X.X/Y</code>
                    You can add several IP addresses: they must be separated by commas.

                    The address <code style="font-size:8pt; font-weight:bold">127.0.0.1</code> is allowed by default.
                </small>
                <?php submit_button(); ?>
<?php
            if (!WP_DEBUG) {
?>
                <div style="position:absolute;top:0;left:0;width:100%;height:100%;background-color:#FAFAFADD;display:flex;justify-content: center;align-content: center"><p style="margin-top: 90px;color: orange">Settings are not available until you'll activate <code>WP_DEBUG</code>.</p></div>
<?php
            }
?>
            </div>


            <hr />
            <div style="width:50%;display:inline-block;float:right">
                <img src="https://cdn3.iconfinder.com/data/icons/sociocons/128/github-sociocon.png" style="display:inline-block;margin-top:3px;margin-right: 10px;height: 16px"/>
                You can report any issue and ask for change on <a href="https://github.com/GotanDev/WordpressLiveReload" target="_blank">GitHub Project</a>. <br />
            </div>
            <img src="https://freeiconshop.com/wp-content/uploads/edd/person-solid.png" style="display:inline-block;margin-top:3px;margin-right: 10px;height: 16px"/>
            Made with Love from French Alps by <a target="blank" href="https://damiencuvillier.com">Damien Cuvillier</a> - <a target="_blank" href="https://gotan.io">Gotan</a> <br />


            <img src="https://freeiconshop.com/wp-content/uploads/edd/coffee-outline-filled.png" style="display:inline-block;margin-top:3px;margin-right: 10px;height: 16px"/>
            If you find this plugin useful, you can <a href="https://www.buymeacoffee.com/damq" target="_blank">buy me a coffee</a>.
    </div>
<?php } ?>
