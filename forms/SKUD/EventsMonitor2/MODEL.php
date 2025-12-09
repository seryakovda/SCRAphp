<?php
namespace forms\SKUD\EventsMonitor2;

use DB\Connect;
use DB\Connection;
use DB\Table\ConnectionSettings;
use DB\Table\nXms_Excel;
use DB\Table\security_userSettings;
use \DB\View\View_Orion_getEvents;
use mysql_xdevapi\Exception;
use Properties\Security;


class MODEL extends \forms\FormsModel
{
    private $ipCameraTrigger;
    private $listCameraViewIp;

    private $arrScreenCamera;

    private $json  = false;

    public $ConnOrion = Array();

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

    /**
     * @param mixed $listCameraViewIp
     */
    public function setListCameraViewIp($listCameraViewIp): void
    {
        $this->listCameraViewIp = $listCameraViewIp;
    }



    /**
     * @param mixed $arrSreenCamera
     */
    public function setArrScreenCamera($arrScreenCamera): void
    {
        $this->json = false;
        try{
            if (strpos($arrScreenCamera, '{') !== false){
                $this->arrScreenCamera = json_decode($arrScreenCamera,true);
                $this->json = true;
            }
        }
        catch (\TypeError $e) {}
        catch (Exception $e)  {}

        if ($this->json === false){
            $this->arrScreenCamera = explode(',',$arrScreenCamera);
        }
    }


    public function getMinIndexScreenCamera()
    {
        $minKey = 0;
        if ($this->json){
            reset($this->arrScreenCamera);
            $minKey = key($this->arrScreenCamera);
        }

        return $minKey;
    }



    public function getListCameraForVideoStream()
    {

        if ($this->json === false){
            $readArray = $this->arrScreenCamera;//если не json то в настройках простая строка с перечисленными ip через запятую
        }else{
            $readArray = $this->arrScreenCamera[$_SESSION['indexScreenCamera']];
        }

        $retArray = Array();
        \models\ErrorLog::saveError($readArray,typeSaveMode: "w+");
        foreach ($readArray['listIP'] as $key => $item){
            \models\ErrorLog::saveError("item");
            \models\ErrorLog::saveError($item);
            $arr = Array();
            if ($this->json){
                $ip = $item['IP'];
                $arr['x'] = $item['x'];
                $arr['y'] = $item['y'];
            }else{
                $ip = $item;
                $arr['x'] = "/100*50";
                $arr['y'] = "/100*50";
            }
            \models\ErrorLog::saveError("IP = $ip");
            $d = new nXms_Excel();
            if($data = $d->where($d::ip,$ip)->select()->fetch()){
                $field = $d::cameraServerIp;
                $arr[$field] = $data[$field];

                $field = $d::cameraChannelGuid;
                $arr[$field] = $data[$field];

                $retArray[] = $arr;
            }
        }
        return $retArray;
    }


    public function getListScreenCamera()
    {
        $retArr = false;
        if ($this->json ){
            $retArr = Array();
            $i = 1;
            foreach ($this->arrScreenCamera as $key => $value){
                $retArr[] = Array (
                    "caption"=>$i,
                    "id"=>$value['id']
                );
                $i ++ ;
            }
        }
        return $retArr;
    }

    public function getData_event($DoorIndex)
    {
        $connMSSQL = new Connect($this->ConnOrion);

        $query = "
        SELECT top (4)
                    plist.id,
                    pList.Name, 
                    pList.FirstName, 
                    pList.MidName, 
                    pList.Company, 
                    Plogdata.TimeVal,
                    case when isnull(Plogdata.Par4,2) = 2 then 'Выход' else 'Вход' END as entryExit,
                    Plogdata.TimeVal as TimeVal1,
                    -- Plogdata.HozOrgan AS ID, 
                    pmark.CodeP,
                    pmark.Config,
                    pMark.Comment,
                    Plogdata.DoorIndex, 
                    Acesspoint.Name as name_Acesspoint , -- Дверь
                    -- plist.DoorName, 
                    pCompany.Name as CompN, 
                    pDivision.Name as DivN,
                    pPost.name as name_pPost,
                    Plogdata.Remark, 
                    AccessZone.Name as ZoneName, 
                    Plogdata.Mode, 
                    case 
						when Plogdata.Mode = 1 then 'Вход'
						else 'Выход'
					END as InOut,
                    Plogdata.ZoneIndex,  
                    Plogdata.Event, 
                    Events.Contents,
                    isnull(pList.status_list,2) as status_list,
                    case isnull(pList.status_list,2)
                        when 0 then
                            case 
                                when pmark.Config = 32896 then '#ec702e' -- рыжый
                                else ''
                            END
                        else
                            '#c31e1e' -- красный
                    end as color_status
--               ,Orion_doorIndex.color as color_doorIndex 

                FROM 
                [dbo].Plogdata  
                    left join 
                        [dbo].plist 
                            on 
                                (Plogdata.HozOrgan = plist.id)      
                    left join 
                        [dbo].pDivision 
                            on 
                            (plist.section = pdivision.ID)    
                    LEFT JOIN  
                        [dbo].AccessZone 
                            ON  
                            (Plogdata.ZoneIndex = AccessZone.GIndex)    
                    left join 
                        [dbo].pCompany 
                            on 
                            (plist.Company=pcompany.ID)  
                    left join 
                        [dbo].pPost 
                            on 
                            (plist.Post=pPost.ID)  
                    LEFT JOIN  
                        [dbo].Acesspoint 
                            ON  
                            (Plogdata.DoorIndex = AcessPoint.GIndex)   
                    left join 
                        [dbo].pmark 
                            on 
                            (Plogdata.ZReserv = pmark.id)  
                    LEFT JOIN 
                        [dbo].Events 
                            ON 
                            ( Plogdata.Event = Events.Event  )
--                    LEFT JOIN 
--                        Orion_doorIndex
--                            ON 
--                            ( Plogdata.DoorIndex = Orion_doorIndex.doorIndex)
                WHERE
                ( Plogdata.Event IN (
                 26,28,29,34                       
                ))
                and DoorIndex in ($DoorIndex)
                order by TimeVal DESC
        ";

        $this->data = $connMSSQL->complexQuery($query);

        return $this->data;
    }

    public function GetEvents($DoorIndex)
    {
        $connMSSQL = new Connect($this->ConnOrion);
        $connMSSQL->table('Proc_reactionToTheEvent')
            ->set('_DoorIndex',$DoorIndex)
            ->SQLExec();
    }

    public function setIpCameraTrigger($ipCameraTrigger)
    {
        $this->ipCameraTrigger = $ipCameraTrigger;
    }


    public function GetEventsNumber()
    {
        $p = new \DB\Proc\Proc_TriggerNumberCamera();
        $p->parameters($this->ipCameraTrigger);
    }

    public function getListCameraEvent()
    {
        $query ="
            SELECT id, number, camera, ipCamera, xmlData, dateTimeEvent, dateTimeFile
            FROM DSSL_EventNumberCamera
            WHERE FIND_IN_SET(ipCamera, '$this->ipCameraTrigger') > 0
            ORDER BY dateTimeEvent DESC
            LIMIT 3
        ";
        $conn =  new Connect();
        return $conn->complexQuery($query);
    }


    public function getDataByNumber($number)
    {
        $query = "
                SELECT 
                    PassTable.id_field,
                    PassTable_1.value as nameORG,
                    CONCAT(Human.surname, ' ', Human.name, ' ', Human.patronName) as FIO 
                FROM Car 
                INNER JOIN PassTable ON Car.id = PassTable.id_RowField 
                INNER JOIN PassHead ON PassTable.id_head = PassHead.id 
                INNER JOIN Human ON PassHead.id_Human = Human.id 
                LEFT OUTER JOIN PassTable AS PassTable_1 ON PassHead.id = PassTable_1.id_field AND 7 = PassTable_1.id_head
                WHERE PassTable.id_field = 2 
                    AND PassHead.del < 0 
                    AND Car.stateNumber = '$number'
                GROUP BY 
                    PassTable.id_field, PassTable_1.value, Human.surname, Human.name, Human.patronName
                LIMIT 50
";
        $conn =  new Connect();
        return $conn->complexQuery($query);
    }


}