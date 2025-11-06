<?php
namespace forms\SKUD\EventsMonitor2;

use DB\Connect;
use DB\Connection;
use DB\Table\ConnectionSettings;
use DB\Table\nXms_Excel;
use \DB\View\View_Orion_getEvents;
use Properties\Security;


class MODEL extends \forms\FormsModel
{
    private $ipCameraTrigger;
    private $listCameraViewIp;

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

        $query = "SELECT        TOP (10) id, number, camera, ipCamera, xmlData, dateTimeEvent, dateTimeFile
                    FROM            DSSL_EventNumberCamera
                    WHERE        (ipCamera IN
                                                 (SELECT        value
                                                   FROM            STRING_SPLIT('$this->ipCameraTrigger', ',') AS STRING_SPLIT_1))
                    order by dateTimeEvent DESC
        ";

        $conn =  new Connect();
        return $conn->complexQuery($query);
    }


    public function getDataByNumber($number)
    {
        $query = "SELECT        TOP (50) dbo.PassTable.id_field, 
                       PassTable_1.value as nameORG,
                       dbo.Human.surname +' '+ dbo.Human.name +' '+ dbo.Human.patronName as FIO 
                      
            FROM            dbo.Car INNER JOIN
                         dbo.PassTable ON dbo.Car.id = dbo.PassTable.id_RowField INNER JOIN
                         dbo.PassHead ON dbo.PassTable.id_head = dbo.PassHead.id INNER JOIN
                         dbo.Human ON dbo.PassHead.id_Human = dbo.Human.id LEFT OUTER JOIN
                         dbo.PassTable AS PassTable_1 ON dbo.PassHead.id = PassTable_1.id_field AND 7 = PassTable_1.id_head
            GROUP BY dbo.PassTable.id_field, PassTable_1.value, dbo.Human.surname, dbo.Human.name, dbo.Human.patronName, dbo.PassHead.del, dbo.PassHead.id_Human,  dbo.Car.stateNumber
            HAVING        (dbo.PassTable.id_field = 2) AND (dbo.PassHead.del < 0) AND (dbo.Car.stateNumber = '$number')";
        $conn =  new Connect();
        return $conn->complexQuery($query);
    }

    public function getListCameraForVideoStream()
    {
        $query = "SELECT *
                    FROM nXms_Excel
                    WHERE FIND_IN_SET(ip, '$this->listCameraViewIp') > 0;
        ";
        $conn =  new Connect();
        return $conn->complexQuery($query);
    }

}