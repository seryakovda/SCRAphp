<style>
    /*Стили авторизации*/
    .regmain {
        position: absolute;
        top: 200px;
        width: 400px;
        right: 0;
        left: 0;
        margin: auto;
        padding: 0px;
        align-content: center;
    }
</style>

<script>
    function ReplacePassword() {
        BlockAPPWait();
        var Password1 = $("#Password1").val();
        var Password2 = $("#Password2").val();
        if (Password1.length > 20 ) {
            _G_BlockAppMessage("Пароль слишком длинный");
            return false;
        }
        if (Password1 != Password2) {
            _G_BlockAppMessage("Пароли не совпадают");
            return false;
        }

        _G_Ajax({
            type: "GET",
            url: "index_ajax.php",
            data: { parent:"SYS",
                r0: "ReplacePassword",
                r1: "ReplacePassword",
                Password1: Password1,
                Password2: Password2
            },
            dataType: 'text',
            success: function (data) {
                if (data == "OK"){
                    _G_BlockAppMessageOK('Пароль успежно изменён. Войдите в программу c <b>новым</b> паролем!','exitAPP()');
                }else{
                    integrationsScriptCSS("BlockAPP", data);
                }

            }
        })
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
</script>
