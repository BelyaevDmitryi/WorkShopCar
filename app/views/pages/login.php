<?php
include "/app/views/header.php";
?>

<body>
<div class="ch-container">
    <div class="row">
        
    <div class="row">
        <div class="col-md-12 center login-header">
            <h2>Ремонтная автомастеская WorkShopCar!</h2>
        </div>
        <!--/span-->
    </div><!--/row-->

    <div class="row">
        <div class="well col-md-5 center login-box">
            <div class="alert alert-info">
                Введите логин и пароль
            </div>
            <form class="form-horizontal" action="/login" method="post">
                <fieldset>
                    <div class="input-group input-group-lg">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user red"></i></span>
                        <input type="text" class="form-control" placeholder="Логин" name="login">
                    </div>
                    <div class="clearfix"></div><br>

                    <div class="input-group input-group-lg">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock red"></i></span>
                        <input type="password" class="form-control" placeholder="Пароль" name="password">
                    </div>
                    <div class="clearfix"></div>

                    <p class="center col-md-5">
                        <button type="submit" class="btn btn-primary" name="submit">Войти</button>
                        <button type="submit" class="btn btn-primary" name="reg">Регистрация</button>
                    </p>
                </fieldset>
            </form>
        </div>
        <!--/span-->
    </div><!--/row-->
    </div><!--/fluid-row-->


<?php
include "/app/views/footer.php";
?>

<?php
if(!empty($_SESSION['error_login'])){
    echo $_SESSION['error_login'];
    $_SESSION['error_login']="";
}
?>