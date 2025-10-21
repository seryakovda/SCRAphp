<style>
    .MsgBlockAPP {
        /*.borderRadiusTop*/
        /*.borderRadiusBottom*/
        /*. backgroundAlert*/
        /*. textColorWhite*/
        position: absolute;
        top: 0px;
        right: 0;
        bottom: 0;
        left: 0;
        margin: auto;
        padding: 0px;
        align-content:baseline;
    }
    .backgroundAlert1 {
        background: #BE1EC3FF; /*для долбанного эксплорера*/

    }

    .backgroundAlert_block {
        background: #ec702e; /*для долбанного эксплорера*/

    }
    .backgroundDatetime{
        font-size: 22px;
        font-weight: bold;
        color: blue;
    }
</style>

<code>
    <div id="frameApp">
        <div id="MsgBlockAPP" class="MsgBlockAPP" style="top:0px;left:0px;">
            <?php
            print $this->windowContent;
            ?>
        </div>
    </div>
</code>

<script>
    function loadscript() {
        JsonSTR_listURL = '<?php print $this->listURL; ?>'
        _G_value0 = JSON.parse(JsonSTR_listURL);
        setTimeout(GetEvents, 2000);
        //setTimeout(GetEventsNumber, 2000);

        let rel = setInterval(reload, 60000);

        updateTime();
        window.setInterval(updateTime,1000);
    }

    function reload()
    {

        for (index = 0; index < _G_value0.length; ++index) {
            imgCamV = '#imgCamV'+(index+1);
            timeNow = Date.now();
            url = _G_value0[index]
            url = url.replace('amp;', '')
            url = url + '&t='+timeNow
            $(imgCamV).attr('src', url);
        }
    }


    function updateTime()
    {
        var time = document.getElementById('DateTime');
        time.innerText = new Date().toLocaleString();
    }

    function GetEvents()
    {
        _G_Ajax({
            type: "POST",
            url: "index_ajax.php",
            data: {
                parent:"SKUD",
                r0: "EventsMonitor2",
                r1: "GetEvents",

            },
            dataType: 'text',
            success: function (data) {
                integrationsScriptCSS("winDor", data)
                GetEvents()
            },
            error: function(){
                setTimeout(reLoad, 60000);
            }
        });
    }

    function GetEventsNumber()
    {
        _G_Ajax({
            type: "POST",
            url: "index_ajax.php",
            data: {
                parent:"SKUD",
                r0: "EventsMonitor2",
                r1: "GetEventsNumber",

            },
            dataType: 'text',
            success: function (data) {
                integrationsScriptCSS("winCamT", data)
                GetEventsNumber()
            },
            error: function(){
                setTimeout(reLoad, 60000);
            }
        });
    }
    function reLoad()
    {
        location.reload();
    }
</script>
