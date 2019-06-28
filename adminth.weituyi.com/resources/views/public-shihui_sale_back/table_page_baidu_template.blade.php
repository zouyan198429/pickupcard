<!-- 前端模板开始 -->
    <!-- 加载中模板部分 开始-->
    <script type="text/template"  id="baidu_template_data_loding">
        <tr><td colspan="14" align="center">信息努力加载中.......</td></tr>
    </script>
    <!-- 加载中模板部分 结束-->
    <!-- 没有数据记录模板部分 开始-->
    <script type="text/template"  id="baidu_template_data_empty">
        <tr><td colspan="14" align="center">当前没有您要查询的记录！</td></tr>
    </script>
    <!-- 没有数据记录模板部分 结束-->
    <!-- 列表分页模板部分 开始-->
    <script type="text/template"  id="baidu_template_data_page">
        <div class="row">
            <?php if(1>2){?>
            <div class="col-xs-4">
                <div aria-live="polite" role="status" id="dynamic-table_info" class="dataTables_info">
                    Showing 1 to 10 of 23 entries
            
                </div>
            </div>
            <?php }?>
                <div class="col-xs-12">
                    <div id="dynamic-table_paginate" class="dataTables_paginate paging_simple_numbers">
                        <ul class="pagination">
                            <?php if(1>2){?>
                                <li id="dynamic-table_previous" tabindex="0" aria-controls="dynamic-table" class="paginate_button previous disabled">
                                    <a href="#">Previous</a>
                                </li>
                                <li tabindex="0" aria-controls="dynamic-table" class="paginate_button active">
                                    <a href="#">1</a>
                                </li>
                                <li tabindex="0" aria-controls="dynamic-table" class="paginate_button ">
                                    <a href="#">2</a>
                                </li>
                                <li tabindex="0" aria-controls="dynamic-table" class="paginate_button ">
                                    <a href="#">3</a>
                                </li>
                                <li id="dynamic-table_next" tabindex="0" aria-controls="dynamic-table" class="paginate_button next">
                                    <a href="#">Next</a>
                                </li>
                            <?php }?>
                        </ul>
                    </div>
                </div>
        </div> 
    </script>
    <!-- 列表分页模板部分 结束-->


    <!-- 确定+取消弹窗模板部分 开始
    $sure_cancel_data = {
        'content':'确定导出Excel？ ',//提示文字
        'sure_event':'excel_sure();',//确定
        'cancel_event':'excel_cancel();',//取消
    };
    -->
    <script type="text/template"  id="baidu_template_sure_cancel">
        <table>
          <tr>
            <td style="width:5px"  rowspan="3"></td>
            <td><img src="{{ asset('/static/images/question.jpg') }}" style="height:25px;width:25px;"></td>
            <td>&nbsp;&nbsp;</td>
            <td style="text-align:left;"><%=content%></td>
          </tr>
          <tr>
            <td></td>
            <td></td>
            <td><br/>
              <button class="btn btn-info butdata m2 sure_submit_btn" type="button" onclick="<%=sure_event%>">确 定</button>&nbsp;&nbsp;&nbsp;&nbsp;
              <button class="btn btn-default butdata m2 sure_cancel_btn" style="margin-left:20px;"  type="button" onclick="<%=cancel_event%>" >取 消</button>
            </td>
          </tr>
        </table>
    </script>
    <!-- 确定+取消弹窗模板部分 结束-->

    <!-- error错误弹窗模板部分 开始
    $sure_cancel_data = {
        'content':'***',//提示文字
    };
    -->
    <script type="text/template"  id="baidu_template_error">
        <table>
          <tr>
            <td style="width:5px"  rowspan="3"></td>
            <td><img src="{{ asset('/static/images/warning.gif') }}" style="height:25px;width:25px;"></td>
            <td>&nbsp;&nbsp;</td>
            <td style="text-align:left;"><%=content%></td>
          </tr>
        </table>
    </script>
    <!-- error错误弹窗模板部分 结束-->
    <!-- 倒记时关闭弹窗模板部分 开始
    $sure_cancel_data = {
        'content':'***',//提示文字
        'sec_num':10,//默认秒数
        'icon_name',//图片名称
    };
    -->
    <script type="text/template"  id="baidu_template_countdown">
        <table>
          <tr>
            <td style="width:5px"  rowspan="3"></td>
            <td  rowspan="3"><img src="/static/images/<%=icon_name%>" style="height:25px;width:25px;"></td>
            <td>&nbsp;&nbsp;</td>
            <td style="text-align:left;"><%=content%></td>
          </tr>
          <tr>
            <td>&nbsp;&nbsp;</td>
            <td style="text-align:left;">窗口将在<b><span  style="color: #F00;" class="show_second"><%=sec_num%></span></b>秒后窗口关闭</td>
          </tr>
        </table>
    </script>
    <!-- 倒记时关闭弹窗模板部分 结束-->
    <!-- 确认搜索条件值表单模板部分 开始-->
    <script type="text/template"  id="baidu_template_search_sure_form">
        <form  id="<%=search_sure_form%>" method="post" action="#">
        <%for(var i = 0; i<input_vlist.length;i++){
        var item = input_vlist[i];
        %>
        <input type="hidden" name="<%=item.name%>" value="<%=item.value%>"/>
        <%}%>
        </form>
    </script>
    <!-- 确认搜索条件值表单模板部分 结束-->

    <!-- [省市区/县]下拉框模板部分 开始-->
    <!-- //遍历json对象的每个key/value对,p为key{key:val,..}-->
    <script type="text/template"  id="baidu_template_option_list">
        <%for(var key in option_json){
			%>
			<option value="<%=key%>"><%=option_json[key]%></option>
			<%
		}%>
    </script>
    <!-- [省市区/县]下拉框模板部分 结束-->
    <!-- 前端模板结束 -->