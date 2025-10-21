<style>
    /* Кнопка выпадающего списка */
    .dropbtn {
        background-color: #daeaff;
        border-collapse: separate;
        color: #436dac;
        padding: 2px;
        font-size: 16px;
        border: none;
        cursor: pointer;
        width: 300px;
        height: 55px;
    }

    /* Кнопка выпадающего меню при наведении и фокусировке */
    .dropbtn:hover, .dropbtn:focus {
        background-color: #7ab8ff;

    }

    /* Поле поиска */
    #myInput {
        box-sizing: border-box;
        background-image: url('\\frontPage\\img\\searchicon.png');
        background-position: 14px 12px;
        background-repeat: no-repeat;
        font-size: 16px;
        padding: 14px 20px 12px 45px;
        border: none;
        border-bottom: 1px solid #ddd;
    }

    /* Поле поиска, когда он получает фокус/нажал на */
    #myInput:focus {outline: 3px solid #ddd;}

    /* Контейнер <div> - необходим для размещения выпадающего содержимого */
    .dropdown {
        position: relative;
        display: inline-block;
    }

    /* Выпадающее содержимое (скрыто по умолчанию) */
    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f6f6f6;
        min-width: 230px;
        border: 1px solid #ddd;
        z-index: 1;
    }

    /* Ссылки внутри выпадающего списка */
    .dropdown-content a {
        background-color: #e2f1fb;
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }

    /* Изменение цвета выпадающих ссылок при наведении курсора */
    .dropdown-content a:hover {
        background-color: #7ab8ff
    }

    /* Показать выпадающее меню (используйте JS, чтобы добавить этот класс в .dropdown-content содержимого, когда пользователь нажимает на кнопку выпадающего списка) */
    .show {display:block;}
</style>

<?php
print "$HTML";
?>

<script>
    /* Когда пользователь нажимает на кнопку,
    переключение между скрытием и отображением раскрывающегося содержимого */
    function myFunction() {
        document.getElementById("myDropdown").classList.toggle("show");
    }

    function filterFunction() {
        var input, filter, ul, li, a, i;
        input = document.getElementById("myInput");
        filter = input.value.toUpperCase();
        div = document.getElementById("myDropdown");
        a = div.getElementsByTagName("a");
        for (i = 0; i < a.length; i++) {
            txtValue = a[i].textContent || a[i].innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                a[i].style.display = "";
            } else {
                a[i].style.display = "none";
            }
        }
    }
    function SelectListFilter(id,name,callBackFunction)
    {
        _G_id = id
        _G_value0 = name
        btn  = document.getElementById("btnDropdown");
        btn.innerText = name
        myFunction()
        eval(callBackFunction)
    }
</script>
