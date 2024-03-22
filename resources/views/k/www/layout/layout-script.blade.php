<script>
    $(function() {

//        $('article').readmore({
//            speed: 150,
//            moreLink: '<a href="#">展开更多</a>',
//            lessLink: '<a href="#">收起</a>'
//        });

        $('.readmore-content').readmore({
            speed: 500,
            lessLink: '<a href="#">收起</a>',
            moreLink: '<a href="#">展开更多</a>',
            collapsedHeight: 240,
            embedCSS: false
        });

        $('.lightcase-image').lightcase({
            maxWidth: 9999,
            maxHeight: 9999
        });

        var viewportSize = $(window).height();
        var lazy_load = function(){
            var scrollTop = $(window).scrollTop();
            $("img").each(function(){
                var _this = $(this);
                var x = viewportSize + scrollTop + _this.position().top;
                if(x>0){
                    _this.attr("src",_this.attr("data-src"));
                }
            })
        };
        // setInterval(lazy_load,1000);

        $('[name="test"]').bootstrapSwitch({
            onText:"展示名片",
            onColor:"success",
            offText:"不展示名片",
            // offColor:"danger",
            size:"small",
            onSwitchChange:function(event,state){
                if(state==true){
                    console.log('已打开');
                }else{
                    console.log('已关闭');
                }
            }
        });





        $('#myCheckbox').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' //可以调整按钮的点击区域
        });
        // 监听 'ifChecked' 事件
        $('#myCheckbox').on('ifChecked', function(event){
            // 当开关打开时的操作
            console.log('开关已打开！');
        });
        // 监听 'ifUnchecked' 事件
        $('#myCheckbox').on('ifUnchecked', function(event){
            // 当开关关闭时的操作
            console.log('开关已关闭！');
        });


        // $('#myCheckbox').iCheck({
        //     checkboxClass: 'icheckbox_square-blue',
        //     radioClass: 'iradio_square-blue',
        //     // increaseArea: '20%' // 增加点击区域面积
        // });
        //
        // // 初始化 radio 按钮
        // $('input[type="radio"]').iCheck({
        //     checkboxClass: 'icheckbox_square-blue',
        //     radioClass: 'iradio_square-blue',
        //     increaseArea: '20%'
        // });
        //
        // // 监听 'ifChecked' 事件
        // $('#myCheckbox').on('ifChecked', function(event){
        //     // 当 checkbox 被选中时执行的代码
        //     console.log('Checkbox is checked.');
        // });
        //
        // // 监听 'ifChanged' 事件
        // $('input').on('ifChanged', function(event){
        //     // 当 checkbox 或 radio 状态改变时执行的代码
        //     console.log('Input state changed.');
        // });

    });
</script>