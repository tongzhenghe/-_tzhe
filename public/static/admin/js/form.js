

//Demo
layui.use('form', function() {
    var form = layui.form;

    //监听提交
    form.on('submit(formDemo)', function(data){
        layer.msg(JSON.stringify(data.field));
        return false;
    });
});


layui.use('upload', function () {

    var upload = layui.upload;

    upload.render({

        elem: '.uploads',

        url: "/index.php/admin/index/uploads"

        , before: function (obj) {

            layer.load();

        }

        , done: function (res, index, upload) {
            console.log(res)
            //预览
            $("#upload-normal-img").attr('src', res.file);
            layer.closeAll('loading'); //关闭loading
        }
        , error: function (index, upload) {
            layer.closeAll('loading'); //关闭loading
        }
    });
});



//多图
layui.use('upload', function () {

    var upload = layui.upload;

    upload.render({

        elem: '.uploadsse',

        multiple: true,//多图

        url: "/index.php/admin/index/uploads"

        , before: function (obj) {

            layer.load();

        }

        , done: function (res, index, upload) {

            console.log(res.file);

            //预览
            $(".demos").append(' <span style="     top: -57px;   left:  5.042%;position: relative;"  class="layui-badge Eliminate">' +
                '<a href="javascript:;" style="    color: #fff;">删除</a>' +
                ' </span><img class="imageses" src="'+res.file+'" width="100px" style="    margin: 11px  -17px;">' +
                '');
            layer.closeAll('loading'); //关闭loading
        }
        , error: function (index, upload) {
            layer.closeAll('loading'); //关闭loading
        }
    });
});


//登陆
layui.use(['form','layer','jquery'], function () {
    // // 操作对象
    var form = layui.form;
    var $ = layui.jquery;
    form.on('submit(login)',function (data) {

        data.field.is_remember =data.field.is_remember  === 'on' ?  1 : 2;
        $.fn.setRequest( data.field,    '/index.php/admin/sign/signin');

    });

});


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
                    if (response.url) {
                        window.location.href = response.url;
                    } else {
                        window.parent.location.reload();
                    }
                });
            } else {
                layer.msg('<span><i class="layui-icon layui-icon-close" style="    color: white; background: red;     margin-right: 13px;border-radius: 10px;"></i>'+response.msg+'</span>', {
                    time : 1000,
                    // time: 2, //2s后自动关闭
                });
            }

        },  dataType);

};