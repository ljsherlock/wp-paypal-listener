<?php

//require('../includes/exam.php');
//require('./wp-blog-header.php' );
// require('./wp-load.php');
if( file_exists('paypal/PaypalIPN.php') )
{
    require('paypal/PaypalIPN.php');
}

// $_POST = 'residence_country=US&invoice=abc1234&address_city=San+Jose&first_name=John&payer_id=TESTBUYERID01&mc_fee=0.44&txn_id=421462822&receiver_email=seller%40paypalsandbox.com&custom=xyz123+CUSTOMHASH&payment_date=12%3A40%3A25+27+Aug+2013+PDT&address_country_code=US&address_zip=95131&item_name1=something&mc_handling=2.06&mc_handling1=1.67&tax=2.02&address_name=John+Smith&last_name=Smith&receiver_id=seller%40paypalsandbox.com&verify_sign=AFcWxV21C7fd0v3bYYYRCpSSRl31AgAAjEU7A5rthY2aP4j1jOIrjuGx&address_country=United+States&payment_status=Completed&address_status=confirmed&business=seller%40paypalsandbox.com&payer_email=buyer%40paypalsandbox.com&notify_version=2.4&txn_type=cart&test_ipn=1&payer_status=verified&mc_currency=USD&mc_gross=12.34&mc_shipping=3.02&mc_shipping1=1.02&item_number1=AK-1234&address_state=CA&mc_gross1=9.34&payment_type=instant&address_street=123%2C+any+street';

/*
 * More detailed breakout of the raw data
  _POST EXAMPLE ARRAY FROM PAYPAL:
  Array
  (
  [residence_country] => US
  [invoice] => abc1234
  [address_city] => San Jose
  [first_name] => John
  [payer_id] => TESTBUYERID01
  [mc_fee] => 0.44
  [txn_id] => 421462822
  [receiver_email] => seller@paypalsandbox.com
  [custom] => xyz123 CUSTOMHASH
  [payment_date] => 12:40:25 27 Aug 2013 PDT
  [address_country_code] => US
  [address_zip] => 95131
  [item_name1] => something
  [mc_handling] => 2.06
  [mc_handling1] => 1.67
  [tax] => 2.02
  [address_name] => John Smith
  [last_name] => Smith
  [receiver_id] => seller@paypalsandbox.com
  [verify_sign] => AFcWxV21C7fd0v3bYYYRCpSSRl31AgAAjEU7A5rthY2aP4j1jOIrjuGx
  [address_country] => United States
  [payment_status] => Completed
  [address_status] => confirmed
  [business] => seller@paypalsandbox.com
  [payer_email] => buyer@paypalsandbox.com
  [notify_version] => 2.4
  [txn_type] => cart
  [test_ipn] => 1
  [payer_status] => unverified
  [mc_currency] => USD
  [mc_gross] => 12.34
  [mc_shipping] => 3.02
  [mc_shipping1] => 1.02
  [item_number1] => AK-1234
  [address_state] => CA
  [mc_gross1] => 9.34
  [payment_type] => instant
  [address_street] => 123, any street
  )
 */

Redwire\Exam::__init__();

$ipn = new PayPalIPN();
// // // Use the sandbox endpoint during testing.
$ipn->useSandbox();
//$verified = $ipn->verifyIPN();
$verified = true;

if ($verified) {
    /*
     * Process IPN
     * A list of variables is availablse here:
     * https://developer.paypal.com/webapps/developer/docs/classic/ipn/integration-guide/IPNandPDTVariables/
     */
     $payment_status = $_POST['payment_status'];
     $payment_status = 'Completed';

     if($payment_status == 'Completed') {
         Redwire\Exam::set_user_extra_value(20, 'CONFIRMED');
         Redwire\Exam::paypal_payment();
         get_header('course_header');
         echo '<div id="main-content">';
            echo '<div class="exam-header centered">';
                echo '<h1>Payment successful</h1>';
                echo '<p><a href="/mark-scheme" class="exam-button">Continune</a></p>';
            echo '</div>';
        echo '</div>';
        echo '</div>'; //content

         get_footer();
     } else {
         // update DB
         Redwire\Exam::set_user_extra_value(20, 'FAILED');
         get_header('course_header');
         echo '<div id="main-content">';
            echo '<div class="exam-header centered">';
                echo '<h1>Payment Failed</h1>';
                echo '<p><a href="/mark-scheme" class="exam-button">Continune</a></p>';
            echo '</div>';
        echo '</div>';
        echo '</div>'; //content
         get_footer();

         $to = 'lewis@redwiredesign.com';
         $subject = 'Paypal IPN Invalid';
         $body = 'Website: ' + bloginfo('name');
         $headers = array('Content-Type: text/html; charset=UTF-8');
         wp_mail( $to, $subject, $body, $headers );
     }
//
} else {
    // IPN invalid, log for manual investigation
    get_header('course_header');
    echo "The response from IPN was: <b>" .$res ."</b>";
    Redwire\Exam::set_user_extra_value(20, 'FAILED');
    get_footer();
}

// Reply with an empty 200 response to indicate to paypal the IPN was received correctly.
header("HTTP/1.1 200 OK");
