<div class="row">
        <div class="box col-md-12">
            <div class="box-inner">
                <div class="box-header well" data-original-title="">
                    <h2>
                    <?php
                    if(isset($_SESSION['login'])){
                        if($_SESSION['login'] == "admin"){
                            echo "Заявки всех пользователей";
                        } else {
                            echo "Ваши заявки";
                        }
                    }
                    ?>
                    </h2>
                </div>
                <div class="box-content">
                    <table class="table table-bordered table-striped table-condensed">
                        <thead>
                        <tr>
                            <th>Пользователь</th>
                            <th>Телефон</th>
                            <th>Название заявки</th>
                            <th>Описание проблемы</th>
                            <th>Фотография проблемы</th>
                            <th>Дата подачи заяки</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if(isset($_SESSION['login'])){
                            if($_SESSION['login'] == "admin"){                                
                                //$params['login'] = $_SESSION['login'];

                                $conn = new models\Connection();
                                $conn->connect();

                                $tableContract = new models\Query("contract");
                                $tableUser = new models\Query("user");

                                $sql_query = $tableContract->selectColumn("login");
                                $userLogin = $conn->exec($sql_query, true);

                                $userLogin = call_user_func_array('array_merge', $userLogin);
                                array_shift($userLogin);

                                //телефон при регистрации
                                $userPhone = array();
                                for($i = 0; $i < count($userLogin); $i++){
                                    $tmp = array();
                                    $tmp['login'] = $userLogin[$i];
                                    $sql_query = $tableUser->selectColumnWithParametrs("phone",$tmp);
                                    $tmpPhone = $conn->exec($sql_query, true);

                                    $tmpPhone = call_user_func_array('array_merge', $tmpPhone);
                                    array_shift($tmpPhone);

                                    $userPhone[] = $tmpPhone[0];
                                }
                                unset($tmpPhone);
                                unset($tmp);                              

                                //телефон при подаче заявки
                                $userPhoneZ = array();
                                $sql_query = $tableContract->selectColumn("phone");
                                $userPhoneZ = $conn->exec($sql_query, true);

                                $userPhoneZ = call_user_func_array('array_merge', $userPhoneZ);
                                array_shift($userPhoneZ);

                                //название заявки
                                $nameZ = array();
                                $sql_query = $tableContract->selectColumn("name");
                                $nameZ = $conn->exec($sql_query, true);

                                $nameZ = call_user_func_array('array_merge', $nameZ);
                                array_shift($nameZ);

                                //описание заявки
                                $descriptionZ = array();
                                $sql_query = $tableContract->selectColumn("description");
                                $descriptionZ = $conn->exec($sql_query, true);

                                $descriptionZ = call_user_func_array('array_merge', $descriptionZ);
                                array_shift($descriptionZ);

                                //фотография проблемы
                                $photoName = array();
                                $sql_query = $tableContract->selectColumn("image_id");
                                $photoName = $conn->exec($sql_query, true);

                                $photoName = call_user_func_array('array_merge', $photoName);
                                array_shift($photoName);


                                $photoType = array();
                                $sql_query = $tableContract->selectColumn("type");
                                $photoType = $conn->exec($sql_query, true);

                                $photoType = call_user_func_array('array_merge', $photoType);
                                array_shift($photoType);

                                $photoDir = array();
                                $sql_query = $tableContract->selectColumn("path");
                                $photoDir = $conn->exec($sql_query, true);

                                $photoDir = call_user_func_array('array_merge', $photoDir);
                                array_shift($photoDir);

                                $photoPath = array();
                                for($i = 0; $i < count($photoName); $i++){
                                    $tmp[] = $photoDir[$i];
                                    $tmp[] = $photoName[$i];
                                    $tmp[] = $photoType[$i];
                                }

                                $tmp1 = "";
                                for($i = 0; $i < count($tmp); $i=$i+3){
                                    $j = $i+2;
                                    $k = $i+1;
                                    if(empty($tmp[$i])){
                                        $tmp1 = "Изображение отсутствует";
                                    } else{
                                        $str = "$tmp[$i]$tmp[$k].$tmp[$j], 1";
                                        $tmp1 = explode(",", $str);
                                        $tmp1 = array_shift($tmp1);
                                    }                                    
                                    $photoPath[] = $tmp1;
                                }

                                unset($tmp1);
                                unset($tmp);
                                unset($str);

                                //дата подачи заявки
                                $dateZ = array();
                                $sql_query = $tableContract->selectColumn("date_create");
                                $dateZ = $conn->exec($sql_query, true);

                                $dateZ = call_user_func_array('array_merge', $dateZ);
                                array_shift($dateZ);

                                $tmp = array();
                                $tmp = implode("|", $dateZ);
                                $tmp = explode("-", $tmp);
                                $tmp = implode("|", $tmp);
                                $tmp = explode("|", $tmp);

                                unset($dateZ);
                                $dateZ = array();
                                $tmp1 = "";
                                for($i = 0; $i < count($tmp); $i=$i+3){
                                    $j = $i + 2;
                                    $k = $i + 1;
                                    $str = "$tmp[$j].$tmp[$k].$tmp[$i], 1"; 
                                    $tmp1 = explode(",", $str);
                                    $tmp1 = array_shift($tmp1);
                                    $dateZ[] = $tmp1;
                                }
                                unset($tmp1);
                                unset($str);
                                
                                for($i = 0; $i < count($userLogin); $i++){
                                    if($i == count($userLogin)-1)
                                    {
                                        echo "<tr class='lastrow'>";
                                        echo "<td>$userLogin[$i]</td>";
                                        echo "<td class='center'>$userPhoneZ[$i]</td>";
                                        echo "<td class='center'>$nameZ[$i]</td>";
                                        echo "<td class='center'>$descriptionZ[$i]</td>";
                                        echo "<td class='center'>";
                                        if(file_exists($photoPath[$i])){
                                            echo '<img src="' . $photoPath[$i] . '" width="300" class="scale-sharp" />';
                                        } else {
                                            echo "Изображение отсутствует";
                                        }
                                        echo "</td>";
                                        echo "<td class='center'>$dateZ[$i]</td>";
                                        echo "</tr>";
                                    } else {
                                        echo "<tr>";
                                        echo "<td>$userLogin[$i]</td>";
                                        echo "<td class='center'>$userPhoneZ[$i]</td>";
                                        echo "<td class='center'>$nameZ[$i]</td>";
                                        echo "<td class='center'>$descriptionZ[$i]</td>";
                                        echo "<td class='center'>";
                                        if(file_exists($photoPath[$i])){
                                            echo '<img src="' . $photoPath[$i] . '" width="300" class="scale-sharp" />';
                                        } else {
                                            echo "Изображение отсутствует";
                                        }
                                        echo "</td>";
                                        echo "<td class='center'>$dateZ[$i]</td>";
                                        echo "</tr>";
                                    }
                                }
                            } else {
                                $conn = new models\Connection();
                                $conn->connect();

                                $tableContract = new models\Query("contract");
                                $tableUser = new models\Query("user");

                                $tmp = array();
                                $tmp['login'] = $_SESSION['login'];

                                $sql_query = $tableContract->selectColumnWithParametrs("login", $tmp);
                                $userLogin = $conn->exec($sql_query, true);

                                $userLogin = call_user_func_array('array_merge', $userLogin);
                                array_shift($userLogin);

                                unset($tmp);

                                //телефон при регистрации
                                $userPhone = array();
                                for($i = 0; $i < count($userLogin); $i++){
                                    $tmp = array();
                                    $tmp['login'] = $userLogin[$i];
                                    $sql_query = $tableUser->selectColumnWithParametrs("phone",$tmp);
                                    $tmpPhone = $conn->exec($sql_query, true);

                                    $tmpPhone = call_user_func_array('array_merge', $tmpPhone);
                                    array_shift($tmpPhone);

                                    $userPhone[] = $tmpPhone[0];
                                }
                                unset($tmpPhone);
                                unset($tmp);                              

                                //телефон при подаче заявки
                                $tmp = array();
                                $tmp['login'] = $_SESSION['login'];

                                $userPhoneZ = array();
                                $sql_query = $tableContract->selectColumnWithParametrs("phone", $tmp);
                                $userPhoneZ = $conn->exec($sql_query, true);

                                $userPhoneZ = call_user_func_array('array_merge', $userPhoneZ);
                                array_shift($userPhoneZ);
                                unset($tmp);

                                //название заявки
                                $tmp = array();
                                $tmp['login'] = $_SESSION['login'];

                                $nameZ = array();
                                $sql_query = $tableContract->selectColumnWithParametrs("name", $tmp);
                                $nameZ = $conn->exec($sql_query, true);

                                $nameZ = call_user_func_array('array_merge', $nameZ);
                                array_shift($nameZ);

                                unset($tmp);

                                //описание заявки
                                $tmp = array();
                                $tmp['login'] = $_SESSION['login'];

                                $descriptionZ = array();
                                $sql_query = $tableContract->selectColumnWithParametrs("description", $tmp);
                                $descriptionZ = $conn->exec($sql_query, true);

                                $descriptionZ = call_user_func_array('array_merge', $descriptionZ);
                                array_shift($descriptionZ);

                                unset($tmp);

                                //фотография проблемы
                                $tmp = array();
                                $tmp['login'] = $_SESSION['login'];

                                $photoName = array();
                                $sql_query = $tableContract->selectColumnWithParametrs("image_id", $tmp);
                                $photoName = $conn->exec($sql_query, true);

                                $photoName = call_user_func_array('array_merge', $photoName);
                                array_shift($photoName);

                                $photoType = array();
                                $sql_query = $tableContract->selectColumnWithParametrs("type", $tmp);
                                $photoType = $conn->exec($sql_query, true);

                                $photoType = call_user_func_array('array_merge', $photoType);
                                array_shift($photoType);

                                $photoDir = array();
                                $sql_query = $tableContract->selectColumnWithParametrs("path", $tmp);
                                $photoDir = $conn->exec($sql_query, true);

                                $photoDir = call_user_func_array('array_merge', $photoDir);
                                array_shift($photoDir);

                                unset($tmp);

                                $photoPath = array();
                                for($i = 0; $i < count($photoName); $i++){
                                    $tmp[] = $photoDir[$i];
                                    $tmp[] = $photoName[$i];
                                    $tmp[] = $photoType[$i];
                                }                                

                                $tmp1 = "";
                                for($i = 0; $i < count($tmp); $i=$i+3){
                                    $j = $i+2;
                                    $k = $i+1;
                                    if(empty($tmp[$i])){
                                        $tmp1 = "Изображение отсутствует";
                                    } else{
                                        $str = "$tmp[$i]$tmp[$k].$tmp[$j], 1";
                                        $tmp1 = explode(",", $str);
                                        $tmp1 = array_shift($tmp1);
                                    }    
                                    $photoPath[] = $tmp1;
                                }
                                unset($tmp1);
                                unset($tmp);
                                unset($str);

                                //дата подачи заявки
                                $tmp = array();
                                $tmp['login'] = $_SESSION['login'];

                                $dateZ = array();
                                $sql_query = $tableContract->selectColumnWithParametrs("date_create", $tmp);
                                $dateZ = $conn->exec($sql_query, true);

                                $dateZ = call_user_func_array('array_merge', $dateZ);
                                array_shift($dateZ);

                                unset($tmp);

                                $tmp = array();
                                $tmp = implode("|", $dateZ);
                                $tmp = explode("-", $tmp);
                                $tmp = implode("|", $tmp);
                                $tmp = explode("|", $tmp);

                                unset($dateZ);
                                $dateZ = array();
                                $tmp1 = "";
                                for($i = 0; $i < count($tmp); $i=$i+3){
                                    $j = $i+2;
                                    $k = $i+1;
                                    $str = "$tmp[$j].$tmp[$k].$tmp[$i], 1"; 
                                    $tmp1 = explode(",", $str);
                                    $tmp1 = array_shift($tmp1);
                                    $dateZ[] = $tmp1;
                                }
                                unset($tmp1);
                                unset($str);

                                for($i = 0; $i < count($userLogin); $i++){
                                    if($i == count($userLogin)-1)
                                    {
                                        echo "<tr class='lastrow'>";
                                        echo "<td>$userLogin[$i]</td>";
                                        echo "<td class='center'>$userPhoneZ[$i]</td>";
                                        echo "<td class='center'>$nameZ[$i]</td>";
                                        echo "<td class='center'>$descriptionZ[$i]</td>";
                                        echo "<td class='center'>";
                                        if(file_exists($photoPath[$i])){
                                            echo '<img src="' . $photoPath[$i] . '" width="300" class="scale-sharp" />';
                                        } else {
                                            echo "Изображение отсутствует";
                                        }
                                        echo "</td>";
                                        echo "<td class='center'>$dateZ[$i]</td>";
                                        echo "</tr>";
                                    } else {
                                        echo "<tr>";
                                        echo "<td>$userLogin[$i]</td>";
                                        echo "<td class='center'>$userPhoneZ[$i]</td>";
                                        echo "<td class='center'>$nameZ[$i]</td>";
                                        echo "<td class='center'>$descriptionZ[$i]</td>";
                                        echo "<td class='center'>";
                                        if(file_exists($photoPath[$i])){
                                            echo '<img src="' . $photoPath[$i] . '" width="300" class="scale-sharp" />';
                                        } else {
                                            echo "Изображение отсутствует";
                                        }
                                        echo "</td>";
                                        echo "<td class='center'>$dateZ[$i]</td>";
                                        echo "</tr>";
                                    }
                                }
                            }
                        }
                        ?>
                        </tbody>
                    </table>
                    <?php
                    if($_SERVER['REQUEST_URI'] == "/allzayavki")
                    {
                        if(isset($_SESSION['login'])){
                            if($_SESSION['login'] == "admin") {
                                echo "<form class='form-horizontal' action='/download' method='post'>";
                                echo "<fieldset>";
                                echo "<p class='center col-md-5'>";
                                echo "<button type='submit' class='btn btn-primary' name='download'>Скачать в Xml</button>";
                                echo "</p>";
                                echo "</fieldset>";
                                echo "</form>";
                            }
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>