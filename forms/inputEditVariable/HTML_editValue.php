<code>
    <?php
    print $HTML;
    ?>
</code>
<script>
    function loadscript() {
        _G_mouseDown_for_moveWindow('<?php print $this->objectFullName;?>')
        <?php
        if ($calendarHelp){
            print '$("#inputEditValue").datepicker();';

        }
        if ($this->pattern != false) {
            $commandMask = "$('#inputEditValue').mask('$this->pattern'";
            if ($this->placeholder != false) {
                $commandMask = $commandMask . ",{ $this->placeholder }";
            }
            $commandMask = $commandMask . ");";
            print $commandMask;
        }
        ?>
        $('#inputEditValue').select();
        $('#inputEditValue').focus();
        $("#inputEditValue").keyup(function (event) {
            if (event.keyCode == 13) {
                saveDate();

            }
        });
    }
    function saveDate() {
        _G_varVal = $("#inputEditValue").val();
        BlockAPPWait();
        <?php
        print "$this->callFunction";
        ?>
    }
</script>