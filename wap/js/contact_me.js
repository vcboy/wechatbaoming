/*
  Jquery Validation using jqBootstrapValidation
   example is taken from jqBootstrapValidation docs 
  */
$(function() {
    $("#pay").hide();
    $("#contactForm input,#contactForm textarea").jqBootstrapValidation({
        preventSubmit: true,
        submitError: function($form, event, errors) {
            // something to have when submit produces an error ?
            // Not decided if I need it yet
        },
        submitSuccess: function($form, event) {
            event.preventDefault(); // prevent default submit behaviour
            // get values from FORM
            //var company = $("input#company").val();
            var name = $("input#name").val();           
            var sex = $("input[name='sex']:checked").val();
            //console.log(sex);
            var nation = $("input#nation").val();  
            var year = $("select#year").val();
            var month = $("select#month").val();
            var day = $("select#day").val();
            var sfz = $("input#sfz").val();
            //var email = $("input#email").val();
            var zkzy = $("input#zkzy").val();
            var bkzy = $("input#bkzy").val();
            var phone = $("input#phone").val();
            var edunum = $('#clonetd .divedu').length;
            var worknum = $('#clonetd_work .divwork').length;
            var eduarr = new Array();
            for(var i = 1;i<edunum+1;i++){
                var datetimes_edu = $("#datetimes_edu"+i).val();
                var school_edu = $("#school_edu"+i).val();
                var major_edu = $("#major_edu"+i).val();
                var education_edu = $("#education_edu"+i).val();
                var edupart = [datetimes_edu,school_edu,major_edu,education_edu];
                eduarr[i-1] = edupart;
            }
            var education = JSON.stringify(eduarr);
            //console.log(eduarr);
            var workarr = new Array();
            for(var i = 1;i<worknum+1;i++){
                var datetimes_work = $("#datetimes_work"+i).val();
                var school_work = $("#school_work"+i).val();
                var major_work = $("#major_work"+i).val();
                var education_work = $("#education_work"+i).val();
                var workpart = [datetimes_work,school_work,major_work,education_work];
                workarr[i-1] = workpart;
            }
            var job = JSON.stringify(workarr);
            var birthday = year+'/'+month+'/'+day;

            //return;

            //var bkzs = $("input#bkzs").val();
            //var zsdj = $("input#zsdj").val();
            //var bkfx = $("input#bkfx").val();
            var message = $("textarea#message").val();
            var plan_id = $('input#plan_id').val();
            var userid = $("input#userid").val();
            var mid = $("input#mid").val();
            var zsid = $("input#zsid").val();
            var jf = $("input#jf").val();

            $.ajax({
                //url: "/wechatbaoming/wap/baoming/submitform",
                url: "/wap/index.php/baoming/submitform",
                type: "POST",
                data: {
                    plan_id: plan_id,
                    zs_id: userid,
                    jf: jf,
                    //company: company,
                    name: name,
                    nation: nation,
                    birthday: birthday,
                    sfz: sfz,
                    tel: phone,
                    sex: sex,
                    //bkzs: bkzs,
                    //zsdj: zsdj,
                    //bkfx: bkfx,
                    education: education,
                    job: job,
                    mid: mid,
                    zsid: zsid,
                    message: message
                },
                cache: false,
                dataType:"json",
                success: function(res) {
                    console.log(res.trade_no);
                    var orderid = res.orderid;
                    if(res.trade_no){
                        //var tourl = "/wechatbaoming/wap/index.php/baoming/pay/id/"+orderid;
                        var tourl = "/wap/index.php/baoming/pay/id/"+orderid;
                        window.location.href=tourl;
                    }else{
                        $('#success').html("<div class='alert alert-danger'>");
                        $('#success > .alert-danger').html("<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;")
                            .append("</button>");
                        $('#success > .alert-danger')
                            .append("<strong> 订单生成失败 </strong>");
                        $('#success > .alert-danger')
                            .append('</div>');
                    }

                    /*// Success message
                    $('#success').html("<div class='alert alert-success'>");
                    $('#success > .alert-success').html("<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;")
                        .append("</button>");
                    $('#success > .alert-success')
                        .append("<strong>报名信息已经提交,您的用户名是身份证号,密码为身份证后6位. </strong>");
                    $('#success > .alert-success')
                        .append('</div>');

                    //clear all fields
                    $('#contactForm').trigger("reset");
                    $("#contactForm").hide();
                    $("#pay").show();*/
                },
                error: function() {
                    // Fail message
                    $('#success').html("<div class='alert alert-danger'>");
                    $('#success > .alert-danger').html("<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;")
                        .append("</button>");
                    //$('#success > .alert-danger').append("<strong>Sorry " + firstName + " it seems that my mail server is not responding...</strong> Could you please email me directly to <a href='mailto:me@example.com?Subject=Message_Me from myprogrammingblog.com;>me@example.com</a> ? Sorry for the inconvenience!");
                    $('#success > .alert-danger').append('</div>');
                    //clear all fields
                    $('#contactForm').trigger("reset");
                },
            })
        },
        filter: function() {
            return $(this).is(":visible");
        },
    });

    $("a[data-toggle=\"tab\"]").click(function(e) {
        e.preventDefault();
        $(this).tab("show");
    });
});


/*When clicking on Full hide fail/success boxes */
$('#name').focus(function() {
    $('#success').html('');
});
