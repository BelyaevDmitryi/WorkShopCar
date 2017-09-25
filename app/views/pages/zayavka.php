<?php
include "/app/views/header.php";
?>

<head>
    <style type="text/css">
        textarea {
            resize: none;
        }
    </style>
</head>

<body>
    <!-- topbar starts -->
    <div class="navbar navbar-default" role="navigation">

        <div class="navbar-inner">
            <button type="button" class="navbar-toggle pull-left animated flip">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">
                <span>WorkShopCar</span></a>

            <div class="btn-group pull-right">
                <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    <i class="glyphicon glyphicon-user"></i>
                    <span class="hidden-sm hidden-xs">
                    <?php 
                    echo $_SESSION['login'];
                    ?>    
                    </span>
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <li><a href='/profile'>Профиль</a></li>
                    <li class="divider"></li>
                    
                    <?php
                    echo "<li><a href='/logout'>Выйти</a></li>";
                    ?>
                </ul>
            </div>
            <!-- user dropdown ends -->
        </div>
    </div>
    <!-- topbar ends -->
<div class="ch-container">
    <div class="row">
        
        <!-- left menu starts -->
        <div class="col-sm-2 col-lg-2">
            <div class="sidebar-nav">
                <div class="nav-canvas">
                    <div class="nav-sm nav nav-stacked">

                    </div>
                    <ul class="nav nav-pills nav-stacked main-menu">
                        <li class="nav-header">Меню</li>
                        <li><a class="ajax-link" href="/"><i class="glyphicon glyphicon-home"></i><span> Главная</span></a>
                        </li>
                        <li><a class="ajax-link" href="/zayavka"><i class="glyphicon glyphicon-plus"></i><span> Подать заявку</span></a>
                        </li>
                        <li><a class="ajax-link" href="/allzayavki"><i class="glyphicon glyphicon-list-alt"></i><span> Все заявки</span></a>
                        </li>
                        <li><a class="ajax-link" href="/logout"><i class="glyphicon glyphicon-lock"></i><span> Выйти</span></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!--/span-->
        <!-- left menu ends -->
        <div id="content" class="col-lg-10 col-sm-10">
        <div class="row">
            <div class="col-md-12 center login-header">
                <h2>Ремонтная автомастеская WorkShopCar!</h2>
            </div>
            <!--/span-->
        </div><!--/row-->
        <div class="well col-md-5 center login-box">
            <div class="alert alert-info">
                Для подачи заявки заполните поля.<br>Все поля (кроме фотографии) обязательны для заполнения
            </div>
            <form class="form-horizontal" enctype="multipart/form-data" action="/addzayavka" method="post">
                <fieldset>
                    <div class="input-group input-group-lg">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-file red"></i></span>
                        <input type="text" class="form-control" placeholder="Название заявки" name="name">
                    </div>
                    <div class="clearfix"></div><br>

                    <div class="input-group input-group-lg">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-phone red"></i></span>
                        <input type="phone" class="form-control" placeholder="Контактный телефон" name="phone">
                    </div>
                    <div class="clearfix"></div><br>

                    <div class="input-group input-group-lg">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-pencil red"></i></span>
                        <textarea type="textarea" class="form-control" placeholder="Описание проблемы" name="description" ></textarea>
                    </div>
                    <div class="clearfix"></div><br>

                    <!-- Поле MAX_FILE_SIZE должно быть указано до поля загрузки файла -->
                    <input type="hidden" name="MAX_FILE_SIZE" value="6291456" />

                    <div class="input-group input-group-lg">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-camera red"></i></span>
                        <input type="file" class="form-control" accept="image/jpg, image/jpeg, image/png" name="image">
                    </div>
                    <div class="clearfix"></div><br>

                    <p class="center col-md-5">
                        <button type="submit" class="btn btn-primary" name="send">Подать заявку</button>
                    </p>
                </fieldset>
            </form>
        </div>
        </div>
    </div>
<?php
include "/app/views/footer.php";
?>

<?php
if(!empty($_SESSION['error_zayavka'])){
    echo $_SESSION['error_zayavka'];
    $_SESSION['error_zayavka']="";
}
?>