<!DOCTYPE html>
<html>
<head>
    <!--Import Google Icon Font-->
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.6/css/materialize.min.css">
    <title>Парсинг Email</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <style>
        #request {
            margin-top: 15px;
        }

        #host {
            margin-top: 15px;
        }

        .tabs .tab a.active {
            background: #ee6e73;
            color: #fff;
        }

        #message {
            display: none;
        }

        .collection-item {
            min-height: 60px;
            list-style-type: none;
        }
    </style>


</head>

<body>


<div class="container">
    <div class="row">
        <div class="col s12">
            <div class="card-panel teal">
                <span class="white-text">
                     Описание
                </span>
                <a style="position: relative; top: -8px;"
                   id="button_work"
                   class="waves-effect waves-light btn right orange darken-4 ">
                    В работе
                </a>
            </div>
        </div>
    </div>


    <div class="col s12 m8 offset-m2 l6 offset-l3" id="message">
        <div class="card-panel  z-depth-1 ">
            <div class="collection col s12">

            </div>
        </div>
    </div>


    <div class="progress" id="progressbar">
        <div class="determinate" style="width: 0%"></div>
    </div>

    <div class="row">
        <div class="col s12">
            <ul class="tabs">
                <li class="tab col s3"><a href="#host">По доменам</a></li>
                <li class="tab col s3"><a href="#request">По запросам</a></li>
            </ul>
        </div>
        <div class="col s12">
            <div class="row">
                <div class="col s12">
                    <div class="card-panel red">
                      <span class="white-text">
                          Ссылка должна быть в формате http://{домен} вконце без "/"
                      </span>
                    </div>
                </div>
            </div>
            <div class="row">
                <form class="col s12" method="post" id="host">
                    <input type="hidden" name="type" value="host"/>
                    <div class="row">
                        <div class="input-field col s6">
                            <textarea id="textarea" name="text" required class="materialize-textarea"></textarea>
                            <label for="textarea">Введите запросы</label>
                        </div>
                        <div class="input-field col s6">
                            <div class="row">
                                <div class="col s12">
                                    <input id="email" type="email" name="email" required class="validate">
                                    <label for="email" data-error="Ошиюка" data-success="Все верно">Введите имейл для
                                        получения отчета</label>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col s12">
                                    <button id="start" style="float:right;" class="btn waves-effect waves-light"
                                            type="button"
                                            name="action">Старт
                                        <i class="material-icons right">send</i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
            </div>


        </div>
        <div id="request" class="col s12">

            <div class="row">
                <form class="col s12" method="post" id="request">
                    <input type="hidden" name="type" value="request"/>
                    <div class="row">
                        <div class="input-field col s6">
                            <textarea id="textarea1" name="text" required class="materialize-textarea"></textarea>
                            <label for="textarea1">Введите запросы (максимум 5 запросов за 1 раз)</label>
                        </div>
                        <div class="col s6">
                            <div class="row">
                                <div class="input-field col s12">
                                    <input id="email" type="email" name="email" required class="validate">
                                    <label for="email" data-error="Ошиюка" data-success="Все верно">Введите имейл для
                                        получения отчета</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s12">
                                    <input id="count" value="10" name="count" type="text" required class="validate">
                                    <label for="count">Сколько сайтов парсить? (от 1 до 100)</label>
                                </div>
                            </div>
                            <div class="row">


                                <div class="col s12">
                                    <button id="start" style="float:right;" class="btn waves-effect waves-light"
                                            type="button"
                                            name="action">Старт
                                        <i class="material-icons right">send</i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
            </div>

        </div>
    </div>

    <div class="row hide" id="in_work">
        <div class="col s12">
            <h2>Процессы в работе</h2>
            <table class="centered">
                <thead>
                <tr>
                    <th data-field="id">#</th>
                    <th data-field="url">Type</th>
                    <th data-field="name">Первый Domain/Request</th>
                    <th data-field="price">E-mail менеджера</th>
                    <th data-field="id">Time</th>
                </tr>
                </thead>
                <tbody id="add_td">


                </tbody>
            </table>

        </div>

    </div>

    <?php if (isset($lists)): ?>

        <div class="row">
            <div class="col s12">
                <div class="progress">
                    <div class="determinate" style="width: 100%"></div>
                </div>
            </div>
            <div class="col s12">


                <table class="centered">
                    <thead>
                    <tr>
                        <th data-field="id">ID</th>
                        <th data-field="url">URL</th>
                        <th data-field="name">TITLE</th>
                        <th data-field="price">EMAIL</th>
                        <th data-field="price">STATUS</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($lists as $k => $list): ?>
                        <tr <?php
                        if ($list['status'] == 0) {
                            echo 'style="background: red;
    color: #fff;"';
                        } ?>>
                            <td><?php echo $k + 1; ?></td>
                            <td><?php echo $list['url']; ?></td>
                            <td><?php echo $list['title']; ?></td>
                            <td><?php echo $list['email']; ?></td>
                            <td><?php
                                if ($list['status'] == 0)
                                    echo 'Нет email';
                                else
                                    echo 'Есть';
                                ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>


            </div>

        </div>
    <?php endif; ?>

</div>

<!--Import jQuery before materialize.js-->
<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
<!-- Compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.6/js/materialize.min.js"></script>
<script>

    $(document).ready(function () {
        $('ul.tabs').tabs();
        $('ul.tabs li').on('click', function () {
            console.log('111');
            $('#textarea1').trigger('autoresize');
        });
        $('#textarea').trigger('autoresize');

    });

</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/piecon/0.5.0/piecon.min.js"></script>
<script>

    var in_process = false;
    var time = <?php echo time(); ?>;

    function updateTable(){
        $.ajax({
            url: window.location.href,
            method: "GET",
            data: {
                "type": "in_work",
                "time": time
            },
            dataType: 'html',
            success: function (html) {

                /*$.each(json, function(arrayID,group) {
                    var n = parseInt($('#add_td tr').last().find('td').first().text());
                    if(isNaN(n)){
                        n = 0;
                    }
                    var html = '<tr>';
                    html += '<td>'+(n+1)+'</td>';
                    html += '<td>'+group[2]+'</td>';
                    html += '<td>'+decodeURIComponent(group[3])+'</td>';
                    html += '<td>'+group[4]+'</td>';
                    html += '</tr>';
                    

                });*/
                $('#add_td').html(html);
            },
            error: function (xhr, status, errorThrown) {

            }

        });
    }
    $(function () {

        $("#progressbar").hide('fast');


        $('#button_work').on('click', function (event) {
            $("#in_work").removeClass('hide');
            $('html,body').animate({
                    scrollTop: $("#in_work").offset().top},
                'slow');
            setInterval(updateTable, 1000);


        });


        $('#request #start').on('click', function (event) {
            $("#message .collection").html("");
            var textarea = $("#request #textarea1").val();
            var email = $("#request #email").val();
            $("#message").hide();

            if (textarea == '' || typeof textarea == 'undefined') {
                $("#message").show();
                $("#message .collection").append('<li class="collection-item">Введите ссылки для запроса</li>');
                return false;
            }
            if (email == '' || typeof email == 'undefined') {
                $("#message").show();
                $("#message .collection").append('<li class="collection-item">Введите email</li>');
                return false;
            }

            $("#progressbar .determinate").css({"width": "0%"});
            Piecon.setProgress(0);
            $("#progressbar").show('fast');

            $.ajax({
                url: window.location.href,
                method: "POST",
                data: {
                    "type": "request",
                    "text": $("#request #textarea1").val(),
                    "email": email,
                    "count": $("#request #count").val()
                },
                dataType: 'json',
                success: function (data) {

                    var file = (Date.now().toString(36) + Math.random().toString(36).substr(2, 5)).toUpperCase();
                    var items = data.items;
                    $("#message").show();

                    if (typeof data.error == 'undefined') {
                        var results = [];
                        $("#message .collection").append('<li class="collection-item">Обработка запроса...</li>');
                        do_export({"items":items, "file":file, "page":0, "results":results, "email":email, "typeMethod":"request"});
                    } else {
                        $("#message .collection").append('<li class="collection-item">' + data.error + '</li>');
                    }
                },
                error: function (xhr, status, errorThrown) {
                    $("#message .collection").append('<li class="collection-item">Ошибка запроса</li>');
                }

            });

            return false;

        });


        $('#host #start').on('click', function (event) {
            event.stopPropagation();
            $("#message .collection").html("");
            var textarea = $("#host #textarea").val();
            var email = $("#host #email").val();
            $("#message").hide();

            if (textarea == '' || typeof textarea == 'undefined') {
                $("#message").show();
                $("#message .collection").append('<li class="collection-item">Введите ссылки для запроса</li>');
                return false;
            }
            if (email == '' || typeof email == 'undefined') {
                $("#message").show();
                $("#message .collection").append('<li class="collection-item">Введите email</li>');
                return false;
            }

            $("#progressbar .determinate").css({"width": "0%"});
            $("#progressbar").show('fast');
            $.ajax({
                url: window.location.href,
                method: "POST",
                data: {
                    "type": "host",
                    "text": $("#host #textarea").val(),
                    "email": email
                },
                dataType: 'json',
                success: function (data) {
                    var file = (Date.now().toString(36) + Math.random().toString(36).substr(2, 5)).toUpperCase();
                    var items = data.items;
                    var results = [];


                    $("#message .collection").append('<li class="collection-item">Обработка запроса...</li>');
                    $("#message").show();


                    do_export({"items":items, "file":file, "page":0, "results":results, "email":email, "typeMethod":"host"});

                },
                error: function (xhr, status, errorThrown) {
                    $("#message .collection").append('<li class="collection-item">Ошибка запроса</li>');
                }

            });

            return false;
        });


        function do_export(inputData) {

            var totalpages = inputData.items.length;

            try {
                $.ajax({
                    url: window.location.href,
                    method: "POST",
                    data: {
                        "type": "checkEmail",
                        "item": JSON.stringify(inputData.items[inputData.page]),
                        "file": inputData.file,
                        "email": inputData.email,
                        "typeMethod": inputData.typeMethod
                    },
                    dataType: 'json',
                    success: function (data) {

                        if (typeof inputData.items[inputData.page] != 'undefined') {

                            try {

                                if(typeof data.status == 'undefined') {
                                    for (var i = 0; i < data.length; i++) {
                                        inputData.results.push(data[i]);
                                        $("#message .collection").append('<li class="collection-item avatar">' + decodeURIComponent(data[i].message) + '</li>');
                                    }
                                }else{
                                    inputData.results.push(data);
                                    $("#message .collection").append('<li class="collection-item avatar">' + decodeURIComponent(data.message) + '</li>');
                                }

                            } catch (err) {

                            }

                            inputData.page = inputData.page+1;

                            do_export(inputData);
                            Piecon.setProgress(Math.round(100 * (inputData.page + 1) / totalpages));
                            var p = 100 * (inputData.page + 1) / totalpages;
                            $("#progressbar .determinate").css({"width": p + "%"});
                        }
                        else {
                            if (typeof inputData.items[inputData.page] == 'undefined') {
                                $.ajax({
                                    url: window.location.href,
                                    method: "POST",
                                    data: {
                                        "type": "writeEmail",
                                        "items": JSON.stringify(inputData.results),
                                        "file": inputData.file,
                                        "email": inputData.email
                                    },
                                    dataType: 'json',
                                    success: function (data) {

                                        $("#message .collection").append('<li class="collection-item avatar">' + decodeURIComponent(data.message) + '</li>');

                                        $("#host #textarea").val("");
                                        $("#host #email").val("");
                                        $("#request #textarea").val("");
                                        $("#request #email").val("");


                                    },
                                    error: function (xhr, status, errorThrown) {
                                        $("#message .collection").append('<li class="collection-item avatar">' + decodeURIComponent(xhr.responseText) + '</li>');
                                        $("#host #textarea").val("");
                                        $("#host #email").val("");
                                        $("#request #textarea").val("");
                                        $("#request #email").val("");

                                    }
                                });

                                Piecon.setProgress(100);
                                $("#progressbar .determinate").css({"width": "100%"});

                            }
                        }

                    },
                    error: function (xhr, status, errorThrown) {
                        console.log(xhr);
                        console.log(status);
                        console.log(errorThrown);
                        $("#message .collection").append('<li class="collection-item">Ошибка запроса</li>');
                    }
                });
            } catch (e) {

            }
        }

    });

</script>
</body>
</html>
