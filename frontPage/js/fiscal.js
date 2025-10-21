/**
 * Created by rezzalbob on 28.08.2020.
 */
function _G_callFiscalWebServer(data)
{

    arrayData = JSON.parse(data);
    var callFunction = arrayData['callFunction'];
    var blockHTML = arrayData['blockHTML'];
    if (arrayData['stop'] == 0) {
        var data0 = arrayData['data'];
        var hostRequest = 'http://' + arrayData['server'] + ':' + arrayData['port'] + arrayData['_url'];
        var xhr = new XMLHttpRequest();
        // xhr.withCredentials = false;
        xhr.open(arrayData['method'], hostRequest, true);
        // xhr.setRequestHeader('Access-Control-Allow-Headers', '*');
        // xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
        xhr.setRequestHeader("Content-Type", "application/json");

        xhr.setRequestHeader("Accept", "text/json");
        xhr.onreadystatechange = function () { // (3)
            if (xhr.readyState != 4) {
                return;
            }
            if (xhr.readyState == 4) {
                //console.log("OK");
                setTimeout(_G_getStatusFiscalWebServer, 2000, data);
            }
            if (xhr.status == 200) {
                console.log("ERR");
                setTimeout(_G_getStatusFiscalWebServer, 2000, data);
            }
        };
        //console.log('------------------',data0)
        xhr.send(data0); // (1)s
    }
    // Спорный момент по поводу мгновенной остановки
    // необходим запуск чего либо знаменующее конец работе
    else {
        if (callFunction != "NULL") {
            callFunction = callFunction + "();";
            eval(callFunction)
        }
        if (blockHTML != "NULL") {
            integrationsScriptCSS(blockHTML, dataResSend)
        }
    }
}


function _G_getStatusFiscalWebServer(data) {
    arrayData = JSON.parse(data);
    //console.log(arrayData);
    var hostRequest = 'http://' + arrayData['server'] + ':' + arrayData['port'] + '/requests/' + arrayData['_uuid'];
    _G_Ajax({
        url: hostRequest,
        type: "GET",
        accepts: "application/json, text/javascript",
        contentType: "application/json",
        dataType: "text",
        cache: false,
        success: function (dataResStatusOperation) {
            _G_returnStatusToServer(data, dataResStatusOperation);
        }

    });
}

function _G_returnStatusToServer(data, dataRes) {
    arrayData = JSON.parse(data);

    var callFunction = arrayData['callFunction'];
    var blockHTML = arrayData['blockHTML'];
    var _uuid = arrayData['_uuid'];
    _G_Ajax({
        type: "GET",
        url: "index_ajax.php",
        data: {
            parent: arrayData['objectParentName'],
            r0: arrayData['objectName'],
            r1: arrayData['callMethod'],
            _uuid: arrayData['_uuid'],
            answer: dataRes
        },
        dataType: 'text',
        success: function (dataResSend) {
            if (callFunction != "NULL") {
                callFunction = callFunction + " ( dataResSend );";
                eval(callFunction)
            }
            if (blockHTML != "NULL") {
                integrationsScriptCSS(blockHTML, dataResSend)
            }
        }
    });
}