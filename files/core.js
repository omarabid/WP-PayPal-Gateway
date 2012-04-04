;
(function($) {
  $(document).ready(function() {
    var common_data = {
      version: $('#paypal_setec_version').val(),
      user: $('#paypal_setec_user').val(),
      password: $('#paypal_setec_password').val(),
      signature: $('#paypal_setec_signature').val()
    },
    setec_data = {
      amount: $('#paypal_setec_amount').val(),
      currency: $('#paypal_setec_currency').val(),
      returnurl: $('#paypal_setec_returnurl').val(),
      cancelurl: $('#paypal_setec_cancelurl').val(),
      paymentaction: $('#paypal_setec_paymentaction').val()
    },
    getec_data = {
      token: $('#paypal_getec_token').val()
    },
    doec_data = {
      token: $('#paypal_doec_token').val(),
      payerid: $('#paypal_doec_payerid').val(),
      amount: $('#paypal_doec_amount').val(),
      currency: $('#paypal_doec_currency').val(),
      paymentaction: $('#paypal_doec_paymentaction').val()
    },
    display_info = $('#display_debug_info');
    $('#paypal_setec_send').on('click', function(e) {
      e.preventDefault();
      var data = {
        action: 'pp_setec',
        common: common_data,
        setec: setec_data
      };
      console.info(common_data);
      $.post(ajaxurl, data, function(response) {
        display_info.html(response);
      });
    });
    $('#paypal_getec_send').on('click', function(e) {
      e.preventDefault();
      var data = {
        action: 'pp_getec',
        common: common_data,
        setec: getec_data
      };
      $.post(ajaxurl, data, function(response) {
        display_info.html(response);
      });
    });
    $('#paypal_doec_send').on('click', function(e) {
      e.preventDefault();
      var data = {
        action: 'pp_doec',
        common: common_data,
        setec: doec_data
      };
      $.post(ajaxurl, data, function(response) {
        display_info.html(response);
      });
    });
  });
})(jQuery);