<style>
    .backgroundCalendar{
        position:relative;
        width: 265px;
        display:none;
        box-shadow:rgba(0, 0, 0, 0.3) 5px 5px 30px;
        /*.backgroundNormal*/
    }
    .inputYear{
        /*.*/
        /*.textColorBlack*/
        position: relative;
        width: 50px;
        border: none;
        background: none;
    }

</style>

<?php
print $HTML;
?>

<script>
    function clickMonth(mainID,inpMonth,textMonth,func,caption,replaceCaption){
        inpMonth=inpMonth+1;
        var ob=$("#"+mainID);
        var ob0 = ob.find("#ButtonCalendar");

        var ob2=ob0.find("td");
        var ob3=ob.find("#secondaryDateYear_"+mainID);
        newSysYear=((ob3.val()-2006)*12)+inpMonth;
        _G_varVal = newSysYear;

        if (!replaceCaption)
            txt = caption+" "+textMonth+" "+ob3.val();
        else
            txt = textMonth+" "+ob3.val();
        ob2.html(txt);
        /*
         $("#"+mainID).children("#mainDateYear").val();
         $("#"+mainID).children("#mainDateMonth").val(textMonth);
         */
        closeCalendar(mainID);

        eval(func);
    }


    function clickYear(mainID)
    {
        $("#"+mainID).children("#ButtonCalendar").slideUp(0);
        setTimeout(function(){$("#"+mainID).children("#BodyCalendar").slideDown(200);}, 5);

    }


    function closeCalendar(mainID)
    {
        $("#"+mainID).children("#BodyCalendar").slideUp(200);
        setTimeout(function(){$("#"+mainID).children("#ButtonCalendar").slideDown(0);}, 200);
    }


    function closeCalendar_infinity(mainID,func)
    {
        _G_varVal = 99999;
        eval(func);
        closeCalendar(mainID);
    }


    function plusYear(mainID) {
        var ob=$("#"+mainID);
        var ob0 = ob.find("#secondaryDateYear_"+mainID);
        var secYear = ob0.val()-0;
        secYear=0+secYear+1;
        ob0.val(secYear);

    }
    function minusYear(mainID) {
        var ob=$("#"+mainID);
        var ob0 = ob.find("#secondaryDateYear_"+mainID);
        var secYear = ob0.val()-0;
        secYear=0+secYear-1;
        ob0.val(secYear);
    }
</script>