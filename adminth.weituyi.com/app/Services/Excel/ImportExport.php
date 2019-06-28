<?php
namespace App\Services\Excel;

use App\Services\Excel\ChunkReadFilter;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**  Define a Read Filter class implementing \PhpOffice\PhpSpreadsheet\Reader\IReadFilter  */
class ImportExport
{
    public static $MEMORY_LIMIT = '1024M';// 增加PHP可用的内存
    /**
     * 测试
     * @return  null
     * @author zouyan(305463219@qq.com)
     */
    public static function test(){
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1','Welcome to Helloweba.');

        $writer = new Xlsx($spreadsheet);
        $writer->save('hello.xlsx');
    }

    /**
     * 导入 获得导入文件数据
     *
     * @param string $fileName 需要导入的文件
     * @param int $dataStartRow 数据开始的行号[有抬头列，从抬头列开始],从1开始
    // 需要的列的值的下标关系：一、通过列序号[1开始]指定；二、通过专门的列名指定;三、所有列都返回[文件中的行列形式],$headRowNum=0 $headArr=[]
     * @param int $headRowNum //0:代表第一种方式，其它数字：第二种方式; 1开始 -必须要设置此值，$headArr 参数才起作用
    // 下标对应关系,如果设置了，则只获取设置的列的值
    // 方式一格式：['1' => 'name'，'2' => 'chinese',]
    // 方式二格式: ['姓名' => 'name'，'语文' => 'chinese',]
     * @param array $headArr 是否需要登陆 0需要1不需要
     * @return  array 列表数据
     * @author zouyan(305463219@qq.com)
     */
    public static function import($fileName, $dataStartRow = 1, $headRowNum = 0, $headArr = []){
//        $fileName = 'students.xlsx';
//        $dataStartRow = 3;// 数据开始的行号[有抬头列，从抬头列开始],从1开始
        // 需要的列的值的下标关系：一、通过列序号[1开始]指定；二、通过专门的列名指定;三、所有列都返回[文件中的行列形式],$headRowNum=0 $headArr=[]
//        $headRowNum = 0;//0:代表第一种方式，其它数字：第二种方式; 1开始 -必须要设置此值，$headArr 参数才起作用
        // 下标对应关系,如果设置了，则只获取设置的列的值
        // 方式一格式：['1' => 'name'，'2' => 'chinese',]
        // 方式二格式: ['姓名' => 'name'，'语文' => 'chinese',]
//        $headArr = [
//            '姓名' => 'name',
//            '语文' => 'chinese',
//            '数学' => 'maths',
//            '外语' => 'english',
//        ];
//        $headArr = [
//            '1' => 'name',
//            '2' => 'chinese',
//            '3' => 'maths',
//            '4' => 'english',
//        ];
        // 最终都要转换为 [ '列编号' => '数组下标关键字']
        ini_set('memory_limit', self::$MEMORY_LIMIT);// 增加PHP可用的内存
        set_time_limit(0);   // 设置脚本最大执行时间 为0 永不过期
        $headColKey = [];
        if($headRowNum == 0) $headColKey = $headArr;
        // 行、列 都是从1开始
        // 请注意，自动类型解析模式比显式模式稍慢。
        // 自动类型解析模式
        // $reader = IOFactory::createReaderForFile($fileName);
        // 显式模式 XLS、XML、XLSX、ODS、SLK、Gnumeric、CSV、HTML
        $inputFileType = IOFactory::identify($fileName);
        /**  Create a new Reader of the type defined in $inputFileType  **/
        $reader =IOFactory::createReader($inputFileType);// 'Xlsx'
        /**  Define how many rows we want to read for each "chunk"  **/
        $chunkSize = 2048 * 2;//2048;
        /**  Create a new Instance of our Read Filter  **/
        $chunkFilter = new ChunkReadFilter();
        /**  Tell the Reader that we want to use the Read Filter  **/
        $reader->setReadFilter($chunkFilter);
        $reader->setReadDataOnly(true);
        $maxRow = 65536;
        if(strtolower($inputFileType) == 'xlsx') $maxRow = 1048576;
        $dataArr = [];
        $numSheets = 1;
        for($sheetUbound = 0;$sheetUbound < $numSheets; $sheetUbound++){
            /**  Loop to read our worksheet in "chunk size" blocks  **/
            for ($startRow = $dataStartRow; $startRow <= $maxRow; $startRow += $chunkSize) {
                /**  Tell the Read Filter which rows we want this iteration  **/
                $chunkFilter->setRows($startRow,$chunkSize);
                /**  Load only the rows that match our filter  **/
                $spreadsheet = $reader->load($fileName);
                if($numSheets <= 1 ) $numSheets = $spreadsheet->getSheetCount();// 第一次，重新获得表格数量
                if($numSheets < 1) break;// 一个表格都没有,则跳出获取数据
                //    Do some processing here
                // $worksheet = $spreadsheet->getActiveSheet(); // 默认第一张表格
                $worksheet =$spreadsheet->setActiveSheetIndex($sheetUbound); //修改第一张表格(表格序号从0开始)

                $highestRow = $worksheet->getHighestRow();// 总行数 6
                if($highestRow < $startRow) break;
                $highestColumn =$worksheet->getHighestColumn();// 总列数 D
                $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn);// e.g.5  4

                $rowEnd = $startRow + ($chunkSize - 1);
                if($rowEnd > $highestRow)  $rowEnd = $highestRow;

                // 抬头处理
                // 抬头方式一
                if($headRowNum > 0 && $headRowNum >= $startRow && $headRowNum <= $rowEnd   && count($headColKey)<=0 && count($headArr) > 0){
                    for($col = 1; $col<= $highestColumnIndex; $col++){  // 获得每一列
                        $temVal = $worksheet->getCellByColumnAndRow($col, $headRowNum)->getValue();
                        if(!empty($temVal))  $temVal = trim($temVal);
                        if(isset($headArr[$temVal])){
                            $headColKey[$col] = $headArr[$temVal];
                            unset($headArr[$temVal]);
                        }
                    }
                    if(count($headArr) > 0) {
                        // 从内存中清除工作簿
                        $spreadsheet->disconnectWorksheets();
                        unset($spreadsheet);
                        throws('没有数据列[' . implode('、', $headArr) .']');
                    }
                }

                for($row = $startRow; $row <= $rowEnd; ++$row){ // 获得行
                    if($headRowNum > 0 && $headRowNum == $row) continue;  // 绕过抬头列

                    $temArr = [];
                    if(count($headColKey) > 0){// 有指定下标
                        foreach($headColKey as $col => $key){
                            $temVal = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
                            if(!empty($temVal))  $temVal = trim($temVal);
                            $temArr[$key] = $temVal;
                        }

                    }else{
                        for($col = 1; $col<= $highestColumnIndex; $col++){  // 获得每一列
                            $temVal = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
                            if(!empty($temVal))  $temVal = trim($temVal);
                            array_push($temArr, $temVal);
                        }
                    }
                    array_push($dataArr, $temArr);
                }
                // 从内存中清除工作簿
                $spreadsheet->disconnectWorksheets();
                unset($spreadsheet);
                if( ($startRow + $chunkSize - 1) > $rowEnd ) break;
            }
        }
        return $dataArr;
        // $spreadsheet = $reader->load($fileName);// 载入excel表格


//        $worksheet = $spreadsheet->getActiveSheet();
//        $highestRow = $worksheet->getHighestRow();// 总行数 6
//        $highestColumn =$worksheet->getHighestColumn();// 总列数 D
//        $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn);// e.g.5  4
//
//        $lines = $highestRow - 2;
//        if ($lines <= 0) {
//            exit('Excel表格中没有数据。');
//        }
//
//        $dataArr = [];
//        for($row = 3; $row <= $highestRow; ++$row){
//            $name = $worksheet->getCellByColumnAndRow(1, $row)->getValue();// 姓名
//            $chinese  = $worksheet->getCellByColumnAndRow(2, $row)->getValue();// 语文
//            $maths = $worksheet->getCellByColumnAndRow(3, $row)->getValue();// 数学
//            $english = $worksheet->getCellByColumnAndRow(4, $row)->getValue();// 外语
//            $dataArr[] = [
//                'name' => $name,
//                'chinese' => $chinese,
//                'maths' => $maths,
//                'english' => $english,
//            ];
//        }
//        // 从内存中清除工作簿
//        $spreadsheet->disconnectWorksheets();
//        unset($spreadsheet);
//        pr($dataArr);
    }


    /**
     * 导出
     * @param string $file_dir 文件路径 保存成文件时指定文件路么 上传全路径 E:/www/shoping/web/data/upload/
     * @param string $create_filename 生成的文件名，可为空，如果文件名为空，则文件名默认按日期的形式生成[注意名称不能有扩展名]
     * @param array $dataArr 需要导出的数据,如果数据为空数组，则只写表头
     * @param int $type 类型1 数据为二维数组，自动分sheet ；2 数据为三维数组,每一维就是一个sheet
     * @param array $headArr 需要导出的表头 不需要则为空数组 ["name"=>'姓名', "chinese"=>'语文', "maths"=>'数学', "english"=>'外语'] $type=1:一维数组 ;type=2，二维数组，下标从0开始...
     * @param int $save_type 保存类型0网页数据流生成，1生成文件 ;2保存为xls
     * @param array $other_arr  其它参数
     *  $other_arr= array(
            'sheet_title' =>'',//标签的名称 $type=1: 字符,type=2，数组，指定每一个sheet
        );
     * @author zouyan(305463219@qq.com)
     * @return  null
     */
    public static function export($file_dir = '', $create_filename = '', $dataArr = [], $type = 1 , $headArr = [], $save_type = 0, $other_arr = []){
        ini_set('memory_limit', self::$MEMORY_LIMIT);// 增加PHP可用的内存
        set_time_limit(0);   // 设置脚本最大执行时间 为0 永不过期
        //如果文件名为空，则文件名默认按日期的形式生成
        $expand = 'xlsx';
        if($save_type == 2) $expand = 'xls';
        if(empty($create_filename))
        {
            //获得当前的日期
            $date = date("Y_m_d_H_i_s",time());
            $fileName = "{$date}.{$expand}";
        }else{
            $fileName = $create_filename . '.' . $expand;
        }
        $hasData = true;
        if(empty($dataArr) && (!empty($headArr))){// 如果数据为空数组，则只写表头
            $hasData = false;
            if($type == 1 ) {// 一维数组
                $dataArr = [$headArr];
            }else{// 二维数组  [['work_num'=>'工号', 'department_name'=>'部门'],['work_num'=>'工号1', 'department_name'=>'部门2']]
                // [[['work_num'=>'工号', 'department_name'=>'部门']],[['work_num'=>'工号1', 'department_name'=>'部门2']]];
                foreach($headArr as $temHead){
                    if(!is_array($temHead)){
                        array_push($dataArr,[$headArr]);
                        break;
                    }else{
                        array_push($dataArr,[$temHead]);
                    }
                }
            }
        }

        $spreadsheet = new Spreadsheet();
        $sheet_title = "";
        if($type == 1 ){
            $maxRow = 65536;
            if(strtolower($expand) == 'xlsx') $maxRow = 1048576;
            $bigDataArr = array_chunk($dataArr,$maxRow,true);
            $sheet_title = (isset($other_arr['sheet_title']) && !empty($other_arr['sheet_title'])) ? $other_arr['sheet_title'] : 'Worksheet';
        }else{
            $bigDataArr = $dataArr;
        }
        $sheetCount = count($bigDataArr);
        $num = 1;
        foreach($bigDataArr as $bigKey => $temDataArr){
            if($num == 1){// 第一次，使用默认创建的表格
                $worksheet = $spreadsheet->getActiveSheet();
            }else{// 第二次及后台循环，使用自己创建的表格
                //创建表格
                $spreadsheet->createSheet($num - 1);
                $worksheet =$spreadsheet->setActiveSheetIndex($num - 1); //修改第一张表格(表格序号从0开始)
            }
            // 设置工作表标题名称
            $tem_title = $sheet_title . $sheetCount . '-' . $num;
            if($type == 2 && isset($other_arr['sheet_title']) && is_array($other_arr['sheet_title']) &&  isset($other_arr['sheet_title'][$num -1])){
                $tem_title = $other_arr['sheet_title'][$num -1];
            }

            $worksheet->setTitle($tem_title);

            $row_num = 1;
            // 表头
            //设置单元格内容
            if($type == 2){
                $temHeadArr = $headArr[$num - 1] ?? [];
            }else{
                $temHeadArr = $headArr;

            }
            $temHeadKeys = array_keys($temHeadArr);// 需要的下标,可能为空，则直接列出数据，否则：列出指定的数据
            if(count($temHeadArr) > 0){
                $col_i = 1;
                $key = ord("A");
                foreach($temHeadArr as $headVal){
                    $colum = chr($key);
                    $worksheet->setCellValueByColumnAndRow($col_i, $row_num, $headVal);
                    $col_i++;
                    $key += 1;
                }
                $styleArray = [
                    'font' => [
                        'bold' => true
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,//\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ];
                //设置单元格样式
                $worksheet->getStyle('A' .$row_num  . ':' . $colum . $row_num)->applyFromArray($styleArray)->getFont()->setSize(14);

                $row_num++;
            }
            if(!$hasData) continue;// 只要表头
            // 数据内容
            if(count($temDataArr) > 0){

                // $row_num = 1;
                $dataRowNum = $row_num;
                // 第一列
                foreach($temDataArr as $row_k => $row_v){
                    // 每一行
                    if(!empty($temHeadKeys)){// 有指定列
                        $tem_row_v = [];
                        foreach($temHeadKeys as $temKey){
                            $tem_row_v[$temKey] = $row_v[$temKey] ?? '';
                        }
                        $row_v = $tem_row_v;
                    }
                    $key = ord("A");
                    foreach($row_v as $col_k => $col_v){
                        $colum = chr($key);
                        //$worksheet->setCellValueByColumnAndRow(4, $j, $dataArr[$i]['english']);
                        if(is_numeric($col_v) && (strlen($col_v) >= 10 || substr( $col_v, 0, 1 ) == '0' ) ){// 数字长度>=10 或 数字第一位为0
                            $worksheet->setCellValueExplicit(
                                $colum . $row_num,
                                $col_v,
                                DataType::TYPE_STRING//\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
                            );
                        }else{
                            $worksheet->setCellValue($colum . $row_num, $col_v);
                        }
                        $key += 1;
                    }
                    $row_num += 1;
                }

                $styleArrayBody = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,//\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '666666'],
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,// \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ];
                //添加所有边框/居中
                $worksheet->getStyle('A1' . ':'. $colum . ($row_num - 1 ))->applyFromArray($styleArrayBody);

            }
            $num++;
        }


        //  保存类型，1生成文件
        switch ($save_type){
            case 0:// 0网页数据流生成 xlsx
                // 保存为xlsx
                // $filename = '成绩表.xlsx';
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="'.$fileName.'"');
                header('Cache-Control: max-age=0');

                $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');// \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
                $writer->save('php://output');
                break;
            case 1:
                // 保存到服务器
                $file_dir=  rtrim($file_dir,'/');
                $saveFile = $fileName;
                if(!empty($file_dir))  $saveFile = $file_dir . '/' . $fileName;
                $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
                $writer->save($saveFile);
                break;
            case 2: // 保存为xls
                // $fileName = '成绩表.xlsx';
                header('Content-Type:application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="'.$fileName.'"');
                header('Cache-Control: max-age=0');

                $writer = IOFactory::createWriter($spreadsheet, 'Xls');//\PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'xls');
                $writer->save('php://output');
                break;
        }
        // 从内存中清除工作簿
        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);
    }

//    /**
//     * 导出
//     *
//     * @author zouyan(305463219@qq.com)
//     */
//    public static function exportTest(){
//        $dataJson = '[{"name":"\u738b\u4e8c\u5c0f","chinese":"82","maths":"78","english":"65"},{"name":"\u674e\u4e07\u8c6a","chinese":"68","maths":"87","english":"79"},{"name":"\u5f20\u4e09\u4e30","chinese":"89","maths":"90","english":"98"},{"name":"\u738b\u8001\u4e94","chinese":"68","maths":"81","english":"72"}]';
//        $dataArr = json_decode($dataJson, true);
//
//        ini_set('memory_limit', self::$MEMORY_LIMIT);// 增加PHP可用的内存
//        $spreadsheet = new Spreadsheet();
//        $worksheet = $spreadsheet->getActiveSheet();
//        // 设置工作表标题名称
//        $worksheet->setTitle('学生成绩表');
//
//        // 表头
//        //设置单元格内容
//        $worksheet->setCellValueByColumnAndRow(1, 1, '学生成绩表');
//        $worksheet->setCellValueByColumnAndRow(1, 2, '姓名');
//        $worksheet->setCellValueByColumnAndRow(2, 2, '语文');
//        $worksheet->setCellValueByColumnAndRow(3, 2, '数学');
//        $worksheet->setCellValueByColumnAndRow(4, 2, '外语');
//        $worksheet->setCellValueByColumnAndRow(5, 2, '总分');
//
//        //合并单元格
//        $worksheet->mergeCells('A1:E1');
//
//        $styleArray = [
//            'font' => [
//                'bold' => true
//            ],
//            'alignment' => [
//                'horizontal' => Alignment::HORIZONTAL_CENTER,//\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
//            ],
//        ];
//        //设置单元格样式
//        $worksheet->getStyle('A1')->applyFromArray($styleArray)->getFont()->setSize(28);
//
//        $worksheet->getStyle('A2:E2')->applyFromArray($styleArray)->getFont()->setSize(14);
//
//        $len = count($dataArr);
//        $j = 0;
//        for ($i= 0; $i < $len; $i++) {
//            $j = $i + 3; //从表格第3行开始
//            $worksheet->setCellValueByColumnAndRow(1, $j, $dataArr[$i]['name']);
//            $worksheet->setCellValueByColumnAndRow(2, $j, $dataArr[$i]['chinese']);
//            $worksheet->setCellValueByColumnAndRow(3, $j, $dataArr[$i]['maths']);
//            $worksheet->setCellValueByColumnAndRow(4, $j, $dataArr[$i]['english']);
//            $worksheet->setCellValueByColumnAndRow(5, $j, $dataArr[$i]['chinese'] + $dataArr[$i]['maths'] + $dataArr[$i]['english']);
//        }
//
//        $styleArrayBody = [
//            'borders' => [
//                'allBorders' => [
//                    'borderStyle' => Border::BORDER_THIN,//\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
//                    'color' => ['argb' => '666666'],
//                ],
//            ],
//            'alignment' => [
//                'horizontal' => Alignment::HORIZONTAL_CENTER,// \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
//            ],
//        ];
//        $total_rows = $len + 2;
//        //添加所有边框/居中
//        $worksheet->getStyle('A1:E'.$total_rows)->applyFromArray($styleArrayBody);
//
//        // 保存为xlsx
////        $filename = '成绩表.xlsx';
////        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
////        header('Content-Disposition: attachment;filename="'.$filename.'"');
////        header('Cache-Control: max-age=0');
////
////        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');// \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
////
////        $writer->save('php://output');
//
//        // 保存到服务器
//        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
//        $writer->save('成绩表aaa.xlsx');
//
//        // 保存为xls
////        $filename = '成绩表.xlsx';
////        header('Content-Type: application/vnd.ms-excel');
////        header('Content-Disposition: attachment;filename="'.$filename.'"');
////        header('Cache-Control: max-age=0');
////
////        $writer = IOFactory::createWriter($spreadsheet, 'xls');//\PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'xls');
////        $writer->save('php://output');
    // 从内存中清除工作簿
//$spreadsheet->disconnectWorksheets();
//unset($spreadsheet);
//    }
}