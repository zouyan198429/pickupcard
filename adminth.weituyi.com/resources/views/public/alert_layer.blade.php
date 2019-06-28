
<!-- 按钮触发模态框 -->
<!--
<button class="btn btn-primary btn-lg" data-toggle="modal" id="alertid">
   开始演示模态框
</button>
-->
<div id="modal_show_id_before" style="display:none;"></div>
<!-- 模态框（Modal） -->
<?php if(1>2){ ?>
	<div class="modal fade" id="alert_Modal" tabindex="-1" role="dialog" 
	   aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog" >
		  <div class="modal-content">
			 <div class="modal-header">
				<button type="button" class="close" 
				   data-dismiss="modal" aria-hidden="true">
					  &times;
				</button>
				<h4 class="modal-title" id="myModalLabel">
				   模态框（Modal）标题
				</h4>
			 </div>
			 <div class="modal-body">
				<!--在这里添加一些文本-->
			 </div>
			 <div class="modal-footer">
				<button type="button" class="btn btn-default" 
				   data-dismiss="modal">关闭
				</button>
				<button type="button" class="btn btn-primary">
				   提交更改
				</button>
			 </div>
		  </div><!-- /.modal-content -->
		</div><!-- /.modal -->
	</div>
<?php } ?>
<!-- 前端模板部分 -->
<script type="text/template"  id="baidu_template_alert_modal">
	<div class="modal fade" id="<%=alert_Modal_id%>" tabindex="-1" role="dialog" 
	   aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog" >
		  <div class="modal-content">
			 <div class="modal-header">
				<button type="button" class="close" 
				   data-dismiss="modal" aria-hidden="true">
					  &times;
				</button>
				<h4 class="modal-title" id="myModalLabel">
				   模态框（Modal）标题
				</h4>
			 </div>
			 <div class="modal-body">
				<!--在这里添加一些文本-->
			 </div>
			 <div class="modal-footer">
				<button type="button" class="btn btn-default btn-xs" 
				   data-dismiss="modal">关闭
				</button>
				<button type="button" class="btn btn-primary btn-xs">
				   提交更改
				</button>
			 </div>
		  </div><!-- /.modal-content -->
		</div><!-- /.modal -->
	</div>

</script>
<!-- 前端模板结束 -->
<script src="{{ asset('/static/js/custom/alert_modal.js') }}"></script>