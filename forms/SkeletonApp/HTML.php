<style>
    .fixLeftBlock {
        /*.shadowNormal*/
        /*.backgroundNormal*/
        /*.borderRadiusTop*/
        position: absolute;
        top: 30px;
        left: 10px;
        width: 200px;
        bottom: 5px;

    }
    .mainContent {
        position: absolute;
        top: 10px;
        left: 10px;
        width: 1000px;
    }

    .fixHeadBlock {
        /*.shadowNormal*/
        /*.backgroundNormal*/
        margin: 0 auto;
        width: 100%;
        height: 20px;
        position: fixed;
        top: 0px;
    }


    .fixHeadBlockMobile {
        /*.shadowNormal*/
        margin: 0 auto;
        width: 100%;
        height: 60px;
        position: fixed;
        top: 0px;
        font-size: 22px;
        display: grid;
        align-content: center;
    }
    .fixBottomBlock {
        /*.shadowNormal*/
        margin-bottom: 0;
        margin: auto;
        width: 100%;
        height: 25px;
        position: fixed;
        bottom: 0px;
        font-size: 22px;
        display: grid;
        align-content: center;
    }

    .TOP{
        width: 100%;
        height: 60px;
    }
    .WorkBlock{
        width: 100%;
        display: grid;
        justify-content: center;

    }
    .BOTTOM{
        width: 100%;
        height: 70px;
    }
</style>

<code>
    <?php
    print $HTML;
    ?>
</code>
<script>

    function loadscript() {
    }


    function exitAPP() {
        _G_Ajax({
            type: "GET",
            url: "index_ajax.php",
            data: {r0: "SkeletonApp", r1: "logOut"},
            dataType: 'text',
            success: function (data) {
                location.reload();
                const cookies = document.cookie.split(";");
                for (let i = 0; i < cookies.length; i++) {
                    const cookie = cookies[i];
                    const eqPos = cookie.indexOf("=");
                    const name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
                    document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT";
                }
                /*    integrationsScriptCSS("body",data)*/
            }
        });
    }

    function replaceValue(_caption,_var,_val,_pass)
    {
        _G_value1 = _var
        BlockAPP();
        _G_Ajax({
            type: "GET",
            url: "index_ajax.php",
            data: {
                r0: "inputEditVariable", r1: "editVariable",
                callFunction: 'replaceValue_0()', //callFunction,
                message: _caption, //message,
                oldValue: _val, //olgValue,
                value: _val, //olgValue,
                pattern: '', //pattern,
                password:_pass,
                placeholder: "reverse: true,"
            },
            dataType: 'text',
            success: function (data) {

                integrationsScriptCSS("BlockAPP", data)
            }
        });
    }

    function replaceValue_0()
    {
        _G_Ajax({
            type: "GET",
            url: "index_ajax.php",
            dataType: 'text',
            data: {
                r0: "SkeletonApp",
                r1: "replaceValue",
                _var:_G_value1,
                _val:_G_varVal,
            },
            success: function () {
                location.reload();
            }
        })
    }
    function testConnectOrion()
    {
        closeBlockAPP()
        _G_Ajax({
            type: "GET",
            url: "index_ajax.php",
            dataType: 'text',
            data: {
                r0: "SkeletonApp",
                r1: "testConnectOrion"
            },
            success: function (data) {
                _G_BlockAppMessageOK(data,'reloadPage()')
            }
        })
    }

    function testConnectPS()
    {
        closeBlockAPP()
        _G_Ajax({
            type: "GET",
            url: "index_ajax.php",
            dataType: 'text',
            data: {
                r0: "SkeletonApp",
                r1: "testConnectPS"
            },
            success: function (data) {
                _G_BlockAppMessageOK(data,'reloadPage()')
            }
        })
    }


    function reloadPage()
    {
        location.reload();
    }
</script>