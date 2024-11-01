=== Plugin Name ===
Contributors: anshulsojatia
Donate link: http://geek.grapable.com
Tags: login security, brute-force attacks
Requires at least: 3.0.1
Tested up to: 3.9.1
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Secures your wp-admin and wp-login pages using an authorization key. Shows 404 to non-logged in users for secure areas.

== Description ==

Protect Wordpress Admin is a plugin that helps wordpress administrators to hide wp-admin and wp-login pages from non-logged in users. The result is that in case of brute force attacks, the protected login pages will return 404 error. 

In order to access the admin area, the users will have to go to wp-login page which in turn requires some authorization key and values passed as parameters to wp-login.php. Then only login page is available. In all the other cases, only 404 page is shown.

== Installation ==

The plugin follows standard wordpress method to upload plugin.

e.g.
1. Download the plugin from repository.
2. Upload the plugin zip file from wordpress admin or to /wp-content/plugins/ using ftp and extract it.
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Configure the plugin through settings menu. Remember you must set Enable = yes from plugin's settings for plugin to work.

== Frequently Asked Questions ==

= What do Admin Authorization Key and Admin Authorization Value mean? =

The key and value are the pair that you pass to wp-login.php page to access login page. e.g. If you have set authorization key as 'authkey' and authorization value as '12345' then in order to access the login page you should type http://your-site-url/wp-login.php?authkey=12345. Without these the url will return a 'page not found' error.

= I forgot my key and value pair. What to do now? =

1. Access your wordpress databases from phpMyAdmin or other client. 
2. Find the table *_options (e.g. wp_options)
3. Execute the sql query "UPDATE wp_options SET value = 'no' WHERE option_name='_protect_admin_enabled'". This will disable the plugin.

== Upgrade Notice ==

No notice yet

== Screenshots ==

No screenshots

== Changelog ==

= 1.0 =
* The first release of plugin.