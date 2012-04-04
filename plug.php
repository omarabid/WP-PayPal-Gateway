<?php
/*
 Plugin Name: WPTuts PayPal Plugin
 Plugin URI: http://wp.tutsplus.com
 Description: WPTuts PayPal Tutorial Plugin
 Author: Abid Omar
 Version: 1.0
 */

// Adds the PayPal Payment Page to the menu
add_action('admin_menu', 'wptuts_add_menu');
function wptuts_add_menu()
{
    add_options_page('PayPal Test', 'PayPal Test', 'manage_options', 'wptuts-paypal-test', 'wptuts_display_page');
}

// Loads JavaScript and CSS files
add_action('admin_print_scripts', 'pp_enqueue_scripts');
function pp_enqueue_scripts()
{
    wp_register_script('pp_core_script', plugins_url('/files/core.js', __FILE__));
    wp_enqueue_script('pp_core_script');
}

add_action('admin_print_styles', 'pp_enqueue_styles');
function pp_enqueue_styles()
{
    wp_register_style('pp_core_style', plugins_url('/files/style.css', __FILE__));
    wp_enqueue_style('pp_core_style');
}

// Display the Test Page Interface
function wptuts_display_page()
{
    print <<<end
  <div class="wrap">
    <div id="icon-options-general" class="icon32"><br></div>
    <h2>PayPal Test</h2>
    <div class="input">
    <h3>Common Settings</h3>
    <table class="form-table">
      <tbody>
        <tr>
          <td><label for="paypal_setec_version">Version</label></td>
          <td><input id="paypal_setec_version"/></td>
        </tr>
        <tr>
          <td><label for="paypal_setec_user">User</label></td>
          <td><input id="paypal_setec_user"/></td>
        </tr>
        <tr>
          <td><label for="paypal_setec_password">Password</label></td>
          <td><input id="paypal_setec_password"/></td>
        </tr>
        <tr>
          <td><label for="paypal_setec_signature">Signature</label></td>
          <td><input id="paypal_setec_signature"/></td>
        </tr>
      </tbody>
    </table>
    <h3>setExpressCheckOut</h3>
    <table class="form-table">
      <tbody>
        <tr>
          <td><label for="paypal_setec_amount">Amount</label></td>
          <td><input id="paypal_setec_amount" /></td>
        </tr>
        <tr>
          <td><label for="paypal_setec_currency">Currency Code</label></td>
          <td><input id="paypal_setec_currency" /></td>
        </tr>
        <tr>
          <td><label for="paypal_setec_returnurl">Return URL</label></td>
          <td><input id="paypal_setec_returnurl" /></td>
        </tr>
        <tr>
          <td><label for="paypal_setec_cancelurl">Cancel URL</label></td>
          <td><input id="paypal_setec_cancelurl" /></td>
        </tr>
        <tr>
          <td><label for="paypal_setec_paymentaction">Payment Action</label></td>
          <td><input id="paypal_setec_paymentaction" /></td>
        </tr>
      </tbody>
    </table>
    <p class="submit">
      <a href="#" class="button-secondary" id="paypal_setec_send">Send</a>
    </p>
    <h3>getExpressCheckOut</h3>
    <table class="form-table">
      <tbody>
        <tr>
          <td><label for="paypal_getec_token">Token</label></td>
          <td><input id="paypal_getec_token" /></td>
        </tr>
      </tbody>
    </table>
    <p class="submit">
      <a href="#" class="button-secondary" id="paypal_getec_send">Send</a>
    </p>
    <h3>doExpressCheckOut</h3>
    <table class="form-table">
      <tbody>
        <tr>
          <td><label for="paypal_doec_token">Token</label></td>
          <td><input id="paypal_doec_token" /></td>
        </tr>
        <tr>
          <td><label for="paypal_doec_payerid">Payer ID</label></td>
          <td><input id="paypal_doec_payerid" /></td>
        </tr>
        <tr>
          <td><label for="paypal_doec_amount">Amount</label></td>
          <td><input id="paypal_doec_amount" /></td>
        </tr>
        <tr>
          <td><label for="paypal_doec_currency">Currency Code</label></td>
          <td><input id="paypal_doec_currency" /></td>
        </tr>
        <tr>
          <td><label for="paypal_doec_paymentaction">Payment Action</label></td>
          <td><input id="paypal_doec_paymentaction" /></td>
        </tr>
      </tbody>
    </table>
    <p class="submit">
      <a href="#" class="button-secondary" id="paypal_doec_send">Send</a>
    </p>
    </div>
    <div class="response">
      <h3>Response</h3>
      <p id="display_debug_info">
      </p>
    </div>
  </div>
end;
}

// Handle AJAX Requests
// SetExpressCheckOut
add_action('wp_ajax_pp_setec', 'pp_ajax_setec');
function pp_ajax_setec()
{
    global $_POST;
    require_once ('paypal.php');
    $paypal = new wp_paypal_gateway (true);
    $paypal->version = $_POST['common']['version'];
    $paypal->user = $_POST['common']['user'];
    $paypal->password = $_POST['common']['password'];
    $paypal->signature = $_POST['common']['signature'];
    $param = array(
        'amount' => $_POST['setec']['amount'],
        'currency_code' => $_POST['setec']['currency'],
        'return_url' => $_POST['setec']['returnurl'],
        'cancel_url' => $_POST['setec']['cancelurl'],
        'payment_action' => $_POST['setec']['paymentaction']
    );
    if ($paypal->setExpressCheckout($param)) {
        var_dump($paypal->getResponse());
    } else {
        var_dump($paypal->debug_info);
    }
    die();
}

// GetExpressCheckOut
add_action('wp_ajax_pp_getec', 'pp_ajax_getec');
function pp_ajax_getec()
{
    global $_POST;
    require_once ('paypal.php');
    $paypal = new wp_paypal_gateway (true);
    $paypal->version = $_POST['common']['version'];
    $paypal->user = $_POST['common']['user'];
    $paypal->password = $_POST['common']['password'];
    $paypal->signature = $_POST['common']['signature'];
    $param = array(
        'amount' => $_POST['getec']['token']
    );
    if ($paypal->getExpressCheckout($param)) {
        print_r($paypal->getResponse());
    } else {
        print_r($paypal->debug_info);
    }
    die();
}

// DoExpressCheckOut
add_action('wp_ajax_pp_doec', 'pp_ajax_doec');
function pp_ajax_doec()
{
    global $_POST;
    require_once ('paypal.php');
    $paypal = new wp_paypal_gateway (true);
    $paypal->version = $_POST['common']['version'];
    $paypal->user = $_POST['common']['user'];
    $paypal->password = $_POST['common']['password'];
    $paypal->signature = $_POST['common']['signature'];
    $param = array(
        'amount' => $_POST['doec']['amount'],
        'currency_code' => $_POST['doec']['currency'],
        'payment_action' => $_POST['doec']['paymentaction'],
        'payer_id' => $_POST['doec']['payerid'],
        'token' => $_POST['doec']['token']
    );
    if ($paypal->doExpressCheckout($param)) {
        print_r($paypal->getResponse());
    } else {
        print_r($paypal->debug_info);
    }
    die();
}