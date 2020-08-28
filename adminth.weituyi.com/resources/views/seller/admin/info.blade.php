

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>设置我的资料</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <link rel="stylesheet" href="{{asset('layui-admin-v1.2.1/src/layuiadmin/layui/css/layui.css')}}" media="all">
  <link rel="stylesheet" href="{{asset('layui-admin-v1.2.1/src/layuiadmin/style/admin.css')}}" media="all">
</head>
<body>

  <div class="layui-fluid">
    <div class="layui-row layui-col-space15">
      <div class="layui-col-md12">
        <div class="layui-card">
          <div class="layui-card-header">设置我的资料</div>
          <div class="layui-card-body" pad15>

            <form method="post"  id="addForm" >
            <div class="layui-form" lay-filter="">
              <div class="layui-form-item" style="display: none;">
                <label class="layui-form-label">我的角色</label>
                <div class="layui-input-inline">
                  <select name="admin_type" lay-verify="">
                    @foreach ($adminType as $k=>$txt)
                    <option value="{{ $k }}" @if ($k === $defaultAdminType) selected @endif disabled>{{ $txt }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="layui-form-mid layui-word-aux">当前角色不可更改为其它角色</div>
              </div>
              <div class="layui-form-item">
                <label class="layui-form-label">用户名</label>
                <div class="layui-input-inline">
                  <input type="text" name="admin_username" value="{{ $admin_username or '' }}"  class="layui-input">
                </div>
                <div class="layui-form-mid layui-word-aux">用于后台登入名</div>
              </div>
              <div class="layui-form-item">
                <label class="layui-form-label">手机</label>
                <div class="layui-input-inline">
                  <input type="text" name="mobile" value="{{ $mobile or '' }}" lay-verify="phone" autocomplete="off" class="layui-input">
                </div>
                <div class="layui-form-mid layui-word-aux">可用于后台登入</div>
              </div>
              <div class="layui-form-item">
                <label class="layui-form-label">真实姓名</label>
                <div class="layui-input-inline">
                  <input type="text" name="real_name" value="{{ $real_name or '' }}"  class="layui-input">
                </div>
                <div class="layui-form-mid layui-word-aux"></div>
              </div>
              {{--
              <div class="layui-form-item">
                <label class="layui-form-label">昵称</label>
                <div class="layui-input-inline">
                  <input type="text" name="nickname" value="贤心" lay-verify="nickname" autocomplete="off" placeholder="请输入昵称" class="layui-input">
                </div>
              </div>
              --}}
              <div class="layui-form-item">
                <label class="layui-form-label">性别</label>
                <div class="layui-input-block">
                  <input type="radio" name="sex" value="1"  @if (isset($sex) && $sex == 1 ) checked @endif title="男">
                  <input type="radio" name="sex" value="2"  @if (isset($sex) && $sex == 2 ) checked @endif title="女">
                </div>
              </div>
              {{--
              <div class="layui-form-item">
                <label class="layui-form-label">头像</label>
                <div class="layui-input-inline">
                  <input name="avatar" lay-verify="required" id="LAY_avatarSrc" placeholder="图片地址" value="http://cdn.layui.com/avatar/168.jpg" class="layui-input">
                </div>
                <div class="layui-input-inline layui-btn-container" style="width: auto;">
                  <button type="button" class="layui-btn layui-btn-primary" id="LAY_avatarUpload">
                    <i class="layui-icon">&#xe67c;</i>上传图片
                  </button>
                  <button class="layui-btn layui-btn-primary" layadmin-event="avartatPreview">查看图片</button >
                </div>
             </div>
             --}}
              <div class="layui-form-item">
                <label class="layui-form-label">电话</label>
                <div class="layui-input-inline">
                  <input type="text" name="tel" value="{{ $tel or '' }}"  autocomplete="off" class="layui-input">
                </div>
              </div>
              <div class="layui-form-item">
                <label class="layui-form-label">QQ/邮箱/微信</label>
                <div class="layui-input-inline">
                  <input type="text" name="qq_number" value="{{ $qq_number or '' }}" lay-verify="email" autocomplete="off" class="layui-input">
                </div>
              </div>
              {{---
              <div class="layui-form-item layui-form-text">
                <label class="layui-form-label">备注</label>
                <div class="layui-input-block">
                  <textarea name="remarks" placeholder="请输入内容" class="layui-textarea"></textarea>
                </div>
              </div>
              --}}
              <div class="layui-form-item">
                <div class="layui-input-block">
                  <button class="layui-btn"  id="submitBtn">确认修改</button>
                  <button type="reset" class="layui-btn layui-btn-primary">重新填写</button>
                </div>
              </div>
            </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="{{asset('js/jquery-3.3.1.min.js')}}"></script>
  <script src="{{asset('layui-admin-v1.2.1/src/layuiadmin/layui/layui.all.js')}}"></script>
  {{--<script src="{{asset('layui-admin-v1.2.1/src/layuiadmin/layui/layui.js')}}"></script>--}}
  @include('public.dynamic_list_foot')
  {{--
  <script>
  layui.config({
    base: '/layui-admin-v1.2.1/src/layuiadmin/' //静态资源所在路径
  }).extend({
    index: 'lib/index' //主入口模块
  }).use(['index', 'set']);
  </script>
  --}}
  <script>
      const SAVE_URL = "{{ url('api/seller/ajax_info_save') }}";
      const SET_URL = "{{url('seller/info')}}";
  </script>
  <script src="{{ asset('/js/common/user_info.js') }}"  type="text/javascript"></script>
</body>
</html>
