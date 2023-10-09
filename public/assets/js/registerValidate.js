// $(document).ready(function() {
//     $('#registerForm').validate({
//       rules: {
//         name: {
//           required: true
//         },
//         email: {
//           required: true,
//           email: true
//         },
//         password: {
//           required: true,
//           minlength: 6
//         },
//         password_confirmation: {
//           required: true,
//           equalTo: '#password'
//         },
//         contact: {
//           required: true
//         },
//         address: {
//           required: true
//         },
//         gender: {
//           required: true
//         },
//         country: {
//           required: true
//         },
//         'hobbies[]': {
//           required: true
//         },
//         skills: {
//           required: true
//         }
//       },
//       messages: {
//         name: 'Please enter your full name',
//         email: {
//           required: 'Please enter your email address',
//           email: 'Please enter a valid email address'
//         },
//         password: {
//           required: 'Please enter a password',
//           minlength: 'Your password must be at least {0} characters long'
//         },
//         password_confirmation: {
//           required: 'Please confirm your password',
//           equalTo: 'Please enter the same password as above'
//         },
//         contact: 'Please enter your contact number',
//         address: 'Please enter your address',
//         gender: 'Please select your gender',
//         country: 'Please select your country',
//         'hobbies[]': 'Please select at least one hobby',
//         skills: 'Please enter your skills'
//       },
//       errorElement: "span",
//       errorPlacement: function (error, element) {
//           // Custom error placement in Bootstrap form
//           error.addClass("invalid-feedback");
//           element.closest(".error-msg").append(error);
//       },
//       highlight: function (element, errorClass, validClass) {
//           $(element).closest(".error-msg").addClass("is-invalid").removeClass("is-valid");
//       },
//       unhighlight: function (element, errorClass, validClass) {
//           $(element).closest(".error-msg").removeClass("is-invalid").addClass("is-valid");
//       }
//     });
//   });

$(document).ready(function() {
  $('#registerForm').validate({
    rules: {
      name: {
        required: true
      },
      email: {
        required: true,
        email: true
      },
      password: {
        required: true,
        minlength: 6
      },
      password_confirmation: {
        required: true,
        equalTo: '#password'
      },
      contact: {
        required: true
      },
      address: {
        required: true
      },
      gender: {
        required: true
      },
      country: {
        required: true
      },
      'hobbies[]': {
        required: true
      },
      skills: {
        required: true
      }
    },
    messages: {
      name: 'Please enter your full name',
      email: {
        required: 'Please enter your email address',
        email: 'Please enter a valid email address'
      },
      password: {
        required: 'Please enter a password',
        minlength: 'Your password must be at least {0} characters long'
      },
      password_confirmation: {
        required: 'Please confirm your password',
        equalTo: 'Please enter the same password as above'
      },
      contact: 'Please enter your contact number',
      address: 'Please enter your address',
      gender: 'Please select your gender',
      country: 'Please select your country',
      'hobbies[]': 'Please select at least one hobby',
      skills: 'Please enter your skills'
    },
    errorPlacement: function(error, element) {
      if (element.attr('type') === 'radio' || element.attr('type') === 'checkbox') {
        error.appendTo(element.closest('.gender-container').length ? '.gender-container' : '.hobby-container');
      } else {
        error.appendTo(element.closest('.input-group').length ? element.closest('.input-group') : element.parent());
      }
    },
    errorElement: 'span',
    errorClass: 'invalid-feedback',
    highlight: function(element, errorClass, validClass) {
      $('.error').addClass(errorClass).removeClass(validClass);
      $('.error').closest('.input-group').addClass('is-invalid');
    },
    unhighlight: function(element, errorClass, validClass) {
      $('.error').removeClass(errorClass).addClass(validClass);
      $('.error').closest('.input-group').removeClass('is-invalid');
    }
  });
});
