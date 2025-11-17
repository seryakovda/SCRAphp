<?php
/**
 * Created by PhpStorm.
 * User: rezzalbob
 * Date: 25.09.2019
 * Time: 0:28
 */

namespace forms\SYS;
use DB\Connect;
use DB\Connection;
use DB\Table\ConnectionSettings;
use DB\Table\PassHead;
use DB\Table\pList;
use DB\Table\pMark;
use models\_G_session;
use Properties\Security;

class MODEL_mobile_SCRA_01 extends \forms\SYS\MODEL
{
    private $ConnOrion;
    public function __construct()
    {
        $d =  new ConnectionSettings();
        $data = $d->select()->fetch();
        $this->ConnOrion = Array();
        $this->ConnOrion["MSSQL"] =  Security::TYPE_dB_MS_SQL;
        $this->ConnOrion["serverName"] =  $data[$d::address_DbOrion];
        $this->ConnOrion["dataBase"] =    $data[$d::db_DbOrion];
        $this->ConnOrion["userName"] =    $data[$d::login_DbOrion];
        $this->ConnOrion["password"] =    $data[$d::pass_DbOrion];
    }

    public function getDataByQrCode($qrCode,$typeCode)
    {
        if ($typeCode == 'FR_Code'){
            $qrCode = $this->extractUidFromAsciiOrHex($qrCode);
        }
        if ($typeCode == 'QR_Code'){
            //$qrCode = (int)$qrCode;
            $qrCode =dechex($qrCode);
        }
        $value = "Не найдено";

        $d0 = new pMark();
        $data00 = $d0->where($d0::CodeP_HEX,$qrCode)
            ->select();
        if ($data0 = $data00->fetch()){

            \models\ErrorLog::saveError($data0);

            $d1 = new pList();
            $data1 = $d1->where($d1::ID,$data0[$d0::Owner])
                ->select();
            if ($res = $data1->fetch()){
                $value = $res[$d1::Name];
            }
        }


        $ret = Array();
        $ret[] = Array(
            'name'      =>  'Номер пропуска',
            'value'     =>  $value . $qrCode,
            'color'     => "#5e8af8",
        );
//
//        $d = new \DB\View\View_PassHead();
//        $res = $d
//            ->where($d::qrCode,$qrCode)
//            ->select()->fetch();
//        $id_human = $res[PassHead::id_Human];
//
//        $color = '#ebf7ff';
//
//        if ($res[$d::del] != -1){ // если не выыдан то информируем мобилник о цвете
//            $color = $res[$d::color];
//        }
//
//
//        $ret[] = Array(
//            'name'      =>  'Номер пропуска',
//            'value'     =>  $res[$d::id],
//            'color'     => $color,
//        );
//
//        $ret[] = Array(
//            'name'      =>  'ФИО',
//            'value'     =>  $res[$d::surname] . ' ' . $res[$d::name] . '.' . $res[$d::patronName] . '.',
//            'color'     => $color,
//        );
//
//        $ret[] = Array(
//            'name'      =>  'Выдан',
//            'value'     =>  date('d.m.Y',strtotime($res[$d::dateStart])),
//            'color'     => $color,
//        );
//
//
//        $ret[] = Array(
//            'name'      =>  'Дата окончания',
//            'value'     =>  date('d.m.Y',strtotime($res[$d::dateEnd])),
//            'color'     => $color,
//        );
//
//        if ($res[$d::del] >= 0){
//            $ret[] = Array(
//                'name'      =>  'Статус',
//                'value'     => $res[$d::name_status],
//                'color'     => $color,
//            );
//        }else{
//            $d = new \DB\View\View_PassTable();
//            $data = $d
//                ->where($d::qrCode,$qrCode)
//                ->orderBy($d::sort )
//                ->select();
//
//            while ($res = $data->fetch()){
//                $ret[] = Array(
//                    'name'      =>  $res[$d::name],
//                    'value'     =>  $res[$d::value],
//                    'color'     => $color,
//                );
//            }
//        }
//        $query = "
//                SELECT
//                       OTIPB_DocumentList.id,
//                       OTIPB_Document.name,
//                       OTIPB_DocumentList.id_document,
//                       OTIPB_DocumentList.numberDoc,
//                       OTIPB_DocumentList.dateDoc,
//                       OTIPB_DocumentList.dateDocEnd,
//                       OTIPB_DocumentList.dateCreate,
//                       OTIPB_BundleDocumentsHuman.id_human
//                FROM
//                     OTIPB_DocumentList
//                INNER JOIN
//                         OTIPB_BundleDocumentsHuman
//                             ON
//                                 OTIPB_DocumentList.id = OTIPB_BundleDocumentsHuman.id_documents
//                INNER JOIN
//                         OTIPB_Document
//                             ON
//                                 OTIPB_DocumentList.id_document = OTIPB_Document.id
//                WHERE
//                      (OTIPB_BundleDocumentsHuman.id_human = $id_human) AND
//                      (OTIPB_DocumentList.del = 0)";
//        $conn = new Connect();
//        $data = $conn->complexQuery($query);
//        $ret[] = Array(
//            'name'      =>  "",
//            'value'     =>  "Имеющиеся документы",
//            'color'     =>  '#aaaaaa',
//        );
//
//        while ($res = $data->fetch()){
//            $color = '#ebf7ff';
//
//
//            if ($res['dateDocEnd'] === null)
//                $doc = '';
//            else{
//                $dateDoc = new \DateTime(date('d.m.Y',strtotime($res['dateDocEnd'])));
//                $dateDoc_1 = new \DateTime(date('d.m.Y',strtotime($res['dateDocEnd'])));
//                $dateDoc_1->modify("-1 month");
//                $dateThis = new \DateTime();
//                if ($dateThis > $dateDoc)
//                    $color = '#ff7070';
//
//                if ( ($dateThis >= $dateDoc_1) && ($dateThis < $dateDoc) )
//                    $color = '#ffff70';
//
//                $doc = $dateDoc->format('d.m.Y');
//            }
//
//
//            $ret[] = Array(
//                'name'      =>  $res[$d::name],
//                'value'     =>  $res['numberDoc'] . $doc  ,
//                'color'     => $color,
//            );
//
//        }

        return $ret;
    }

    public function extractUidFromAsciiOrHex($input) {
        // 1) если вход выглядит как hex-последовательность байтов (только 0-9A-F и длина чётная > 2), декодируем в ASCII
        $s = trim($input);

        // распознаём hex-bytes: только [0-9A-Fa-f] и чётная длина и не содержит пробелов
        if (preg_match('/^[0-9A-Fa-f]+$/', $s) && (strlen($s) % 2) === 0) {
            // decode hex to bytes and treat them as ASCII characters
            $raw = hex2bin($s);
            if ($raw === false) return "";
            // превратим в строку тех ASCII-символов
            $s = $raw;
        }

        // теперь $s — строка, содержащая ASCII-символы, среди которых есть HEX-цифры
        // получим только HEX-символы (0-9 A-F)
        $hexchars = '';
        // если $s — бинарная строка, преобразование ord->chr уже сделает корректно; используем preg_replace:
        $hexchars = preg_replace('/[^0-9A-Fa-f]/', '', $s);
        $hexchars = strtoupper($hexchars);

        if (strlen($hexchars) < 6) return "";

        // Найдём последнюю подряд 6-символьную последовательность
        if (!preg_match_all('/[0-9A-F]{6}/', $hexchars, $matches, PREG_OFFSET_CAPTURE)) {
            return "";
        }

        $last = end($matches[0]);
        $candidate6 = $last[0];
        $pos = $last[1]; // позиция в $hexchars (0-based)

        // Попробуем взять следующий ниббл (HEX символ) сразу после candidate6
        $next_pos = $pos + 6;
        $next_nibble = '0';
        if ($next_pos < strlen($hexchars)) {
            $next_nibble = $hexchars[$next_pos];
        }

        // Сконвертируем и составим UID
        $cand_val = hexdec($candidate6);          // 24-bit
        $nib_val  = hexdec($next_nibble) & 0xF;   // 4-bit
        $uid_val = (($cand_val << 4) & 0xFFFFFF) | $nib_val;

        return strtoupper(str_pad(dechex($uid_val & 0xFFFFFF), 6, '0', STR_PAD_LEFT));
    }

    public function getEmarin($input)
    {
        $num = hexdec($this->extractUidFromAsciiOrHex($input));
        $conn = new Connect($this->ConnOrion);
        return $conn->complexQuery("select dbo.Func_get_eMarinKey($num) as Emarin")->fetchField('Emarin');
    }
}