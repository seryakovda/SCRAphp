$(document).ready(function () {

    // findIP(addIP);
    sendSetUpData();
});



function _G_Ajax(data)
{
    if (typeof data['error'] === "undefined") {
        data['error'] = function (data) {
            fixingAnError(data)
        }
    }
    $.ajax(data);
}


function loadCSS()
{
    _G_Ajax({
        type: "GET",
        url: "frontPage/css/varebl.php",
        dataType: 'text',

        success: function (data) {
            integrationsScriptCSS("head", data);

            startAuthorization();
        }
    });
}


function sendSetUpData()
{
    var heightBrowse = $(window).height();
    var widthBrowse = false;

    detect = new MobileDetect(window.navigator.userAgent);

    if (detect.phone() != null){
        _G_Device = 'mobile'
        BlockAPP();
        widthBrowse = $('#BlockAPP').width();
        heightBrowse = $('#BlockAPP').height();
        console.log(widthBrowse,heightBrowse)

        // if ($.cookie('_G_widthBrowse') === undefined) {
        //
        // }else{
        //     widthBrowse = $.cookie('_G_widthBrowse');
        // }

    }else{
        _G_Device = 'workstation'
        widthBrowse = _G_getWidth();
        heightBrowse = _G_getHeight();
    }
    _G_Ajax({
        type: "GET",
        url: "index_ajax.php",
        data: {
            r0: "SYS",
            r1:"sendSetUpData",
            heightBrowse: heightBrowse,
            widthBrowse : widthBrowse,
            device:_G_Device
        },
        dataType: 'text',

        success: function () {
            loadCSS();
        }
    });
}


function _G_getWidth()
{
    block = "#fixHeadBlock"
    if($("#fixHeadBlock").length != 0)
        block = "#fixHeadBlock"
    if($("#MsgBlockAPP").length != 0)
        block = "#MsgBlockAPP"

    if($(block).length == 0) {
        _G_widthPage = 1280;
    }else {
        if (_G_widthPage === false) {
            _G_widthPage = $(block).width();
        }
    }
    return _G_widthPage;
}


function _G_getHeight()
{
    block = "#fixLeftBlock"
    if($("#fixHeadBlock").length != 0)
        block = "#fixLeftBlock"
    if($("#MsgBlockAPP").length != 0)
        block = "#MsgBlockAPP"

    if($(block).length == 0) {
        _G_heightPage = 720;
    }else{
        if (_G_heightPage === false) {
            _G_heightPage = $(block).height();
        }
    }
    return _G_heightPage;
}


function startAuthorization()
{

    var data1 = {r0: "Authorization",
        LevelAuthorization: "0"};
    console.log(1,data1)

    if (_G_Device == 'mobile'){
        if ($.cookie('_G_parent') !== undefined)
            data1['parent'] = $.cookie('_G_parent')
        if ($.cookie('_G_route') !== undefined)
            data1['r0'] = $.cookie('_G_route')
    }
    console.log(2,data1)


    _G_Ajax({
        type: "GET",
        url: "index_ajax.php",
        data: data1,
        dataType: 'text',

        success: function (data) {
            //if (_G_Device == 'workstation'){

            if ($('div').is('#enterAuthorization'))
                DOMObject = "enterAuthorization";
            else
                DOMObject = "head";

            if ($.cookie('_G_route') !== undefined)
                DOMObject = 'mainContent'


            integrationsScriptCSS(DOMObject, data)
        }

        //}
    });
}
function RunMenu(route, parent, f_BlockAPPWait = 0) {
    if (f_BlockAPPWait == 1){
        BlockAPPWait();
    }
    if (_G_Device == 'mobile'){
        $.cookie('_G_parent', parent);
        $.cookie('_G_route', route);
    }
    _G_Ajax({
        type: "GET",
        url: "index_ajax.php",
        dataType: 'text',
        data: {
            r0: route,
            parent: parent
        },
        success: function (data) {
            if (f_BlockAPPWait == 1){
                closeBlockAPP();
            }
            integrationsScriptCSS("mainContent", data)
        }
    });
}

function getStyleSheet(unique_title) {
    for (var i = 0; i < document.styleSheets.length; i++) {
        var sheet = document.styleSheets[i];
        var mcssrules = sheet.cssRules;
        for (key in mcssrules) {
            var inobj = mcssrules[key];
            key1 = 'selectorText';
            mselectorText = inobj[key1];
            key = 'cssText';
            mcssText = inobj[key];
            if (mselectorText == unique_title) {
                var regexp = /\{([\s\S]*?)}/;
                var matches = mcssText.match(regexp);
                return matches[1];
            }
        }
        if (sheet.title == unique_title) {
            return sheet;
        }
    }
}
function integrationsScriptCSS(IDDOMObject, data) {


    if (data.indexOf('Fatal error') != -1){
        fixingAnError(data)
        return;
    }

    if (data.search(/outputBlockAPP/i) != -1) {
        if (!$('div').is('#BlockAPP')) {
            BlockAPP();
        }
        IDDOMObject = "BlockAPP";
    }
    /*
    Выполняться только Если найден Элемент с указанным id
     */
    nameidDomObject = '#'+IDDOMObject;
    if (($('div').is(nameidDomObject) == true) ||
        (IDDOMObject == 'head')) {
        var macroCSS = [/*Ниже описаны классы которые будут заменятся в пришедшем коде CSS*/
            ".borderRadiusBottom",
            ".shadowNormal",
            ".borderTop",
            ".borderBottom",
            ".BorderAll",
            ".borderRadiusTop",
            ".borderRadiusBottom",
            ".backgroundNormal",
            ".backgroundInsert",
            ".backgroundAlert",
            ".textColorWhite",
            ".textColorBlack",
            ".textFontBig",
            ".textFontNormal",
            ".textFontSmall",
            ".background-Menu",
            ".background-Insert-Menu",
            ".backgroundNormalAM",
            ".backgroundNormalCFEO"
        ];
        var regexpCSS = /<style>([\s\S]*?)<\/style>/g;//;
        var matches1 = data.match(regexpCSS);
        if (matches1 !== null) { //если имеются стили
            for (var $ii = 0; $ii < matches1.length; $ii++) { // крутим пока стили не кончатся
                var regexpCSS_1 = /<style[^>]*>([\s\S]*?)<\/style>/;
                var matches = matches1[$ii].match(regexpCSS_1);
                var newStyle = matches[1];
                macroCSS.forEach(function (item) {
                    regexpCSS_1 = new RegExp("/\\*" + item + "\\*/", 'g');
                    var styleSheetObject = getStyleSheet(item);
                    newStyle = newStyle.replace(regexpCSS_1, styleSheetObject);
                });
                if (matches !== null) {
                    var style = document.createElement('style');
                    style.textContent = newStyle;
                    style.type = "text/css";
                    document.getElementsByTagName("head")[0].appendChild(style);
                    /*            document.head.innerHTML += '<style type="text/css">' + newStyle + '</style>'*/
                }
            }
        }
        //Далее грузится HTML
        var regexpCode = /<code[^>]*>([\s\S]*?)<\/code>/;
        var matchesCode = data.match(regexpCode);
        if (matchesCode !== null) {
            var srtMatches = matchesCode[1];

            var regexpCode2 = new RegExp("<style[^>]*>([\\s\\S]*?)<\/style>", 'g');
            srtMatches = srtMatches.replace(regexpCode2, "");

            var regexpCode3 = new RegExp("<script[^>]*>([\\s\\S]*?)<\/script>", 'g');
            srtMatches = srtMatches.replace(regexpCode3, "");

            var regexpCode4 = new RegExp("<runScript[^>]*>([\\s\\S]*?)<\/runScript>", 'g');
            srtMatches = srtMatches.replace(regexpCode4, "");

            if (IDDOMObject == "head") {
                if (srtMatches.length > 2) {
                    document.getElementsByTagName("body")[0].innerHTML = srtMatches;
                }
            } else {
                document.getElementById(IDDOMObject).innerHTML = '';
                document.getElementById(IDDOMObject).innerHTML = srtMatches;
            }
        }
        //далее грузятся скрипты
        var regexpScript = new RegExp("<script[^>]*>([\\s\\S]*?)<\\/script>", 'g');
        var matchesScript = data.match(regexpScript);
        if (matchesScript !== null) {
            for (var $ii = 0; $ii < matchesScript.length; $ii++) {
                var e = document.createElement("script");
                var mach = matchesScript[$ii];
                regexpScript = new RegExp("<script>", 'g');
                mach = mach.replace(regexpScript, "");
                regexpScript = new RegExp("</script>", 'g');
                mach = mach.replace(regexpScript, "");

                regexpCode4 = new RegExp("<runScript[^>]*>([\\s\\S]*?)<\/runScript>", 'g');
                mach = mach.replace(regexpCode4, "");


                e.text = mach;

                e.type = "text/javascript";
                document.getElementsByTagName("head")[0].appendChild(e);

            }
        }
        loadscript();
    }

    var regexpRun = new RegExp("<runScript[^>]*>([\\s\\S]*?)<\\/runScript>", 'g');
    var matchesRun = data.match(regexpRun);
    if (matchesRun !== null) {
        for (var $iiRun = 0; $iiRun < matchesRun.length; $iiRun++) {
            var machRun = matchesRun[$iiRun];
            regexp = new RegExp("<runScript>", 'g');
            machRun = machRun.replace(regexp, "");
            regexp = new RegExp("</runScript>", 'g');
            machRun = machRun.replace(regexp, "");
            eval(machRun)
        }
    }

}

function BlockAPP()
{

    if (!$('div').is('#BlockAPP')) {
        var div1 = document.createElement("div");
        div1.id = "BlockAPP";
        div1.setAttribute("class", "BlockAPP");
        document.body.appendChild(div1);
    }
}

function BlockAPPWait() {
    if (!$('div').is('#BlockAPP')) {
        BlockAPP();
    }
    var div1 = document.createElement("div");
    div1.id = "msgWait";
    div1.setAttribute("class", "MsgWait");
    div1.setAttribute("style", "top:0px;left:0px;width:200px;height:400px");
    $("#BlockAPP").empty();
    $("#BlockAPP").append(div1);
}

function closeBlockAPP() {
    $("#BlockAPP").empty();
    $("#BlockAPP").remove();
}
function getCookie(name) {
    var matches = document.cookie.match(new RegExp(
        "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
    ));
    return matches ? decodeURIComponent(matches[1]) : undefined;
}
function setCookie(name, value, options) {
    options = options || {};

    var expires = options.expires;

    if (typeof expires == "number" && expires) {
        var d = new Date();
        d.setTime(d.getTime() + expires * 1000);
        expires = options.expires = d;
    }
    if (expires && expires.toUTCString) {
        options.expires = expires.toUTCString();
    }

    value = encodeURIComponent(value);

    var updatedCookie = name + "=" + value;

    for (var propName in options) {
        updatedCookie += "; " + propName;
        var propValue = options[propName];
        if (propValue !== true) {
            updatedCookie += "=" + propValue;
        }
    }

    document.cookie = updatedCookie;
}
function _G_buttonSelect(id_greed, callFunction_txt,Catalog) {
    _G_varVal = _G_foundCheckedRowInGrid_returnObject(id_greed);
    _G_Catalog = Catalog;
    eval(callFunction_txt);
}

function _G_refreshFilter(parent, className, name_div_greed, filterBlockName) {
    var data = {};
    data['parent'] = parent;
    data['r0'] = className;
    data['r1'] = "getListFilter";
    var allFilterElement = $("#" + filterBlockName).find(".filterElement");
    for (var i = 0; i < allFilterElement.length; i++) {
        item = allFilterElement[i];
        data[item.name] = item.value
    }
    _G_Ajax({
        type: "GET",
        url: "index_ajax.php",
        data: data,
        dataType: 'text',
        success: function (data) {
            integrationsScriptCSS(name_div_greed, data)
        }
    });
}

function saveDateOutClassMethod(className, methodName, varName, varVal) {
    _G_Ajax({
        type: "GET",
        url: "index_ajax.php",
        data: {
            r0: className, r1: methodName,
            varName: varName,
            varVal: varVal
        },
        dataType: 'text',
        success: function (data) {
            integrationsScriptCSS("body", data)
        }
    });
}


function _G_downloadReport(id_report) {

    _G_Ajax({
        type: "GET",
        url: "index_ajax.php",
        dataType: 'text',
        data: {
            r0: "SYS",
            r1: "downloadReport",
            id_report: id_report
        },
        success: function (data) {
            try {
                var dataArray = JSON.parse(data);
            } catch (e) {
                _G_BlockAppMessage('Ошибка формирования для печати. Обратитесь в слубу поддержки');
                return;
            }
            var path_download="/download/" + dataArray['id'];
            //var nowDate = new Date();
            newFileName = dataArray['fileName'];
            var link = document.createElement('a');
            link.setAttribute('href', path_download);
            link.setAttribute('download', newFileName);
            link.click();

            closeBlockAPP();
        }
    });
}

function _G_BlockAppMessage(message) {
    BlockAPP();
    $.ajax({
        type: "GET",
        url: "index_ajax.php",
        data: {
            r0: "inputEditVariable", r1: "messageReadeOnly",
            message: message
        },
        dataType: 'text',
        success: function (data) {
            integrationsScriptCSS('BlockAPP', data)
        }
    });
}

function fixingAnError(data)
{
    var error = "";
    if (typeof (data) != "string")
        error = JSON.stringify(data);
    else
        error = data;

    console.log(error);
    $.ajax({
        type: "POST",
        url: "index_ajax.php",
        data: {
            r0: "SYS", r1: "fixingAnError",
            error: error
        },
        dataType: 'text',
        success: function () {
            //_G_BlockAppMessage('Ошибка выполнения. Обратитесь в службу поддержки'); 
        }
    });
}

function _G_BlockAppQuestion_YesOrNo(message, callFunction) {
    BlockAPP();
    _G_Ajax({
        type: "GET",
        url: "index_ajax.php",
        data: {
            r0: "inputEditVariable",
            r1: "yesOrNotButtons",
            message: message,
            callFunction: callFunction
        },
        dataType: 'text',
        success: function (data) {
            integrationsScriptCSS("BlockAPP", data);
        }
    });
}

function _G_BlockAppMessageOK(message, callFunction) {
    BlockAPP();
    _G_Ajax({
        type: "GET",
        url: "index_ajax.php",
        data: {
            r0: "inputEditVariable",
            r1: "messageReadeOnly",
            message: message,
            callFunction: callFunction
        },
        dataType: 'text',
        success: function (data) {
            integrationsScriptCSS("BlockAPP", data);
        }
    });
}

function _G_BlockApp_Input_catalog(catalog, callFunction, filterArray, oldValue) {
    BlockAPP();
    //filretArray

    var data = new FormData();

    data.append("r0", 'inputEditVariable');
    data.append("r1", 'executeCatalog');
    data.append("callFunction", callFunction);
    data.append("_class", catalog);


    if (oldValue !== false)
        data.append("oldValue", oldValue);
    if (filterArray !== false) {
        filterArray = JSON.stringify(filterArray);
        data.append("filterArray", filterArray);
    }
    _G_Ajax({
        type: "POST",
        url: "index_ajax.php",
        data: data,
        cache: false,
        dataType: 'text',
        // отключаем обработку передаваемых данных, пусть передаются как есть
        processData: false,
        // отключаем установку заголовка типа запроса. Так jQuery скажет серверу что это строковой запрос
        contentType: false,
        success: function (data) {
            integrationsScriptCSS("BlockAPP", data)
        }
    });
}

function _G_BlockApp_Input( olgValue, pattern, message, callFunction)
{
    BlockAPP();
    _G_Ajax({
        type: "GET",
        url: "index_ajax.php",
        data: {
            r0: "inputEditVariable", r1: "editVariable",
            callFunction: callFunction,
            message: message,
            oldValue: olgValue,
            value: olgValue,
            pattern: pattern,
            placeholder: "reverse: true,"
        },
        dataType: 'text',
        success: function (data) {

            integrationsScriptCSS("BlockAPP", data)
        }
    });
}

function _G_BlockApp_playVideo(message, callFunction)
{
    BlockAPP();
    _G_Ajax({
        type: "GET",
        url: "index_ajax.php",
        data: {
            r0: "inputEditVariable", r1: "playVideo",
            callFunction: callFunction,
            message: message
        },
        dataType: 'text',
        success: function (data) {
            integrationsScriptCSS("BlockAPP", data)
        }
    });
}
function _G_BlockApp_Calendar(message, callFunction)
{
    BlockAPP();
    _G_Ajax({
        type: "GET",
        url: "index_ajax.php",
        data: {
            r0: "inputEditVariable", r1: "Calendar",
            callFunction: callFunction,
            message: message
        },
        dataType: 'text',
        success: function (data) {
            integrationsScriptCSS("BlockAPP", data)
        }
    });
}

function _G_foundCheckedRowInGrid_returnObject(idGreed) { //возвращает массив выбранных элементов
    var nameObject = "#" + idGreed;
    var allCheckedLS = $(nameObject).find(".id").find("input");
    var retData = {};
    var j = 0;
    for (var i = 0; i < allCheckedLS.length; i++) {
        if (allCheckedLS[i].checked == true) {
            retData[j] = allCheckedLS[i].name;
            j++;
        }
    }
    if ($.isEmptyObject(retData)) {
        return false;
    } else {
        return retData;//возвращает массив выбранных элементов
    }
}

function _G_getReport(params)
{
    BlockAPP();
    BlockAPPWait();
    var data = {};

    for (var key in params) {
        data[key] = params[key]
        if (key == 'greed'){
            data['insertedRow'] = insertedRow = _G_foundCheckedRowInGrid_returnObject(params[key]);
        }
    }
    data['r1'] = "prepareData";


    _G_Ajax({
        type: "POST",
        url: "index_ajax.php",
        data: data,
        dataType: 'text',
        timeout: 1800000,
        success: function (data) {
            console.log(data)
            if (params.wait == '1') {
                closeBlockAPP();
                _G_downloadReport(data);
            }else {
                _G_BlockAppMessage('Задание для формирования отчета поставлено!')
            }
            closeBlockAPP();
        }
    });
}


function _G_reactionToRightArrow(idElementR,idElementL,e)
{
    if (e.code == 'ArrowDown')
        $(idElementR).focus();

    if (e.code == 'ArrowUp')
        $(idElementL).focus();
}

function _G_reactionToDownEnter(idElementR,e)
{
    if ((e.code == 'Enter') || (e.code == 'NumpadEnter')){
        $(idElementR).focus();
        e.preventDefault();
        return false;

    }
}
function _G_mouseDown_for_moveWindow(moveIdBlock)
{
    $("#head__"+moveIdBlock).mousedown(function(event) {
        _G_moveIdBlock = moveIdBlock;
        _G_mouseMemX = event.pageX;
        _G_mouseMemY = event.pageY;
        var topLeft = $("#WinMain__"+moveIdBlock).offset();
        _G_windowMemX = topLeft['left'];
        _G_windowMemY = topLeft['top'];
    });
}

$( document).mouseup(function() {
    _G_moveIdBlock = false;
});

$(document).mousemove(function (event) {
    if (_G_moveIdBlock !== false){
        var deltaX = (event.pageX -_G_mouseMemX);
        var deltaY = (event.pageY -_G_mouseMemY);
        var X =  _G_windowMemX + deltaX;
        var Y =  _G_windowMemY + deltaY;
        $("#WinMain__"+_G_moveIdBlock).offset({top:Y, left:X})
    }
});

function _G_InputNumber(caption,callFunction)
{
    _G_varVal = '';
    BlockAPP();
    $.ajax({
        type: "GET",
        url: "index_ajax.php",
        dataType: 'text',
        data: {parent:"Mobile",r0: "inputData",
            caption:caption,
            callFunction:callFunction
        },
        success: function (data) {
            integrationsScriptCSS('BlockAPP', data);
        }
    });
}

function _G_pressEnter(IdElement,runFunction)
{
    $('#'+IdElement).keypress( function(e) {
        if (event.keyCode==13) {
            eval(runFunction);
        }
    });
}


function _G_PrintPdf(url) {
    var iframe = document.createElement('iframe');
    // iframe.id = 'pdfIframe'
    iframe.className='pdfIframe'
    document.body.appendChild(iframe);
    iframe.style.display = 'none';
    iframe.onload = function () {
        setTimeout(function () {
            iframe.focus();
            iframe.contentWindow.print();
            URL.revokeObjectURL(url)
            document.body.removeChild(iframe)
        }, 1);
    };
    iframe.src = url;
    URL.revokeObjectURL(url)
}

function loadscript() {

}