<script type="text/javascript">
(function() {
  var form = document.getElementById("mad_mimi_signup_form"),
      submit = document.getElementById("webform_submit_button"),
      validEmail = /.+@.+\..+/,
      isValid;
      
  form.onsubmit = function(event) {
    validate();
    if(!isValid) {
      revalidateOnChange();
      return false;
    }
  };
  
  function validate() {
    isValid = true;
    emailValidation();
    fieldsValidation();
    listsValidation();
    updateFormAfterValidation();
  }

  function emailValidation() {
    var email = document.getElementById("signup_email");
    if(!validEmail.test(email.value)) {
      errorMessage(email);
      isValid = false;
    } else {
      removeErrorMessage(email);
    }
  }

  function fieldsValidation() {
    for(var i = 0; i < form.elements.length; ++i) {
      var input = form.elements[i];
      if(input.id == "signup_email") continue;
      if(input.className.indexOf("required") >= 0) {
        if(input.value == "") {
          errorMessage(input)
          isValid = false;
        } else {
          removeErrorMessage(input);
        }
      }
    }
  }

  function listsValidation() {
    var mainListsDiv = document.getElementById("signup_audience_lists");
    if(mainListsDiv && mainListsDiv.className.indexOf("required") >= 0) {
      var listSelected = false;
      for(var i = 0; i < form.elements.length; ++i) {
        var input = form.elements[i];
        if(input.type != "checkbox") continue;
        if(input.checked) listSelected = true;
      }
      if(!listSelected) {
        if(mainListsDiv.className.indexOf("invalid") == -1) mainListsDiv.className += " invalid";
        isValid = false;
      } else {
        mainListsDiv.className = mainListsDiv.className.replace(/ invalid/g, "");
      }
    }
  }

  function errorMessage(input) {
    input.className   = "required invalid";
    input.placeholder = input.getAttribute("data-required-field");
  }

  function removeErrorMessage(input) {
    input.className   = "required";
    input.placeholder = "";
  }

  function updateFormAfterValidation() {
    form.className = isValid ? "" : "mimi_invalid";
    submit.value = submitButtonText();
    submit.disabled = !isValid;
    submit.className = isValid ? "submit" : "disabled";
  }

  function submitButtonText() {
    var text;
    if(isValid) {
      text = submit.getAttribute("data-default-text");
    } else {
      var allFieldsValid = checkIfAllFieldsAreValid();
      if(allFieldsValid) {
        text = submit.getAttribute("data-choose-list");
      } else {
        text = submit.getAttribute("data-invalid-text");
      }
    }
    return text;
  }

  function checkIfAllFieldsAreValid() {
    var allFieldsValid = true
    for(var i = 0; i < form.elements.length; ++i) {
      var input = form.elements[i];
      if(input.placeholder && input.placeholder.length > 0) allFieldsValid = false;
    }
    return allFieldsValid;
  }

  function revalidateOnChange() {
    for(var i = 0; i < form.elements.length; ++i) {
      var input = form.elements[i];
      if(input.className.indexOf("required") >= 0 || input.type == "checkbox") {
        input.onchange = validate;
      }
    }
  }
})();
</script>