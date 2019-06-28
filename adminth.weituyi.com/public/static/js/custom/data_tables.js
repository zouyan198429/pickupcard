
            //销毁Datatables实例,并重新填充内容和实例化动态表格对象
            //dynamic_obj 动态表格对象
            //aoColumns 列配置
            //dynamic_id 动太表格 的id名称 dynamic-table
            //body_data_id 动太表格 内容列表id
            //data_html 动太表格 内容列表代码
            //baidu_template_page 分页百度模板id baidu_template_data_page,'':则没有分页
            //return 返回 DataTable对象
            function datatables_destroy(dynamic_obj,aoColumns,dynamic_id,body_data_id,data_html,baidu_template_page){ 
                dynamic_obj.destroy();
                //alert(body_data_id);
                $('#'+body_data_id).html(data_html); 
                dynamic_obj = reset_dabatables(dynamic_obj,aoColumns,dynamic_id,baidu_template_page);
                return dynamic_obj;
            }
            //myTable = reset_dabatables(myTable,AO_COLUMNS,'dynamic-table','');
            //初始化动态表格
            //

            //初始化动态表格,调用：第一次新加，后面每次，则重载
            //dynamic_obj 动态表格对象
            //aoColumns 列配置
            //dynamic_id 动太表格 的id名称 dynamic-table
            //baidu_template_page 分页百度模板id baidu_template_data_page,'':则没有分页
            //return 返回 DataTable对象
            function reset_dabatables(dynamic_obj,aoColumns,dynamic_id,baidu_template_page){
                var is_first = true;//是否第一次实例化
                if(dynamic_obj !== null){ 
                    //alert('非第一次');
                    is_first = false;
                    dynamic_obj=null;                    
                    $('#'+dynamic_id ).unbind(); //移除所有
                }else{
                    //alert('第一次');
                }
                if(dynamic_obj === null){
                    dynamic_obj = 
                    $('#'+dynamic_id)
                    //.wrap("<div class='dataTables_borderWrap' />")   //if you are applying horizontal scrolling (sScrollX)
                    .DataTable( {
                        bAutoWidth: false,//是否自适应宽度
                        "aoColumns": aoColumns,
                        "aaSorting": [], //[[1, "asc"]], //默认的排序方式，第2列，升序排列 
                        //"bProcessing": true,//DataTables载入数据时，是否显示‘进度’提示
                        //"bServerSide": true,//是否启动服务器端数据导入
                        //"sAjaxSource": "http://127.0.0.1/table.php"	,
                        
                        //"bStateSave" : true, //是否打开客户端状态记录功能,此功能在ajax刷新纪录的时候不会将个性化设定回复为初始化状态 
                        //"bScrollInfinite" : false, //是否启动初始化滚动条
                        "bFilter" : false, //是否启动过滤、搜索功能
                        "sProcessing" : "正在获取数据，请稍后...", 
                        
                        
                        
                        //"sScrollY": "200px",
                        "bPaginate": false,//是否显示（应用）分页器
                        "bInfo" : false, //是否显示页脚信息，DataTables插件左下角显示记录数
                        //"sScrollX": "100%",
                        //"sScrollXInner": "120%",
                        //"bScrollCollapse": true,
                        //Note: if you are applying horizontal scrolling (sScrollX) on a ".table-bordered"
                        //you may want to wrap the table inside a "div.dataTables_borderWrap" element

                        //"iDisplayLength": 50


                        select: {
                            style: 'multi'
                        }
                    });
                }
                //alert('ok1');

                /*
                //各种按钮的css样式
                $.fn.dataTable.Buttons.defaults.dom.container.className = 'dt-buttons btn-overlap btn-group btn-overlap';
                //定义各种按钮
                new $.fn.dataTable.Buttons( dynamic_obj, {
                    buttons: [
                        {
                          "extend": "colvis",
                          "text": "<i class='fa fa-search bigger-110 blue'></i> <span class='hidden'>Show/hide columns</span>",
                          "className": "btn btn-white btn-primary btn-bold",
                          columns: ':not(:first):not(:last)'
                        },
                        {
                          "extend": "copy",
                          "text": "<i class='fa fa-copy bigger-110 pink'></i> <span class='hidden'>Copy to clipboard</span>",
                          "className": "btn btn-white btn-primary btn-bold"
                        },
                        {
                          "extend": "csv",
                          "text": "<i class='fa fa-database bigger-110 orange'></i> <span class='hidden'>Export to CSV</span>",
                          "className": "btn btn-white btn-primary btn-bold"
                        },
                        {
                          "extend": "excel",
                          "text": "<i class='fa fa-file-excel-o bigger-110 green'></i> <span class='hidden'>Export to Excel</span>",
                          "className": "btn btn-white btn-primary btn-bold"
                        },
                        {
                          "extend": "pdf",
                          "text": "<i class='fa fa-file-pdf-o bigger-110 red'></i> <span class='hidden'>Export to PDF</span>",
                          "className": "btn btn-white btn-primary btn-bold"
                        },
                        {
                          "extend": "print",
                          "text": "<i class='fa fa-print bigger-110 grey'></i> <span class='hidden'>Print</span>",
                          "className": "btn btn-white btn-primary btn-bold",
                          autoPrint: false,
                          message: 'This print was produced using the Print button for DataTables'
                        }		  
                    ]
                } );
                //各种导出按钮
                dynamic_obj.buttons().container().appendTo( $('.tableTools-container') );

                //style the message box
                var defaultCopyAction = dynamic_obj.button(1).action();
                dynamic_obj.button(1).action(function (e, dt, button, config) {
                        defaultCopyAction(e, dt, button, config);
                        $('.dt-button-info').addClass('gritter-item-wrapper gritter-info gritter-center white');
                });


                var defaultColvisAction = dynamic_obj.button(0).action();
                dynamic_obj.button(0).action(function (e, dt, button, config) {
                    defaultColvisAction(e, dt, button, config);
                    if($('.dt-button-collection > .dropdown-menu').length == 0) {
                        $('.dt-button-collection')
                        .wrapInner('<ul class="dropdown-menu dropdown-light dropdown-caret dropdown-caret" />')
                        .find('a').attr('href', '#').wrap("<li />")
                    }
                    $('.dt-button-collection').appendTo('.tableTools-container .dt-buttons')
                });

                ////

                setTimeout(function() {
                    $($('.tableTools-container')).find('a.dt-button').each(function() {
                        var div = $(this).find(' > div').first();
                        if(div.length == 1) div.tooltip({container: 'body', title: div.parent().text()});
                        else $(this).tooltip({container: 'body', title: $(this).text()});
                    });
                }, 500);
                */
                dynamic_obj.on( 'select', function ( e, dt, type, index ) {
                    if ( type === 'row' ) {
                        var check_obj = $( dynamic_obj.row( index ).node() ).find('input:checkbox');
                        //$( dynamic_obj.row( index ).node() ).find('input:checkbox').prop('checked', true);
                        if(!check_obj.prop('disabled')){
                            check_obj.prop('checked', true);
                        }
                    }
                } );
                dynamic_obj.on( 'deselect', function ( e, dt, type, index ) {
                    if ( type === 'row' ) {
                        var check_obj = $( dynamic_obj.row( index ).node() ).find('input:checkbox');
                        //$( dynamic_obj.row( index ).node() ).find('input:checkbox').prop('checked', false);
                        if(!check_obj.prop('disabled')){
                            check_obj.prop('checked', false);
                        }
                    }
                } );
                //if(!is_first){
                //    alert('ok-非第一次');
                //    return dynamic_obj;  
                //}
                /////////////////////////////////
                //table checkboxes初始化复选框
                $('th input[type=checkbox], td input[type=checkbox]').prop('checked', false);

                //select/deselect all rows according to table header checkbox全选/取消全选
                $('#'+dynamic_id+' > thead > tr > th input[type=checkbox], #'+dynamic_id+'_wrapper input[type=checkbox]').eq(0).unbind(); //移除所有
                $('#'+dynamic_id+' > thead > tr > th input[type=checkbox], #'+dynamic_id+'_wrapper input[type=checkbox]').eq(0).on('click', function(){
                    var th_checked = this.checked;//checkbox inside "TH" table header

                    //alert('全选');
                    $('#'+dynamic_id).find('tbody > tr').each(function(){
                        var row = this;
                        if(th_checked) dynamic_obj.row(row).select();
                        else  dynamic_obj.row(row).deselect();
                    });
                });

                //select/deselect a row when the checkbox is checked/unchecked 单个复选框操作
                $('#'+dynamic_id + ' td input[type=checkbox]').unbind(); //移除所有
                $('#'+dynamic_id).on('click', 'td input[type=checkbox]' , function(){
                    var row = $(this).closest('tr').get(0);
                    //alert('单选');
                    if(this.checked) dynamic_obj.row(row).deselect();
                    else dynamic_obj.row(row).select();
                });

                //下拉菜单
                $('#'+dynamic_id+' .dropdown-toggle').unbind(); //移除所有
                $(document).on('click', '#'+dynamic_id+' .dropdown-toggle', function(e) {
                    e.stopImmediatePropagation();
                    e.stopPropagation();
                    e.preventDefault();
                });
                //移除上下的row
                $('#'+dynamic_id).parent().find('.row').remove();
                if(baidu_template_page !=''){
                    var page_html = resolve_baidu_template(baidu_template_page,{},'');
                    $('#'+dynamic_id).after(page_html);
                }
                //alert('ok2');
                return dynamic_obj;
            }

            /********************************/
            //add tooltip for small view action buttons in dropdown menu 提示菜单
            $('[data-rel="tooltip"]').tooltip({placement: tooltip_placement});

            //tooltip placement on right or left工具提示
            function tooltip_placement(context, source) {
                var $source = $(source);
                var $parent = $source.closest('table')
                var off1 = $parent.offset();
                var w1 = $parent.width();

                var off2 = $source.offset();
                //var w2 = $source.width();

                if( parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2) ) return 'right';
                return 'left';
            }