$(function () {
    //预览
    hui('.imgs').click(function(){
        var index = hui(this).index();
        console.log(index)
        previewImg(index);
    });

    function previewImg(index){
        var pswpElement = document.querySelectorAll('.pswp')[0];
        var items = [];
        //获取图片数据并填充近数组
        hui('.imgs').each(function(eimg){
            var imgObj = {src:eimg.getAttribute('src'), w:eimg.naturalWidth, h:eimg.naturalHeight};
            items.push(imgObj);
        });
        var options = {index: index};
        var gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items, options);
        gallery.init();
    }


    //多图
    layui.use('upload', function () {

        var upload = layui.upload;

        upload.render({

            elem: '.uploadsse',

            multiple: true,//多图

            url: "/index.php/index/uploads"

            , before: function (obj) {

                hui.loading('上传中..');

            }

            , done: function (res, index, upload) {

                console.log(res);

                //预览
                $(".img-demo").append(' <img src="'+res.file+'"/><i class="i3">x</i>');
               	hui.closeLoading();
            }
            , error: function (index, upload) {
               	hui.closeLoading();
            }
        });
    });

 //多图
    layui.use('upload', function () {

        var upload = layui.upload;

        upload.render({

            elem: '.uploadsse-appro-yj',

            multiple: false,//多图

            url: "/index.php/index/uploads"

            , before: function (obj) {

                hui.loading('上传中..');

            }

            , done: function (res, index, upload) {

                //预览
                $(".demo-div").append(' <div class="img-yj"><img src="'+res.file+'" onclick="preview(this);"><i class="del-app-file">x</i></div>');

               	hui.closeLoading();
            }
            , error: function (index, upload) {
               	hui.closeLoading();
            }
        });
    });

    //删除
    $(".demo-div").on("click", ".del-app-file", function () {
        $(this).prev().remove();
        $(this).remove();
    });


    //意见pdf附件
    layui.use('upload', function () {

        var upload = layui.upload;

        upload.render({

            elem: '.pdf-file',
            accept: 'file',
            exts: 'txt|docx|doc|xls|xlsx|pdf',
            multiple: false,

            url: "/index.php/index/nopic"

            , before: function (obj) {

                hui.loading('上传中..');

            }

            , done: function (res, index, upload) {
                var html = ' <div class="pdf-yj">\n' +
                    '            <a href="'+res.file+'" style="color: #3388ff;" data-item="'+res.filename+'">'+res.filename+'</a><i class="del-app-pdf">x</i>\n' +
                    '            </div>';
                $(".demo-div").append(html);
                //预览
                hui.closeLoading();
            }
            , error: function (index, upload) {
                hui.closeLoading();
            }
        });
    });


    //删除
    $(".demo-div").on("click", ".del-app-pdf", function () {
        $(this).prev().remove();
        $(this).remove();
    });
    //审批意见-End







    //pdf附件
    layui.use('upload', function () {

        var upload = layui.upload;

        upload.render({

            elem: '.nopic',
            accept: 'file',
            exts: 'txt|docx|doc|xls|xlsx|pdf',
            multiple: true,

            url: "/index.php/index/nopic"

            , before: function (obj) {

                hui.loading('上传中..');

            }

            , done: function (res, index, upload) {
                var html = ' <p>'+res.filename+'<a href="'+res.file+'" data-item="'+res.filename+'">预览</a><span id="del_nopic" >x</spanid></p>';
                $(".nopic-demo").append(html);
                //预览
               	hui.closeLoading();
            }
            , error: function (index, upload) {
               	hui.closeLoading();
            }
        });
    });



    //单图
    layui.use('upload', function () {
        var upload = layui.upload;

        upload.render({

            elem: '.upload',

            multiple: false,//多图

            url: "/index.php/index/uploads"

            , before: function (obj) {

                hui.loading('上传中..');

            }

            , done: function (res, index, upload) {

                console.log(res);

                //预览
                $(".photo-demo").attr("style", "display:block");
                $(".photo-demo").attr("src", res.file);
                $(".Eliminate").show();
               	hui.closeLoading();
            }
            , error: function (index, upload) {
                console.log(index)
               	hui.closeLoading();
            }
        });
    });

    //delete_icon
    $(".Eliminate").on("click", function () {
        $(".photo-demo").attr("src", "");
        $(".photo-demo").hide();
        $(this).hide();
    });


    $(".nopic-demo").on('click', '#del_nopic', function () {
        $(this).parent('p').remove();
    });

    //附件上传
    layui.use('upload', function () {

        var upload = layui.upload;

        upload.render({
            elem: '.uploads-accessory'
            ,exts: 'txt|ppt|xls|pdf'
            ,accept: 'file'
            ,multiple: false
            ,url: "/index.php/index/up_accessory"
            , before: function (obj) {
                hui.loading('上传中..');
            } ,done: function (res, index, upload) {

                console.log(res);

                hui.closeLoading();
            }
            , error: function (index, upload) {
               	hui.closeLoading();
            }
        });
    });



    $(".register").on("click", function () {
        window.location.replace('/index/sign/register/do/valid');
    });

    $(".appty").on("click", function () {
        window.location.href='/index/tyapproval';
    });

    //报销
   $(".salary-link").on("click", function () {
        window.location.href='/index/salary';
    });

   //采购
   $(".procurement-link").on("click", function () {
        window.location.href='/index/procurement';
    });


  $(".return-login").on("click", function () {
        window.location.replace('/index/sign/signin');
    });

    $(".ty-unit").on("click", function () {
        window.location.replace('/index/signin');
    });
    
    $('#but-valid').unbind('click').on('click', function () {
        var tel = $("input[name='tel']").val()
            ,request_url = $("input[name='request_url']").val();

        $.post(
            request_url
          ,{tel:tel}
           ,function( response ) {
                console.log(response);
                if ( response.code === 200 ) {
                    //展示valid_captcha页面；
                    $(".tel").hide();
                    $(".captcha").show();
                    $(".reg").children("h3").text(response.tel);
                } else {
                    hui.toast(response.msg);
                }

            },  'json');

    });


    //报销添加项
    $("#add_rei").on("click", function () {

        //添加多项：
        $(".del-group").show();
        var new_r = $(".procurement-group").clone();
        $(this) .after(new_r);
        var html = '<div class="hui-form-items" id="add_rei" style="border-bottom:none;">\n' +
            '            <span style="color:#3296FA; margin: auto;font-size: 16px;"><i class="fa fa-plus-square" style="color: #3388ff;" aria-hidden="true"></i>增加报销明细</span>\n' +
            '        </div>';
        $("#add_rei").remove();
        $(".procurement-group:last").after(html);
    });


    $("#form1").on("click", "#add_rei", function () {

       $(".procurement-group:last").after(" <div class=\"procurement-group\">"+$(".procurement-group:last").html()+"</div>");

        var html = '<div class="hui-form-items" id="add_rei" style="border-bottom:none;">\n' +
            '            <span style="color:#3296FA; margin: auto;font-size: 16px;"><i class="fa fa-plus-square" style="color: #3388ff;" aria-hidden="true"></i>增加报销明细</span>\n' +
            '        </div>';
        $("#add_rei").remove();
        $(".procurement-group:last").after(html);

        if ($(".procurement-group").length-1 >= 1) {
            $(".del-group").show();
        }


    });

    /*首个删除*/
    $(".del-group").on("click", function () {


        // hui.confirm('确定删除当前报销明细吗？', ['取消','确认'], function() {

        if ($(".procurement-group").length-1 === 1) {
            $(".del-group").hide();
        }
        $(this).parent(".procurement-group").remove();

        // },function(){
        //     hui.toast('取消删除');
        // });


    });



   $("#submitProcurement").on("click", function () {

       var intro = $("textarea[name='intro']").val()
           ,typeid = $("select[name='typeid']").val()
           ,pro_date = $("input[name='pro_date']").val() //报销方式
           ,name = $("input[name='name']").val()
           ,specs = $("input[name='specs']").val()
           ,num = $("input[name='num']").val()
           ,unit = $("input[name='unit']").val()
           ,pro_price = $("input[name='pro_price']").val()
           ,use_add = $("input[name='use_add']").val()
           ,supplier = $("input[name='supplier']").val()
           ,payTypeId = $("select[name='payTypeId']").val()
           , generalId = $("input[name='generalId']").val()
           , request_url = $("input[name='request_url']").val();

       //文件
       var image = [];
       $.each($(".img-demo").children("img"), function (key, val ) {
           image.push($(val).attr("src"))
       });

       //附件
       var nopic = [];
       $.each($(".nopic-demo").children("p"), function (key, val ) {
           nopic.push($(val).children('a').attr("href")+'|'+ $(val).children('a').attr("data-item"));
       });

       formData = {intro:intro, pro_price:pro_price,typeid:typeid,pro_date:pro_date,name:name,specs:specs,use_add:use_add,supplier:supplier, num:num, unit:unit,payTypeId:payTypeId, nopic:nopic,image:image, generalId:generalId};
       $.fn.setRequest3(formData, request_url )

   });




    //
    //
    // hui.confirm('确定删除当前报销明细吗？', ['取消','确认'], function() {
    //     if ($(".procurement-group").length-1 === 1) {
    //         $(".del-group").hide();
    //     }
    //     $(this).parent(".procurement-group").remove();
    //     //跳转返回登陆连接
    // },function(){
    //     hui.toast('取消删除');
    // });








    /*append后删除*/
    $("#form1").on("click", ".del-group", function () {

        // hui.confirm('确定删除当前报销明细吗？', ['取消','确认'], function() {

      if ($(".procurement-group").length-1 === 1) {
          $(".del-group").hide();
      }

        //先获取节点个数， 如果节点相当于1 就隐藏当前删除元素
        $(this).parent(".procurement-group").remove();

        // });



    });





    //报销添加项-END




    //重发送验证码
    $('.btn-s').unbind('click').on('click' ,function () {
        $(".click-send").hide();
        $(".second_show").show();
        $.post(
            '/index/sign/register/do/rewire.html'
            ,{tel:$(".reg").children("h3").text()}
            ,function( response ) {
                console.log(response);
                if ( response.code === 200 ) {
                    //展示valid_captcha页面；
                    $(".reg").children("h3").text(response.tel);
                } else {
                    hui.toast(response.msg);
                }

            },  'json');

       var  intDiff = parseInt(60);//倒计时总秒数量

        var i = window.setInterval(function() {
            var day=0,
                hour=0,
                minute=0,
                second=0;//时间默认值
            if(intDiff > 0) {
                day = Math.floor(intDiff / (60 * 60 * 24));
                hour = Math.floor(intDiff / (60 * 60)) - (day * 24);
                minute = Math.floor(intDiff / 60) - (day * 24 * 60) - (hour * 60);
                second = Math.floor(intDiff) - (day * 24 * 60 * 60) - (hour * 60 * 60) - (minute * 60);
            }
            if (minute <= 9) minute = '0' + minute;
            if (second <= 9) second = '0' + second;
            $('#minute_show').html('<s></s>'+minute+':');
            $('#second_show').html('<s></s>'+second+'<span style="color: #a3a1a1;font-size: 12px;">&nbsp;&nbsp;后重发验证码</span>');
            intDiff--;
            if(intDiff < 0)
            {
                clearInterval(i);
                $(".click-send").show();
                $(".second_show").hide();
            }

        }, 1000);
    });


    
    
    //验证码
    $('#but-valid-captcha').on('click' , function () {
        // 获取验证码
        var captcha  = $("input[name='captcha']").val()
            , tel = $(".reg").children("h3").text()
            ,request_url2 = $("input[name='request_url2']").val();
        $.fn.setRequest2({captcha:captcha, tel:tel}, request_url2)

        
    });
    
    
    

  //注册
    $("#submitBtn").on("click", function () {
        var password = $("input[name='password']").val(),
            repassword = $("input[name='repassword']").val(),
            url = $("input[name='request_url']").val(),
            user_tel = $("input[name='user_tel']").val(),
            user_name = $("input[name='user_name']").val(),
            compid = $("select[name='compid']").val();
            formData = {password:password,repassword:repassword,user_tel:user_tel,user_name:user_name, compid:compid};
            $.post(
                url
                ,formData,
                function( response ) {
                    console.log(response)
                    if ( response.code === 200) {

                        hui.iconToast('提交成功', 'success');
                        // hui.alert('注册成功，前往登陆','好的', function(){
                        //     window.location.replace(response.url); //手机网页式跳转
                        // });
                        setTimeout(function(){
                            window.location.replace(response.url); //手机网页式跳转
                        },2000);
                    } else {
                        hui.toast(response.msg);
                    }

                },  'json');

    });


    /**
     *退出
     */
    $("#logout").on('click', function () {
        hui.confirm('您要退出吗？', ['取消','确认'], function() {
            //调用退出接口
            $.fn.setRequest2({'logout': true}, '/index/sign/logout');
            //跳转返回登陆连接
        },function(){
            hui.toast('取消退出');
        });

    });


    /**
     * 设置
     */
    $("#account_update").on("click", function () {
       var new_password = $("input[name='new_password']").val()
            ,renew_password = $("input[name='renew_password']").val()
            ,old_password = $("input[name='old_password']").val()
            ,icon =  $(".photo-demo").attr("src")
            ,sex = $("select[name='sex']").val()
            ,tel = $("input[name='tel']").val()
            ,department_id = $("select[name='department_id']").val()
           ,remarks =  $("textarea[name='remarks']").val()
           ,request_url = $("input[name='request_url']").val()
           ,uid = $("input[name='uid']").val();
        $.fn.setRequest2({new_password:new_password, renew_password:renew_password,old_password:old_password,icon:icon, sex:sex, tel:tel, department_id:department_id, remarks:remarks, uid:uid}, request_url);

    });


    //登陆
    $("#login-But").on("click", function () {
        var user_name = $("input[name='user_name']").val(),
            password = $("input[name='password']").val(),
            request_url = $("input[name='request_url']").val(),
            dataForm = {user_name : user_name, password: password};

            $.fn.setRequest2(dataForm, request_url);

    });



    //完善
    $("#submitBtn-perfect").on("click", function () {

        var photo = $(".photo-demo").attr("src")
            ,sex = $("input[name='sex']").val()
            ,birth = $("input[name='birth']").val()
            ,department_id = $("select[name='department_id']").val()
            ,remarks =  $("textarea[name='remarks']").val()
            ,request_url = $("input[name='request_url']").val()
            ,dataForm = {photo:photo, sex : sex, birth:birth, department_id: department_id, remarks:remarks};
            $.fn.setRequest2(dataForm, request_url);
    });


//提交审批
    $("#submitApp").on("click", function () {

        var content = $("input[name='content']").val()
            ,detail = $("textarea[name='detail']").val()
            , request_url = $("input[name='request_url']").val();
        //文件
       var img = [];
       $.each($(".img-demo").children("img"), function (key, val ) {
           img.push($(val).attr("src"))
       });

        //附件
        var nopic = [];
        $.each($(".nopic-demo").children("p"), function (key, val ) {
            nopic.push($(val).children('a').attr("href")+'|'+ $(val).children('a').attr("data-item"));
        });


        //审核人
        var app_men = [];
        $.each($(".hui-tags-active"), function (key, val ) {
            var id = $(val).attr("data-value");
            app_men.push(id);
        });

         var person_cs = [];
            $.each($(".hui-tags-active2"), function (key, val ) {
                var id = $(val).attr("data-value");
                person_cs.push(id);
        });

            formData = {content:content, detail:detail, nopic:nopic, img:img, app_men:app_men, person_cs:person_cs};

        $.fn.setRequest2(formData, request_url )


    });

    $("#submitNext").on("click", function () {

        //审核人
        var app_people = []
            ,url = $("input[name='url']").val()
            ,module_name = $("input[name='module_name']").val()
            , request_url = $("input[name='request_url']").val()
            , session_d = $("input[name='session_d']").val();
        $.each($(".hui-tags-active"), function (key, val ) {
            var id = $(val).attr("data-value");
            app_people.push(id);
        });

        //抄送人
        var cs_people = [];
        $.each($(".hui-tags-active2"), function (key, val ) {
            var id = $(val).attr("data-value");
            cs_people.push(id);
        });

        formData = {app_people:app_people, cs_people:cs_people, url: url, module_name: module_name,session_d:session_d};

        $.fn.setRequest2(formData, request_url )

    });





    //报销审批表单提交
    $("#submitReimbursement").on("click", function () {

        var title = $("input[name='title']").val()
            ,r_money = $("input[name='r_money']").val()
            ,r_company = $("input[name='r_company']").val()
            ,r_typeid = $("select[name='r_typeid']").val()//报销方式
            ,r_date = $("input[name='r_date']").val()
            ,intro =  $("textarea[name='intro']").val()
            ,generalId = $("input[name='generalId']").val()
            ,request_url = $("input[name='request_url']").val();

        //图片文件
        var image = [];
        $.each($(".img-demo").children("img"), function (key, val ) {
            image.push($(val).attr("src"));
        });

        //附件
        var nopic = [];
        $.each($(".nopic-demo").children("p"), function (key, val ) {
            nopic.push($(val).children('a').attr("href")+'|'+ $(val).children('a').attr("data-item"));
        });

        formData = {r_money:r_money, title:title,r_company:r_company, r_typeid:r_typeid,r_date:r_date, intro:intro,nopic:nopic,image:image,  generalId:generalId};
        $.fn.setRequest3(formData, request_url );

    });



    //报销审批表单提交
    $("#submitSalary").on("click", function () {

        var title = $("input[name='title']").val()
            ,salary_money = $("input[name='salary_money']").val()
            ,typeid = $("select[name='typeid']").val()//报销方式
            ,intro =  $("textarea[name='intro']").val()
            ,generalId = $("input[name='generalId']").val()
            ,request_url = $("input[name='request_url']").val();

        //图片文件
        var image = [];
        $.each($(".img-demo").children("img"), function (key, val ) {
            image.push($(val).attr("src"));
        });

        //附件
        var nopic = [];
        $.each($(".nopic-demo").children("p"), function (key, val ) {
            nopic.push($(val).children('a').attr("href")+'|'+ $(val).children('a').attr("data-item"));
        });

        formData = {salary_money:salary_money, title:title,typeid:typeid, intro:intro,nopic:nopic,image:image,  generalId:generalId};

        $.fn.setRequest3(formData, request_url );

    });





    //删除
    $(".img-demo").on("click", ".i3",  function () {
        $(this).prev().remove();
        $(this).remove()
    });



    //同意意见
    $(".approy").on("click", function () {
        var agree_reason =  $("textarea[name='appro_yj']").val()
            ,request_url = $("input[name='request_url']").val();
        var img = [];

        $.each($(".img-demo").children("img"), function (key, val ) {
            img.push($(val).attr("src"))
        });

        //图片
        var img_file = $(".img-yj").children("img").attr("src")
        //附件
        ,annex_filename = $(".pdf-yj").children("a").attr("data-item")
        ,annex = $(".pdf-yj").children("a").attr("href");

        short_message = $('input[type=checkbox]:checked').val();

        // $.each($(".annex-demo").children("p"), function (key, val ) {
        //     annex.push($(val).children('a').attr("href"))
        // });

        formData = {agree_reason:agree_reason,img_file:img_file, annex:annex, annex_filename:annex_filename,short_message:short_message?short_message:false};

        $.fn.setRequest2(formData, request_url );

    });




    //拒绝
    $(".appron").on("click", function () {
        var reject_reason =  $("textarea[name='appro_yj']").val()
            //附件
            ,annex_filename = $(".pdf-yj").children("a").attr("data-item")
            ,annex = $(".pdf-yj").children("a").attr("href")
            ,short_message = $('input[type=checkbox]:checked').val()
            ,request_url = $("input[name='request_url']").val();

        // var img = [];
        // $.each($(".img-demo").children("img"), function (key, val ) {
        //     img.push($(val).attr("src"))
        // });

        //图片
        var img_file = $(".img-yj").children("img").attr("src");
        formData = {reject_reason:reject_reason,img_file
                :img_file
            ,annex_filename:annex_filename, annex:annex, short_message:short_message};
        $.fn.setRequest2(formData, request_url );

    });



    //文件上传uploadappro-yj




    //个人
    $("#nav-home-pro").on("click", function () {
        $(".pro-home").show();
        $(".set-home").hide();
        $('.title').text('审批');
        $(this).children().children(".layui-icon-vercode").css('color', '#2196F3');
        $(this).children(".hui-footer-text").css('color', '#2196F3');
        $("#nav-homes-set").children().children('.layui-icon-set').css('color','#949494' );
        $("#nav-homes-set").children('.hui-footer-text').css('color','#949494' );
    });

    //set
    $("#nav-homes-set").on("click", function () {
        $('.title').text('个人中心');
        $(this).children().children(".layui-icon-set").css('color', '#2196F3');
        $(this).children(".hui-footer-text").css('color', '#2196F3');
        $("#nav-home-pro").children().children('.layui-icon-vercode').css('color','#949494' );
        $("#nav-home-pro").children('.hui-footer-text').css('color','#949494' );
        $(".set-home").attr("style", "display:block;");
        $(".pro-home").hide();
        $(".set-home").show();
    });


    //撤回

    $(".withdraw_request").on("click", function () {

        $this = $(this);
        //撤回直接改变状态， 关闭审批磁条数据
        hui.confirm('您确定要撤销申请吗？', ['取消','确认'], function() {

            //需要数据： 此条审批id、 类型、  请求接口；
            var approval_type = $this.attr('data-item')
                , approval_id = $this.attr('data-id')
                ,url = $this.attr('data-url');
            formData = {approval_type:approval_type,approval_id:approval_id};
            $.fn.setRequest2(formData, url );


        },function(){
            hui.toast('已取消');
        });


    })

});


/**
 *
 * @param formData
 * @param url
 * @param type
 * @param dataType
 * @returns {boolean}
 */
$.fn.setRequest2 = function ( formData, url, type = 'post', dataType  = 'json') {
    if ( !formData ) {
        alert('请求数据失败');
        return false;
    }
    if ( ( url  ===  ""  ||  undefined  )  ) {
        alert('参数错误');
        return false;
    }
    $.post(
        url,
        formData,
        function( response ) {
            console.log(response);
            if ( response.code === 200) {
              hui.iconToast(response.msg, 'success');
                // hui.upToast('提交成功 !');

              if (response.url) {
                   setTimeout(function() {
                           window.location.replace(response.url); //手机网页式跳转
                       },2000);
              }


            } else {
                hui.toast(response.msg);
            }

        },  dataType);

};




//带提示
$.fn.setRequest3 = function ( formData, url, type = 'post', dataType  = 'json') {
    if ( !formData ) {
        alert('请求数据失败');
        return false;
    }
    if ( ( url  ===  ""  ||  undefined  )  ) {
        alert('参数错误');
        return false;
    }
    $.post(
        url,
        formData,
        function( response ) {
            console.log(response);
            if ( response.code === 200) {
                // hui.iconToast(response.msg, 'success');
                hui.confirm('提交成功！', ['否','继续添加'], function() {

                    //当前页面刷新
                    window.location.reload();

                },function(){

                    //回到指定页
                    window.location.replace('/index/personal'); //手机网页式跳转

                });

            } else {
                hui.toast(response.msg);
            }

        },  dataType);

};








$.fn.refersh = function () {location.reload();};


/*huidate封装*/
function  huiDate() {

  hui.datePicker({

        format : "yyyy-mm-dd",

        language : "zh-CN",

        autoclose : false,

        todayBtn : true,

        todayHighlight : true,

        showMeridian : false,

        minView : "month",

        pickerPosition : "bottom-left",

    });

}


//图片预览
function preview(obj) {
    var img = new Image();
    img.src = obj.src;
    var imgHtml = "<img  src='" + obj.src + "' width='100%'/>";
    //弹出层
    layer.open({
        type: 1,
        shade: 0.8,
        offset: 'auto',
        area: [300 + 'px'],
        shadeClose:true,
        scrollbar: false,
        title: "图片预览", //不显示标题
        content: imgHtml, //捕获的元素，注意：最好该指定的元素要存放在body最外层，否则可能被其它的相对元素所影响
        cancel: function () {
            //layer.msg('捕获就是从页面已经存在的元素上，包裹layer的结构', { time: 5000, icon: 6 });
        }
    });
}