<?php 
//说明:
// 注意class 的结构:最上层 category_select;可以通过它去获得下面相关的第一级[cate_first_id]第二级[cate_two_id]第三级[cate_three_id] css
//分别传入三个参数  cate_first_id:第一级id;cate_two_id:第二级id;cate_three_id:第三级id --不传代表没有相关的下拉框
//附加参数 disabled

?>
<div class="row category_select">
	<?php if(!empty($cate_first_id)){?>
        <div class="col-sm-4">
            <select class="chosen-select form-control cate_first_id" id="<?php echo $cate_first_id;?>" name="<?php echo $cate_first_id;?>" data-placeholder="请选择" <?php echo $disabled; ?>>
                <option value="" selected="selected">请选择</option>
            </select>
        </div>
    <?php }?>
    <?php if(!empty($cate_two_id)){?>
        <div class="col-sm-4">
            <select class="chosen-select form-control cate_two_id" id="<?php echo $cate_two_id;?>" name="<?php echo $cate_two_id;?>" data-placeholder="请选择" <?php echo $disabled; ?>>
                <option value="" selected="selected">请选择</option>
            </select>
        </div>
    <?php }?>
    <?php if(!empty($cate_three_id)){?>
        <div class="col-sm-4">
            <select class="chosen-select form-control cate_three_id" id="<?php echo $cate_three_id;?>" name="<?php echo $cate_three_id;?>" data-placeholder="请选择"  <?php echo $disabled; ?>>
                <option value="" selected="selected">请选择</option>
            </select>
        </div>
    <?php }?>
</div>