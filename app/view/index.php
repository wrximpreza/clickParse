<!DOCTYPE html>
<html>
<head>
    <!--Import Google Icon Font-->
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.6/css/materialize.min.css">
    <title>Парсинг Email</title>

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
            </div>
        </div>
    </div>
    <?php if($this->msg->hasMessages()): ?>
        <!--<div class="col s12 m8 offset-m2 l6 offset-l3">
            <div class="card-panel grey lighten-5 z-depth-1">
                <div class="row valign-wrapper">
                    <div class="col s12">
                      <span class="black-text">

                      </span>
                    </div>
                </div>
            </div>
        </div>-->
        <div class="col s12 m8 offset-m2 l6 offset-l3">
            <div class="card-panel  z-depth-1 ">
                <div class=" valign-wrapper">
                    <div class="col s12">
                  <span class="black-text ">
                     <?php echo $this->msg->display();?>
                  </span>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

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
        <div  class="col s12">
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
                                    <input id="email" type="email" name="email"  required class="validate">
                                    <label for="email" data-error="Ошиюка"  data-success="Все верно">Введите имейл для
                                        получения отчета</label>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col s12">
                                    <button id="start"   style="float:right;" class="btn waves-effect waves-light" type="button"
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
                            <label for="textarea1">Введите запросы</label>
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
                                    <input id="count" value="10" name="count" type="text"  required class="validate">
                                    <label for="count">Сколько сайтов парсить? (от 1 до 999)</label>
                                </div>
                            </div>
                            <div class="row">


                                <div class="col s12">
                                    <button id="start"  style="float:right;" class="btn waves-effect waves-light" type="button"
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

    <?php if(isset($lists)): ?>

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
                <?php foreach($lists as $k=>$list): ?>
                        <tr <?php
                    if($list['status'] == 0){ echo 'style="background: red;
    color: #fff;"'; }?>>
                            <td><?php echo $k+1; ?></td>
                            <td><?php echo $list['url']; ?></td>
                            <td><?php echo $list['title']; ?></td>
                            <td><?php echo $list['email']; ?></td>
                            <td><?php
                                if($list['status'] == 0)
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

    $(function() {

        $("#progressbar").hide('fast');

        $('#request #start').on('click', function(event) {
            event.stopPropagation();

            $("#progressbar .determinate").css({"width": "0%"});

            $("#progressbar").show('fast');

            $.ajax({
                url: window.location.href,
                method:"POST",
                data: {
                    "type": "request",
                    "text": $("#request #textarea1").val(),
                    "email":$("#request #email").val(),
                    "count":$("#request #count").val(),
                },
                dataType: 'json',
                success: function(data){

                    //do_export(1, data);

                },
                error:function(xhr, status, errorThrown) {
                    alert(errorThrown+'\n'+xhr.responseText);
                }

            });

            return false;

        });


        $('#host #start').on('click', function(event) {
            event.stopPropagation();

            $("#progressbar .determinate").css({"width": "0%"});

            $("#progressbar").show('fast');

            $.ajax({
                url: window.location.href,
                method:"POST",
                data: {
                    "type": "host",
                    "text": $("#host #textarea").val(),
                    "email":$("#host #email").val()
                },
                dataType: 'json',
                success: function(data){
                    var file = (Date.now().toString(36) + Math.random().toString(36).substr(2, 5)).toUpperCase();
                    var items = data.items;
                    var results = [];
                    do_export(items, file, 0, results);

                },
                error:function(xhr, status, errorThrown) {
                    alert(errorThrown+'\n'+xhr.responseText);
                }

            });

            return false;
        });


        function do_export(items, file, page, results)
        {

            var totalpages = items.length;

            $.ajax({
                url: window.location.href,
                method:"POST",
                data: {
                    "type": "checkEmail",
                    "item": items[page],
                    "file": file
                },
                dataType: 'json',
                success: function(data){

                    if(items.length != page)
                    {
                        results.push(data);
                        do_export(items, file, page+1, results);
                        Piecon.setProgress(Math.round(100*(page+1)/totalpages));
                        var p = 100*(page+1)/totalpages;
                        $("#progressbar .determinate").css({"width" : p+"%"});
                    }
                    else
                    {
                        if(items.length == page)
                        {
                            $.ajax({
                                url: window.location.href,
                                method:"POST",
                                data: {
                                    "type": "writeEmail",
                                    "items": JSON.stringify(results),
                                    "file": file
                                },
                                dataType: 'json',
                                success: function(data){


                                },
                                error:function(xhr, status, errorThrown) {


                                }


                            });

                            Piecon.setProgress(100);
                            $("#progressbar .determinate").css({"width" : "100%"});

                        }
                    }

                },
                error:function(xhr, status, errorThrown) {

                    return false;
                }


            });
        }

    });

</script>
</body>
</html>
