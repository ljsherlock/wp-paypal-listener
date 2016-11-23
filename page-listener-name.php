<?php

 if( file_exists('paypal/PaypalIPN.php') )
 {
     require('paypal/PaypalIPN.php');
 }

 $ipn = new PayPalIPN();

 // Use the sandbox endpoint during testing.
 // DISABLE FOR PRODUCTION
 $ipn->useSandbox();

 // Determine validaity of IPN
 $verified = $ipn->verifyIPN();
 if ($verified) {
     /*
      * Process IPN
      * A list of variables is availablse here:
      * https://developer.paypal.com/webapps/developer/docs/classic/ipn/integration-guide/IPNandPDTVariables/
      */
      $payment_status = $_POST['payment_status'];
      $payment_status = 'Completed';

      // If payment is completed
      if($payment_status == 'Completed')
      {
          // make any data changes
          // render
          Timber::render( array('project/page/page-' . $post->post_name . '.twig', 'project/pages/page.twig' ), $context );
      }
       else // Anything else (Declined, Reversed etc)
      {
          // make any data changes
          // render
          Timber::render( array('project/page/page-' . $post->post_name . '.twig', 'project/pages/page.twig' ), $context );
      }

 }
 else // IPN invalid, log for manual investigation
 {
     $to = 'lewis@redwiredesign.com';
     $subject = 'Paypal IPN Invalid';
     $body = 'Website: ' + bloginfo('name');
     $headers = array('Content-Type: text/html; charset=UTF-8');
     wp_mail( $to, $subject, $body, $headers );
 }

 // Reply with an empty 200 response to indicate to paypal the IPN was received correctly.
 header("HTTP/1.1 200 OK");
