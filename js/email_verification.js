$(document).ready(function () {
  $('#email').on('blur', function () {
    const email = $(this).val();
    const msg = $('#email_msg');

    if (email === '') {
      msg.html('');
      $('#submit_btn').prop('disabled', true);
      return;
    }

    $.post('../utils/email_exists.php', { email: email }, function (res) {
      const data = JSON.parse(res);

      if (data.exists) {
        msg.html('<span style="color:red;">✖ Email already exists</span>');
        $('#submit_btn').prop('disabled', true);
      } else {
        msg.html('<span style="color:green;">✔ Email is valid</span>');
        $('#submit_btn').prop('disabled', false);
      }
    });
  });
});
