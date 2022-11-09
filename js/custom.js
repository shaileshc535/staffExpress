/* For scrolltop start */
$(window).scroll(function () {
  if ($(this).scrollTop() > 50) {
    $(".scrolltop:hidden").stop(true, true).fadeIn();
  } else {
    $(".scrolltop").stop(true, true).fadeOut();
  }
});
$(function () {
  $(".scroll").click(function () {
    $("html,body").animate({ scrollTop: $(".thetop").offset().top }, "1000");
    return false;
  });
});

/* Nav Fixed After Scroll Another Process */
// cache the element
var $navBar = $(".headersec");
// find original navigation bar position
var navPos = $navBar.offset().top;
// on scroll
$(window).scroll(function () {
  // get scroll position from top of the page
  var scrollPos = $(this).scrollTop();

  // check if scroll position is >= the nav position
  if (scrollPos >= navPos + 20) {
    $navBar.addClass("posi");
  } else {
    $navBar.removeClass("posi");
  }
});

/* Dropdown Toggle */
$(document).ready(function () {
  $("#regform").validate();
  $("#jobpost").validate();
  $("#jobpost1").validate();
  $("#login").validate();
  $("#employer_info").validate();
  $("#checkout_form").validate();
  $("#contactus").validate();
  $("#healthinfo").validate();
  $("#contractform").validate();
  $("#stffprofile").validate();
  $("#replyform").validate();
  $("#msgtoemp").validate();
  $("#review").validate();
  $("#staffreview").validate();
  $("#send_invitn").validate();
  $("#staffexpinfo").validate();

  $(".navbar .dropdown").hover(
    function () {
      $(this).find(".dropdown-menu").first().stop(true, true).slideDown(150);
    },
    function () {
      $(this).find(".dropdown-menu").first().stop(true, true).slideUp(105);
    }
  );

  if (window.innerWidth > 768) {
    $("ul.messleftshort a.loadmsgs").trigger("click");
  }
});
// Click Function on for root nav
$(".dropdown-toggle").click(function () {
  var location = $(this).attr("href");
  window.location.href = location;
  return false;
});

$(".banner_heading_all").owlCarousel({
  loop: true,
  items: 1,
  autoplay: true,
  smartSpeed: 500,
  autoplayHoverPause: true,
});

/* Owl Carousel Signin page content */
$(document).ready(function () {
  /* Owl Carousel */
  $("#signinsec").owlCarousel({
    loop: true,
    autoplay: true,
    autoplayTimeout: 4000,
    autoplayHoverPause: true,
    margin: 7,
    responsiveClass: true,
    responsive: {
      0: {
        items: 1,
        nav: false,
      },
      600: {
        items: 1,
        nav: false,
      },
      1000: {
        items: 1,
        nav: false,
        loop: true,
        margin: 20,
      },
    },
  });
});

/* Form Upload */

$("form").on("change", ".file-upload-field", function () {
  $(this)
    .parent(".file-upload-wrapper")
    .attr(
      "data-text",
      $(this)
        .val()
        .replace(/.*(\/|\\)/, "")
    );
});

/* Field Copy Paste */

function clipboard(elem, event) {
  elem.prev('input[type="text"]').focus().select();
  document.execCommand(event);
  elem.prev('input[type="text"]').blur();
  elem.addClass("clicked");
  setTimeout(function () {
    elem.removeClass("clicked");
  }, 500);
}

$(".btn-copy").on("click", function () {
  clipboard($(this), "copy");
});

$(".btn-cut").on("click", function () {
  clipboard($(this), "cut");
});

var a = 0;
$(window).scroll(function () {
  var oTop = $("#counter").offset().top - window.innerHeight;
  if (a == 0 && $(window).scrollTop() > oTop) {
    $(".counter-value").each(function () {
      var $this = $(this),
        countTo = $this.attr("data-count");
      $({
        countNum: $this.text(),
      }).animate(
        {
          countNum: countTo,
        },

        {
          duration: 7000,
          easing: "swing",
          step: function () {
            $this.text(Math.floor(this.countNum));
          },
          complete: function () {
            $this.text(this.countNum);
            //alert('finished');
          },
        }
      );
    });
    a = 1;
  }
});

/* Side Nav for Mobile */
function openNav() {
  document.getElementById("mySidenav").style.width = "100%";
}

function closeNav() {
  document.getElementById("mySidenav").style.width = "0";
}

$(document).on("submit", "#regform", function () {
  if ($(this).valid()) {
    var pass = $.trim($("#pass").val());
    var cpass = $.trim($("#cpass").val());
    if (pass == cpass) {
      return true;
    } else {
      //$("#error").text("Password and confirm password must be same.");
      $("#cpass").css("border-bottom", "1px solid #f00");
      setTimeout(function () {
        $("#error").text("");
      }, 4000);
      return false;
    }
  }
});

var siteurl = "https://www.staffexpress.com.au/staging/";

/*$(document).on('change', '#category', function () {
  var val = $(this).val();
  var allquals = '';
  if (val != "") {
    $.ajax({
      type: "POST",
      dataType: "json",
      url: siteurl + "getqualifn.php",
      cache: false,
      data: { cat: val, action: 'getqualifn' },
      beforeSend: function () { },
      complete: function () { },
      success: function (json) {

        if (json.allquals.length > 0) {
          $.each(json.allquals, function (i, row) {
            allquals += '<div class="form-check"><input name="qual[]" class="form-check-input" type="checkbox" value="' + row.id + '"><label class="form-check-label">' + row.qual + '</label></div>';
          });
          $(".check_box").html(allquals);
          $("#qual_req").show();
        }
        else {
          $(".check_box").html('');
          $("#qual_req").hide();
        }

      },
      error: function (xhr, ajaxOptions, thrownError) {
        console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
      }
    });
  }
  else {
    $(".check_box").html('');
    $("#qual_req").hide();
  }
});*/

$('input[name="applicn_deadln"]').on("click", function () {
  var option = $('input[name="applicn_deadln"]:checked').val();
  if (option == 1) {
    $("#deadlndt").show();
    $("#applicn_deadln_date").addClass("required");
  } else {
    $("#deadlndt").hide();
    $("#applicn_deadln_date").removeClass("required");
  }
});

$(document).on("click", "#accept_offer", function () {
  $("#avl_form_apply").submit();
});

/*$(document).on('click', '#accept_offer', function () {
  var jobid = $("#jobid").val();
  var userid = $("#userid").val();
  
  var avlblty = $('input[name="avlblty"]:checked').val();
  var availble_date1 = $("#availble_date1").val();
  var availble_date2 = $("#availble_date2").val();
  var availble_time1 = $("#availble_time1").val();
  var availble_time2 = $("#availble_time2").val();
  
  var starttime = $("#starttime").val();
  var endtime = $("#endtime").val();
  var multi_day = $("#demo-multi-day").val();
  var notes = $("#notes").val();
  if (jobid != "" && userid != "") {
    $.ajax({
      type: "POST",
      dataType: "json",
      url: siteurl + "accept.php",
      cache: false,
      data: { jobid: jobid, userid: userid, avlblty: avlblty, multi_day: multi_day, availble_date1: availble_date1, availble_date2: availble_date2, availble_time1: availble_time1, availble_time2: availble_time2, starttime: starttime, endtime: endtime, notes: notes, action: 'acceptoffr' },
      beforeSend: function () { $("#accept_offer").text("Processing...").attr("disabled", "disabled"); },
      complete: function () { $("#accept_offer").text("Submit Interest for this Job").removeAttr("disabled"); },
      success: function (json) {

        if (json.success == 1) {
          $("#applydiv").hide();
          
          //$("#accept_offer").hide();
          //$("#apply_suc").text("Applied");
      //$("#interested").text("Applied");
      location.href=siteurl+'myappliedjobs';
        }
        else if (json.success == 2) {
          alert("You have uncompleted section(s). Please go to your profile to complete the steps.");
        }
        else if (json.success == 3) {
          alert("Please select days(s) to apply.");
        }

      },
      error: function (xhr, ajaxOptions, thrownError) {
        console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
      }
    });
  }
});*/

$(document).on("submit", "#avl_form_apply", function () {
  var jobid = $("#jobid").val();
  var userid = $("#userid").val();
  var workmode = $("#workmode").val();
  var avlblty = $('input[name="avlblty"]:checked').val();
  var myselctdate = $("#myselctdate").val();
  if (avlblty == "2") {
    if (myselctdate == "") {
      alert("Please select dates.");
      return false;
    }
  }

  /*var from = $("#from").val();
  var starttime = $("#starttime").val();
  var to = $("#to").val();
  var endtime = $("#endtime").val();
  var avlblty = $('input[name="avlblty"]:checked').val();
  var availble_date1 = $("#availble_date1").val();
  var availble_date2 = $("#availble_date2").val();
  var availble_time1 = $("#availble_time1").val();
  var availble_time2 = $("#availble_time2").val();
  
  var starttime = $("#starttime").val();
  var endtime = $("#endtime").val();
  var multi_day = $("#demo-multi-day").val();
  var notes = $("#notes").val();*/
  var option = $('input[name="select_mode"]:checked').val();
  if (jobid != "" && userid != "") {
    if (workmode == "2") {
      if (option == "1") {
        var starttime = $("#starttime").val();
        var endtime = $("#endtime").val();
        if (starttime == "" && endtime == "") {
          alert("Please enter start time and end time.");
          return false;
        } else {
          if (starttime >= endtime) {
            alert("Start time must be less than end time.");
            return false;
          }
        }
      }
      if (option == "2") {
        var error = [];
        var error2 = [];
        var spdtstrttime;
        var spdtendtime;
        var haserror = 0;
        var haserror2 = 0;
        var haserror3 = 0;
        var haserror4 = 0;

        $(".spdtstrttime").each(function () {
          spdtstrttime = $(this).val();
          error.push(spdtstrttime);
        });

        $(".spdtendtime").each(function () {
          spdtendtime = $(this).val();
          error2.push(spdtendtime);
        });

        for (let i = 0; i < error.length; i++) {
          if (error[i] == "") {
            haserror = 1;
            break;
          }
        }
        for (let i = 0; i < error.length; i++) {
          if (error2[i] == "") {
            haserror2 = 1;
            break;
          }
        }

        if (haserror == 1 || haserror2 == 1) {
          alert("Please enter start time and end time.");
          return false;
        }
      }
    }
    $.ajax({
      type: "POST",
      dataType: "json",
      url: siteurl + "accept.php",
      cache: false,
      data: $("#avl_form_apply").serialize(),
      beforeSend: function () {
        $("#accept_offer").text("Processing...").attr("disabled", "disabled");
      },
      complete: function () {
        $("#accept_offer")
          .text("Submit Interest for this Job")
          .removeAttr("disabled");
      },
      success: function (json) {
        if (json.success == 1) {
          $("#applydiv").hide();

          location.href = siteurl + "myappliedjobs";
        } else if (json.success == 2) {
          alert(
            "You have uncompleted section(s). Please go to your profile to complete the steps."
          );
        } else if (json.success == 3) {
          alert("Please select days(s) to apply.");
        }
      },
      error: function (xhr, ajaxOptions, thrownError) {
        console.log(
          thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
        );
      },
    });
    return false;
  }
});

$(document).on("click", "#accept_offer_direct", function () {
  $("#avl_form_direct").submit();
});

$(document).on("submit", "#avl_form_direct", function () {
  var jobid = $("#jobid").val();
  var userid = $("#userid").val();
  var workmode = $("#workmode").val();
  var avlblty = $('input[name="avlblty"]:checked').val();
  var myselctdate = $("#myselctdate").val();
  if (avlblty == "2") {
    if (myselctdate == "") {
      alert("Please select dates.");
      return false;
    }
  }

  var option = $('input[name="select_mode"]:checked').val();
  if (jobid != "" && userid != "") {
    if (workmode == "2") {
      if (option == "1") {
        var starttime = $("#starttime").val();
        var endtime = $("#endtime").val();
        if (starttime == "" && endtime == "") {
          alert("Please enter start time and end time.");
          return false;
        } else {
          if (starttime >= endtime) {
            alert("Start time must be less than end time.");
            return false;
          }
        }
      }
      if (option == "2") {
        var error = [];
        var error2 = [];
        var spdtstrttime;
        var spdtendtime;
        var haserror = 0;
        var haserror2 = 0;
        var haserror3 = 0;
        var haserror4 = 0;

        $(".spdtstrttime").each(function () {
          spdtstrttime = $(this).val();
          error.push(spdtstrttime);
        });

        $(".spdtendtime").each(function () {
          spdtendtime = $(this).val();
          error2.push(spdtendtime);
        });

        for (let i = 0; i < error.length; i++) {
          if (error[i] == "") {
            haserror = 1;
            break;
          }
        }
        for (let i = 0; i < error.length; i++) {
          if (error2[i] == "") {
            haserror2 = 1;
            break;
          }
        }

        if (haserror == 1 || haserror2 == 1) {
          alert("Please enter start time and end time.");
          return false;
        }
      }
    }

    $.ajax({
      type: "POST",
      dataType: "json",
      url: siteurl + "accept.php",
      cache: false,
      data: $("#avl_form_direct").serialize(),
      beforeSend: function () {
        $("#accept_offer").text("Processing...").attr("disabled", "disabled");
      },
      complete: function () {
        $("#accept_offer")
          .text("Submit Interest for this Job")
          .removeAttr("disabled");
      },
      success: function (json) {
        if (json.success == 1) {
          $("#applydiv").hide();
          $("#apply_suc").text("Your interest is notified to employer.");
          $(".acceptoffer").hide();
          $("#success").show();
          //location.href = siteurl + 'myappliedjobs';
        } else if (json.success == 2) {
          alert(
            "You have uncompleted section(s). Please go to your profile to complete the steps."
          );
        } else if (json.success == 3) {
          alert("Please select days(s) to apply.");
        }
      },
      error: function (xhr, ajaxOptions, thrownError) {
        console.log(
          thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
        );
      },
    });
    return false;
  }
});

$(document).on("click", "#hire", function () {
  //$("#myhire").submit();
  var jobid = $("#jobid").val();
  var userid = $("#userid").val();
  if (window.confirm("Are you sure to shortlist the staff?") == true) {
    if (jobid != "" && userid != "") {
      $.ajax({
        type: "POST",
        dataType: "json",
        url: siteurl + "send_request.php",
        cache: false,
        data: { jobid: jobid, userid: userid, action: "send_req" },
        beforeSend: function () {
          /*$("#reject").text("Processing...").attr("disabled", "disabled");*/
        },
        complete: function () {
          /*$("#reject").text("No").removeAttr("disabled");*/
        },
        success: function (json) {
          if (json.success == 1) {
            $("#confrmn_sent").text("Confirmation Sent");
            $("#confirmation").modal("show");
            $(".intrsttick").attr("id", "");
            $(".intrstcross").attr("id", "");
          }
        },
        error: function (xhr, ajaxOptions, thrownError) {
          console.log(
            thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
          );
        },
      });
    }
  }
});

$(document).on("click", "#sendmsg", function () {
  var mymsg = $.trim($("#mymsg").val());

  var userid = $("#staff_id").val();
  var employerid = $("#emp_id").val();
  var usertype = $("#usertype").val();
  var jobid = $("#jobid").val();
  var msgid = $("#msgid").val();
  if (hasNumber(mymsg)) {
    alert("Message must not contain numbers.");
    return false;
  }
  if (mymsg.includes("@")) {
    alert("Message must not contain @.");
    return false;
  }

  var sentmsg;
  if (mymsg != "") {
    $.ajax({
      type: "POST",
      dataType: "json",
      url: siteurl + "sendmsg.php",
      cache: false,
      data: {
        userid: userid,
        employerid: employerid,
        usertype: usertype,
        mymsg: mymsg,
        jobid: jobid,
        msgid: msgid,
        action: "sendmsg",
      },
      beforeSend: function () {
        $("#sendmsg").text("Sending...").attr("disabled", "disabled");
      },
      complete: function () {
        $("#sendmsg").text("Send Message").removeAttr("disabled");
      },
      success: function (json) {
        if (json.success == "1") {
          if (usertype == 1) $("#success").text("Your message has been sent.");
          else if (usertype == 2)
            $("#success").text("Your message has been sent.");

          /*sentmsg = '<div class="client_messages" style="margin-bottom:15px;"><div class="client_messages_content sendermsg"><div class="client_messages_name"><p class="messdatestyl"><span class="lintst"><span class="dtabso">' + json.msgdt + '</span></span></p><h6>' + json.sender + '</h6></div><p>' + json.msg + '</p></div></div><div style="clear:both;"></div>';*/

          sentmsg =
            '<div class="client_messages" style="margin-bottom:10px;"><div class="client_messages_content sendermsg"><div class="client_messages_name"><p class="messdatestyl"><span class="lintst"><span class="dtabso">' +
            json.msgdt +
            "</span></span></p><h6>" +
            json.sender +
            " <span>" +
            json.msgtime +
            "</span></h6></div><p>" +
            json.msg +
            '</p></div></div><div style="clear:both;"></div>';

          $("#existingmsg").append(sentmsg);
          $("#existingmsg").scrollTop($("#existingmsg")[0].scrollHeight);

          if (json.parentmsgid != 0) {
            $("#msgid").val(json.parentmsgid);
          }

          $("#mymsg").val("");
          $(".nomsg").hide();
          setTimeout(() => {
            $("#success").text("");
          }, 6000);
        } else {
          alert("Message sending failed, please try again.");
        }
      },
      error: function (xhr, ajaxOptions, thrownError) {
        console.log(
          thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
        );
      },
    });
  }
});

$('input[name="uniform"]').on("click", function () {
  var option = $('input[name="uniform"]:checked').val();
  if (option == "Other") {
    $("#selctuniform").show();
    $("#selctuniform").addClass("required");
  } else {
    $("#selctuniform").hide();
    $("#selctuniform").val("");
    $("#selctuniform").removeClass("required");
  }
});

$(document).on("click", "#righttowork", function () {
  if ($(this).prop("checked") == true)
    $("#norighttowork").prop("checked", false);
});
$(document).on("click", "#norighttowork", function () {
  if ($(this).prop("checked") == true) $("#righttowork").prop("checked", false);
});

$(document).on("click", "#msg_employr", function () {
  $("#message").submit();
});

$(document).on("click", "#reject", function () {
  var jobid = $("#jobid").val();
  var userid = $("#userid").val();
  if (window.confirm("Are you sure to reject this staff?") == true) {
    if (jobid != "" && userid != "") {
      $.ajax({
        type: "POST",
        dataType: "json",
        url: siteurl + "reject_candidt.php",
        cache: false,
        data: { jobid: jobid, userid: userid, action: "reject" },
        beforeSend: function () {
          /*$("#reject").text("Processing...").attr("disabled", "disabled");*/
        },
        complete: function () {
          /*$("#reject").text("No").removeAttr("disabled");*/
        },
        success: function (json) {
          if (json.success == 1) {
            //$(".acceptoffer").hide();
            //$("#rejected").show();
            $(".intrsttick").attr("id", "");
            $(".intrstcross").attr("id", "").addClass("active");
          }
        },
        error: function (xhr, ajaxOptions, thrownError) {
          console.log(
            thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
          );
        },
      });
    }
  }
});

$(function () {
  $("#existingmsg").scrollTop($("#existingmsg")[0].scrollHeight);
});

$(document).on("change", "#mytype", function () {
  var val = $(this).val();
  if (val == 1) {
    $("#foremp").show();
    $("#name").addClass("required");
    $(".forstaff").hide();
    $("#fname").removeClass("required");
    $("#lname").removeClass("required");
  } else if (val == 2) {
    $("#foremp").hide();
    $("#name").removeClass("required");
    $(".forstaff").show();
    $("#fname").addClass("required");
    $("#lname").addClass("required");
  }
});

$(document).on("blur", "#number", function () {
  var number = $.trim($("#number").val());
  var abnacn = $.trim($("#abnacn").val());

  if (abnacn == "ABN") {
    if (number.length != 11) {
      $("#error").text("ABN must be of 11 digits.");
    }
  }
  if (abnacn == "ACN") {
    if (number.length != 9) {
      $("#error").text("ACN must be of 9 digits.");
    }
  }
  setTimeout(function () {
    $("#error").text("");
  }, 4000);
});

$(document).on("submit", "#employer_info", function () {
  if ($(this).valid()) {
    var number = $.trim($("#number").val());
    var abnacn = $.trim($("#abnacn").val());
    if (abnacn == "ABN") {
      if (number.length == 11) {
        return true;
      } else {
        $("#error").text("ABN must be of 11 digits.");
        $("#number").css("border-bottom", "1px solid #f00");
        return false;
      }
    }
    if (abnacn == "ACN") {
      if (number.length == 9) {
        return true;
      } else {
        $("#error").text("ACN must be of 9 digits.");
        $("#number").css("border-bottom", "1px solid #f00");
        return false;
      }
    }
  }
});

$(document).on("change", "#contract", function () {
  var val = $(this).val();
  if (val != "") {
    if (val == 2) {
      $("#ourcontract").show();
      $("#owncontract").hide();
      $("#upld_contract").removeClass("required");
    }
    if (val == 3) {
      $("#ourcontract").hide();
      $("#owncontract").show();
      $("#upld_contract").addClass("required");
    }
    if (val == 1) {
      $("#ourcontract").hide();
      $("#owncontract").hide();
      $("#upld_contract").removeClass("required");
    }
  } else {
    $("#ourcontract").hide();
    $("#owncontract").hide();
    $("#upld_contract").removeClass("required");
  }
});

$('input[name="receive_app"]').on("click", function () {
  var option = $(this).val();
  if (option != "") {
    $("#notified").show();
  } else $("#notified").hide();
});

$('input[name="notified"]').on("click", function () {
  var option = $(this).val();
  if (option != "") {
    $("#notified").show();
  } else $("#notified").hide();
});

$("#hasallgy").on("click", function () {
  if ($(this).prop("checked") == true) {
    $("#allergy").show();
  } else $("#allergy").hide();
});

$("#hasid").on("click", function () {
  if ($(this).prop("checked") == true) {
    $("#infectious").show();
  } else $("#infectious").hide();
});

$("#hasother").on("click", function () {
  if ($(this).prop("checked") == true) {
    $("#otherdis").show();
  } else $("#otherdis").hide();
});

$('input[name="superannuation"]').on("click", function () {
  var option = $(this).val();
  if (option == "1") {
    $("#mysuper").show();
    $("#supname").addClass("required");
    $("#supnumber").addClass("required");
    $("#supabn").addClass("required");
  } else {
    $("#mysuper").hide();
    $("#supname").removeClass("required");
    $("#supnumber").removeClass("required");
    $("#supabn").removeClass("required");
  }
});

$(document).on("click", "#clockin", function () {
  var jobid = $("#jobid").val();
  var userid = $("#userid").val();
  if (window.confirm("Are you sure to start work?") == true) {
    if (jobid != "" && userid != "") {
      $.ajax({
        type: "POST",
        dataType: "json",
        url: siteurl + "clockin.php",
        cache: false,
        data: { jobid: jobid, userid: userid, action: "clockin" },
        beforeSend: function () {
          $("#clockin").text("Processing...").attr("disabled", "disabled");
        },
        complete: function () {
          $("#clockin").text("Start Work").removeAttr("disabled");
        },
        success: function (json) {
          if (json.success == 1) {
            $("#clckin").hide();
            //$("#clckot").show();
            $("#clockindiv").show();
          } else {
            alert("Sorry!! You have already started the work.");
          }
        },
        error: function (xhr, ajaxOptions, thrownError) {
          console.log(
            thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
          );
        },
      });
    }
  }
});

$(document).on("click", ".clockcodesub", function () {
  var clockincode = $(".clockincode").val();
  var jobid = $("#jobid").val();
  var userid = $("#userid").val();
  if (!isNaN(clockincode) != "" && jobid != "" && userid != "") {
    $.ajax({
      type: "POST",
      dataType: "json",
      url: siteurl + "submitclockincode.php",
      cache: false,
      data: {
        clockincode: clockincode,
        jobid: jobid,
        userid: userid,
        action: "submitcode",
      },
      beforeSend: function () {
        $(".clockcodesub").text("Processing...").attr("disabled", "disabled");
      },
      complete: function () {
        $(".clockcodesub").text("Submit").removeAttr("disabled");
      },
      success: function (json) {
        if (json.success == 1) {
          $("#clockindiv").hide();
          $("#clockinsucces").show();
          $("#breakdiv").show();
          $("#myclockout").show();
          $("#prevtext").hide();
          $("#totalhour").text("Work has been started.");
        } else if (json.success == 2) {
          alert("Sorry!! Clockin code incorrect. Please enter correct code.");
        } else {
          alert("Sorry!! Some error occurred.");
        }
      },
      error: function (xhr, ajaxOptions, thrownError) {
        console.log(
          thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
        );
      },
    });
  }
});

$(document).on("click", "#clockout", function () {
  var jobid = $("#jobid").val();
  var userid = $("#userid").val();
  var usertype = $("#usertype").val();
  if (window.confirm("Are you sure to clock out?") == true) {
    if (jobid != "" && userid != "") {
      $.ajax({
        type: "POST",
        dataType: "json",
        url: siteurl + "clockoutcandidt.php",
        cache: false,
        data: { jobid: jobid, userid: userid, action: "clockout" },
        beforeSend: function () {
          $("#clockout").text("Processing...").attr("disabled", "disabled");
        },
        complete: function () {
          $("#clockout").text("Clock out for today").removeAttr("disabled");
        },
        success: function (json) {
          if (json.success == 1) {
            $("#clockout").hide();
            $("#myclockout").hide();
            $("#myclockout2").hide();
            $("#clockoutsucces").show();
            $("#clockinsucces").hide();
            $("#totalhour").html(json.total_hours + "<br>");
            $("#prevtext").hide();
            $("#breakdiv").hide();
          } else if (json.success == 3) {
            if (usertype == 1) {
              alert("Staff has not ended break yet.");
            }
            if (usertype == 2) {
              alert(
                "You have not ended break yet. Please end break to clock out."
              );
            }
          } else {
            alert("Sorry!! Some error occurred.");
          }
        },
        error: function (xhr, ajaxOptions, thrownError) {
          console.log(
            thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
          );
        },
      });
    }
  }
});

$(document).on("click", "#allowmore", function () {
  var jobid = $("#jobid").val();
  var userid = $("#userid").val();
  $.ajax({
    type: "POST",
    dataType: "json",
    url: siteurl + "allowmorehour.php",
    cache: false,
    data: { jobid: jobid, userid: userid, action: "allowmore" },
    beforeSend: function () {
      $("#allowmore").text("Processing...").attr("disabled", "disabled");
    },
    complete: function () {
      $("#allowmore").text("Yes").removeAttr("disabled");
    },
    success: function (json) {
      if (json.success == 1) {
        $("#allowmrhr").hide();
        alert(
          "The staff is notified that you have asked him/her to work for some extra hours. staff will let you know how many extra hours he/she needs."
        );
        $("#extrareq").show();
      } else {
        alert("Sorry!! Some problem occurred.");
      }
    },
    error: function (xhr, ajaxOptions, thrownError) {
      console.log(
        thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
      );
    },
  });
});
$(document).on("click", "#notallowmore", function () {
  $("#allowmrhr").hide();
  alert("Okay, then staff will leave at the ending time.");
});

$(document).on("click", "#submorehour", function () {
  var jobid = $("#jobid").val();
  var userid = $("#userid").val();
  var morehours = $.trim($("#morehours").val());
  if (jobid != "" && userid != "" && morehours != "") {
    $.ajax({
      type: "POST",
      dataType: "json",
      url: siteurl + "requestmorehour.php",
      cache: false,
      data: {
        jobid: jobid,
        userid: userid,
        morehours: morehours,
        action: "requestmorehour",
      },
      beforeSend: function () {
        $("#submorehour").text("Processing...").attr("disabled", "disabled");
      },
      complete: function () {
        $("#submorehour").text("Submit").removeAttr("disabled");
      },
      success: function (json) {
        if (json.success == 1) {
          $("#reqmorehr").hide();
          $("#extrahour")
            .html(
              "You have requested for " +
                morehours +
                " extra hour(s). It is subject to approval of the employer."
            )
            .show();
        } else {
          alert("Sorry!! Some problem occurred.");
        }
      },
      error: function (xhr, ajaxOptions, thrownError) {
        console.log(
          thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
        );
      },
    });
  }
});

$(document).on("click", "#approve", function () {
  var jobid = $("#jobid").val();
  var userid = $("#userid").val();
  if (jobid != "" && userid != "") {
    $.ajax({
      type: "POST",
      dataType: "json",
      url: siteurl + "approvemore.php",
      cache: false,
      data: { jobid: jobid, userid: userid, action: "approvemore" },
      beforeSend: function () {
        $("#approve").text("Processing...").attr("disabled", "disabled");
      },
      complete: function () {
        $("#approve").text("Approve").removeAttr("disabled");
      },
      success: function (json) {
        if (json.success == 1) {
          $("#approvemrhr").hide();
          $("#extrahrstatus")
            .html("You have approved " + json.extrahour + " more hours.")
            .show();
          // Please go <a href='" + siteurl + "escrowmore?id=" + jobid + "&userid=" + userid + "'>here</a> to escrow more.
        } else {
          alert("Sorry!! Some problem occurred.");
        }
      },
      error: function (xhr, ajaxOptions, thrownError) {
        console.log(
          thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
        );
      },
    });
  }
});

$(document).on("click", "#notapprove", function () {
  var jobid = $("#jobid").val();
  var userid = $("#userid").val();
  if (jobid != "" && userid != "") {
    $.ajax({
      type: "POST",
      dataType: "json",
      url: siteurl + "rejectmore.php",
      cache: false,
      data: { jobid: jobid, userid: userid, action: "rejectmore" },
      beforeSend: function () {
        $("#notapprove").text("Processing...").attr("disabled", "disabled");
      },
      complete: function () {
        $("#notapprove").text("Reject").removeAttr("disabled");
      },
      success: function (json) {
        if (json.success == 1) {
          $("#approvemrhr").hide();
          $("#extrahrstatus")
            .html("You have rejected the request for more hours.")
            .show();
        } else {
          alert("Sorry!! Some problem occurred.");
        }
      },
      error: function (xhr, ajaxOptions, thrownError) {
        console.log(
          thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
        );
      },
    });
  }
});

$(document).on("click", "#sub_dispute", function () {
  var jobid = $("#jobid").val();
  var userid = $("#userid").val();
  var reason = $("#reason").val();
  var other_reason = $("#other_reason").val();
  if (reason == "4" && other_reason == "") {
    alert("Please type other reason.");
    return false;
  }
  $.ajax({
    type: "POST",
    dataType: "json",
    url: siteurl + "raisedispute.php",
    cache: false,
    data: {
      jobid: jobid,
      userid: userid,
      reason: reason,
      other_reason: other_reason,
      action: "disputeemp",
    },
    beforeSend: function () {
      $("#sub_dispute").text("Submitting...").attr("disabled", "disabled");
    },
    complete: function () {
      $("#sub_dispute").text("Submit").removeAttr("disabled");
    },
    success: function (json) {
      if (json.success == 1) {
        $("#dispute").trigger("reset");
        $("#disputesucces").show();
      } else {
        alert("Sorry!! Some problem occurred.");
      }
    },
    error: function (xhr, ajaxOptions, thrownError) {
      console.log(
        thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
      );
    },
  });
});

$(document).on("change", "#reason", function () {
  var val = $(this).val();
  if (val == "4") {
    $("#other_reason").show();
  } else {
    $("#other_reason").hide();
  }
});

$('input[name="jobtype"]').on("click", function () {
  var option = $(this).val();
  if (option == "2") {
    $("#payment_option").show();
  } else {
    $("#payment_option").hide();
    $("#payforjob").hide();
    $("#card_number").removeClass("required");
    $("#card_exp_month").removeClass("required");
    $("#card_exp_year").removeClass("required");
    $("#card_cvc").removeClass("required");
  }
});

$(document).on("click", "#showfilter", function () {
  $("#filter").slideToggle();
});

$(document).on("click", "#same", function () {
  if ($(this).prop("checked") == true) {
    $("#street_address").val($("#company_address").val());
    $("#suburb").val($("#company_suburb").val());
    $("#location").val($("#postcode").val());
    $("#state").val($("#company_state").val());
    $("#working_country").val($("#country").val());
  } else {
    $("#street_address").val("");
    $("#suburb").val("");
    $("#location").val("");
    $("#state").val("");
    $("#working_country").val("");
  }
});

$(document).on("change", "#sortby", function () {
  $("#sortform").submit();
});

$(document).on("click", ".accept_offer", function () {
  var id = $(this).attr("id");
  var id_arr = id.split("_");
  var jobid = id_arr[1];
  var userid = $("#userid").val();
  if (jobid != "" && userid != "") {
    $.ajax({
      type: "POST",
      dataType: "json",
      url: siteurl + "accept.php",
      cache: false,
      data: { jobid: jobid, userid: userid, action: "acceptoffr" },
      beforeSend: function () {
        $("#apply_" + jobid)
          .text("Processing...")
          .attr("disabled", "disabled");
      },
      complete: function () {},
      success: function (json) {
        if (json.success == 1) {
          $("#applybtn_" + jobid).hide();
          $("#success_" + jobid).show();
        }
      },
      error: function (xhr, ajaxOptions, thrownError) {
        console.log(
          thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
        );
      },
    });
  }
});

$(document).on("click", ".issue", function () {
  if ($(this).prop("checked") == true) {
    $("#nomedical").prop("checked", false);
  }
});

$(document).on("click", "#nomedical", function () {
  if ($(this).prop("checked") == true) {
    $(".issue").prop("checked", false);
    $("#allergy").hide();
    $("#infectious").hide();
    $("#otherdis").hide();
  }
});

$(document).on("click", "#precontract", function () {
  $("#viewcontract").submit();
});

$(document).on("click", "#back", function () {
  $(".formback").submit();
});

$(document).on("click", "#showuni", function () {
  if ($(this).prop("checked") == true) {
    $("#uniformdiv").show();
    $("#uni_0").addClass("required");
  } else {
    $("#uniformdiv").hide();
    $("#uni_0").removeClass("required");
  }
});

$(document).on("click", "#classic", function () {
  $("#payforjob").show();
  $("#myoption").val("1");
  $("#card_number").addClass("required");
  $("#card_exp_month").addClass("required");
  $("#card_exp_year").addClass("required");
  $("#card_cvc").addClass("required");
  $(".classic").addClass("active");
  $(".premium").removeClass("active");
});

$(document).on("click", "#premium", function () {
  $("#payforjob").show();
  $("#myoption").val("2");
  $("#card_number").addClass("required");
  $("#card_exp_month").addClass("required");
  $("#card_exp_year").addClass("required");
  $("#card_cvc").addClass("required");
  $(".classic").removeClass("active");
  $(".premium").addClass("active");
});

$(document).on("click", "#showlunch", function () {
  if ($(this).prop("checked") == true) {
    $("#lunchdiv").show();
    $("#lunchbrk").addClass("required");
  } else {
    $("#lunchdiv").hide();
    $("#lunchbrk").removeClass("required");
  }
});

$(document).on("keyup", "#howmnypeople", function () {
  if ($(this).val() == 0) {
    $(this).val("");
  }
});

/*$(document).on('keyup', '.checkno', function () {
  var val = $(this).val();
  var regex = /^[\w]+([-_\s]{1}[a-z0-9]+)*$/i;
  if (!regex.test(val)) {
    $(this).val('');
  }
});*/
$(".checkno").keydown(function (e) {
  var k = e.keyCode || e.which;
  var ok =
    (k >= 65 && k <= 90) || // A-Z
    (k >= 96 && k <= 105) || // a-z
    (k >= 35 && k <= 40) || // arrows
    k == 9 || //tab
    k == 46 || //del
    k == 8 || // backspaces
    (!e.shiftKey && k >= 48 && k <= 57); // only 0-9 (ignore SHIFT options)

  if (!ok || (e.ctrlKey && e.altKey)) {
    e.preventDefault();
  }
});

$('input[name="avlblty"]').on("click", function () {
  var avlblty = $('input[name="avlblty"]:checked').val();
  if (avlblty == 2) {
    $("#particular").show();
  } else {
    $("#particular").hide();
  }
});

$(document).on("submit", "#contactus", function () {
  if ($(this).valid() == true) {
    $.ajax({
      type: "POST",
      dataType: "json",
      url: siteurl + "contact.php",
      cache: false,
      data: $(this).serialize(),
      beforeSend: function () {
        $("#sub_help").val("Submitting...").attr("disabled", "disabled");
      },
      complete: function () {
        $("#sub_help").val("Submit").removeAttr("disabled");
      },
      success: function (json) {
        if (json.success == 1) {
          $("#contactus").trigger("reset");
          $("#helpsucces").show();
        } else {
          alert("Sorry!! Some problem occurred.");
        }
      },
      error: function (xhr, ajaxOptions, thrownError) {
        console.log(
          thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
        );
      },
    });
    return false;
  }
});

$(document).on("submit", "#msgtoemp", function () {
  if ($(this).valid() == true) {
    $.ajax({
      type: "POST",
      dataType: "json",
      url: siteurl + "msgtoemp.php",
      cache: false,
      data: $(this).serialize(),
      beforeSend: function () {
        $("#sub_help").val("Submitting...").attr("disabled", "disabled");
      },
      complete: function () {
        $("#sub_help").val("Submit").removeAttr("disabled");
      },
      success: function (json) {
        if (json.success == 1) {
          $("#msgtoemp").trigger("reset");
          $("#helpsucces").show();
        } else {
          alert("Sorry!! Some problem occurred.");
        }
      },
      error: function (xhr, ajaxOptions, thrownError) {
        console.log(
          thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
        );
      },
    });
    return false;
  }
});

$(document).on("submit", "#healthinfo", function () {
  if (
    $("#nomedical").prop("checked") == false &&
    $("#heart").prop("checked") == false &&
    $("#diabetes").prop("checked") == false &&
    $("#bloodpr").prop("checked") == false &&
    $("#hasallgy").prop("checked") == false &&
    $("#hasid").prop("checked") == false &&
    $("#hasother").prop("checked") == false
  ) {
    alert("Please check at least one option.");
    return false;
  } else return true;
});

$('input[name="paytype"]').on("click", function () {
  var option = $(this).val();
  if (option == "2") {
    $("#hourly").show();
    $("#commission").hide();
    $("#annual").hide();
    $("#annualcomm").hide();
  }
  if (option == "1") {
    $("#hourly").hide();
    $("#commission").hide();
    $("#annual").show();
    $("#annualcomm").hide();
  }
  if (option == "3") {
    $("#hourly").hide();
    $("#commission").show();
    $("#annual").hide();
    $("#annualcomm").hide();
  }
  if (option == "4") {
    $("#hourly").hide();
    $("#annualcomm").show();
    $("#commission").hide();
    $("#annual").hide();
  }
});

$('input[name="righttowork"]').on("click", function () {
  var val = $('input[name="righttowork"]:checked').val();
  if (val == "2") {
    $("#otherrightto").show();
    $("#otherrightto").addClass("required");
  } else {
    $("#otherrightto").hide();
    $("#otherrightto").removeClass("required");
  }
});

$('input[name="work_with_child"]').on("click", function () {
  var val = $('input[name="work_with_child"]:checked').val();
  if (val == "2") {
    $("#otherchild").show();
    $("#otherchild").addClass("required");
  } else {
    $("#otherchild").hide();
    $("#otherchild").removeClass("required");
  }
});

$('input[name="work_timeframe"]').on("click", function () {
  var val = $('input[name="work_timeframe"]:checked').val();
  if (val == "2") {
    $("#othertmfr").show();
    $("#othertmfr").addClass("required");
  } else {
    $("#othertmfr").hide();
    $("#othertmfr").removeClass("required");
  }
});

$(document).on("click", "#showother", function () {
  if ($(this).prop("checked") == true) {
    $("#otherlunch").show();
    $("#otherlunch").addClass("required");
    //$("#lunchbrk").removeClass("required");
  } else {
    $("#otherlunch").hide();
    $("#otherlunch").removeClass("required");
    //$("#lunchbrk").addClass("required");
  }
});

$(document).on("submit", "#contractform", function () {
  if ($("#contracttype").val() == "0" || $("#contracttype").val() == "") {
    alert("Please select our contract or your own contract.");
    return false;
  } else return true;
});

$(document).on("submit", "#checkout_form", function () {
  if ($("#myoption").val() == "") {
    alert("Please select one payment type.");
    return false;
  } else return true;
});

$(document).on("change", "#upld_contract", function () {
  var val = $(this).val();
  if (val != "") {
    $("#contracttype").val("2");
  } else {
    $("#contracttype").val("");
  }
});

$(document).on("submit", "#stffprofile", function () {
  if ($(".mydays input[type=checkbox]:checked").length == 0) {
    alert("Please select available days.");
    return false;
  } else return true;
});

$(document).on("click", "#avl_sub", function () {
  var jobid = $("#jobid").val();
  var userid = $("#userid").val();
  var from = $("#from").val();
  var starttime = $("#starttime").val();
  var to = $("#to").val();
  var endtime = $("#endtime").val();
  var avlblty = $('input[name="avlblty"]:checked').val();
  if (jobid != "" && userid != "") {
    if (avlblty == 2) {
      if (from == "" || starttime == "" || to == "" || endtime == "") {
        alert("Please enter available timings.");
        return false;
      }
    }
    $.ajax({
      type: "POST",
      dataType: "json",
      url: siteurl + "submitavlity.php",
      cache: false,
      data: {
        jobid: jobid,
        userid: userid,
        from: from,
        starttime: starttime,
        to: to,
        endtime: endtime,
        avlblty: avlblty,
        action: "submitavalibly",
      },
      beforeSend: function () {
        $("#avl_sub").text("Processing...").attr("disabled", "disabled");
      },
      complete: function () {},
      success: function (json) {
        if (json.success == 1) {
          $("#avlblsec").hide();
          $("#avblsucces").show();
        }
        $("#avl_sub").text("Submit").removeAttr("disabled");
      },
      error: function (xhr, ajaxOptions, thrownError) {
        console.log(
          thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
        );
      },
    });
  }
});

$(document).on("click", "#myback", function () {
  $(".formback").submit();
});

$(document).on("click", ".msgstaff", function () {
  var id = $(this).attr("id");
  var id_arr = id.split("_");
  var senderid = id_arr[1];
  var jobid = id_arr[2];
  var msgid = id_arr[3];
  var msgto = id_arr[4];
  $("#jobid").val(jobid);
  $("#msgid").val(msgid);
  $("#staff_id").val(senderid);
  $("#msgto").text(msgto);
  $(".replyboxmeg").show();
});

function hasNumber(myString) {
  return /\d/.test(myString);
}

$(document).on("submit", "#replyform", function () {
  if ($(this).valid() == true) {
    var reply = $("#reply").val();
    if (hasNumber(reply)) {
      alert("Message must not contain numbers.");
      return false;
    }
    if (reply.includes("@")) {
      alert("Message must not contain @.");
      return false;
    }

    $.ajax({
      type: "POST",
      dataType: "json",
      url: siteurl + "sendmsg.php",
      cache: false,
      data: $("#replyform").serialize(),
      beforeSend: function () {
        $("#mysendmsg").val("Sending...").attr("disabled", "disabled");
      },
      complete: function () {
        $("#mysendmsg").val("Reply").removeAttr("disabled");
      },
      success: function (json) {
        if (json.success == "1") {
          if (json.usertype == 1) {
            $("#success").text("Your message has been sent.");
            setTimeout(() => {
              $("#success").text("");
            }, 6000);
          } else if (json.usertype == 2) {
            $("#msgsuccess").text("Your message has been sent.");
            setTimeout(() => {
              $("#msgsuccess").text("");
            }, 6000);
          }

          $("#replyform").trigger("reset");
          $("#mymsg").val("");
          $(".nomsg").hide();
        } else {
          alert("Message sending failed, please try again.");
        }
      },
      error: function (xhr, ajaxOptions, thrownError) {
        console.log(
          thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
        );
      },
    });
    return false;
  }
});

$(document).on("click", ".viewempmsgs", function () {
  var id = $(this).attr("id");
  var id_arr = id.split("_");
  var senderid = id_arr[1];
  var jobid = id_arr[2];
  $.ajax({
    type: "POST",
    dataType: "json",
    url: siteurl + "viewmsg2.php",
    cache: false,
    data: { id: senderid, jobid: jobid },
    beforeSend: function () {
      $(".msgview").text("Fetching...").attr("disabled", "disabled");
    },
    complete: function () {
      $(".msgview").text("View").removeAttr("disabled");
    },
    success: function (json) {
      if (json.success == "1") {
        $("#existingmsg").html(json.msgs);
        $("#msgstaff").text(json.staff);
        $("#unread_" + senderid + "_" + jobid).hide();
        $("#rejectModal").modal("show");
      } else {
        alert("Some problem occurred, please try again.");
      }
    },
    error: function (xhr, ajaxOptions, thrownError) {
      console.log(
        thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
      );
    },
  });
});

$(document).on("click", ".msgemp", function () {
  var id = $(this).attr("id");
  var id_arr = id.split("_");
  var msgid = id_arr[3];
  $("#msgid").val(msgid);
  $(".replyboxmeg").show();
});

$(document).on("click", "#msg_job_emp", function () {
  $(".replyboxmeg").show();
});

$(document).on("click", ".viewstaffmsgs", function () {
  var id = $(this).attr("id");
  var id_arr = id.split("_");
  var senderid = id_arr[1];
  var jobid = id_arr[2];
  $.ajax({
    type: "POST",
    dataType: "json",
    url: siteurl + "viewstaffmsg2.php",
    cache: false,
    data: { id: senderid, jobid: jobid },
    beforeSend: function () {
      $(".msgview").text("Fetching...").attr("disabled", "disabled");
    },
    complete: function () {
      $(".msgview").text("View").removeAttr("disabled");
    },
    success: function (json) {
      if (json.success == "1") {
        $("#existingmsg").html(json.msgs);
        $("#msgstaff").text(json.emp);
        $("#unread_" + senderid + "_" + jobid).hide();
        $("#rejectModal").modal("show");
      } else {
        alert("Some problem occurred, please try again.");
      }
    },
    error: function (xhr, ajaxOptions, thrownError) {
      console.log(
        thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
      );
    },
  });
});

$(document).on("click", ".viewempmsgs2", function () {
  var id = $(this).attr("id");
  var id_arr = id.split("_");
  var senderid = id_arr[1];
  var jobid = id_arr[2];
  var msgid = id_arr[3];
  var clickedmsg = $("#clickedmsg").val();
  $.ajax({
    type: "POST",
    dataType: "json",
    url: siteurl + "viewmsg2.php",
    cache: false,
    data: { id: senderid, jobid: jobid },
    beforeSend: function () {
      $("#msgstaff").html(
        "Fetching messages <img src='" +
          siteurl +
          "images/sp-loading.gif' alt=''>"
      );
    },
    complete: function () {
      //$("#existingmsg").html("View");
    },
    success: function (json) {
      if (json.success == "1") {
        $("#existingmsg").html(json.msgs);
        $("#msgstaff").hide();
        $("#msgid").val(msgid);
        $("#staff_id").val(senderid);
        $("#jobid").val(jobid);
        $("#unrd_" + senderid + "_" + jobid + "_" + msgid).hide();
        $("#mymsgbox").show();
        $("#existingmsg").scrollTop($("#existingmsg")[0].scrollHeight);
        if (window.innerWidth < 768) {
          $(".messemployee").hide();
        }
        if (json.clockedout == 1) {
          //$("#showsendbox").hide();
        } else {
          //$("#showsendbox").show();
        }

        if (clickedmsg != "") {
          $("#" + clickedmsg).removeClass("current");
        }
        $("#" + id).addClass("current");
        $("#clickedmsg").val(id);

        //$("#unread_" + senderid + "_" + jobid).hide();
      } else {
        alert("Some problem occurred, please try again.");
      }
    },
    error: function (xhr, ajaxOptions, thrownError) {
      console.log(
        thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
      );
    },
  });
});

$(document).on("click", ".viewstaffmsgs2", function () {
  var id = $(this).attr("id");
  var id_arr = id.split("_");
  var senderid = id_arr[1];
  var jobid = id_arr[2];
  var msgid = id_arr[3];
  var clickedmsg = $("#clickedmsg").val();
  $.ajax({
    type: "POST",
    dataType: "json",
    url: siteurl + "viewstaffallmsg.php",
    cache: false,
    data: { id: senderid, jobid: jobid },
    beforeSend: function () {
      $("#msgstaff").html(
        "Fetching messages <img src='" +
          siteurl +
          "images/sp-loading.gif' alt=''>"
      );
    },
    complete: function () {
      //$("#existingmsg").html("View");
    },
    success: function (json) {
      if (json.success == "1") {
        $("#existingmsg").html(json.msgs);
        $("#msgstaff").hide();
        $("#msgid").val(msgid);
        $("#emp_id").val(senderid);
        $("#jobid").val(jobid);
        $("#unrd_" + senderid + "_" + jobid + "_" + msgid).hide();
        $("#mymsgbox").show();
        $("#existingmsg").scrollTop($("#existingmsg")[0].scrollHeight);
        if (window.innerWidth < 768) {
          $(".messemployee").hide();
        }
        if (json.clockedout == 1) {
          //$("#showsendbox").hide();
        } else {
          //$("#showsendbox").show();
        }

        if (clickedmsg != "") {
          $("#" + clickedmsg).removeClass("current");
        }
        $("#" + id).addClass("current");
        $("#clickedmsg").val(id);
        //$("#unread_" + senderid + "_" + jobid).hide();
      } else {
        alert("Some problem occurred, please try again.");
      }
    },
    error: function (xhr, ajaxOptions, thrownError) {
      console.log(
        thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
      );
    },
  });
});

$(document).on("click", "#backtojob", function () {
  location.href = siteurl + "myjobs";
});

$(document).on("click", ".loadquery", function () {
  var id = $(this).attr("id");
  var id_arr = id.split("_");
  var jobid = id_arr[1];
  $.ajax({
    type: "POST",
    dataType: "json",
    url: siteurl + "loadqueries.php",
    cache: false,
    data: { jobid: jobid },
    beforeSend: function () {
      $("#view_" + jobid).attr("disabled", "disabled");
    },
    complete: function () {
      $("#view_" + jobid).removeAttr("disabled");
    },
    success: function (json) {
      if (json.success == "1") {
        $("#existingmsg").html(json.msgs);
        $("#rejectModal").modal("show");
      } else {
        alert("Some problem occurred, please try again.");
      }
    },
    error: function (xhr, ajaxOptions, thrownError) {
      console.log(
        thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
      );
    },
  });
});

var typingTimer;
var doneTypingInterval = 1000;
var $input = $("#myaddress");

//on keyup, start the countdown
$input.on("keyup", function () {
  if ($(this).val() != "") {
    //$("#loading").show();
  } else {
    //$("#slctloc").val('');
    $("#loading").hide();
  }

  clearTimeout(typingTimer);

  typingTimer = setTimeout(doneTyping, doneTypingInterval);
});

//on keydown, clear the countdown
$input.on("keydown", function () {
  clearTimeout(typingTimer);
});

function doneTyping() {
  //do something
  var mylocations = "";
  var keyword = $input.val();
  if (keyword != "") {
    if (keyword.length > 2) {
      $.ajax({
        type: "POST",
        dataType: "json",
        url: siteurl + "getplace.php",
        cache: false,
        data: { keyword: keyword, action: "getplaces" },
        beforeSend: function () {
          $("#loading").show();
        },
        complete: function () {
          $("#loading").hide();
        },
        success: function (json) {
          if (json.success == "1") {
            if (json.locations.length > 0) {
              $.each(json.locations, function (i, row) {
                mylocations +=
                  "<li><a href='javascript:void(0);' data-id='" +
                  row.place_id +
                  "' class='mylocations'>" +
                  row.mydesc +
                  "</a></li>";
              });
              $("#mylocations ul").html(mylocations);
              $("#mylocations").show();
            }
          }
        },
        error: function (xhr, ajaxOptions, thrownError) {
          console.log(
            thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
          );
        },
      });
    }
  } else {
    $("#mylocations").hide();
  }
}

$(document).on("click", ".mylocations", function () {
  var placeid = $(this).data("id");
  var mytext = $(this).text();
  if (placeid != "") {
    $("#myaddress").val(mytext);
    //$("#slctloc").val(placeid);
    $("#mylocations").hide();
  }
});

$(document).on("click", "#startbreak", function () {
  var jobid = $("#jobid").val();
  var userid = $("#userid").val();
  if (window.confirm("Are you sure to start break?") == true) {
    if (jobid != "" && userid != "") {
      $.ajax({
        type: "POST",
        dataType: "json",
        url: siteurl + "startbrk.php",
        cache: false,
        data: { jobid: jobid, userid: userid, action: "startbrk" },
        beforeSend: function () {
          $("#startbreak").text("Processing...").attr("disabled", "disabled");
        },
        complete: function () {
          $("#startbreak").text("Start Break").removeAttr("disabled");
        },
        success: function (json) {
          if (json.success == 1) {
            //$("#breakdiv").hide();
            //$("#clckot").show();
            $("#breakstartdiv").show();
            $(".brkstartcode").val("");
          } else {
            alert("Sorry!! You have not ended the previous break.");
          }
        },
        error: function (xhr, ajaxOptions, thrownError) {
          console.log(
            thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
          );
        },
      });
    }
  }
});

$(document).on("click", "#breakcodesub", function () {
  var jobid = $("#jobid").val();
  var userid = $("#userid").val();
  var brkstartcode = $(".brkstartcode").val();
  if (!isNaN(brkstartcode) && jobid != "" && userid != "") {
    $.ajax({
      type: "POST",
      dataType: "json",
      url: siteurl + "subbrkstartcode.php",
      cache: false,
      data: {
        jobid: jobid,
        userid: userid,
        brkstartcode: brkstartcode,
        action: "brkcodesub",
      },
      beforeSend: function () {
        $("#breakcodesub").text("Processing...").attr("disabled", "disabled");
      },
      complete: function () {
        $("#breakcodesub").text("Submit").removeAttr("disabled");
      },
      success: function (json) {
        if (json.success == 1) {
          $("#breakstartdiv").hide();
          //$("#clckot").show();
          $("#breaksucces").show();
          $("#endbreaksuccess").hide();
        } else {
          alert("Sorry!! Wrong code entered.");
        }
      },
      error: function (xhr, ajaxOptions, thrownError) {
        console.log(
          thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
        );
      },
    });
  }
});

$(document).on("click", "#endbreak", function () {
  var jobid = $("#jobid").val();
  var userid = $("#userid").val();
  if (window.confirm("Are you sure to end break?") == true) {
    if (jobid != "" && userid != "") {
      $.ajax({
        type: "POST",
        dataType: "json",
        url: siteurl + "endbrk.php",
        cache: false,
        data: { jobid: jobid, userid: userid, action: "endbrk" },
        beforeSend: function () {
          $("#endbreak").text("Processing...").attr("disabled", "disabled");
        },
        complete: function () {
          $("#endbreak").text("End Break").removeAttr("disabled");
        },
        success: function (json) {
          if (json.success == 1) {
            $("#breaksucces").hide();
            $("#breakenddiv").show();
            $(".breakendcode").val("");
          } else {
            alert("Sorry!! You have not started the break.");
          }
        },
        error: function (xhr, ajaxOptions, thrownError) {
          console.log(
            thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
          );
        },
      });
    }
  }
});

$(document).on("click", "#breakendcodesub", function () {
  var jobid = $("#jobid").val();
  var userid = $("#userid").val();
  var breakendcode = $(".breakendcode").val();
  if (!isNaN(breakendcode) && jobid != "" && userid != "") {
    $.ajax({
      type: "POST",
      dataType: "json",
      url: siteurl + "subbrkendcode.php",
      cache: false,
      data: {
        jobid: jobid,
        userid: userid,
        breakendcode: breakendcode,
        action: "brkendcodesub",
      },
      beforeSend: function () {
        $("#breakendcodesub")
          .text("Processing...")
          .attr("disabled", "disabled");
      },
      complete: function () {
        $("#breakendcodesub").text("Submit").removeAttr("disabled");
      },
      success: function (json) {
        if (json.success == 1) {
          $("#breakenddiv").hide();
          $("#endbreaksuccess").show();
          $("#breaksucces").hide();
        } else {
          alert("Sorry!! Wrong code entered.");
        }
      },
      error: function (xhr, ajaxOptions, thrownError) {
        console.log(
          thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
        );
      },
    });
  }
});

$(document).on("click", ".myresume", function () {
  var resumepath;
  var id = $(this).attr("id");
  var user_arr = id.split("_");
  var userid = user_arr[1];
  if (!isNaN(userid) && userid != "") {
    $("#resume .modal-body embed").attr("src", "");
    $("#resume .modal-body iframe").attr("src", "");
    $.ajax({
      type: "POST",
      dataType: "json",
      url: siteurl + "fetchresume.php",
      cache: false,
      data: { userid: userid, action: "fetchstaffresume" },
      beforeSend: function () {
        $("#" + id)
          .html(
            "<i class='fa fa-file-text' aria-hidden='true'></i>&nbsp; Loading..."
          )
          .attr("disabled", "disabled");
      },
      complete: function () {
        $("#" + id)
          .html(
            "<i class='fa fa-file-text' aria-hidden='true'></i>&nbsp; Resume"
          )
          .removeAttr("disabled");
      },
      success: function (json) {
        if (json.success == 1) {
          resumepath = siteurl + "uploads/resumes/" + json.resume;
          if (json.ext == "pdf") {
            $("#resume .modal-body embed").attr("src", resumepath);
            $("#resume .modal-body iframe").hide();
          }
          if (json.ext == "docx") {
            //var docpath = "https://docs.google.com/gview?url=" + resumepath + "&embedded=true";
            var docpath =
              "https://view.officeapps.live.com/op/embed.aspx?src=" +
              resumepath +
              "";
            $("#resume .modal-body iframe").attr("src", docpath);
            $("#resume .modal-body embed").hide();
          }

          $("#resume").modal("show");
        } else {
          alert("Sorry!! Some problem occurred.");
        }
      },
      error: function (xhr, ajaxOptions, thrownError) {
        console.log(
          thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
        );
      },
    });
  }
});

$(document).on("click", ".mycl", function () {
  var resumepath;
  var id = $(this).attr("id");
  var user_arr = id.split("_");
  var userid = user_arr[1];
  if (!isNaN(userid) && userid != "") {
    $("#letter .modal-body embed").attr("src", "");
    $("#letter .modal-body iframe").attr("src", "");
    $.ajax({
      type: "POST",
      dataType: "json",
      url: siteurl + "fetchcl.php",
      cache: false,
      data: { userid: userid, action: "fetchstaffcl" },
      beforeSend: function () {
        $("#" + id)
          .html(
            "<i class='fa fa-file-text' aria-hidden='true'></i>&nbsp; Loading..."
          )
          .attr("disabled", "disabled");
      },
      complete: function () {
        $("#" + id)
          .html(
            "<i class='fa fa-file-text' aria-hidden='true'></i>&nbsp; Cover Letter"
          )
          .removeAttr("disabled");
      },
      success: function (json) {
        if (json.success == 1) {
          resumepath = siteurl + "uploads/resumes/" + json.cover_letter;
          if (json.ext == "pdf") {
            $("#letter .modal-body embed").attr("src", resumepath);
            $("#letter .modal-body iframe").hide();
          }
          if (json.ext == "docx") {
            var docpath =
              "https://view.officeapps.live.com/op/embed.aspx?src=" +
              resumepath +
              "";
            $("#letter .modal-body iframe").attr("src", docpath);
            $("#letter .modal-body embed").hide();
          }

          $("#letter").modal("show");
        } else {
          alert("Sorry!! Some problem occurred.");
        }
      },
      error: function (xhr, ajaxOptions, thrownError) {
        console.log(
          thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
        );
      },
    });
  }
});

$(document).on("click", ".myeml", function () {
  var id = $(this).attr("id");
  var user_arr = id.split("_");
  var userid = user_arr[1];
  if (!isNaN(userid) && userid != "") {
    $.ajax({
      type: "POST",
      dataType: "json",
      url: siteurl + "fetchemail.php",
      cache: false,
      data: { userid: userid, action: "fetchstaffemail" },
      beforeSend: function () {
        $("#" + id)
          .html(
            "<i class='fa fa-envelope' aria-hidden='true'></i>&nbsp; Loading..."
          )
          .attr("disabled", "disabled");
      },
      complete: function () {
        $("#" + id)
          .html("<i class='fa fa-envelope' aria-hidden='true'></i>&nbsp; Email")
          .removeAttr("disabled");
      },
      success: function (json) {
        if (json.success == 1) {
          $("#eml .modal-body p").html(json.email);

          $("#eml").modal("show");
        } else {
          alert("Sorry!! Some problem occurred.");
        }
      },
      error: function (xhr, ajaxOptions, thrownError) {
        console.log(
          thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
        );
      },
    });
  }
});

$(document).on("submit", "#review", function () {
  if ($(this).valid() == true) {
    $.ajax({
      type: "POST",
      dataType: "json",
      url: siteurl + "submtrating.php",
      cache: false,
      data: $(this).serialize(),
      beforeSend: function () {
        $("#subrate").val("Submitting...").attr("disabled", "disabled");
      },
      complete: function () {
        $("#subrate").val("Submit").removeAttr("disabled");
      },
      success: function (json) {
        if (json.success == 1) {
          $("#review").trigger("reset");
          $("#review").hide();
          $("#ratesuccess").show();
        } else {
          alert("Sorry!! Some problem occurred.");
        }
      },
      error: function (xhr, ajaxOptions, thrownError) {
        console.log(
          thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
        );
      },
    });
    return false;
  }
});

/*mobiscroll.setOptions({
  locale: mobiscroll.localeEn,         
  theme: 'ios',                       
  themeVariant: 'light'
});

$(function () {

  $('.timepicker2').mobiscroll().datepicker({
    controls: ['time'],
    timeFormat: 'HH:mm'
  });

});*/

$(function () {
  var today = new Date();
  var dd = today.getDate();
  var mm = today.getMonth() + 1; //January is 0!
  var yyyy = today.getFullYear();

  if (dd < 10) {
    dd = "0" + dd;
  }

  if (mm < 10) {
    mm = "0" + mm;
  }

  today = yyyy + "-" + mm + "-" + dd;

  /*myRange = $('#demo-multi-day').mobiscroll().datepicker({
    controls: ['calendar'],
    select: 'range',
    calendarType: 'month',
    pages: 2,
    min: today
  }).mobiscroll('getInst');*/
});

$(document).on("click", "#interested", function () {
  var jobid = $("#jobid").val();
  $.ajax({
    type: "POST",
    dataType: "json",
    url: siteurl + "getmyjobdates.php",
    cache: false,
    data: { jobid: jobid, action: "getmyjob" },
    beforeSend: function () {},
    complete: function () {},
    success: function (json) {
      if (json.success == 1) {
        if (json.strttime != "" && json.endtime != "") {
          if (json.jobsrtdate != "" && json.jobenddate != "") {
            $("#availability h5").html(
              json.title +
                " (" +
                json.jobsrtdate +
                " at " +
                json.strttime +
                " - " +
                json.jobenddate +
                " at " +
                json.endtime +
                ")"
            );
            $("#fullavl").html(
              json.jobsrtdate +
                " at " +
                json.strttime +
                " - " +
                json.jobenddate +
                " at " +
                json.endtime
            );
          } else if (json.jobsrtdate != "") {
            if (json.covertype == 1) {
              $("#availability h5").html(
                json.title +
                  " (" +
                  json.jobsrtdate +
                  " at " +
                  json.strttime +
                  " - " +
                  json.endtime +
                  ")"
              );
              $("#fullavl").html(
                json.jobsrtdate + " at " + json.strttime + " - " + json.endtime
              );
            } else if (json.covertype == 2) {
              $("#availability h5").html(
                json.title +
                  " (From " +
                  json.jobsrtdate +
                  " at " +
                  json.strttime +
                  " - " +
                  json.endtime +
                  ")"
              );
              $("#fullavl").html(
                "From " +
                  json.jobsrtdate +
                  " at " +
                  json.strttime +
                  " - " +
                  json.endtime
              );
            }
          } else {
            $("#availability h5").html(
              json.title + " (" + json.strttime + " - " + json.endtime + ")"
            );
            $("#fullavl").html(json.strttime + " - " + json.endtime);
          }
        } else {
          if (json.jobsrtdate != "" && json.jobenddate != "") {
            $("#availability h5").html(
              json.title +
                " (" +
                json.jobsrtdate +
                " - " +
                json.jobenddate +
                ")"
            );
            if (json.is_shift == "1") {
              $("#fullavl").html(
                json.jobsrtdate +
                  " - " +
                  json.jobenddate +
                  " " +
                  json.myallshifts +
                  " " +
                  json.shifttimes_str
              );
            } else if (json.is_shift == "2") {
              $("#fullavl").html(
                json.jobsrtdate + " - " + json.jobenddate + " " + json.noshift
              );
            }
          } else if (json.jobsrtdate != "") {
            if (json.is_shift == "1") {
              var shifttexts = json.myallshifts + " " + json.shifttimes_str;
            } else if (json.is_shift == "2") {
              var shifttexts = json.noshift;
            }
            if (json.covertype == 1) {
              $("#availability h5").html(
                json.title +
                  " (" +
                  json.jobsrtdate +
                  " at " +
                  json.strttime +
                  " - " +
                  json.endtime +
                  ") " +
                  shifttexts
              );
              $("#fullavl").html(
                json.jobsrtdate +
                  " at " +
                  json.strttime +
                  " - " +
                  json.endtime +
                  " " +
                  shifttexts
              );
            } else if (json.covertype == 2) {
              $("#availability h5").html(
                json.title +
                  " (From " +
                  json.jobsrtdate +
                  " at " +
                  json.strttime +
                  " - " +
                  json.endtime +
                  ") " +
                  shifttexts
              );
              $("#fullavl").html(
                "From " +
                  json.jobsrtdate +
                  " at " +
                  json.strttime +
                  " - " +
                  json.endtime +
                  " " +
                  shifttexts
              );
            }
          } else {
            $("#availability h5").html(json.title);
            if (json.is_shift == "1")
              $("#fullavl").html(json.myallshifts + " " + json.shifttimes_str);
            else if (json.is_shift == "2") $("#fullavl").html(json.noshift);
          }
        }

        //$("#caldates").html(json.calendar);
        //$("#mydaternge").show();

        $("#start_date").val(json.start_date);
        $("#end_date").val(json.end_date);
        $("#workmode").val(json.workmode);
        if (json.workmode == "2") {
          $("#mytimes").show();
        } else {
          $("#mytimes").hide();
        }
      } else {
        alert("Sorry!! Some problem occurred.");
      }
    },
    error: function (xhr, ajaxOptions, thrownError) {
      console.log(
        thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
      );
    },
  });
  $("#availability").modal("show");
});

$('input[name="select_mode"]').on("click", function () {
  var start_date = $("#start_date").val();
  var end_date = $("#end_date").val();
  var workmode = $("#workmode").val();
  var mydate_arr;
  var date_arr;
  var dispdate;
  var mydate;

  var option = $('input[name="select_mode"]:checked').val();
  if (option == 1) {
    $("#selectrange").show();
    $("#selectspdates").hide();
    $("#mytimes").html("");
    $("#myselctdate").val("");
    flatpickr("#selectrange", {
      mode: "range",
      minDate: start_date,
      maxDate: end_date,
      dateFormat: "Y-m-d",
      inline: true,
      onChange: function (dateStr, dateObj) {
        if (dateObj != "") {
          $("#myselctdate").val(dateObj);
          if (workmode == 2) {
            $("#timesdiv #avltimes").show();
            $("#timesdiv #avldates").hide();
            $("#mytimes").html("");
            var mytimes =
              '<div class="row splgapbt"><div class="col-md-6 col-6"><label>Enter Time</label><input name="starttime[]" class="form-control timepicker" id="starttime" type="text" placeholder="Start" value="" autocomplete="off"></div><div class="col-md-6 col-6"><label>&nbsp;</label><input name="endtime[]" id="endtime" class="form-control timepicker" type="text" placeholder="End" value="" autocomplete="off"></div></div>';
            $("#mytimes").append(mytimes);

            $("input.timepicker").timepicker({
              timeFormat: "HH:mm",
              dynamic: false,
              dropdown: true,
              scrollbar: true,
              interval: 30,
              maxTime: "23:00",
              startTime: "06:00",
            });
          } else {
            $("#mytimes").html("");
            /*$("#timesdiv #avldates").show();
              $("#timesdiv #avltimes").hide();
              var mytimes = '<input type="hidden" id="myseldate_' + mydateid + '" name="mydates[]" value="' + mydate + '"><div class="row"><div class="col-md-12"><input name="starttime[]" type="hidden" value="00:00:00"><input name="endtime[]" type="hidden" value="00:00:00"></div></div>';
              $("#mydates").append(mytimes);*/
          }
          //return false;
        }
      },
    });
    flatpickr("#selectspdates", {
      mode: "multiple",
      minDate: start_date,
      maxDate: end_date,
      dateFormat: "Y-m-d",
    });
  } else {
    var myselctdate_arr;
    var addtimerow = 0;
    $("#selectrange").hide();
    $("#selectspdates").show();
    $("#mytimes").html("");
    $("#myselctdate").val("");
    $("#alreadyselctdate").val("");

    flatpickr("#selectspdates", {
      mode: "multiple",
      minDate: start_date,
      maxDate: end_date,
      dateFormat: "Y-m-d",
      inline: true,
      onChange: function (dateStr, dateObj) {
        if (dateObj != "") {
          var myselctdate = $("#alreadyselctdate").val();

          if (dateObj.includes(",")) {
            date_arr = dateObj.split(", ");

            var no_of_ele = date_arr.length;

            if (myselctdate != "") {
              myselctdate_arr = myselctdate.split(", ");
              var no_of_ele_pre = myselctdate_arr.length;

              if (no_of_ele > no_of_ele_pre) {
                addtimerow = 1;
                var lastdate = date_arr[no_of_ele - 1];
                mydate_arr = lastdate.split("-");
                dispdate =
                  mydate_arr[2] + "/" + mydate_arr[1] + "/" + mydate_arr[0];
              } else {
                addtimerow = 0;
                for (var i = 0; i < no_of_ele_pre; i++) {
                  if (!date_arr.includes(myselctdate_arr[i])) {
                    var uncheckeddate = myselctdate_arr[i];
                  }
                }
                $("#totaldttime_" + uncheckeddate).remove();
              }
            }
          } else {
            mydate_arr = dateObj.split("-");
            if (myselctdate != "") {
              myselctdate_arr = myselctdate.split(", ");
              var no_of_ele_pre = myselctdate_arr.length;

              if (no_of_ele_pre == 2) {
                for (var i = 0; i < no_of_ele_pre; i++) {
                  if (!dateObj.includes(myselctdate_arr[i])) {
                    var uncheckeddate = myselctdate_arr[i];
                  }
                }
                addtimerow = 0;
                $("#totaldttime_" + uncheckeddate).remove();
              }
            } else {
              dispdate =
                mydate_arr[2] + "/" + mydate_arr[1] + "/" + mydate_arr[0];
              addtimerow = 1;
            }
          }
          var mydateid = mydate_arr[2] + mydate_arr[1] + mydate_arr[0];
          mydate = mydate_arr[0] + "-" + mydate_arr[1] + "-" + mydate_arr[2];
          if (addtimerow == 1) {
            if (workmode == 2) {
              $("#timesdiv #avltimes").show();
              $("#timesdiv #avldates").hide();
              var mytimes =
                '<div id="totaldttime_' +
                mydate +
                '"><input type="hidden" id="myseldate_' +
                mydateid +
                '" name="mydates[]" value="' +
                mydate +
                '"><div id="mytimediv_' +
                mydateid +
                '"><label class="seldtpoprt">' +
                dispdate +
                '</label><div class="row splgapbt"><div class="col-md-5 col-4"><input name="starttime[]" id="starttm_' +
                mydateid +
                '" class="form-control timepicker spdtstrttime" type="text" placeholder="Start" value="" autocomplete="off"></div><div class="col-md-5 col-4"><input name="endtime[]" id="endtm_' +
                mydateid +
                '" class="form-control timepicker spdtendtime" type="text" placeholder="End" value="" autocomplete="off"></div><div class="col-md-2 col-4"><a href="javascript:void(0);" class="jobapplybutn addmytime" id="mytimeadd_' +
                mydateid +
                '">Add</a></div></div></div></div>';
              $("#mytimes").append(mytimes);

              $("input.timepicker").timepicker({
                timeFormat: "HH:mm",
                dynamic: false,
                dropdown: true,
                scrollbar: true,
                interval: 30,
                maxTime: "23:00",
                startTime: "06:00",
              });
            } else {
              //$("#mytimes").html("");
              $("#timesdiv #avldates").show();
              $("#timesdiv #avltimes").hide();
              var mytimes =
                '<div id="totaldttime_' +
                mydate +
                '"><input type="hidden" id="myseldate_' +
                mydateid +
                '" name="mydates[]" value="' +
                mydate +
                '"><div class="row"><div class="col-md-12"><input name="starttime[]" type="hidden" value="00:00:00"><input name="endtime[]" type="hidden" value="00:00:00"></div></div></div>';
              $("#mydates").append(mytimes);
            }
          }
          $("#alreadyselctdate").val(dateObj);
          $("#myselctdate").val(dateObj);
        } else {
          $("#mytimes").html("");
          $("#mydates").html("");
          $("#myselctdate").val("");
          $("#alreadyselctdate").val("");
        }
      },
    });
    flatpickr("#selectrange", {
      mode: "range",
      minDate: start_date,
      maxDate: end_date,
      dateFormat: "Y-m-d",
    });
  }
});

$(document).on("click", ".addmytime", function () {
  var id = $(this).attr("id");
  var id_arr = id.split("_");
  var fromtime = $("#starttm_" + id_arr[1]).val();
  var totime = $("#endtm_" + id_arr[1]).val();
  if (fromtime != "" && totime != "") {
    if (fromtime < totime) {
      $("#mytimediv_" + id_arr[1]).hide();
      alert("This time is added.");
    } else {
      alert("Start time must be less than end time.");
    }
  } else {
    alert("Please add time for this date.");
  }
});

$('input[name="avlblty"]').on("click", function () {
  var option = $('input[name="avlblty"]:checked').val();
  var today = new Date();
  var dd = today.getDate();
  var mm = today.getMonth() + 1; //January is 0!
  var yyyy = today.getFullYear();

  if (dd < 10) {
    dd = "0" + dd;
  }

  if (mm < 10) {
    mm = "0" + mm;
  }

  today = yyyy + "-" + mm + "-" + dd;
  if (option == 1) {
    var firstdate = $("#availble_date1").val();
    var lastdate = $("#availble_date2").val();
    $("#fullavl").show();
    $("#mydaternge").hide();
    //$('#mytable').hide();
    $(".jobapplybutn").show();
    //$('#demo-multi-day').val('');
    /*$('#demo-multi-day').mobiscroll().datepicker({
      returnFormat: 'iso8601'
    });
    $('#demo-multi-day').mobiscroll('setVal', [firstdate, lastdate]);
    $('#demo-multi-day').mobiscroll('getVal');*/

    //myRange.setVal([firstdate, lastdate]);
  } else {
    $("#fullavl").hide();
    $("#mydaternge").show();
    //$('#mytable').show();
    $(".jobapplybutn").show();
    //$('#demo-multi-day').val('');
    //$('#demo-multi-day').attr("readonly","readonly");
    /*$('#demo-multi-day').mobiscroll().datepicker({
      controls: ['calendar'],
      display: 'inline',
      calendarType: 'month',
      pages: 2,
      selectMultiple: true,
      touchUi: true,
      min: today,
      dateFormat: 'YYYY-MM-DD'
    });*/
  }
});

$(document).on("submit", "#staffreview", function () {
  if ($(this).valid() == true) {
    $.ajax({
      type: "POST",
      dataType: "json",
      url: siteurl + "submtratingstaff.php",
      cache: false,
      data: $(this).serialize(),
      beforeSend: function () {
        $("#subrate").val("Submitting...").attr("disabled", "disabled");
      },
      complete: function () {
        $("#subrate").val("Submit").removeAttr("disabled");
      },
      success: function (json) {
        if (json.success == 1) {
          $("#staffreview").trigger("reset");
          $("#staffreview").hide();
          $("#ratesuccess").show();
        } else {
          alert("Sorry!! Some problem occurred.");
        }
      },
      error: function (xhr, ajaxOptions, thrownError) {
        console.log(
          thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
        );
      },
    });
    return false;
  }
});

$(document).on("click", "#showcats", function () {
  $("#allcats").slideToggle();
});

/*$(document).on('blur', '#allcats', function () {
  
  if($("#allcats").css('display') != 'none'){
	  $("#allcats").slideUp();
  }
});*/
$(document).on("click", function (event) {
  // If the target is not the container or a child of the container, then process
  // the click event for outside of the container.
  if (
    $(event.target).closest(".mycatsec").length === 0 &&
    $("#allcats").css("display") != "none"
  ) {
    $("#allcats").slideUp();
  }
});

/*$(document).on('click', '.mycats', function () {
  var myid = $(this).attr("id");
  var id_arr = myid.split("_");
  var catid = id_arr[1];
	
  if($(this).prop('checked') == true){
    if(!isNaN(catid)){
      $.ajax({
        type: "POST",
        dataType: "json",
        url: siteurl + "getsubcat.php",
        cache: false,
        data: {catid: catid},
        beforeSend: function () {  },
        complete: function () {  },
        success: function (json) {

        if (json.success == 1) {
          if (json.subcat.length > 0) {
            var subcats = '<ul>';
            $.each(json.subcat, function (i, row) {
            subcats += '<li><input type="checkbox" class="myresctcats" name="catid[]" value="'+row.id+'" data-value="'+row.category+'" />&nbsp; '+row.category+'</li>';
            });
            subcats += '</ul>';
            
            
            $("#maincatli_"+catid).html(subcats);
            $("#maincatli_"+catid).show();
          }
        }
        else {
          alert("Sorry!! Some problem occurred.");
        }

        },
        error: function (xhr, ajaxOptions, thrownError) {
        console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
      });
    }
  }
  else{
    $("#maincatli_"+catid).html("");
  }
});*/

$(document).on("submit", "#stffprofile", function () {
  var error = false;
  if ($(".mycatsec input[type=checkbox]:checked").length == 0) {
    alert("Please check at least one category.");
    error = true;
  } else {
    if ($(".mysubcatsec input[type=checkbox]:checked").length == 0) {
      alert("Please check at least one job category.");
      error = true;
    } else {
      error = false;
    }
  }

  if (error == true) return false;
  else return true;
});

$(document).on("click", ".myresctcats", function () {
  var myresctcats = [];
  $.each($("input[name='catid[]']:checked"), function () {
    myresctcats.push($(this).val());
  });

  if (myresctcats.length > 10) {
    alert("Maximum 10 job categories are allowed.");
    $(this).prop("checked", false);
  }
});

/*$(document).on('submit', '#stffprofile', function () {
  if ($('.mysubcatsec input[type=checkbox]:checked').length == 0) {
    alert("Please check at least one category.");
    return false;
  }
  else
    return true;

});*/

$(document).on("click", ".mycats", function () {
  var myid = $(this).attr("id");
  var id_arr = myid.split("_");
  var catid = id_arr[1];
  var totalcats;
  var myselectedcats;
  //$("#selectdcats ul").html("");
  var subcats;

  var myselectedcats = [];
  if ($(this).prop("checked") == true) {
    $.each($("input[name='maincatid[]']:checked"), function () {
      myselectedcats.push($(this).val());
    });
    $("#myselectedcats").val(myselectedcats.length);
  } else {
    myselectedcats = $("#myselectedcats").val();
    myselectedcats = myselectedcats - 1;

    if (myselectedcats > 0) {
      if (myselectedcats > 1) {
        $("#showcats").val(myselectedcats + " classifications");
      } else {
        $("#showcats").val(myselectedcats + " classification");
      }
    } else {
      $("#showcats").val("");
    }
    $("#myselectedcats").val(myselectedcats);
  }

  if ($(this).prop("checked") == true) {
    if (myselectedcats.length < 6) {
      if (!isNaN(catid)) {
        var mymaincat = $(this).data("value");
        $.ajax({
          type: "POST",
          dataType: "json",
          url: siteurl + "getsubcat.php",
          cache: false,
          data: { catid: catid },
          beforeSend: function () {},
          complete: function () {},
          success: function (json) {
            if (json.success == 1) {
              totalcats = myselectedcats.length;
              if (totalcats > 1) {
                $("#showcats").val(totalcats + " classifications");
              } else {
                $("#showcats").val(totalcats + " classification");
              }
              /*if (json.subcat.length > 0) {
                var subcats = '<ul>';
                $.each(json.subcat, function (i, row) {
                  subcats += '<li><input type="checkbox" class="myresctcats" name="catid[]" value="' + row.id + '" data-value="' + row.category + '" />&nbsp; ' + row.category + '</li>';
                });
                subcats += '</ul>';
  
                $("#maincatli_" + catid).html(subcats);
                $("#maincatli_" + catid).show();
              }*/

              if (json.subcat.length > 0) {
                subcats =
                  '<li id="subcatli_' +
                  catid +
                  '" style="margin-bottom:10px;"><span>' +
                  mymaincat +
                  "</span>";
                $.each(json.subcat, function (i, row) {
                  subcats +=
                    '<div>&nbsp;&nbsp;&nbsp;&nbsp; <input type="checkbox" id="myresctcats_' +
                    row.id +
                    '" class="myresctcats" name="catid[]" value="' +
                    row.id +
                    '" data-value="' +
                    row.category +
                    '" />&nbsp; ' +
                    row.category +
                    "</div>";
                });
                subcats += "</li>";

                $("#selectdcats ul").prepend(subcats);
                $("#selectdcats").show();
              }
            } else {
              alert("Sorry!! Some problem occurred.");
            }
          },
          error: function (xhr, ajaxOptions, thrownError) {
            console.log(
              thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
            );
          },
        });
      }
    } else {
      alert("Maximum 5 categories are allowed.");
      $(this).prop("checked", false);
    }
  } else {
    $("#subcatli_" + catid).html("");
    $("#subcatli_" + catid).remove();
    if (myselectedcats == 0) {
      $("#selectdcats").hide();
    }
  }
});

$(document).on("click", ".prev", function () {
  var month;
  var id = $(this).attr("id");
  var id_arr = id.split("_");
  var currmonth = id_arr[1];
  var curryear = id_arr[2];
  $.ajax({
    type: "POST",
    dataType: "json",
    url: siteurl + "getcalendar.php",
    cache: false,
    data: { currmonth: currmonth, curryear: curryear, action: "previous" },
    beforeSend: function () {
      $("#load").show();
    },
    complete: function () {
      $("#load").hide();
    },
    success: function (json) {
      if (json.success == "1") {
        $("#caldates2").html(json.calendar);
        $("#current2").html(json.prevmonth + " " + json.year);
        month = json.month;

        $(".prev").prop("id", "prev_" + month + "_" + json.year);
        $(".next").prop("id", "next_" + month + "_" + json.year);
      }
    },
    error: function (xhr, ajaxOptions, thrownError) {
      console.log(
        thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
      );
    },
  });
});

$(document).on("click", ".next", function () {
  var month;
  var id = $(this).attr("id");
  var id_arr = id.split("_");
  var currmonth = id_arr[1];
  var curryear = id_arr[2];
  $.ajax({
    type: "POST",
    dataType: "json",
    url: siteurl + "getcalendar.php",
    cache: false,
    data: { currmonth: currmonth, curryear: curryear, action: "next" },
    beforeSend: function () {
      $("#load").show();
    },
    complete: function () {
      $("#load").hide();
    },
    success: function (json) {
      if (json.success == "1") {
        $("#caldates2").html(json.calendar);
        $("#current2").html(json.nextmonth + " " + json.year);
        month = json.month;

        $(".prev").prop("id", "prev_" + month + "_" + json.year);
        $(".next").prop("id", "next_" + month + "_" + json.year);
      }
    },
    error: function (xhr, ajaxOptions, thrownError) {
      console.log(
        thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
      );
    },
  });
});

$(document).on("click", ".editavl", function () {
  //$("#accept_offer").show();
  var alldates = "";
  var strttime;
  var endtime;
  var id = $(this).attr("id");
  var id_arr = id.split("_");
  var jobid = id_arr[1];
  var userid = id_arr[2];

  $("#jobid").val(jobid);
  $("#userid").val(userid);

  var today = new Date();
  var dd = today.getDate();
  var mm = today.getMonth() + 1; //January is 0!
  var yyyy = today.getFullYear();

  if (dd < 10) {
    dd = "0" + dd;
  }

  if (mm < 10) {
    mm = "0" + mm;
  }
  $("#myavldates").html("");
  today = yyyy + "-" + mm + "-" + dd;
  $.ajax({
    type: "POST",
    dataType: "json",
    url: siteurl + "getavlity.php",
    cache: false,
    data: { jobid: jobid, userid: userid, action: "getavlity" },
    beforeSend: function () {
      $("#" + id)
        .text("Loading...")
        .attr("disabled", "disabled");
    },
    complete: function () {
      $("#" + id)
        .html(
          "<i class='fa fa-pencil' aria-hidden='true'></i> &nbsp; Edit Availability"
        )
        .removeAttr("disabled");
    },
    success: function (json) {
      if (json.success == "1") {
        $("#workmode").val(json.workmode);
        if (json.strttime != "" && json.endtime != "") {
          if (json.jobsrtdate != "" && json.jobenddate != "") {
            $("#availability h5").html(
              json.title +
                " (" +
                json.jobsrtdate +
                " at " +
                json.strttime +
                " - " +
                json.jobenddate +
                " at " +
                json.endtime +
                ")"
            );
            $("#fullavl").html(
              json.jobsrtdate +
                " at " +
                json.strttime +
                " - " +
                json.jobenddate +
                " at " +
                json.endtime
            );
          } else if (json.jobsrtdate != "") {
            if (json.covertype == 1) {
              $("#availability h5").html(
                json.title +
                  " (" +
                  json.jobsrtdate +
                  " at " +
                  json.strttime +
                  " - " +
                  json.endtime +
                  ")"
              );
              $("#fullavl").html(
                json.jobsrtdate + " at " + json.strttime + " - " + json.endtime
              );
            } else if (json.covertype == 2) {
              $("#availability h5").html(
                json.title +
                  " (From " +
                  json.jobsrtdate +
                  " at " +
                  json.strttime +
                  " - " +
                  json.endtime +
                  ")"
              );
              $("#fullavl").html(
                "From " +
                  json.jobsrtdate +
                  " at " +
                  json.strttime +
                  " - " +
                  json.endtime
              );
            }
          } else {
            $("#availability h5").html(
              json.title + " (" + json.strttime + " - " + json.endtime + ")"
            );
            $("#fullavl").html(json.strttime + " - " + json.endtime);
          }
        } else {
          if (json.jobsrtdate != "" && json.jobenddate != "") {
            $("#availability h5").html(
              json.title +
                " (" +
                json.jobsrtdate +
                " - " +
                json.jobenddate +
                ")"
            );
            if (json.is_shift == "1") {
              $("#fullavl").html(
                json.jobsrtdate +
                  " - " +
                  json.jobenddate +
                  " " +
                  json.myallshifts +
                  " " +
                  json.shifttimes_str
              );
            } else if (json.is_shift == "2") {
              $("#fullavl").html(
                json.jobsrtdate + " - " + json.jobenddate + " " + json.noshift
              );
            }
          } else if (json.jobsrtdate != "") {
            if (json.is_shift == "1") {
              var shifttexts = json.myallshifts + " " + json.shifttimes_str;
            } else if (json.is_shift == "2") {
              var shifttexts = json.noshift;
            }
            if (json.covertype == 1) {
              $("#availability h5").html(
                json.title +
                  " (" +
                  json.jobsrtdate +
                  " at " +
                  json.strttime +
                  " - " +
                  json.endtime +
                  ") " +
                  shifttexts
              );
              $("#fullavl").html(
                json.jobsrtdate +
                  " at " +
                  json.strttime +
                  " - " +
                  json.endtime +
                  " " +
                  shifttexts
              );
            } else if (json.covertype == 2) {
              $("#availability h5").html(
                json.title +
                  " (From " +
                  json.jobsrtdate +
                  " at " +
                  json.strttime +
                  " - " +
                  json.endtime +
                  ") " +
                  shifttexts
              );
              $("#fullavl").html(
                "From " +
                  json.jobsrtdate +
                  " at " +
                  json.strttime +
                  " - " +
                  json.endtime +
                  " " +
                  shifttexts
              );
            }
          } else {
            $("#availability h5").html(json.title);
            if (json.is_shift == "1")
              $("#fullavl").html(json.myallshifts + " " + json.shifttimes_str);
            else if (json.is_shift == "2") $("#fullavl").html(json.noshift);
          }
        }

        //$("#caldates").html(json.calendar);
        $("#start_date").val(json.start_date);
        $("#end_date").val(json.end_date);

        if (json.avlblty == 1) {
          $("#complete").prop("checked", true);
          $("#fullavl").show();
          $("#mydaternge").hide();
          //$('#mytable').hide();
        } else {
          $("#part").prop("checked", true);
          $("#fullavl").hide();

          if (json.apply_type == 1) {
            //loading range
            $("#selectrange").show();
            $("#selectspdates").hide();
            $("#mytimes").html("");
            //$("#selectrange").val(json.rangestartdate + " to " + json.rangeenddate);
            if (json.workmode == 2) {
              alldates +=
                "<li>" +
                json.rangestartdatedisp +
                " - " +
                json.rangeenddatedisp +
                "<br><span>(" +
                json.fromtime +
                " - " +
                json.totime +
                ")</span></li>";
            } else {
              alldates +=
                "<li>" +
                json.rangestartdatedisp +
                " - " +
                json.rangeenddatedisp +
                "</li>";
            }
            //alert('"' + json.startdate_cal + '"' + ', ' + '"' + json.enddate_cal + '"');
            var myrange =
              '["' +
              json.startdate_cal +
              '"' +
              ", " +
              '"' +
              json.enddate_cal +
              '"]';
            flatpickr("#selectrange", {
              mode: "range",
              minDate: json.start_date,
              maxDate: json.end_date,
              dateFormat: "Y-m-d",
              //defaultDate: json.availble_dates_str_cal,
              inline: true,
              onChange: function (dateStr, dateObj) {
                if (dateObj != "") {
                  $("#myselctdate").val(dateObj);
                  if (json.workmode == 2) {
                    $("#timesdiv #avltimes").show();
                    $("#timesdiv #avldates").hide();
                    $("#mytimes").html("");
                    var mytimes =
                      '<div class="row splgapbt"><div class="col-md-6 col-6"><label>Enter Time</label><input name="starttime[]" class="form-control timepicker" id="starttime" type="text" placeholder="Start" value="" autocomplete="off"></div><div class="col-md-6 col-6"><label>&nbsp;</label><input name="endtime[]" id="endtime" class="form-control timepicker" type="text" placeholder="End" value="" autocomplete="off"></div></div>';
                    $("#mytimes").append(mytimes);

                    $("input.timepicker").timepicker({
                      timeFormat: "HH:mm",
                      dynamic: false,
                      dropdown: true,
                      scrollbar: true,
                      interval: 30,
                      maxTime: "23:00",
                      startTime: "06:00",
                    });
                  } else {
                    $("#mytimes").html("");

                    /*$("#timesdiv #avldates").show();
                      $("#timesdiv #avltimes").hide();
                      var mytimes = '<input type="hidden" id="myseldate_' + mydateid + '" name="mydates[]" value="' + mydate + '"><div class="row"><div class="col-md-12"><input name="starttime[]" type="hidden" value="00:00:00"><input name="endtime[]" type="hidden" value="00:00:00"></div></div>';
                      $("#mydates").append(mytimes);*/
                  }
                }
              },
            });
            flatpickr("#selectspdates", {
              mode: "multiple",
              minDate: json.start_date,
              maxDate: json.end_date,
              dateFormat: "Y-m-d",
            });
            $("#range").prop("checked", true);
          } else if (json.apply_type == 2) {
            // loading specific dates

            var myselctdate_arr;
            var addtimerow = 0;
            $("#selectrange").hide();
            $("#selectspdates").show();
            $("#mytimes").html("");
            //$("#selectspdates").val(json.availble_dates_str);
            if (json.workmode == 2) {
              $.each(json.avails, function (i, row) {
                alldates +=
                  "<li id='myavldatesli_" +
                  row.availble_id +
                  "'>" +
                  row.availble_date +
                  "<br><span>(" +
                  row.starttime +
                  " - " +
                  row.endtime +
                  ")</span> <a href='javascript:void(0);' id='myavldatesall_" +
                  row.availble_id +
                  "' class='del_availblty'><i class='fa fa-times'></i></a></li>";
              });
            } else {
              $.each(json.avails, function (i, row) {
                alldates +=
                  "<li id='myavldatesli_" +
                  row.availble_id +
                  "'>" +
                  row.availble_date +
                  " <a href='javascript:void(0);' id='myavldatesall_" +
                  row.availble_id +
                  "' class='del_availblty'><i class='fa fa-times'></i></a></li>";
              });
            }
            flatpickr("#selectspdates", {
              mode: "multiple",
              minDate: json.start_date,
              maxDate: json.end_date,
              dateFormat: "Y-m-d",
              //defaultDate: json.availble_dates_str_cal,
              inline: true,
              onChange: function (dateStr, dateObj) {
                if (dateObj != "") {
                  var myselctdate = $("#alreadyselctdate").val();

                  if (dateObj.includes(",")) {
                    date_arr = dateObj.split(", ");

                    var no_of_ele = date_arr.length;

                    if (myselctdate != "") {
                      myselctdate_arr = myselctdate.split(", ");
                      var no_of_ele_pre = myselctdate_arr.length;

                      if (no_of_ele > no_of_ele_pre) {
                        addtimerow = 1;
                        var lastdate = date_arr[no_of_ele - 1];
                        mydate_arr = lastdate.split("-");
                        dispdate =
                          mydate_arr[2] +
                          "/" +
                          mydate_arr[1] +
                          "/" +
                          mydate_arr[0];
                      } else {
                        addtimerow = 0;
                        for (var i = 0; i < no_of_ele_pre; i++) {
                          if (!date_arr.includes(myselctdate_arr[i])) {
                            var uncheckeddate = myselctdate_arr[i];
                          }
                        }
                        $("#totaldttime_" + uncheckeddate).remove();
                      }
                    }
                  } else {
                    mydate_arr = dateObj.split("-");
                    if (myselctdate != "") {
                      myselctdate_arr = myselctdate.split(", ");
                      var no_of_ele_pre = myselctdate_arr.length;

                      if (no_of_ele_pre == 2) {
                        for (var i = 0; i < no_of_ele_pre; i++) {
                          if (!dateObj.includes(myselctdate_arr[i])) {
                            var uncheckeddate = myselctdate_arr[i];
                          }
                        }
                        addtimerow = 0;
                        $("#totaldttime_" + uncheckeddate).remove();
                      }
                    } else {
                      dispdate =
                        mydate_arr[2] +
                        "/" +
                        mydate_arr[1] +
                        "/" +
                        mydate_arr[0];
                      addtimerow = 1;
                    }
                  }
                  var mydateid = mydate_arr[2] + mydate_arr[1] + mydate_arr[0];
                  mydate =
                    mydate_arr[0] + "-" + mydate_arr[1] + "-" + mydate_arr[2];
                  if (addtimerow == 1) {
                    if (json.workmode == 2) {
                      $("#timesdiv #avltimes").show();
                      $("#timesdiv #avldates").hide();
                      var mytimes =
                        '<div id="totaldttime_' +
                        mydate +
                        '"><input type="hidden" id="myseldate_' +
                        mydateid +
                        '" name="mydates[]" value="' +
                        mydate +
                        '"><div id="mytimediv_' +
                        mydateid +
                        '"><label class="seldtpoprt">' +
                        dispdate +
                        '</label><div class="row splgapbt "><div class="col-md-5 col-4"><input name="starttime[]" id="starttm_' +
                        mydateid +
                        '" class="form-control timepicker spdtstrttime" type="text" placeholder="Start" value="" autocomplete="off"></div><div class="col-md-5 col-4"><input name="endtime[]" id="endtm_' +
                        mydateid +
                        '" class="form-control timepicker spdtendtime" type="text" placeholder="End" value="" autocomplete="off"></div><div class="col-md-2 col-4"><a href="javascript:void(0);" class="jobapplybutn addmytime" id="mytimeadd_' +
                        mydateid +
                        '">Add</a></div></div></div></div>';
                      $("#mytimes").append(mytimes);

                      $("input.timepicker").timepicker({
                        timeFormat: "HH:mm",
                        dynamic: false,
                        dropdown: true,
                        scrollbar: true,
                        interval: 30,
                        maxTime: "23:00",
                        startTime: "06:00",
                      });
                    } else {
                      //$("#mytimes").html("");
                      $("#timesdiv #avldates").show();
                      $("#timesdiv #avltimes").hide();
                      var mytimes =
                        '<div id="totaldttime_' +
                        mydate +
                        '"><input type="hidden" id="myseldate_' +
                        mydateid +
                        '" name="mydates[]" value="' +
                        mydate +
                        '"><div class="row"><div class="col-md-12"><input name="starttime[]" type="hidden" value="00:00:00"><input name="endtime[]" type="hidden" value="00:00:00"></div></div></div>';
                      $("#mydates").append(mytimes);
                    }
                  }
                  $("#alreadyselctdate").val(dateObj);
                  $("#myselctdate").val(dateObj);
                } else {
                  $("#mytimes").html("");
                  $("#mydates").html("");
                  $("#myselctdate").val("");
                  $("#alreadyselctdate").val("");
                }
              },
            });
            flatpickr("#selectrange", {
              mode: "range",
              minDate: json.start_date,
              maxDate: json.end_date,
              dateFormat: "Y-m-d",
            });
            $("#spcfc_dt").prop("checked", true);
          }

          $("#myavldates").html(alldates);

          //$("#starttime").val(strttime);
          //$("#endtime").val(endtime);
          $("#notes").val(json.notes);

          //$("#caldates").html(json.calendar);

          $("#mydaternge").show();
          //$('#mytable').show();

          if (json.workmode == "2") {
            //$("#mytimes").show();
          } else {
            //$("#mytimes").hide();
          }
        }
        $("#applydiv").show();
        $("#apply_suc").text("");
        $("#edit_avlblty").show();
        $("#availability").modal("show");
      } else {
        alert("Sorry!! Some problem occurred.");
      }
    },
    error: function (xhr, ajaxOptions, thrownError) {
      console.log(
        thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
      );
    },
  });
});

$(document).on("click", "#edit_avlblty", function () {
  $("#avl_form").submit();
});

$(document).on("submit", "#avl_form", function () {
  var jobid = $("#jobid").val();
  var userid = $("#userid").val();
  var workmode = $("#workmode").val();
  var avlblty = $('input[name="avlblty"]:checked').val();
  var myselctdate = $("#myselctdate").val();
  if (avlblty == "2") {
    if (myselctdate == "") {
      alert("Please select dates.");
      return false;
    }
  }

  var option = $('input[name="select_mode"]:checked').val();
  if (jobid != "" && userid != "") {
    if (workmode == "2") {
      if (option == "1") {
        var starttime = $("#starttime").val();
        var endtime = $("#endtime").val();
        if (starttime == "" && endtime == "") {
          alert("Please enter start time and end time.");
          return false;
        } else {
          if (starttime >= endtime) {
            alert("Start time must be less than end time.");
            return false;
          }
        }
      }
      if (option == "2") {
        var error = [];
        var error2 = [];
        var spdtstrttime;
        var spdtendtime;
        var haserror = 0;
        var haserror2 = 0;
        var haserror3 = 0;
        var haserror4 = 0;

        $(".spdtstrttime").each(function () {
          spdtstrttime = $(this).val();
          error.push(spdtstrttime);
        });

        $(".spdtendtime").each(function () {
          spdtendtime = $(this).val();
          error2.push(spdtendtime);
        });

        for (let i = 0; i < error.length; i++) {
          if (error[i] == "") {
            haserror = 1;
            break;
          }
        }
        for (let i = 0; i < error.length; i++) {
          if (error2[i] == "") {
            haserror2 = 1;
            break;
          }
        }

        if (haserror == 1 || haserror2 == 1) {
          alert("Please enter start time and end time.");
          return false;
        }
      }
    }
    $.ajax({
      type: "POST",
      dataType: "json",
      url: siteurl + "edit_avlblty.php",
      cache: false,
      data: $("#avl_form").serialize(),
      beforeSend: function () {
        $("#edit_avlblty").text("Processing...").attr("disabled", "disabled");
      },
      complete: function () {
        $("#edit_avlblty")
          .text("Edit Availability for this Job")
          .removeAttr("disabled");
      },
      success: function (json) {
        if (json.success == 1) {
          $("#applydiv").hide();
          /*$("#success").show();
          $("#msgbox").show();
          $("#msgtext").hide();
          $("#jobapplied").hide();
          $("#avlblsubmit").show();*/
          //$("#accept_offer").hide();
          $("#apply_suc").text("Availability is changed.");
          $("#edit_avlblty").hide();
          $("#myselctdate").val("");
          $("#alreadyselctdate").val("");
        } else if (json.success == 2) {
          alert(
            "You have uncompleted section(s). Please go to your profile to complete the steps."
          );
        } else if (json.success == 3) {
          alert("Please select days(s) to apply.");
        }
      },
      error: function (xhr, ajaxOptions, thrownError) {
        console.log(
          thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
        );
      },
    });
    return false;
  }
});

$(document).on("click", "#hire2", function () {
  //$("#availability").modal('show');
  //$("#myhire").submit();
  var jobid = $("#jobid").val();
  var userid = $("#userid").val();
  if (window.confirm("Are you sure to shortlist the staff?") == true) {
    if (jobid != "" && userid != "") {
      $.ajax({
        type: "POST",
        dataType: "json",
        url: siteurl + "send_request.php",
        cache: false,
        data: { jobid: jobid, userid: userid, action: "send_req" },
        beforeSend: function () {
          /*$("#reject").text("Processing...").attr("disabled", "disabled");*/
        },
        complete: function () {
          /*$("#reject").text("No").removeAttr("disabled");*/
        },
        success: function (json) {
          if (json.success == 1) {
            $("#confrmn_sent").text("Shortlisted");
            $("#confirmation").modal("show");
            $(".intrsttick").attr("id", "");
            $(".intrstcross").attr("id", "");
          }
        },
        error: function (xhr, ajaxOptions, thrownError) {
          console.log(
            thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
          );
        },
      });
    }
  }
});

$(document).on("click", "#no_change", function () {
  $("#availability").modal("hide");
  $("#myhire").submit();
});
$(document).on("click", "#yes_change", function () {
  $("#availability").modal("hide");
  $("#avl_update").val("1");
  $("#myhire").submit();
});

$(document).on("click", "#loadcl", function () {
  var userid = $(this).data("id");
  var mycls = "";
  $("#showcoverlettr h5").text("");
  var path;
  if (!isNaN(userid)) {
    $.ajax({
      type: "POST",
      dataType: "json",
      url: siteurl + "getdocs.php",
      cache: false,
      data: { userid: userid, action: "loadcl" },
      beforeSend: function () {},
      complete: function () {},
      success: function (json) {
        if (json.success == 1) {
          $("#showcoverlettr h5").text("Cover Letter");
          path = siteurl + "uploads/resumes/" + json.docname;
          if (json.ext == "pdf") {
            $("#showcoverlettr .modal-body embed").attr("src", path);
            $("#showcoverlettr .modal-body iframe").hide();
          }
          if (json.ext == "docx") {
            var docpath =
              "https://view.officeapps.live.com/op/embed.aspx?src=" + path + "";
            $("#showcoverlettr .modal-body iframe").attr("src", docpath);
            $("#showcoverlettr .modal-body embed").hide();
          }

          $("#showcoverlettr").modal("show");
        }
      },
      error: function (xhr, ajaxOptions, thrownError) {
        console.log(
          thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
        );
      },
    });
  }
});

$(document).on("click", "#loadqual", function () {
  var userid = $(this).data("id");
  var mycls = "";
  $("#showdocs h5").text("");
  $(".mydocuments").html("");
  if (!isNaN(userid)) {
    $.ajax({
      type: "POST",
      dataType: "json",
      url: siteurl + "getdocs.php",
      cache: false,
      data: { userid: userid, action: "loadqual" },
      beforeSend: function () {},
      complete: function () {},
      success: function (json) {
        if (json.success == 1) {
          $("#showdocs h5").text("Qualification Documents");
          $.each(json.mycls, function (i, row) {
            mycls +=
              "<div class='row'><div class='col-md-9'><p>" +
              row.docname +
              "</p></div><div class='col-md-3'><a href='" +
              siteurl +
              "uploads/resumes/" +
              row.docname +
              "' target='_blank'>Download</a></div></div>";
          });
          $(".mydocuments").html(mycls);
          $("#showdocs").modal("show");
        }
      },
      error: function (xhr, ajaxOptions, thrownError) {
        console.log(
          thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
        );
      },
    });
  }
});

$(document).on("click", "#loadcert", function () {
  var userid = $(this).data("id");
  var mycls = "";
  $("#showdocs h5").text("");
  $(".mydocuments").html("");
  if (!isNaN(userid)) {
    $.ajax({
      type: "POST",
      dataType: "json",
      url: siteurl + "getdocs.php",
      cache: false,
      data: { userid: userid, action: "loadcert" },
      beforeSend: function () {},
      complete: function () {},
      success: function (json) {
        if (json.success == 1) {
          $("#showdocs h5").text("Certificates");
          $.each(json.mycls, function (i, row) {
            mycls +=
              "<div class='row'><div class='col-md-9'><p>" +
              row.docname +
              "</p></div><div class='col-md-3'><a href='" +
              siteurl +
              "uploads/resumes/" +
              row.docname +
              "' target='_blank'>Download</a></div></div>";
          });
          $(".mydocuments").html(mycls);
          $("#showdocs").modal("show");
        }
      },
      error: function (xhr, ajaxOptions, thrownError) {
        console.log(
          thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
        );
      },
    });
  }
});

$(document).on("click", ".prev2", function () {
  var month;
  var id = $(this).attr("id");
  var id_arr = id.split("_");
  var currmonth = id_arr[1];
  var curryear = id_arr[2];
  $.ajax({
    type: "POST",
    dataType: "json",
    url: siteurl + "getcalendaremp.php",
    cache: false,
    data: { currmonth: currmonth, curryear: curryear, action: "previous" },
    beforeSend: function () {
      $("#load").show();
    },
    complete: function () {
      $("#load").hide();
    },
    success: function (json) {
      if (json.success == "1") {
        $("#caldates").html(json.calendar);
        $("#current").html(json.prevmonth + " " + json.year);
        month = json.month;

        $(".prev2").prop("id", "prev_" + month + "_" + json.year);
        $(".next2").prop("id", "next_" + month + "_" + json.year);
      }
    },
    error: function (xhr, ajaxOptions, thrownError) {
      console.log(
        thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
      );
    },
  });
});

$(document).on("click", ".next2", function () {
  var month;
  var id = $(this).attr("id");
  var id_arr = id.split("_");
  var currmonth = id_arr[1];
  var curryear = id_arr[2];
  $.ajax({
    type: "POST",
    dataType: "json",
    url: siteurl + "getcalendaremp.php",
    cache: false,
    data: { currmonth: currmonth, curryear: curryear, action: "next" },
    beforeSend: function () {
      $("#load").show();
    },
    complete: function () {
      $("#load").hide();
    },
    success: function (json) {
      if (json.success == "1") {
        $("#caldates").html(json.calendar);
        $("#current").html(json.nextmonth + " " + json.year);
        month = json.month;

        $(".prev2").prop("id", "prev_" + month + "_" + json.year);
        $(".next2").prop("id", "next_" + month + "_" + json.year);
      }
    },
    error: function (xhr, ajaxOptions, thrownError) {
      console.log(
        thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
      );
    },
  });
});

$('input[name="covid_vaccn"]').on("click", function () {
  var myoption = $('input[name="covid_vaccn"]:checked').val();
  if (myoption == 1) {
    $("#cert_upload").show();
    $("#vaccn_cert").addClass("required");
  } else {
    $("#cert_upload").hide();
    $("#vaccn_cert").removeClass("required");
  }
});

$(document).on("change", "#myjobs", function () {
  var id = $(this).val();
  location.href = siteurl + "viewjob/" + id + "?action=contacting";
});

$(document).on("click", "#no_confirm", function () {
  var jobid = $("#jobid").val();
  var userid = $("#userid").val();
  $.ajax({
    type: "POST",
    dataType: "json",
    url: siteurl + "confirm_reject.php",
    cache: false,
    data: { jobid: jobid, userid: userid, action: "no_confirm" },
    beforeSend: function () {
      $("#load").show();
    },
    complete: function () {
      $("#load").hide();
    },
    success: function (json) {
      if (json.success == "1") {
        $("#confirmreq").hide();
        $(".closered2").show();
      }
    },
    error: function (xhr, ajaxOptions, thrownError) {
      console.log(
        thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
      );
    },
  });
});
$(document).on("click", "#yes_confirm", function () {
  var jobid = $("#jobid").val();
  var userid = $("#userid").val();
  $.ajax({
    type: "POST",
    dataType: "json",
    url: siteurl + "confirm_accpt.php",
    cache: false,
    data: { jobid: jobid, userid: userid, action: "yes_confirm" },
    beforeSend: function () {
      $("#load").show();
    },
    complete: function () {
      $("#load").hide();
    },
    success: function (json) {
      if (json.success == "1") {
        $("#confirmreq").hide();
        $(".opengrn").show();
      }
    },
    error: function (xhr, ajaxOptions, thrownError) {
      console.log(
        thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
      );
    },
  });
});

$(document).on("click", ".sendhire", function () {
  //$("#availability").modal('show');
  //$("#myhire").submit();
  var id = $(this).attr("id");
  var id_arr = id.split("_");

  var jobid = id_arr[1];
  var userid = id_arr[2];
  if (window.confirm("Are you sure to shortlist the staff?") == true) {
    if (jobid != "" && userid != "") {
      $.ajax({
        type: "POST",
        dataType: "json",
        url: siteurl + "send_request.php",
        cache: false,
        data: { jobid: jobid, userid: userid, action: "send_req" },
        beforeSend: function () {
          /*$("#reject").text("Processing...").attr("disabled", "disabled");*/
        },
        complete: function () {
          /*$("#reject").text("No").removeAttr("disabled");*/
        },
        success: function (json) {
          if (json.success == 1) {
            $("#conf_" + jobid + "_" + userid).text("Shortlisted");
            $("#hire2_" + jobid + "_" + userid).removeClass("sendhire");
            $("#reject_" + jobid + "_" + userid).removeClass("rejectstaff");
            $("#confirmation").modal("show");
          }
        },
        error: function (xhr, ajaxOptions, thrownError) {
          console.log(
            thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
          );
        },
      });
    }
  }
});

$(document).on("click", ".rejectstaff", function () {
  var id = $(this).attr("id");
  var id_arr = id.split("_");

  var jobid = id_arr[1];
  var userid = id_arr[2];
  if (window.confirm("Are you sure to reject this staff?") == true) {
    if (jobid != "" && userid != "") {
      $.ajax({
        type: "POST",
        dataType: "json",
        url: siteurl + "reject_candidt.php",
        cache: false,
        data: { jobid: jobid, userid: userid, action: "reject" },
        beforeSend: function () {
          /*$("#reject").text("Processing...").attr("disabled", "disabled");*/
        },
        complete: function () {
          /*$("#reject").text("No").removeAttr("disabled");*/
        },
        success: function (json) {
          if (json.success == 1) {
            //$(".acceptoffer").hide();
            //$("#rejected").show();
            $("#reject_" + jobid + "_" + userid).addClass("active");
            $("#reject_" + jobid + "_" + userid).removeClass("rejectstaff");
            $("#hire2_" + jobid + "_" + userid).removeClass("sendhire");
          }
        },
        error: function (xhr, ajaxOptions, thrownError) {
          console.log(
            thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
          );
        },
      });
    }
  }
});

/*$(document).on('click', ".myresctcats", function () {
  var catid = $(this).val();
  $("#mycatid").val(catid);
  var category = $(this).data("value");
  var myexps;
  var myresctcats = [];
  
  if ($(this).prop('checked') == true) {
    
    $.each($("input[name='catid[]']:checked"), function () {
    myresctcats.push($(this).val());
    });
    
    if (myresctcats.length < 11) {
      myexps = '<div id="subcatexp_'+catid+'"><input type="hidden" name="catidforexp[]" value="'+catid+'"><div><div class="exppagenewdesign"><h5>'+category+' - </h5><div class="row"><div class="col-sm-6"><label>year(s)</label><input type="text" class="form-control digits required" name="experience[]" value=""></div><div class="col-sm-6"><label>month(s)</label><input type="text" class="form-control digits required" name="experience_month[]" value=""></div></div></div></div></div>';
      $("#myexps").append(myexps);
    }
    else{
      alert("Maximum 10 job categories are allowed.");
      $(this).prop('checked', false);
    }
  }
  else{
    $("#subcatexp_"+catid).remove();
  }

  if ($(this).prop('checked') == true) {
    $("#myexp h5 span").text("");
    $("#myexpselect").html("");
    var category = $(this).data("value");

    $("#myexp h5 span").text(category);
    var options;
    options = '<option value="0">0</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="10+">10+</option>';
    $("#myexpselect").html(options);
    $("#myexp").modal('show');
  }
  else {
    if (catid != "") {
      $.ajax({
        type: "POST",
        dataType: "json",
        url: siteurl + "removeexpence.php",
        cache: false,
        data: { catid: catid, action: 'remexp' },
        beforeSend: function () { $("#allcats checkbox").attr("disabled", "disabled"); },
        complete: function () { $("#allcats checkbox").removeAttr("disabled"); },
        success: function (json) {

          if (json.success == 1) {
            $("#myqual_" + catid).remove();
            if (json.total == 0) {
              $(".myallexp label").hide();
            }
          }

        },
        error: function (xhr, ajaxOptions, thrownError) {
          console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
      });
    }
  }
});*/

$(document).on("click", "#modal_close", function () {
  var catid = $("#mycatid").val();
  $("#myresctcats_" + catid).prop("checked", false);
  $("#myexp").modal("hide");
});

$(document).on("click", "#add_exp", function () {
  var myexp = $("#myexpselect").val();
  var mycat = $("#myexp h5 span").text();
  var catid = $("#mycatid").val();
  var exps;
  if (catid != "") {
    $.ajax({
      type: "POST",
      dataType: "json",
      url: siteurl + "addexpence.php",
      cache: false,
      data: { myexp: myexp, catid: catid, action: "addexp" },
      beforeSend: function () {
        $("#add_exp").text("Adding...").attr("disabled", "disabled");
      },
      complete: function () {
        $("#add_exp").text("Add").removeAttr("disabled");
      },
      success: function (json) {
        if (json.success == 1) {
          exps =
            "<p id='myqual_" +
            catid +
            "'>" +
            myexp +
            " year(s) in " +
            mycat +
            "</p>";
          $("#experiences").append(exps);
          $(".myallexp label").show();
          $("#myexp").modal("hide");
        }
      },
      error: function (xhr, ajaxOptions, thrownError) {
        console.log(
          thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
        );
      },
    });
  }
});

$(document).on("click", ".calprev", function () {
  var month;
  var id = $(this).attr("id");
  var id_arr = id.split("_");
  var currmonth = id_arr[1];
  var curryear = id_arr[2];
  var jobid = $("#jobid").val();
  $.ajax({
    type: "POST",
    dataType: "json",
    url: siteurl + "jobcalendar.php",
    cache: false,
    data: {
      jobid: jobid,
      currmonth: currmonth,
      curryear: curryear,
      action: "previous",
    },
    beforeSend: function () {
      $("#load").show();
    },
    complete: function () {
      $("#load").hide();
    },
    success: function (json) {
      if (json.success == "1") {
        $("#caldates").html(json.calendar);
        $("#current").html(json.prevmonth + " " + json.year);
        month = json.month;

        $(".calprev").prop("id", "prev_" + month + "_" + json.year);
        $(".calnext").prop("id", "next_" + month + "_" + json.year);
      }
    },
    error: function (xhr, ajaxOptions, thrownError) {
      console.log(
        thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
      );
    },
  });
});

$(document).on("click", ".calnext", function () {
  var month;
  var id = $(this).attr("id");
  var id_arr = id.split("_");
  var currmonth = id_arr[1];
  var curryear = id_arr[2];
  var jobid = $("#jobid").val();
  $.ajax({
    type: "POST",
    dataType: "json",
    url: siteurl + "jobcalendar.php",
    cache: false,
    data: {
      jobid: jobid,
      currmonth: currmonth,
      curryear: curryear,
      action: "next",
    },
    beforeSend: function () {
      $("#load").show();
    },
    complete: function () {
      $("#load").hide();
    },
    success: function (json) {
      if (json.success == "1") {
        $("#caldates").html(json.calendar);
        $("#current").html(json.nextmonth + " " + json.year);
        month = json.month;

        $(".calprev").prop("id", "prev_" + month + "_" + json.year);
        $(".calnext").prop("id", "next_" + month + "_" + json.year);
      }
    },
    error: function (xhr, ajaxOptions, thrownError) {
      console.log(
        thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
      );
    },
  });
});

$(document).on("click", ".jobcalprev", function () {
  var month;
  var id = $(this).attr("id");
  var id_arr = id.split("_");
  var currmonth = id_arr[1];
  var curryear = id_arr[2];
  var jobid = $("#jobid").val();
  var userid = id_arr[3];
  if (jobid != "" && userid != "") {
    $.ajax({
      type: "POST",
      dataType: "json",
      url: siteurl + "jobconfcalendar.php",
      cache: false,
      data: {
        jobid: jobid,
        userid: userid,
        currmonth: currmonth,
        curryear: curryear,
        action: "previous",
      },
      beforeSend: function () {
        $("#load").show();
      },
      complete: function () {
        $("#load").hide();
      },
      success: function (json) {
        if (json.success == "1") {
          $("#caldates_" + jobid + "_" + userid).html(json.calendar);
          $("#current_" + jobid + "_" + userid).html(
            json.prevmonth + " " + json.year
          );
          month = json.month;

          $(".jobcalprev").prop(
            "id",
            "prev_" + month + "_" + json.year + "_" + userid
          );
          $(".jobcalnext").prop(
            "id",
            "next_" + month + "_" + json.year + "_" + userid
          );
        }
      },
      error: function (xhr, ajaxOptions, thrownError) {
        console.log(
          thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
        );
      },
    });
  }
});

$(document).on("click", ".jobcalnext", function () {
  var month;
  var id = $(this).attr("id");
  var id_arr = id.split("_");
  var currmonth = id_arr[1];
  var curryear = id_arr[2];
  var userid = id_arr[3];
  var jobid = $("#jobid").val();
  if (jobid != "" && userid != "") {
    $.ajax({
      type: "POST",
      dataType: "json",
      url: siteurl + "jobconfcalendar.php",
      cache: false,
      data: {
        jobid: jobid,
        userid: userid,
        currmonth: currmonth,
        curryear: curryear,
        action: "next",
      },
      beforeSend: function () {
        $("#load").show();
      },
      complete: function () {
        $("#load").hide();
      },
      success: function (json) {
        if (json.success == "1") {
          $("#caldates_" + jobid + "_" + userid).html(json.calendar);
          $("#current_" + jobid + "_" + userid).html(
            json.prevmonth + " " + json.year
          );
          month = json.month;

          $(".jobcalprev").prop(
            "id",
            "prev_" + month + "_" + json.year + "_" + userid
          );
          $(".jobcalnext").prop(
            "id",
            "next_" + month + "_" + json.year + "_" + userid
          );
        }
      },
      error: function (xhr, ajaxOptions, thrownError) {
        console.log(
          thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
        );
      },
    });
  }
});

/*$(document).on('click', '.jobavldates', function () {
  var id = $(this).attr("id");
  var id_arr = id.split("_");
  var year = id_arr[1];
  var month = id_arr[2];
  var day = id_arr[3];
  var mydate = year + "-" + month + "-" + day;
  var dispdate = day + "/" + month + "/" + year;
  var workmode = $("#workmode").val();

  var myselctdate = $("#myselctdate").val();
  if (myselctdate != mydate) {
    if (workmode == 2) {
      $("#timesdiv #avltimes").show();
      var mytimes = '<input type="hidden" id="starttime_' + mydate + '" name="mydates[]" value="' + mydate + '"><div class="row"><div class="col-md-6"><label>' + dispdate + '</label><input name="starttime[]" class="form-control timepicker" type="text" placeholder="Start" value="" autocomplete="off"></div><div class="col-md-6"><label>&nbsp;</label><input name="endtime[]" id="endtime_' + mydate + '" class="form-control timepicker" type="text" placeholder="End" value="" autocomplete="off"></div></div>';
      $("#mytimes").append(mytimes);

      $('input.timepicker').timepicker({ timeFormat: 'HH:mm', dynamic: false, dropdown: true, scrollbar: true, interval: 30, maxTime: '23:00', startTime: '06:00' });
    }
    else {
      $("#timesdiv #avldates").show();
      var mytimes = '<input type="hidden" id="starttime_' + mydate + '" name="mydates[]" value="' + mydate + '"><div class="row"><div class="col-md-12"><label>' + dispdate + '</label><input name="starttime[]" type="hidden" value="00:00:00"><input name="endtime[]" type="hidden" value="00:00:00"></div></div>';
      $("#mydates").append(mytimes);
    }

    $(".jobapplybutn").show();
  }
  $("#myselctdate").val(mydate);

});*/

$(document).on("change", "#myjobs_conf", function () {
  var id = $(this).val();
  location.href = siteurl + "emp_confirmation/" + id;
});

/*$(document).on('click', '.sndcnfmsn', function () {
  $(".newcnfrmjob").show();
});*/

$(document).on("click", ".timinp", function () {
  var id = $(this).attr("id");
  var id_arr = id.split("_");
  $("#mycal_" + id_arr[1] + "_" + id_arr[2]).slideToggle();
});

$(document).on("click", ".myjobcormdates", function () {
  var myallseldates;
  var id = $(this).attr("id");
  var id_arr = id.split("_");
  var year = id_arr[1];
  var month = id_arr[2];
  var day = id_arr[3];
  var jobid = id_arr[4];
  var userid = id_arr[5];
  var mydate = year + "-" + month + "-" + day;
  var dispdate = day + "/" + month + "/" + year;

  var myseldates = $("#myseldates").val();
  if (!myseldates.includes(mydate)) {
    myallseldates = $("#mydt_" + jobid + "_" + userid).val();
    if (myallseldates != "") {
      myallseldates = myallseldates + ", " + dispdate;
    } else {
      myallseldates = dispdate;
    }
    $("#mydt_" + jobid + "_" + userid).val(myallseldates);
  }
  if (myseldates != "") {
    myseldates = myseldates + "," + mydate;
  } else {
    myseldates = mydate;
  }

  $("#myseldates").val(myseldates);
});

$(document).on("click", ".sendconfrm", function () {
  var id = $(this).attr("id");
  var id_arr = id.split("_");
  var jobid = id_arr[1];
  var userid = id_arr[2];
  var myallseldates = $("#mydt_" + jobid + "_" + userid).val();
  if (jobid != "" && userid != "") {
    //if(myallseldates != ""){

    $.ajax({
      type: "POST",
      dataType: "json",
      url: siteurl + "send_confirmation.php",
      cache: false,
      data: { jobid: jobid, userid: userid, action: "send_confirm" },
      beforeSend: function () {
        $("#sendconf_" + jobid + "_" + userid)
          .text("Processing...")
          .attr("disabled", "disabled");
      },
      complete: function () {
        $("#sendconf_" + jobid + "_" + userid)
          .text("Confirmation Sent")
          .removeAttr("disabled");
      },
      success: function (json) {
        if (json.success == "1") {
          $("#sendconf_" + jobid + "_" + userid)
            .text("Confirmation Sent")
            .removeClass("sendconfrm");
        }
      },
      error: function (xhr, ajaxOptions, thrownError) {
        console.log(
          thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
        );
      },
    });
    /*}
    else{
      alert("Please select date.");
    }*/
  }
});

$(document).on("change", "#myjobs_staff", function () {
  var id = $(this).val();
  location.href = siteurl + "myappliedjobs/" + btoa(id) + "?do=confirmjob";
});

$(document).on("click", ".cofrmaccept", function () {
  var id = $(this).attr("id");
  var id_arr = id.split("_");
  var jobid = id_arr[1];
  var userid = id_arr[2];
  //var myallseldates = $("#mydt_" + jobid + "_" + userid).val();
  if (jobid != "" && userid != "") {
    $.ajax({
      type: "POST",
      dataType: "json",
      url: siteurl + "accept_offer.php",
      cache: false,
      data: { jobid: jobid, userid: userid, action: "acceptoffr" },
      beforeSend: function () {
        $("#accept_" + jobid + "_" + userid)
          .html("<i class='fa fa-check' aria-hidden='true'></i> Processing...")
          .attr("disabled", "disabled");
      },
      complete: function () {
        $("#accept_" + jobid + "_" + userid)
          .html("<i class='fa fa-check' aria-hidden='true'></i>")
          .removeAttr("disabled");
      },
      success: function (json) {
        if (json.success == "1") {
          $("#accept_" + jobid + "_" + userid)
            .addClass("accepted")
            .removeClass("cofrmaccept");
        }
      },
      error: function (xhr, ajaxOptions, thrownError) {
        console.log(
          thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
        );
      },
    });
  }
});

$(document).on("click", ".sendnote", function () {
  var id = $(this).attr("id");
  var id_arr = id.split("_");
  var jobid = id_arr[1];
  var userid = id_arr[2];
  var empnote = $("#empnote_" + jobid + "_" + userid).val();
  if (jobid != "" && userid != "") {
    if (empnote != "") {
      $.ajax({
        type: "POST",
        dataType: "json",
        url: siteurl + "send_note_to_staff.php",
        cache: false,
        data: {
          jobid: jobid,
          userid: userid,
          empnote: empnote,
          action: "send_note",
        },
        beforeSend: function () {
          $("#sendnote_" + jobid + "_" + userid)
            .text("Sending...")
            .attr("disabled", "disabled");
        },
        complete: function () {
          $("#sendnote_" + jobid + "_" + userid)
            .text("Note sent")
            .removeAttr("disabled");
        },
        success: function (json) {
          if (json.success == "1") {
            $("#sendnote_" + jobid + "_" + userid)
              .text("Note sent")
              .removeClass("sendnote");
          }
        },
        error: function (xhr, ajaxOptions, thrownError) {
          console.log(
            thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
          );
        },
      });
    }
  }
});

$('input[name="add_time"]').on("click", function () {
  var option = $('input[name="add_time"]:checked').val();
  if (option == 1) {
    $("#addtime").show();
    $("#starttime").addClass("required");
    $("#endtime").addClass("required");
    $("#shiftjobdiv").hide();
    $("#is_shift").removeClass("required");
  } else {
    $("#addtime").hide();
    $("#starttime").removeClass("required");
    $("#endtime").removeClass("required");
    $("#shiftjobdiv").show();
    $("#is_shift").addClass("required");
  }
});

$(document).on("click", "#pills-home-tab-int", function () {
  $("#contact_type").val("video");
  $("#videolink").addClass("required");
  $("#phoneno").removeClass("required");
  $("#address").removeClass("required");
});
$(document).on("click", "#pills-profile-tab-int", function () {
  $("#contact_type").val("phonecall");
  $("#videolink").removeClass("required");
  $("#phoneno").addClass("required");
  $("#address").removeClass("required");
});
$(document).on("click", "#pills-contact-tab-int", function () {
  $("#contact_type").val("inperson");
  $("#videolink").removeClass("required");
  $("#phoneno").removeClass("required");
  $("#address").addClass("required");
});

$(document).on("submit", "#send_invitn", function () {
  if ($(this).valid() == true) {
    $.ajax({
      type: "POST",
      dataType: "json",
      url: siteurl + "send_intvew_invitn.php",
      cache: false,
      data: $("#send_invitn").serialize(),
      beforeSend: function () {
        $(".sndinvi").val("Sending...").attr("disabled", "disabled");
      },
      complete: function () {
        $(".sndinvi").val("Send invitation").removeAttr("disabled");
      },
      success: function (json) {
        if (json.success == "1") {
          $("#send_invitn").trigger("reset");
          $(".sndinvi").val("Send invitation");
          $("#invitation_success").html(
            "<span style='color:green;font-size:14px;line-height:40px;'>Invitation Sent.</span>"
          );
          setTimeout(function () {
            $("#invitation_success").html("");
          }, 4000);
        }
      },
      error: function (xhr, ajaxOptions, thrownError) {
        console.log(
          thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
        );
      },
    });
    return false;
  }
});

$(document).on("click", ".employercats", function () {
  var myid = $(this).attr("id");
  var id_arr = myid.split("_");
  var catid = id_arr[1];
  var totalcats;
  var myselectedcats;
  //$("#selectdcats ul").html("");
  var subcats;

  var myselectedcats = [];
  if ($(this).prop("checked") == true) {
    var categoryname = $(this).data("value");
    $.each($("input[name='maincatid[]']:checked"), function () {
      myselectedcats.push($(this).val());
    });
    $("#myselectedcats").val(myselectedcats.length);
  } else {
    myselectedcats = $("#myselectedcats").val();
    myselectedcats = myselectedcats - 1;

    if (myselectedcats > 0) {
      /*if (myselectedcats > 1) {
        $("#showcats").val(myselectedcats + " classifications");
      }
      else {
        $("#showcats").val(myselectedcats + " classification");
      }*/
    } else {
      $("#showcats").val("");
    }
    $("#myselectedcats").val(myselectedcats);
  }

  if ($(this).prop("checked") == true) {
    if (myselectedcats.length < 2) {
      if (!isNaN(catid)) {
        var mymaincat = $(this).data("value");
        $.ajax({
          type: "POST",
          dataType: "json",
          url: siteurl + "getsubcat.php",
          cache: false,
          data: { catid: catid },
          beforeSend: function () {},
          complete: function () {},
          success: function (json) {
            if (json.success == 1) {
              totalcats = myselectedcats.length;
              if (totalcats > 1) {
                $("#showcats").val(totalcats + " classifications");
              } else {
                $("#showcats").val(categoryname);
              }
              if (json.subcat.length > 0) {
                var subcats = "<ul>";
                $.each(json.subcat, function (i, row) {
                  subcats +=
                    '<li><input type="checkbox" class="myempresctcats" name="catid[]" value="' +
                    row.id +
                    '" data-value="' +
                    row.category +
                    '" />&nbsp; ' +
                    row.category +
                    "</li>";
                });
                subcats += "</ul>";

                $("#maincatli_" + catid).html(subcats);
                $("#maincatli_" + catid).show();
              }
            } else {
              alert("Sorry!! Some problem occurred.");
            }
          },
          error: function (xhr, ajaxOptions, thrownError) {
            console.log(
              thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
            );
          },
        });
      }
    } else {
      alert("Maximum 1 category is allowed.");
      $(this).prop("checked", false);
    }
  } else {
    $("#maincatli_" + catid).html("");
    $("#maincatli_" + catid).hide();
    $("#showcats").val("");
  }
});

$('input[name="experienced"]').on("click", function () {
  var myoption = $('input[name="experienced"]:checked').val();
  if (myoption == 1) {
    $("#expbox").show();
  } else {
    $("#expbox").hide();
  }
});

$(document).on("click", ".addmyexps", function () {
  var myextraexp;
  myextraexp =
    '<div class="mobjobad"><div class="row" style="margin-bottom:10px;"><div class="col-sm-3"><label>Previous Company</label><input type="text" name="company[]" class="form-control required" value=""></div><div class="col-sm-3"><label>Role/Title</label><input type="text" name="designation[]" class="form-control required" value=""></div><div class="col-sm-4 col-8"><div class="row"><div class="col-sm-6 col-6"><label style="text-transform:none;">Year(s)</label><input type="text" class="form-control digits required myexpyr" name="experience[]" value=""></div><div class="col-sm-6 col-6"><label style="text-transform:none;">Month(s)</label><input type="text" class="form-control digits required myexpmth" name="experience_month[]" value=""></div></div></div><div class="col-sm-2 col-4"><label>&nbsp;</label><a href="javascript:void(0);" class="addmyexps"><i class="fa fa-plus"></i></a> &nbsp;<a href="javascript:void(0);" class="removemyexps"><i class="fa fa-minus"></i></a></div></div></div>';

  $("#myexps").append(myextraexp);
});

$(document).on("click", ".removemyexps", function () {
  $(this).closest("div.mobjobad").remove();
});

$(document).on("click", "#noexpernce", function () {
  if ($(this).prop("checked") == true) {
    $(".mustexp").val("");
    $(".mustexp").removeClass("required");
  } else {
    $(".mustexp").addClass("required");
  }
});

$(document).on("submit", "#jobpost1", function () {
  var error = false;
  var starttime = $("#starttime").val();
  var endtime = $("#endtime").val();

  var shiftstrt = $(".shiftstrt").val();
  var shiftend = $(".shiftend").val();

  //var noshiftsrttime = $("#noshiftsrttime").val();
  //var noshiftendtime = $("#noshiftendtime").val();

  if (starttime != "" && endtime != "") {
    if (starttime < endtime) return true;
    else {
      alert("Start time must be less than end time.");
      return false;
    }
  } else if (shiftstrt != "" && shiftend != "") {
    if (shiftstrt < shiftend) return true;
    else {
      alert("Start time must be less than end time.");
      return false;
    }
  } else {
    return true;
  }
});

$(document).on("click", ".del_availblty", function () {
  var id = $(this).attr("id");
  var id_arr = id.split("_");
  var availid = id_arr[1];
  if (
    window.confirm("Are you sure to delete this availability date?") == true
  ) {
    $.ajax({
      type: "POST",
      dataType: "json",
      url: siteurl + "del_availblty.php",
      cache: false,
      data: { availid: availid, action: "del_avlty" },
      beforeSend: function () {
        //$(".sndinvi").val("Sending...").attr("disabled", "disabled");
      },
      complete: function () {
        //$(".sndinvi").val("Send invitation").removeAttr("disabled");
      },
      success: function (json) {
        if (json.success == "1") {
          $("#myavldatesli_" + availid).remove();
        } else {
          alert("Sorry!! Some problem occurred.");
        }
      },
      error: function (xhr, ajaxOptions, thrownError) {
        console.log(
          thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
        );
      },
    });
  }
});

$('input[name="covertype"]').on("click", function () {
  var option = $('input[name="covertype"]:checked').val();
  if (option == "1") {
    $("#shwdaterange").show();
    $("#wanttoaddtm").hide();
    if (!$("#from").hasClass("required")) $("#from").addClass("required");

    $("#want_add_time").removeClass("required");
    $("#longstartdt").removeClass("required");
  } else {
    $("#shwdaterange").hide();
    $("#wanttoaddtm").show();
    if ($("#from").hasClass("required")) $("#from").removeClass("required");

    $("#want_add_time").addClass("required");
  }
});

$('input[name="is_shift"]').on("click", function () {
  var option = $('input[name="is_shift"]:checked').val();
  if (option == "1") {
    $("#shifttype").show();
    $(".shifttm").addClass("required");
    $("#addtimenoshift").hide();
    //$("#noshiftsrttime").removeClass("required");
    //$("#noshiftendtime").removeClass("required");
  } else {
    $("#shifttype").hide();
    $(".shifttm").removeClass("required");
    $("#addtimenoshift").show();
    //$("#noshiftsrttime").addClass("required");
    //$("#noshiftendtime").addClass("required");
  }
});

$(document).on("click", ".addmyshifttime", function () {
  var myextraexp;
  myextraexp =
    '<div class="row"><div class="col-sm-5 col-4"><div class="timlt" style="width:100%;"><label>Start Time</label><input type="text" placeholder="Start Time" name="shiftstrt[]" class="form-control timepicker shifttm shiftstrt required" value="" autocomplete="off"></div></div><div class="col-sm-5 col-4"><div class="timrt" style="width:100%;"><label>End Time</label><br><input type="text" placeholder="End Time" name="shiftend[]" class="form-control timepicker shifttm shiftend required" value="" autocomplete="off"></div></div><div class="col-sm-2 col-4"><label>&nbsp;</label><a href="javascript:void(0);" class="addmyshifttime"><i class="fa fa-plus"></i></a> &nbsp;<a href="javascript:void(0);" class="removemytime"><i class="fa fa-minus"></i></a></div></div>';

  $("#myshifttimes").append(myextraexp);
  $("input.timepicker").timepicker({
    timeFormat: "HH:mm",
    dynamic: false,
    dropdown: true,
    scrollbar: true,
    interval: 30,
    maxTime: "23:00",
    startTime: "06:00",
  });
});

$(document).on("click", ".removemytime", function () {
  $(this).closest("div.row").remove();
});

$(document).on("click", ".myempresctcats", function () {
  var myempresctcats = [];
  $.each($("input[name='catid[]']:checked"), function () {
    myempresctcats.push($(this).val());
  });

  if (myempresctcats.length > 1) {
    alert("Maximum 1 job category is allowed.");
    $(this).prop("checked", false);
  }
});

$(document).on("keyup", ".myexpyr", function () {
  var myval = $(this).val();
  if (isNaN(myval)) {
    $(this).val("");
  }
});

$(document).on("keyup", ".myexpmth", function () {
  var myval = $(this).val();
  if (isNaN(myval)) {
    $(this).val("");
  }
});

$(document).on("submit", "#staffexpinfo", function () {
  var myexpmonth;
  var error = [];
  var haserror = 0;

  $(".myexpmth").each(function () {
    myexpmonth = $(this).val();
    error.push(myexpmonth);
  });

  for (let i = 0; i < error.length; i++) {
    if (error[i] > 11) {
      haserror = 1;
      break;
    }
  }

  if (haserror == 1) {
    alert("Month must be 0-11.");
    return false;
  } else {
    return true;
  }
});

$(document).on("submit", "#jobpost1", function () {
  var option = $('input[name="is_shift"]:checked').val();
  if (option == "1") {
    if ($(".myshiftoptns input[type=checkbox]:checked").length == 0) {
      alert("Please select shift type.");
      return false;
    } else return true;
  }
});

$(document).on("change", "#abnacn", function () {
  var val = $(this).val();
  if (val == "ABN" || val == "ACN") {
    $("#abnacnno").show();
  } else {
    $("#abnacnno").hide();
  }
});

$(document).on("click", "#showcatssrch", function () {
  $("#allcatsrch").slideToggle();
});

$(document).on("click", ".emplyrcatsrch", function () {
  var myid = $(this).attr("id");
  var id_arr = myid.split("_");
  var catid = id_arr[1];
  var totalcats;
  var myselectedcats;
  //$("#selectdcats ul").html("");
  var subcats;

  var myselectedcats = [];
  if ($(this).prop("checked") == true) {
    $.each($("input[name='maincatid[]']:checked"), function () {
      myselectedcats.push($(this).val());
    });
    $("#myselectedcats").val(myselectedcats.length);
  } else {
    myselectedcats = $("#myselectedcats").val();
    myselectedcats = myselectedcats - 1;

    if (myselectedcats > 0) {
      if (myselectedcats > 1) {
        $("#showcats").val(myselectedcats + " classifications");
      } else {
        $("#showcats").val(myselectedcats + " classification");
      }
    } else {
      $("#showcats").val("");
    }
    $("#myselectedcats").val(myselectedcats);
  }

  if ($(this).prop("checked") == true) {
    if (!isNaN(catid)) {
      var mymaincat = $(this).data("value");
      $.ajax({
        type: "POST",
        dataType: "json",
        url: siteurl + "getsubcat.php",
        cache: false,
        data: { catid: catid },
        beforeSend: function () {},
        complete: function () {},
        success: function (json) {
          if (json.success == 1) {
            totalcats = myselectedcats.length;
            if (totalcats > 1) {
              $("#showcats").val(totalcats + " classifications");
            } else {
              $("#showcats").val(totalcats + " classification");
            }
            if (json.subcat.length > 0) {
              var subcats = "<ul>";
              $.each(json.subcat, function (i, row) {
                subcats +=
                  '<li><input type="checkbox" class="mysubcatsrch" name="catid[]" value="' +
                  row.id +
                  '" data-value="' +
                  row.category +
                  '" />&nbsp; ' +
                  row.category +
                  "</li>";
              });
              subcats += "</ul>";

              $("#maincatli_" + catid).html(subcats);
              $("#maincatli_" + catid).show();
            }
          } else {
            alert("Sorry!! Some problem occurred.");
          }
        },
        error: function (xhr, ajaxOptions, thrownError) {
          console.log(
            thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
          );
        },
      });
    }
  } else {
    $("#maincatli_" + catid).html("");
    $("#maincatli_" + catid).hide();
    //$("#showcats").val('');
  }
});

$(document).on("click", ".addoptnlupld", function () {
  var myoptnluplds;
  myoptnluplds =
    '<div class="row"><div class="col-sm-8 col-8"><input type="file" name="jobupload[]" class="form-control"><span>(PDF or Docx file)</span></div><div class="col-sm-2 col-4"><a href="javascript:void(0);" class="addoptnlupld"><i class="fa fa-plus"></i></a>&nbsp;<a href="javascript:void(0);" class="removemyjobupld"><i class="fa fa-minus"></i></a></div></div>';

  $("#myoptnlupld").append(myoptnluplds);
});

$(document).on("click", ".removemyjobupld", function () {
  $(this).closest("div.row").remove();
});

$('input[name="is_heavy_lifting"]').on("click", function () {
  var value = $('input[name="is_heavy_lifting"]:checked').val();
  if (value == 1) {
    $("#heavy_lifting").show();
    $("#heavy_lifting").addClass("required");
  } else {
    $("#heavy_lifting").hide();
    $("#heavy_lifting").removeClass("required");
  }
});

$('input[name="is_immunised"]').on("click", function () {
  var value = $('input[name="is_immunised"]:checked').val();
  if (value == 1) {
    $("#no_of_doses").show();
    $("#no_of_doses").addClass("required");
  } else {
    $("#no_of_doses").hide();
    $("#no_of_doses").removeClass("required");
  }
});

$('input[name="want_add_time"]').on("click", function () {
  var option = $('input[name="want_add_time"]:checked').val();
  if (option == "1") {
    $("#showlongstartdt").show();
    $("#longstartdt").addClass("required");
  } else {
    $("#showlongstartdt").hide();
    $("#longstartdt").removeClass("required");
  }
});

$(document).on("change", ".change_stat", function () {
  var jobid = $(this).attr("id");
  var jobid_arr = jobid.split("_");
  var myjobid = jobid_arr[2];
  var myjobval = $("#" + jobid).val();
  if (myjobval != "") {
    if (myjobval == "2") {
      if (
        window.confirm(
          "Are you sure you want this job to be deleted? It will not be shown to any candidates and you will not have access to this job or any candidates who have applied. It will not be recoverable."
        ) == true
      ) {
        location.href = siteurl + "myjobs?action=jobdelete&id=" + myjobid;
      }
    } else {
      $.ajax({
        type: "POST",
        dataType: "json",
        url: siteurl + "changejobstat.php",
        cache: false,
        data: { myjobval: myjobval, myjobid: myjobid },
        beforeSend: function () {},
        complete: function () {},
        success: function (json) {
          if (json.success == 1) {
            $("#jobstatus").text("Job status changed.");
            $("#showjobstatus").modal("show");
          } else {
            alert("Sorry!! Some problem occurred.");
          }
        },
        error: function (xhr, ajaxOptions, thrownError) {
          console.log(
            thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText
          );
        },
      });
    }
  }
});
