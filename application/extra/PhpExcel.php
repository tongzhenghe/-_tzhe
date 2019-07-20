<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/7
 * Time: 10:38
 */

namespace app\extra;

class  PhpExcel{

   public static  function expExcel($data = [],$name = 'excel')
    {
        $excel = new PHPExcel; //引用phpexcel
       iconv('UTF-8', 'gb2312', $name); //针对中文名转码
    $header = ['ID', '名称']; //表头,名称可自定义
    $excel->setActiveSheetIndex(0);
    $excel->getActiveSheet()->setTitle($name); //设置表名
    $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(18);
    $excel->getActiveSheet()->getColumnDimension('B')->setWidth(80);

    $letter = ['A', 'B'];//列坐标

    //生成表头
    for ($i = 0; $i < count($header); $i++) {
        //设置表头值
        $excel->getActiveSheet()->setCellValue("$letter[$i]1", $header[$i]);
        //设置表头字体样式
        $excel->getActiveSheet()->getStyle("$letter[$i]1")->getFont()->setName('宋体');
        //设置表头字体大小
        $excel->getActiveSheet()->getStyle("$letter[$i]1")->getFont()->setSize(14);
        //设置表头字体是否加粗
        $excel->getActiveSheet()->getStyle("$letter[$i]1")->getFont()->setBold(true);
        //设置表头文字水平居中
        $excel->getActiveSheet()->getStyle("$letter[$i]1")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //设置文字上下居中
        $excel->getActiveSheet()->getStyle($letter[$i])->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        //设置单元格背景色
        $excel->getActiveSheet()->getStyle("$letter[$i]1")->getFill()->getStartColor()->setARGB('FFFFFFFF');
        $excel->getActiveSheet()->getStyle("$letter[$i]1")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $excel->getActiveSheet()->getStyle("$letter[$i]1")->getFill()->getStartColor()->setARGB('FF6DBA43');
        //设置字体颜色
        $excel->getActiveSheet()->getStyle("$letter[$i]1")->getFont()->getColor()->setARGB('FFFFFFFF');
    }

    //写入数据
    foreach ($data as $k => $v) {
        //从第二行开始写入数据（第一行为表头）
        $excel->getActiveSheet()->setCellValue('A' . ($k + 2), $v['gid']);
        $excel->getActiveSheet()->setCellValue('B' . ($k + 2), $v['gname']);
        $excel->getActiveSheet()->setCellValue('C' . ($k + 2), $v['price']);
        $excel->getActiveSheet()->setCellValue('D' . ($k + 2), $v['gnum']);
        $excel->getActiveSheet()->setCellValue('E' . ($k + 2), $v['pnum']);
    }

    //设置单元格边框
    $excel->getActiveSheet()->getStyle("A1:E" . (count($data) + 1))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

//清理缓冲区，避免中文乱码
    ob_end_clean();
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="' . $name . '.xls"');
    header('Cache-Control: max-age=0');

//导出数据
    $res_excel = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
    $res_excel->save('php://output');

}

}

