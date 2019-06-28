<?php 
//说明:
// 注意class 的结构:最上层 area_select;可以通过它去获得下面相关的省[province_id]市[city_id]县/区[area_id] css
//分别传入三个参数  province_id:省id;city_id:市id;area_id:县/区id --不传代表没有相关的下拉框
//附加参数 disabled

?>
<div class="row area_select">
	<?php if(!empty($province_id)){?>
        <div class="col-sm-4">
            <select class="chosen-select form-control province_id" id="<?php echo $province_id;?>" name="<?php echo $province_id;?>" data-placeholder="请选择省" <?php echo $disabled; ?>>
                <option value="" selected="selected">请选择省</option>
            </select>
        </div>
    <?php }?>
    <?php if(!empty($city_id)){?>
        <div class="col-sm-4">
            <select class="chosen-select form-control city_id" id="<?php echo $city_id;?>" name="<?php echo $city_id;?>" data-placeholder="请选择市" <?php echo $disabled; ?>>
                <option value="" selected="selected">请选择市</option>
            </select>
        </div>
    <?php }?>
    <?php if(!empty($area_id)){?>
        <div class="col-sm-4">
            <select class="chosen-select form-control area_id" id="<?php echo $area_id;?>" name="<?php echo $area_id;?>" data-placeholder="请选择县/区"  <?php echo $disabled; ?>>
                <option value="" selected="selected">请选择县/区</option>
            </select>
        </div>
    <?php }?>
</div>