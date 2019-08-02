

/*表单提交*/

$(function () {

    $(".generaladd").on("click", function () {

       var name =  $("input[name='name']").val(),
           sort =  $("input[name='sort']").val(),
           icon =  $("#upload-normal-img").attr("src"),
           url =  $("input[name='url']").val(),
           bgcolor =  $("input[name='bgcolor']").val(),
           icon_code =  $("input[name='icon_code']").val(),
           pid = $("select[name='pid']").val(),
           id =  $("input[name='hidId']").val();
            request_url = $("input[name='request_url']").val();
            formData = {
                name:name,
                sort:sort,
                icon:icon,
                bgcolor:bgcolor,
                icon_code:icon_code,
                url:url,
                pid:pid,
                id  :id  ? id : null
            };

        $.fn.setRequest( formData,    request_url );

    });

    $(".department_add").on("click", function () {

       var name =  $("input[name='name']").val(),
           remarks =  $("textarea[name='remarks']").val(),
           id =  $("input[name='hidId']").val();
            request_url = $("input[name='request_url']").val();
            formData = {
                name:name,
                remarks:remarks,
                id  :id  ? id : null
            };

        $.fn.setRequest( formData,    request_url );

    });

    //采购类型
 $(".procurement_add").on("click", function () {

       var name =  $("input[name='name']").val(),
           sort =  $("input[name='sort']").val(),
           intro =  $("textarea[name='intro']").val(),
           id =  $("input[name='hidId']").val();
            request_url = $("input[name='request_url']").val();
            formData = {
                name:name,
                sort:sort,
                intro:intro,
                id  :id  ? id : null
            };

        $.fn.setRequest( formData, request_url );

    });


 //工资类型
  $(".salary_addtype").on("click", function () {

       var name =  $("input[name='name']").val(),
           sort =  $("input[name='sort']").val(),
           intro =  $("textarea[name='intro']").val(),
           id =  $("input[name='hidId']").val();
            request_url = $("input[name='request_url']").val();
            formData = {
                name:name,
                sort:sort,
                intro:intro,
                id  :id  ? id : null
            };

        $.fn.setRequest( formData, request_url );

    });


  //通用报销方式
  $(".reimbursement_addtype").on("click", function () {

       var name =  $("input[name='name']").val(),
           sort =  $("input[name='sort']").val(),
           intro =  $("textarea[name='intro']").val(),
           id =  $("input[name='hidId']").val();
            request_url = $("input[name='request_url']").val();
            formData = {
                name:name,
                sort:sort,
                intro:intro,
                id  :id  ? id : null
            };

        $.fn.setRequest( formData, request_url );

    });





 $("._addpaytype").on("click", function () {

       var name =  $("input[name='name']").val(),
           sort =  $("input[name='sort']").val(),
           intro =  $("textarea[name='intro']").val(),
           id =  $("input[name='hidId']").val();
            request_url = $("input[name='request_url']").val();
            formData = {
                name:name,
                sort:sort,
                intro:intro,
                id  :id  ? id : null
            };

        $.fn.setRequest( formData, request_url );

    });




    //账户注册
    $(".adminadd").on("click", function () {
            var ue =  UE.getEditor('editor');
        //对编辑器的操作最好在编辑器ready之后再做
        //ue.ready(function() {
            // 设置编辑器的内容
            // ue.setContent('hello');
            //获取html内容，返回: <p>hello</p>
            var intro = ue.getContent();
            //获取纯文本内容，返回: hello
            // var txt = ue.getContentTxt();
        // });
        var comname =  $("input[name='comname']").val(),
            icon =  $("#upload-normal-img").attr("src"),
            password =  $("input[name='password']").val(),
            repassword =  $("input[name='repassword']").val(),
            is_manage =   $("input:radio[name='is_manage']:checked").val(),
            id =  $("input[name='hidId']").val();
            request_url = $("input[name='request_url']").val();
        formData = {
            comname:comname,
            icon:icon,
            password:password,
            is_manage:is_manage,
            repassword:repassword,
            intro : intro,
            id  :id  ? id : null
        };

        $.fn.setRequest( formData,    request_url );


    });
    
    $(".adminaccount_setting").on('click', function () {
        //获取简介
        var ue =  UE.getEditor('editor')
            ,intro = ue.getContent()
            ,id =  $("input[name='hidId']").val()
            ,request_url = $("input[name='request_url']").val();
        $.fn.setRequest( {intro:intro,  id  :id  ? id : null},    request_url );

    })
    


    //审批人选择
    $(".select-user").on("click" , function () {
        //1、 将自己的颜色改变， 同时将用户
        $(this).children('a').toggleClass('toggle-on');
        //2、 将获取到当前用户id和name append到左侧并按照顺序排列
        var id = $(this).attr('data-id')
            ,username = $(this).children('a').text();

        //append发到左侧流程表
        var html = '<li class="layui-timeline-item"><div class="layui-timeline-content layui-text st"><p data-id="'+id+'">'+username+'</p><i class="layui-icon layui-icon-close-fill setdel-u"></i></div><i class="layui-icon layui-icon-down"></i></li>';
        $('.center-users').prev().append(html);
    });



    //switch取消
    $(".select-user").on("click", '.toggle-on', function () {


        var arr = ['a','b','c'];
        if($.inArray('a', arr) >= 0){
            alert('存在');
        }else{
            alert('不存在');
        }


       //
       //
       //  //获取当前id。 并和左侧比对， 如果相同就删除
       // var   id_arr = [],right_id = $(this).parent('span').attr('data-id');
       // $.each($('.center-users').prev().children().children().children('p'), function (k, v) {
       //     id_arr.push($(v).attr('data-id'));
       // });

       //获取左边所有id
       // $('.center-users').prev(),





    });

    //通过左边删除
    $(".center-users").prev().on("click", ".setdel-u", function () {
        console.log(this);


    });


});



//公共组件
$(function () {

    //switch
    $(".switch").on("click", function () {
        var id = $(this).attr('data-ids'),
            field = $(this).attr('data-field'),
            point = $(this).attr('data-do'),
            value  = $(this).attr('data-switch-value'),
            tel = $(this).attr('data-tel'),
            url = $(this).attr('data-switch-url');
            formData = {id:id, do: point, value: value, field:field, tel :tel ? tel : null};
            console.log(url)
        $.fn.setRequest( formData, url );
    });

});

//删除
$(".delete-data").on("click", function () {
    var id = $(this).attr("data-ids"),
        url = $(this).attr("data-url");
    layer.open({
        title:'警告！',
        content: '确定删除？'
        ,btn: ['确定','取消']
        ,yes: function(index, layero) {
            //回调
            formData = {id:id, do: '_delete'};
            $.fn.setRequest( formData, url );
        }
    });


});


//提交设置模块审批
$(".default_pro").on("click", function () {
    var checkID = [];//定义一个空数组
    $("input[name='default_appro']:checked").each(function(i){//把所有被选中的复选框的值存入数组
     checkID[i] =$(this).val();
    });

    formData = {moduleID:checkID, 'userid': $("input[name='hiddenUserId']").val()};
    $.fn.setRequest( formData, '/index.php/admin/index/default_pro' );

});

/**
 *
 * @param formData
 * @param url
 * @param type
 * @param dataType
 * @returns {boolean}
 */
$.fn.setRequest = function ( formData, url, type = 'post', dataType  = 'json') {

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

            if ( response.code === 200 &&response.status === true ) {
                layer.msg('<span><i class="layui-icon layui-icon-ok" style="    color: white; background: #6cd965;     margin-right: 13px;border-radius: 10px;"></i>'+response.msg+'</span>', {
                    time : 1000,
                    maxWidth: 850,
                }, function() {
                    layer.close(layer.index);
                    window.parent.location.reload();
                });
            } else {
                layer.msg('<span><i class="layui-icon layui-icon-close" style="    color: white; background: red;     margin-right: 13px;border-radius: 10px;"></i>'+response.msg+'</span>', {
                    time : 1000,
                      // time: 2, //2s后自动关闭
               });
            }

        },  dataType);

};



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
        area: [500 + 'px',550+'px'],
        shadeClose:true,
        scrollbar: false,
        title: "图片预览", //不显示标题
        content: imgHtml, //捕获的元素，注意：最好该指定的元素要存放在body最外层，否则可能被其它的相对元素所影响
        cancel: function () {
            //layer.msg('捕获就是从页面已经存在的元素上，包裹layer的结构', { time: 5000, icon: 6 });
        }
    });
}



$.fn.createModuleFrame = function(title,src,opt) {
    opt === undefined && (opt = {});
    layer.open({
        type: 2,
        title:title,
        area: [(opt.w || 80)+'%', (opt.h || 80)+'%'],
        fixed: false, //不固定
        maxmin: true,
        moveOut:false,//true  可以拖出窗外  false 只能在窗内拖
        anim:5,//出场动画 isOutAnim bool 关闭动画
        offset:'auto',//['100px','100px'],//'auto',//初始位置  ['100px','100px'] t[ 上 左]
        shade:0,//遮罩
        resize:true,//是否允许拉伸
        content: src,//内容
        move:'.layui-layer-title',// 默认".layui-layer-title",// 触发拖动的元素

    });
}