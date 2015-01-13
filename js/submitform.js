$(document).ready(function(){
  var fields = {};

  var makeCheck = function(obj) {
    return function() {
      if(obj.verify(obj.input.val())) {
        obj.input.removeClass('error');
        obj.info.removeClass('errortext');
        obj.input.addClass('success');
        obj.info.text('');
        return true;
      }

      obj.input.removeClass('success');
      obj.info.removeClass('successtext');
      obj.input.addClass('error');
      obj.info.addClass('errortext');
      obj.info.text(obj.errTxt);
      return false;
    }
  };

  var notBlank = function(content) {
    return content !== '';
  };

  fields['netid'] = {
    input: $('#netid'),
    info: $('#netidInfo'),
    errTxt: "Please enter a valid netid.",
    verify: notBlank,
  };

  fields['fullname'] = {
    input: $('#fullname'),
    info: $('#fullnameInfo'),
    errTxt: "Please enter a name.",
    verify: notBlank,
  };

  fields['email'] = {
    input: $('#email'),
    info: $('#emailInfo'),
    errTxt: "Please enter a valid email address.",
    verify: notBlank,
  };

  fields['type'] = {
    input: $('#type'),
    info: $('#typeInfo'),
    errTxt: "Please enter a valid type.",
    verify: notBlank,
  };

  fields['title'] = {
    input: $('#title'),
    info: $('#titleInfo'),
    errTxt: "Please enter a valid title.",
    verify: notBlank,
  };

  fields['wage'] = {
    input: $('#wage'),
    info: $('#wageInfo'),
    errTxt: "Please enter a valid wage.",
    verify: notBlank,
  };

  fields['acct'] = {
    input: $('#acct'),
    info: $('#acctInfo'),
    errTxt: "Please enter a valid acct number.",
    verify: notBlank,
  };

  for (var item in fields) {
    if (! fields.hasOwnProperty(item)) {
      continue;
    }

    fields[item].input.blur(makeCheck(fields[item]));
  }

	$("#submit").click(function() {
		var error = false;
    var postObj = {};

    for (var item in fields) {
      if (! fields.hasOwnProperty(item)) {
        continue;
      }

      postObj[item] = fields[item].input.val();

      if (! makeCheck(fields[item])()) {
        error = true;
      }
    }

		if(!error) {
			$(this).hide();
			$("#loading").append('<img src="images/loading.gif" alt="Loading" id="loading" />');

      console.log("posting: ")
      console.log(postObj)
			$.post("data.php", postObj, function(data, textStatus) {
				$("#sendEmail").slideUp("normal", function() {
					$("#sendEmail").before('<h2>'+ textStatus +', refresh to edit again.</h2>');
					$('#dialog').dialog("close");
        });
      });
    } else {
      return false;
    }
  });
});
