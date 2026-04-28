$(document).ready(function () {

  function checkPasswordMatch() {
    let pass = $("input[name='password']").val();
    let confirmPass = $("input[name='confirm-password']").val();

    if (confirmPass.length === 0) {
      $("#pass-error").addClass("d-none");
      return true;
    }

    if (pass !== confirmPass) {
      $("#pass-error").removeClass("d-none");
      return false;
    } else {
      $("#pass-error").addClass("d-none");
      return true;
    }
  }

  // check while typing
  $("input[name='password'], input[name='confirm-password']").on("keyup", function () {
    checkPasswordMatch();
  });

  // block form submit if mismatch
  $("form").on("submit", function (e) {
    if (!checkPasswordMatch()) {
      e.preventDefault();
    }
  });
});
