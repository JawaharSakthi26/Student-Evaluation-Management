  $(document).ready(function(){
  $('#loginValidate').validate({
      rules: {
        email: {
          required: true,
          email: true,
        },
        password: {
          required: true,
          // minlength: 5
        }
      },
      messages: {
        email: {
          required: "Please enter your email address",
          email: "Please enter your valid email address"
        },
        password: {
          required: "Please provide a password",
          // minlength: "Your password must be at least 5 characters long"
        }
      },
      errorElement: 'div',
      errorPlacement: function (error, element) {
        error.addClass('invalid-feedback');
        element.closest('.input-group').append(error);
      },
      highlight: function (element, errorClass, validClass) {
        $(element).addClass('is-invalid');
      },
      unhighlight: function (element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
      }
    });
  });