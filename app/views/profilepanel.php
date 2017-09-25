<?php
//header("Content-Type: image/jpeg");
?>
<div class="row">
    <div class="box col-md-12">
        <div class="box-inner">
            <div class="box-header well" data-original-title="">
                <h2><i class="glyphicon glyphicon-user"></i> Профиль</h2>

            </div>
            <div class="box-content">
                <div class="row">
                    <div class="col-md-12"><h4>Данные профиля</h4>
                        Login: <?php echo $_SESSION['login']; ?><br>
                        Телефон при регистрации:
                        <?php
                        $conn = new models\Connection();
                        $conn->connect();

                        $tableUser = new models\Query("user");
                        $params = array();
                        $params['login'] = $_SESSION['login'];
                        $sql_query = $tableUser->selectColumnWithParametrs("phone",$params);
                        $userPhone = $conn->exec($sql_query,true);
                        echo $userPhone[0]['phone'];
                        ?>
                    </div>
                    <div class="col-md-12">
                        <?php
                        include "/app/views/tablepanel.php";
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/span-->
</div><!--/row-->