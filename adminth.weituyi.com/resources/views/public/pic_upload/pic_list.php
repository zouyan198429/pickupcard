<?php 
//说明:
//参数 
//upload_id 上传对象的id名称[唯一]  supplier_pic_list
//upload_type 各种上传操作编号 - 主要是上传配置信息不同
//show_div 1显示图片相关的文字 2显示[批量上传]按钮
//max_num 最多上传数量 6
//extend_txt 可上传格式 png/jpg
//pic_w_h 图片高宽 800px*800px
//pic_size 图片大小 2M

?>
<div class="upload_pic" id="<?php echo $upload_id; ?>"  upload_type="<?php echo $upload_type; ?>">
    <?php if( ($show_div & 1) == 1 ){ ?>
        <div style="float:left; color:#f00;margin:20px">
            <?php 
            $show_txt_arr = array();            
            if(!empty(trim($max_num))){ 
               $show_txt_arr[]="最多".$max_num."张图片"; 
            } 
            if(!empty(trim($extend_txt))){ 
                $show_txt_arr[]="格式：".$extend_txt;
            } 
            if(!empty(trim($pic_w_h))){ 
                $show_txt_arr[]= "大小：". $pic_w_h;
            } 
            if(!empty(trim($pic_size))){ 
               $show_txt_arr[]= $pic_size."以下";
            } 
            if(!empty($show_txt_arr)){
                echo implode('；', $show_txt_arr).'!';
            }
            ?>
        </div>
    <?php } ?>
    <?php if( ($show_div & 2) == 2 ){ ?>
    <div class="btn btn-info btn-sm upload" style='margin:10px'>
        批量上传
        <input type="file" class="upload_big_img"  multiple>
    </div>
    <?php } ?>
    <div style="position:relative; width:100%; float:left" class="images_vcl">

    </div>
</div>