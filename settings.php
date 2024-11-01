<?php
if (!current_user_can("manage_options")) {
    die("Access Denied");
}
$protect_admin_key = "authkey";
$protect_admin_value = "12345";
$enable = "no";
if (isset($_REQUEST['submit'])) {
    
    if (isset($_REQUEST['_protect_admin_key']))
        $protect_admin_key = $_REQUEST['_protect_admin_key'];
    
    if (isset($_REQUEST['_protect_admin_value']))
        $protect_admin_value = $_REQUEST['_protect_admin_value'];
    
    if (isset($_REQUEST['enable_plugin']))
        $enable = $_REQUEST['enable_plugin'];
    
    update_option('_protect_admin_key', $protect_admin_key);
    update_option('_protect_admin_value', $protect_admin_value);
    update_option('_protect_admin_enabled', $enable);
    $msg = 'Successfully saved the options';
}
else {
    $protect_admin_key = get_option("_protect_admin_key");
    $protect_admin_value = get_option("_protect_admin_value");
    $enable = get_option("_protect_admin_enabled");
    
    if($protect_admin_key == false)
        $protect_admin_key = "authkey";
    
    if($protect_admin_value == false)
        $protect_admin_value = "12345";
    
    if($enable == false)
        $enable = "no";
}
?>
<div class="wrap">
    <h2>Protect Admin Settings</h2>
    <div><br/><b><?php echo $msg; ?></b><br/></div>
    <form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post">

        <table class="form-table">            
            <tr>
                <th scope="row">Admin Authorization Key</th>
                <td><input type="text" name="_protect_admin_key" value="<?php echo $protect_admin_key ?>"/></td>
                <td>The key that will be passed in the url e.g. authkey, password or accesscode. The key should then be passed as parameter to url </td>
            </tr>
            <tr>
                <th scope="row">Admin Authorization Value</th>
                <td><input type="text" name="_protect_admin_value" value="<?php echo $protect_admin_value ?>" /></td>
                <td>The authorization value that'll be used to grant access to login page. You can use following placeholders to create dynamic authorization
                    <br/>
                    <ul>
                        <li>{DAY} - Represents day of today's date (01-31) in two digits</li>
                        <li>{MONTH} - Represents month of today's month (01-12) in two digits</li>
                        <li>{YEAR} - Represents year of today's date in four digits</li>
                    </ul>
                </td>
            </tr>
            <tr>
                <th scope="row">Enable</th>
                <td>
                    <select name='enable_plugin'>
                        <option value='yes' <?php echo $enable == "yes" ? "selected" : "" ?>>Yes</option>
                        <option value='no' <?php echo $enable == "no" ? "selected" : "" ?>>No</option>
                    </select>
                </td>
                <td style='color: red'>WARNING: MAKE SURE YOU REMEMBER YOUR AUTHORIZATION KEY AND VALUE. AFTER LOG OUT YOU WILL SEE A 404 IF KEY AND VALUE ARE NOT PASSED IN URL</td>
            </tr>
            <tr>
                <th></th>
                <td> <input type="submit" name="submit" class="button" value="Save Changes" /></td>
            </tr>
            <tr>
                <th>How to access login page?</th>
                <td colspan='2'>
                    The following example demonstrate how you can access login page after activating the plugin.
                    <div>
                        <h3>Example 1</h3>
                        Admin Authorization Key = authkey<br>
                        Admin Authorization Value = 12345<br>
                        Login Url = <?php bloginfo('url') ?>/wp-login.php?authkey=12345<br>
                    </div>
                    
                    <div>
                        <h3>Example 2</h3>
                        Admin Authorization Key = accesscode<br>
                        Admin Authorization Value = {YEAR}{MONTH}{DAY}<br>
                        Login Date = 1st February 2014<br>
                        Login Url = <?php bloginfo('url') ?>/wp-login.php?accesscode=20140201<br>
                    </div>
                </td>
            </tr>
        </table>    

       
    </form>