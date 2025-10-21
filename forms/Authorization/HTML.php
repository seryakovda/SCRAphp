<style>
    /*Стили авторизации*/
    .regmain {
        position: absolute;
        top: 200px;
       /* width: 400px;*/
        right: 0;
        left: 0;
        margin: auto;
        padding: 0px;
        align-content: center;
    }
</style>

<code>
    <div class="t001__uptitle t-uptitle t-uptitle_sm t-animate t-animate_started" data-animate-style="fadeinup" data-animate-group="yes" style="text-transform: uppercase; transition-delay: 0.6s; height: 230px" field="subtitle">
        <div  class = "enterAuthorization">
            <?php
            print $HTMLPrint;
            ?>
        </div>
    </div>
</code>
<script>



    function startAutorization() {
        BlockAPPWait();
        _G_login = $("#Login").val();
        _G_Password = $("#Password").val();

        _G_Ajax({
            type: "GET",
            url: "index_ajax.php",
            data: {
                r0: "Authorization",
                r1: "enterLogin",
                login: _G_login,
                password: _G_Password,
                LevelAuthorization: "1"
            },
            dataType: 'text',
            success: function (data) {
                if (data == "LoadLevel_2"){

                    LoadLevel_2()
                }else{
                    integrationsScriptCSS("head", data);
                    location.reload();
                }

            }
        })
    }

    function LoadLevel_2() {
        _G_Ajax({
            type: "GET",
            url: "index_ajax.php",
            data: {r0: "Authorization",
                LevelAuthorization: "1"
                },
            dataType: 'text',

            success: function (data) {
                closeBlockAPP();
                if (_G_Device == 'workstation'){

                    if ($('div').is('#enterAuthorization'))
                        DOMObject = "enterAuthorization";
                    else
                        DOMObject = "head";

                    integrationsScriptCSS(DOMObject, data)
                }

            }
        });
    }

    function startAutorization_level2() {
        var Code = $("#Code").val();
        _G_Ajax({
            type: "GET",
            url: "index_ajax.php",
            data: {
                r0: "Authorization",
                r1: "enterLogin_level2",
                login: _G_login,
                password: _G_Password,
                Code:Code,
                LevelAuthorization: "2"
            },
            dataType: 'text',
            success: function (data) {
                console.log(data);
                integrationsScriptCSS("head", data);
                location.reload();
            }
        })
    }
    function startAutorization_level2_off() {
        _G_Ajax({
            type: "GET",
            url: "index_ajax.php",
            data: {
                r0: "Authorization",
                LevelAuthorization: "0"
            },
            dataType: 'text',
            success: function (data) {
                integrationsScriptCSS("head", data);
                location.reload();
            }
        })
    }
    function loadscript() {
        _G_pressEnter("Password","startAutorization()")
    }

</script>

<runScript>

    if ($('input').is('#Login')) {
        $('#Login').focus();
    }

    if ($('input').is('#Password')) {
        $('#Password').keydown(function(e) {
            if(e.keyCode === 13) {
                startAutorization();
            }
        });
    }
    if ($('input').is('#Code')) {
        $('#Code').focus();
        $('#Code').keydown(function(e) {
            if(e.keyCode === 13) {
                startAutorization_level2();
            }
        });
    }

</runScript>
