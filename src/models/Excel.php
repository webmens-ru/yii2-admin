<?php


namespace wm\admin\models;


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class Excel extends Model
{

    public static function generate($arr){
        $data = self::prepareDate(ArrayHelper::toArray($arr));
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getActiveSheet()
            ->fromArray(
                $data,
                null,
                'A1'
            );
        $spreadsheet->getActiveSheet()
            ->getStyle(self::getHeadRange($data))
            ->applyFromArray(
                [
                    'font' => [
                        'bold' => true,
                    ],
                ]
            );
        $spreadsheet->getActiveSheet()
            ->getStyle(self::getRange($data))
            ->applyFromArray(
                [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => [
                                'rgb' => '000000']
                        ],
                    ],
                ]
            );
        self::setAutoWidthForColumns('A', self::getNameFromNumber(count(reset($data))), $spreadsheet);
        $writer = new Xlsx($spreadsheet);
        ob_start();
        $writer->save('php://output');
        $file = ob_get_contents();
        ob_clean();
    }
    /**
     * @param $data
     * @return string
     */
    public static function getHeadRange($data)
    {
        $result = 'A1:Z1';
        if (is_array($data)) {
            $count = count(reset($data));
            $result = 'A1:' . self::getNameFromNumber($count) . '1';
        }
        return $result;
    }

    /**
     * @param $data
     * @return string
     */
    public static function getRange($data)
    {
        $result = 'A1:Z1';
        if (is_array($data)) {
            $countRow = count($data);
            $count = count(reset($data));
            $result = 'A1:' . self::getNameFromNumber($count) . $countRow;
        }
        return $result;
    }

    /**
     * @param $num
     * @return string
     */
    public static function getNameFromNumber($num)
    {
        $numeric = ($num - 1) % 26;
        $letter = chr(65 + $numeric);
        $num2 = intval(($num - 1) / 26);
        if ($num2 > 0) {
            return self::getNameFromNumber($num2) . $letter;
        } else {
            return $letter;
        }
    }


    /**
     * @param $data
     * @return array
     * @throws \Exception
     */
    public static function prepareDate($data)
    {
        $result = [];
        if (ArrayHelper::getValue($data, 'schema')) {
            $columns = ArrayHelper::getColumn(ArrayHelper::getValue($data, 'schema'), 'code');
            $temp = [ArrayHelper::map(ArrayHelper::getValue($data, 'schema'), 'code', 'title')];
            $temp = array_merge($temp, self::getValues(ArrayHelper::getValue($data, 'grid'), $columns));
            $data = array_merge($temp, self::getValues(ArrayHelper::getValue($data, 'footer'), $columns));
        }
        foreach ($data as $row) {
            $tempRow = [];
            foreach ($row as $key => $value) {
                $tempValue = '';
                if (is_array($value)) {
                    switch (ArrayHelper::getValue($value, 'type')) {
                        case 'date':
                            if (ArrayHelper::getValue($value, 'format') == 'DD.MM.YYYY') {
                                $date = strtotime(ArrayHelper::getValue($value, 'title'));
                                $tempValue = date('d.m.Y', $date);
                            } elseif (ArrayHelper::getValue($value, 'format') == 'DD.MM.YYYY HH:mm:ss') {
                                $date = strtotime(ArrayHelper::getValue($value, 'title'));
                                $tempValue = date('d.m.Y h:i:s', $date);
                            } else {
                                $tempValue = ArrayHelper::getValue($value, 'title');
                            }
                            break;
                        default:
                            $tempValue = ArrayHelper::getValue($value, 'title');
                    }
                } else {
                    $tempValue = $value;
                }


                $tempRow[$key] = $tempValue;
            }
            $result[] = $tempRow;
        }
        return $result;
    }

    public static function getValues($data, $keys = ['id'])
    {
        $result = [];
        foreach ($data as $value) {
            $temp = [];
            foreach ($keys as $key) {
                $temp[$key] = ArrayHelper::getValue($value, $key);
            }

            $result[] = $temp;
        }
        return $result;
    }

    /**
     * Устанавливает стили для ячейки (ячеек)
     * @param string $cell Координаты ячейки (ячеек), пример: 'A1:A5'
     * @param array $styleArray
     * @param Spreadsheet $spreadsheet
     */
    public static function setStyleForExcell($cell, $styleArray, Spreadsheet $spreadsheet)
    {
        $spreadsheet->getActiveSheet()
            ->getStyle($cell)
            ->applyFromArray($styleArray);
    }

    /**
     * Устанавливает автоматическую ширину для столбцов
     * @param string $columnStart
     * @param string $columnEnd
     * @param Spreadsheet $spreadsheet
     */
    public static function setAutoWidthForColumns($columnStart, $columnEnd, Spreadsheet $spreadsheet)
    {
        foreach (range($columnStart, $columnEnd) as $columnID) {
            $spreadsheet->getActiveSheet()
                ->getColumnDimension($columnID)
                ->setAutoSize(true);
        }
    }

}