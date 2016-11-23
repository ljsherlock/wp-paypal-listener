# Paypal Listener for Wordpress

[https://github.com/paypal/ipn-code-samples](https://github.com/paypal/ipn-code-samples)

Listens for Paypal __POST__ variable sent after user completes payment ( or fails ), and renders based on the result ( Payment Confirmed/Unconfirmed ).


## The listener URL needs to be added to the paypal account

Log in to your PayPal business account and specify the Notification URL to your IPN listener. For detailed instructions about how to specify the Notification URL, see Identifying your IPN listener to PayPal. As an example, the web server where you host a PHP listener might resemble the following URL:


In addition to enabling the IPN service and setting the Notification URL location through your PayPal account, you can also set the location using the NOTIFYURL parameter in an API call. By dynamically setting the Notification URL, you can set up different listeners for different needs (such as if you are supporting different merchant sites with a single PayPal account).
