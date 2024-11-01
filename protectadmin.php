<?php

/*
  Plugin Name: Wordpress Protect Admin
  Description: Protects admin pages by displaying a 404 page unless an authorization key is passed along
  Version: 1.0
  Author: Anshul Sojatia | Apexol Technologies
  Author URI: http://apexol.com/
  License: GPL2
 */

global $pagenow;

class WordpressProtectAdmin {

    public function WordpressProtectAdmin() {
        $this->restrict_admin();
        add_action('admin_menu', array($this, "insert_admin_menu"));
        add_filter('logout_url', array($this, '_protected_logout'), 10, 2);
    }

    function insert_admin_menu() {
        add_options_page("Protect Admin Settings", "Protect Admin", "manage_options", "wordpress-protect-admin", array($this, '_settings_page'));
    }

    function _settings_page() {
        include("settings.php");
    }

    function _protected_logout($logout_url, $redirect_to) {        
        $protect_admin_key = get_option("_protect_admin_key");
        $protect_admin_value = get_option("_protect_admin_value");
        
        if (empty($protect_admin_key) || empty($protect_admin_value))
            return;

        $protect_admin_value = $this->_replaceValues($protect_admin_value);
        //$logout_url = $logout_url . "?redirect_to=" . $redirect . "&$protect_admin_key=$protect_admin_value";
        $logout_url = add_query_arg($protect_admin_key, $protect_admin_value, $logout_url);
        $logout_url = add_query_arg("redirect_to", get_bloginfo('url') . "/wp-login.php?$protect_admin_key=$protect_admin_value&loggedout=true", $logout_url);
        return $logout_url;
    }

    function _replaceValues($protect_admin_value) {
        $replacements = array(
            "{DAY}" => date("d"),
            "{MONTH}" => date("m"),
            "{YEAR}" => date("Y")
        );
        foreach ($replacements as $from => $to):
            $protect_admin_value = str_replace($from, $to, $protect_admin_value);
        endforeach;        
        return $protect_admin_value;
    }

    function restrict_admin() {

        if (!is_user_logged_in()) {
            $enable = get_option("_protect_admin_enabled");
            if ($enable == false || $enable == "no"):
                return;
            endif;
            $uri = $_SERVER['REQUEST_URI'];
            if (strpos($uri, 'wp-admin') !== FALSE && !(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')):
                //USER IS TRYING TO ACCESS WP-ADMIN WITHOUT LOGGING IN....BANG ON
                header("HTTP/1.0 404 Not Found");
                global $wp_query;
                $wp_query->set_404();
                $_404 = get_template_directory() . "/404.php";
                if (file_exists($_404)) {
                    include($_404);
                    exit;
                }
            endif;
            global $pagenow;
            if (!isset($_REQUEST['wp-submit'])) {
                if (!isset($_REQUEST['action']) || $_REQUEST['action'] !== 'logout') {
                    $protect_admin_key = get_option("_protect_admin_key");
                    $protect_admin_value = get_option("_protect_admin_value");

                    if (empty($protect_admin_key) || empty($protect_admin_value))
                        return;

                    $protect_admin_value = $this->_replaceValues($protect_admin_value);

                    if ($pagenow == 'wp-login.php' && (!isset($_REQUEST[$protect_admin_key]) || $_REQUEST[$protect_admin_key] != $protect_admin_value )) {
                        header("HTTP/1.0 404 Not Found");
                        global $wp_query;
                        $wp_query->set_404();
                        $_404 = get_template_directory() . "/404.php";
                        if (file_exists($_404)) {
                            include($_404);
                            exit;
                        }
                        exit;
                    }
                }
            }
        }
    }

}

add_action('init', 'initialize_wordpress_protect_admin');

function initialize_wordpress_protect_admin() {
    new WordpressProtectAdmin();
}
