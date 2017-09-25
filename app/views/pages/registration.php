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
                Введите логин, пароль и телефон
            </div>
            <form class="form-horizontal" action="/registration" method="post">
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
                    <div class="clearfix"></div><br>

                    <div class="input-group input-group-lg">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-phone red"></i></span>
                        <input type="phone" class="form-control" placeholder="Телефон в формате 79999999999" name="phone">
                    </div>
                    <div class="clearfix"></div><br>

                    <p class="center col-md-5">
                        <button type="submit" class="btn btn-primary" name="reg">Зарегистрироваться</button>
                        <button type="submit" class="btn btn-primary" name="submit">Войти</button>
                    </p>
                </fieldset>
            </form>
        </div>
        <!--/span-->
    </div><!--/row-->
    </div><!--/fluid-row-->

<?php
if(isset($_POST['reg'])){
    $params = array();
    if(empty($_POST['login'])){
        echo "Введите Логин";
    } else{
        if(empty($_POST['password'])){
            echo "Введите пароль";
        } else{
            if(empty($_POST['phone'])){
                echo "Введите телефон";
            } else {
                $is_correct_phone = (bool)preg_match('/^(7){1}+(9){1}+\d{9}$/',$_POST['phone']);
                if($is_correct_phone == true){
                    $conn = new models\Connection();
                    $conn->connect();
                    $tableUser = new models\Query("user");

                    $params = array();
                    $params['login'] = $_POST['login'];
                    $tmp_sql_query = $tableUser->selectDataWithParametrs($params);
                    $tmp_result = $conn->exec($tmp_sql_query,true);
                    if(count($tmp_result)<1){
                        $params['password'] = hash('whirlpool',$_POST['password']);
                        $params['phone'] = $_POST['phone'];

                        $fields = array();
                        $fields['login'] = "login";
                        $fields['password'] = "password";
                        $fields['phone'] = "phone";

                        $sql_query = $tableUser->insertDataWithParametrs($fields,$params);
                        $result = $conn->exec($sql_query,false);

                        if(count($result)>0){
                            echo "Вы зарегестрировались! ";
                            echo "Перейдите по ссылке для <a href='/login'>авторизации</a>";
                            exit();
                        }
                    } else {
                        echo "Такой логин уже существует! Придумайте другой логин. Если у вас уже есть логин перейдите по ссылке для <a href='/login'>авторизации</a>";
                        exit();
                    }
                } else {
                    echo "Введите телефон в формате 79ххххххххх";
                }
            }
        }
    }
}
if(isset($_POST['submit'])){
    header("Location:/login");
    exit();
}
?>

<?php
include "/app/views/footer.php";
?>