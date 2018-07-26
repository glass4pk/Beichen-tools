<?php
namespace app\admin\controller;

use PHPExcel;
use PHPExcel_IOFactory;
use think\Exception;

class Excel
{
    private $excel;
    private $isOk;
    private $error;

    public function __construct()
    {
        $this->isOk = false;
    }

    /**
     * 打开Excel表
     *
     * @param string $inputFileType 待打开的文件路径
     * @return void
     */
    public function open($inputFileType = '')
    {
        ini_set('memory_limit','1024M');
        if (!\file_exists($inputFileType)) {
            return false;
        }
        try {
            $this->excel = PHPExcel_IOFactory::load($inputFileType);
            $this->isOk = true;
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            $this->isOk = $e->getMessage();
        } finally {
            return $this->isOk;
        }
    }

    /**
     * 获取excel的数据
     *
     * @param integer $s 字段头header
     * @return void
     */
    public function getData($s = 0)
    {
        if ($this->isOk) {
            $sheet = $this->excel->getSheet($s); // 读取第一个表
            $allRows = $sheet->getHighestRow(); // 取得总行数
            $allColumms = $sheet->getHighestColumn(); // 取得中列数
            /**
             * 循环读取每个单元个的数据
             */
            return $sheet;
            // $dataset = array();
            // $dataset['rows'] = $allRows;
            // $dataset['columns'] = (int)($allColumms - 'A') +1;
            // $dataset['haveTitle'] = true;
            // $dataset['content'] = [];
            // for ($row = 1;$row <= $allRows;$row++) {
            //     for ($columm = 'A';$columm <= $allColumms;$columm++) {
            //         array_push($dataset['content'],$columm.$row,$sheet->getCell($columm.$row)->getValue());
            //     }
            // }
            // return $dataset;
        }
        return false;
    }

    /**
     * 将数据写入到Excel表中
     *
     * @param array $data
     * @param string $filePath 写入文件的路径
     * @return void
     */
    public function saveToExcel($title=array(),$data=array(),$fileName='',$savePath='',$isDown=false)
    {
        //横向单元格标识
	    $cellName = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ');
        // 纵向单元格标识
        $row = 1;
        // 创建Excel文档对象
        $objPHPExcel = new PHPExcel();
        // set excel information
        $objPHPExcel->getProperties()->setCreator('北辰青年技术部')
        ->setTitle('cp配对结果')
        ->setSubject('cp配对结果预览');

        // 重命名工作sheet
        $objPHPExcel->getActiveSheet()->setTitle('匹配结果');
        // 设置第一个sheet为工作的sheet;
        $objPHPExcel->setActiveSheetIndex(0);
        // 设置标题
        if ($title) {
            $cnt = count($title);
            $i = 0;
            foreach ($title as $v) {
                $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$i].$row,$v);
                $i++;
            }
            $row++;
        }
        if ($data) {
            $i = 0;
            foreach ($data as $v) {
                $j = 0;
                foreach($v as $cell) {
                    $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j].($i+$row),$cell);
                    $j++;
                }
                $i++;
            }
        }
        // 保存为Excel 2007格式文件，
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
        $filePath = '';
        if ($savePath[strlen($savePath)-1] == DIRECTORY_SEPARATOR) {
            $filePath = $savePath.$fileName.'.xlsx';
        } else {
            $filePath = $savePath . DIRECTORY_SEPARATOR . $fileName.'.xlsx';
        }
        $objWriter->save($filePath);
    }
}