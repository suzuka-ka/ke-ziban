<!DOCTTPE html>
<html lang = "ja">
<head>
<meta charset = "utf-8">
<title>mission_5-1</title>
</head>
<body>
    <!--DB-->
    <?php
        //DB接続設定
        $dsn = 'データベース名';
        $user = 'ユーザー名';
        $password = 'パスワード';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

        //もしテーブルtb_mission5がないとき新規作成
        $sql = 'create table if not exists tb_5'
                .'('
                .'id INT PRIMARY KEY,'   //投稿番号
                .'name char (32),'   //半角英数で32字の文字列
                .'comment TEXT,'     //長めの文章も入力できる
                .'date TEXT,'
                .'password char(32)'    //半角英数で32字の文字列
                .');';
        $stmt = $pdo -> query($sql);
    ?>

    <!--ボタン押されたときの処理-->
    <?php
        //送信ボタンを押したとき
        if(isset($_POST['submit']) && $_POST['submit'] === "送信"){
            //echo "0<br>";
            //入力フォームの名前とコメント、パスワードが入力されているとき
            if(isset($_POST['name']) && $_POST["name"] !== ""){
                //echo "0.5<br>";
                if(isset($_POST['comment']) && $_POST['comment'] !== ""){
                    //echo "１<br>";
                    //テーブルtb_mission5にのデータを更新($_POST['hidden']に値があるとき)
                    if(isset($_POST['hidden']) && $_POST['hidden'] !== ""){
                        //echo "1<br>";
                        $sql = 'update tb_5 set name = :name, comment = :comment, date = :date, password = :password where id = :id;';
                        $stmt = $pdo -> prepare($sql);
                        $stmt -> bindParam(':id', $id, PDO::PARAM_INT);
                        $stmt -> bindParam(':name', $name, PDO::PARAM_STR);
                        $stmt -> bindParam(':comment', $comment, PDO::PARAM_STR);
                        $stmt -> bindParam(':date', $date, PDO::PARAM_STR);
                        $stmt -> bindParam(':password', $pass, PDO::PARAM_STR);
                        $id = $_POST['hidden'];
                        $name = $_POST['name'];
                        $comment = $_POST['comment'];
                        $date = date("Y/m/d H:i:s");
                        $pass = $_POST['pass'];
                        $stmt -> execute();
                        //echo "2<br>";
                    }
                    //テーブルtb_mission5に新規登録($_POST['hidden']に値がないとき)
                    else{
                        //現在、何行登録されているか
                        $sql = 'select id from tb_5;';
                        $stmt = $pdo -> query($sql);
                        $result = $stmt -> fetchAll();
                        $i = 0;
                        foreach($result as $row){
                            $i++;
                        }
                        $i++;
                        //最終行の番号と一致しないとき
                        if(isset($row[0]) && $row[0] !== $i){
                            $i = $row[0] + 1;
                        }
                        
                        //新規登録内容
                        $sql = $pdo -> prepare('insert into tb_5(id, name, comment, date, password) values(:id, :name, :comment, :date, :password);');
                        $sql -> bindParam(':id', $id, PDO::PARAM_INT);  //番号の変数
                        $sql -> bindParam(':name', $name, PDO::PARAM_STR);  //名前の変数
                        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);    //コメントの変数
                        $sql -> bindParam(':date', $date, PDO::PARAM_STR);  //日付の変数
                        $sql -> bindParam(':password', $pass, PDO::PARAM_STR);   //パスワードの変数
                        //入力フォームで入力されたそれぞれを変数に代入
                        $id = $i;
                        $name = $_POST['name'];
                        $comment = $_POST['comment'];
                        $date = date("Y/m/d H:i:s");
                        $pass = $_POST['pass'];
                        $sql -> execute();  //実行
                        //echo "2<br>";
                    }
                }
                else{
                    $error1 = "コメントは入力必須です<br>";
                }
            }
            elseif(isset($_POST['comment']) && $_POST["comment"] !== ""){
                $error1 = "名前は入力必須です<br>";
            }
            else{
                $error1 = "名前とコメントは入力必須です<br>";
            }
        }
        //削除ボタンを押したとき
        elseif (isset($_POST['delete']) && $_POST['delete'] === "削除") {
            //echo "0<br>";
            //入力フォームの削除番号とパスワードが入力されているとき
            if(isset($_POST['d-num']) && $_POST['d-num'] !== ""){
                //echo "0.5<br>";
                if(isset($_POST['d-pass']) && $_POST['d-pass'] !== ""){
                    //echo "1<br>";
                    //削除番号とパスワードが投稿番号とパスワードに一致したかどうか
                    $sql = 'select * from tb_5 where id = :id && password = :password;';
                    $stmt = $pdo -> prepare($sql);
                    $stmt -> bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt -> bindParam(':password', $pass, PDO::PARAM_STR);
                    $id = $_POST['d-num'];
                    $pass = $_POST['d-pass'];
                    $stmt -> execute();
                    $result = $stmt -> fetchAll();
                    $i = 0;
                    foreach($result as $row){
                        $i++;
                    }
                    //削除できないとき(削除番号かパスワードが一致しなかった時)
                    if($i === 0){
                        $error2 = "削除番号またはパスワードが間違っていました<br>";
                    }
                    else{
                        //1行を削除(削除番号とパスワードの両方が一致した時)
                        $sql = 'delete from tb_5 where id = :id && password = :password;';
                        $stmt = $pdo -> prepare($sql);
                        $stmt -> bindParam(':id', $id, PDO::PARAM_INT);
                        $stmt -> bindParam(':password', $pass, PDO::PARAM_STR);
                        $id = $_POST['d-num'];
                        $pass = $_POST['d-pass'];
                        $stmt -> execute();
                        //echo "2<br>";
                    }
                }
                else{
                    $error2 = "パスワードは入力必須です<br>";
                }
            }
            elseif(isset($_POST['d-pass']) && $_POST['d-pass'] !== ""){
                $error2 = "削除番号は入力必須です<br>";
            }
            else{
                $error2 = "削除番号とパスワードは入力必須です<br>";
            }
        }
        //編集ボタンを押したとき
        elseif(isset($_POST['hensyu']) && $_POST['hensyu'] !== ""){
            //echo "0<br>";
            //入力フォームの編集番号とパスワードが入力されているとき
            if(isset($_POST['h-num']) && $_POST['h-num'] !== ""){
                //echo "0.5<br>";
                if(isset($_POST['h-pass']) && $_POST['h-pass'] !== ""){
                    //echo "1<br>";
                    //編集番号とパスワードの両方が一致しているとき
                    $sql = 'select * from tb_5 where id = :id && password = :password;';
                    $stmt = $pdo -> prepare($sql);
                    $stmt -> bindParam(':id', $id, PDO::PARAM_STR);
                    $stmt -> bindParam(':password', $pass, PDO::PARAM_STR);
                    $id = $_POST['h-num'];
                    $pass = $_POST['h-pass'];
                    $stmt -> execute();
                    $result = $stmt -> fetchAll();
                    //$hnameと$hcommentに編集番号の行の名前とコメントを代入
                    $i = 0;
                    foreach($result as $row){
                        $hname = $row['name'];
                        $hcomment = $row['comment'];
                        $hpass = $row['password'];
                        $i++;
                    }
                    if($i === 0){
                        $error3 = "編集番号またはパスワードが間違っていました<br>";
                    }
                }
                else{
                    $error3 = "パスワードは入力必須です<br>";
                }
                //echo "2<br>";
            }
            elseif(isset($_POST['h-pass']) && $_POST['h-pass'] !== ""){
                $error3 = "編集番号は入力必須です<br>";
            }
            else{
                $error3 = "編集番号とパスワードは入力必須です<br>";
            }

        }
    ?>

    ※パスワードがなくても投稿できますが、その場合は削除・編集ができません。<br>
    ※下に表示される投稿内容でパスワードは表示されないので注意してください。内容変更のときも3項目の変更が可能です。<br>
    <form action = "" method = "post">
        <br>--投稿または内容変更--<br>
        <span style = "color: red"><?php if(isset($error1)){ echo $error1; } ?></span>
        名前　　　：<input type = "text" name = "name" value = "<?php if(isset($hname)){ echo $hname; } ?>" placeholder = "名前"><br>
        コメント　：<input type = "text" name = "comment" value = "<?php if(isset($hcomment)){ echo $hcomment; } ?>" placeholder = "コメント"><br>
        パスワード：<input type = "text" name = "pass" value = "<?php if(isset($hpass)){ echo $hpass; } ?>" placeholder = "パスワード">
        <input type = "submit" name = "submit" value = "送信">
        <input type = "hidden" name = "hidden" value = "<?php if(isset($_POST['h-num'])){ echo $_POST['h-num']; } ?>"><br>
        <br>--削除--<br>
        <span style = "color: red"><?php if(isset($error2)){ echo $error2; } ?></span>
        削除番号　：<input type = "number" name = "d-num" value = "" placeholder = "削除対象の投稿番号"><br>
        パスワード：<input type = "text" name = "d-pass" value = "" placeholder = "削除対象のパスワード">
        <input type = "submit" name = "delete" value = "削除"><br>
        <br>--編集--<br>
        <span style = "color: red"><?php if(isset($error3)){ echo $error3; } ?></span>
        編集番号　：<input type = "number" name = "h-num" value = "" placeholder = "編集対象の投稿番号"><br>
        パスワード：<input type = "text" name = "h-pass" value = "" placeholder = "編集対象のパスワード">
        <input type = "submit" name = "hensyu" value = "編集"><br>
    </form>
    <hr><hr>

    <!--ブラウザに表示-->
    <?php
        $sql = 'select * from tb_5;';
        $stmt = $pdo -> query($sql);
        $result = $stmt -> fetchAll();
        foreach($result as $row){
            echo $row['id']." ".$row['name']." ".$row['comment']." ".$row['date']."<br>";
        }
    ?>
</body>
</html>