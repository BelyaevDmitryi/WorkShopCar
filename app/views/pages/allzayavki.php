<?php
include "/app/views/header.php";
?>

<head>
    <style type="text/css">
    tr.lastrow{
        background: #3986AC;
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
        <?php
        include "/app/views/tablepanel.php";
        ?>        
        </div>
        <div>
            <?php
            if(isset($_SESSION['login'])){
                if($_SESSION['login'] == "admin") {
                    include "/app/models/XmlManipulation.php";
                }
            }
            ?>
        </div>
    </div>

<?php
include "/app/views/footer.php";
?>