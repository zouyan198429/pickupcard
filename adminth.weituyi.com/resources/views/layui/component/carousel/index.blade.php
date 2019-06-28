

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>轮播</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <link rel="stylesheet" href="{{asset('layui-admin-v1.2.1/src/layuiadmin/layui/css/layui.css')}}" media="all">
  <link rel="stylesheet" href="{{asset('layui-admin-v1.2.1/src/layuiadmin/style/admin.css')}}" media="all">
</head>
<body>

  <style>
  /* 为了区分效果 */
  #LAY-demo-carousel div[carousel-item]>*{text-align: center; line-height: 280px; color: #666;}
  #LAY-demo-carousel div[carousel-item]>*:nth-child(2n){background-color: #E2E2E2;}
  #LAY-demo-carousel div[carousel-item]>*:nth-child(2n+1){background-color: #eee;}
  #test-carousel-normal-2 div[carousel-item]>*{line-height: 120px;}
  </style>

  <div class="layui-card layadmin-header">
    <div class="layui-breadcrumb" lay-filter="breadcrumb">
      <a lay-href="">主页</a>
      <a><cite>组件</cite></a>
      <a><cite>轮播</cite></a>
    </div>
  </div>
  
  <div class="layui-fluid" id="LAY-demo-carousel">
    <div class="layui-row layui-col-space15">
      <div class="layui-col-md12">
        <div class="layui-card">
          <div class="layui-card-header">常规轮播</div>
          <div class="layui-card-body">
            <div class="layui-carousel" id="test-carousel-normal" lay-filter="test-carousel-normal">
              <div carousel-item="">
                <div>条目1</div>
                <div>条目2</div>
                <div>条目3</div>
                <div>条目4</div>
                <div>条目5</div>
              </div>
            </div> 
            <div class="layui-carousel" id="test-carousel-normal-2" style="margin-top: 15px;">
              <div carousel-item="">
                <div>条目1</div>
                <div>条目2</div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <div class="layui-col-md12">
        <div class="layui-card">
          <div class="layui-card-header">设定各种参数</div>
          <div class="layui-card-body">
            
            <div class="layui-form">
              <div class="layui-form-item">
                <div class="layui-inline">
                  <label class="layui-form-label">宽高</label>
                  <div class="layui-input-inline" style="width: 98px;">
                    <input type="tel" name="width" value="600" autocomplete="off" placeholder="width" class="layui-input test-carousel-demoSet">
                  </div>
                  <div class="layui-input-inline" style="width: 98px;">
                    <input type="tel" name="height" value="280" autocomplete="off" placeholder="height" class="layui-input test-carousel-demoSet">
                  </div>
                </div>
              </div>
              
              <div class="layui-form-item">
                <label class="layui-form-label">动画类型</label>
                <div class="layui-input-block">
                  <div class="layui-btn-group test-carousel-demoTest" style="margin-top: 5px;">
                    <button class="layui-btn layui-btn-sm" style="background-color: #5FB878;" data-type="set" data-key="anim" data-value="default">左右切换</button>
                    <button class="layui-btn layui-btn-sm" data-type="set" data-key="anim" data-value="updown">上下切换</button>
                    <button class="layui-btn layui-btn-sm" data-type="set" data-key="anim" data-value="fade">渐隐渐显</button>
                  </div> 
                </div>
              </div>
              
              <div class="layui-form-item">
                <label class="layui-form-label">箭头状态</label>
                <div class="layui-input-block">
                  <div class="layui-btn-group test-carousel-demoTest" style="margin-top: 5px;">
                    <button class="layui-btn layui-btn-sm" style="background-color: #5FB878;" data-type="set" data-key="arrow" data-value="hover">悬停显示</button>
                    <button class="layui-btn layui-btn-sm" data-type="set" data-key="arrow" data-value="always">始终显示</button>
                    <button class="layui-btn layui-btn-sm" data-type="set" data-key="arrow" data-value="none">不显示</button>
                  </div> 
                </div>
              </div>
              
              <div class="layui-form-item">
                <label class="layui-form-label">指示器位置</label>
                <div class="layui-input-block">
                  <div class="layui-btn-group test-carousel-demoTest" style="margin-top: 5px;">
                    <button class="layui-btn layui-btn-sm" style="background-color: #5FB878;" data-key="indicator" data-type="set" data-value="inside">容器内部</button>
                    <button class="layui-btn layui-btn-sm" data-type="set" data-key="indicator" data-value="outside">容器外部</button>
                    <button class="layui-btn layui-btn-sm" data-type="set" data-key="indicator" data-value="none">不显示</button>
                  </div> 
                </div>
              </div>
              
              <div class="layui-form-item">
                <div class="layui-inline">
                  <label class="layui-form-label">自动切换</label>
                  <div class="layui-input-block">
                    <input type="checkbox" name="switch" lay-skin="switch" checked="" lay-text="ON|OFF" lay-filter="test-carousel-autoplay">
                  </div>
                </div>
                <div class="layui-inline">
                  <label class="layui-form-label" style="width: auto;">时间间隔</label>
                  <div class="layui-input-inline" style="width: 120px;">
                    <input type="tel" name="interval" value="3000" autocomplete="off" placeholder="毫秒" class="layui-input test-carousel-demoSet">
                  </div>
                </div>
              </div>
            </div>
             
            <div class="layui-carousel" id="test-carousel-demo" lay-filter="test-carousel-demo">
              <div carousel-item="">
                <div>条目1</div>
                <div>条目2</div>
                <div>条目3</div>
                <div>条目4</div>
                <div>条目5</div>
              </div>
            </div> 
            
          </div>
        </div>
      </div>
      
      <div class="layui-col-md12">
        <div class="layui-card">
          <div class="layui-card-header">填充轮播元素 - 以图片为例</div>
          <div class="layui-card-body">
            <div class="layui-carousel" id="test-carousel-img">
              <div carousel-item="">
                <div><img src="//res.layui.com/images/layui/demo/1.png"></div>
                <div><img src="//res.layui.com/images/layui/demo/2.png"></div>
                <div><img src="//res.layui.com/images/layui/demo/3.png"></div>
                <div><img src="//res.layui.com/images/layui/demo/4.png"></div>
                <div><img src="//res.layui.com/images/layui/demo/5.png"></div>
                <div><img src="//res.layui.com/images/layui/demo/6.png"></div>
                <div><img src="//res.layui.com/images/layui/demo/7.png"></div>
              </div>
            </div>
          </div>
        </div>
      </div>      
    </div>
  </div>
  
  <script src="{{asset('layui-admin-v1.2.1/src/layuiadmin/layui/layui.js')}}"></script>
  <script>
  layui.config({
    base: '/layui-admin-v1.2.1/src/layuiadmin/' //静态资源所在路径
  }).extend({
    index: 'lib/index' //主入口模块
  }).use(['index', 'carousel', 'form'], function(){
    var carousel = layui.carousel
    ,form = layui.form;
  
    //常规轮播
    carousel.render({
      elem: '#test-carousel-normal'
      ,arrow: 'always'
    });
    
    //改变下时间间隔、动画类型、高度
    carousel.render({
      elem: '#test-carousel-normal-2'
      ,interval: 1800
      ,anim: 'fade'
      ,height: '120px'
    });
    
    //设定各种参数
    var ins3 = carousel.render({
      elem: '#test-carousel-demo'
    });
    //图片轮播
    carousel.render({
      elem: '#test-carousel-img'
      ,width: '778px'
      ,height: '440px'
      ,interval: 5000
    });
    
    //事件
    carousel.on('change(test-carousel-demo)', function(res){
      console.log(res)
    });
    
    var $ = layui.$, active = {
      set: function(othis){
        var THIS = 'layui-bg-normal'
        ,key = othis.data('key')
        ,options = {};
        
        othis.css('background-color', '#5FB878').siblings().removeAttr('style'); 
        options[key] = othis.data('value');
        ins3.reload(options);
      }
    };
    
    //监听开关
    form.on('switch(test-carousel-autoplay)', function(){
      ins3.reload({
        autoplay: this.checked
      });
    });
    
    $('.test-carousel-demoSet').on('keyup', function(){
      var value = this.value
      ,options = {};
      if(!/^\d+$/.test(value)) return;
      
      options[this.name] = value;
      ins3.reload(options);
    });
    
    //其它示例
    $('.test-carousel-demoTest .layui-btn').on('click', function(){
      var othis = $(this), type = othis.data('type');
      active[type] ? active[type].call(this, othis) : '';
    });
  
  });
  </script>
</body>
</html>