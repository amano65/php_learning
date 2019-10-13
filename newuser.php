<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>Sign Up</title>
        <link rel="stylesheet" href="style.css">
    </head>

    <div id="header-fixed">
        <div id="header-bk">
            <div id="header">Cycle Course Reviewer</div>
        </div>
    </div>

    <body>
<?php
//DB接続
    $dsn = 'db';
    $user = 'user';
    $password = 'password';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

//新規ユーザー登録機能
    if(isset($_POST["submit"])){
        if(!empty($_POST["name"]) && !empty($_POST["pass"])){
            $check = 0;
            $name = $_POST["name"];
            $pass = $_POST["pass"];
    
        //ユーザー名一致検索
            $sql = 'select name from UserDB';
            $stmt = $pdo -> query($sql);
            $result = $stmt -> fetchAll();
            foreach($result as $row){
                if($name == $row){
                    $check = 1;
                    echo "このユーザー名は既に使われています"."<br>";
                }
            }
    
        //ユーザー登録
            if($check == 0){
                $sql = $pdo -> prepare("INSERT INTO UserDB (name, pass) VALUES (:name, :pass)");
                $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
                $sql -> execute();
                echo "登録しました"."<br>";
            }
        }else{
            echo "ユーザー名またはパスワードが空欄です"."<br>";
        }
    }

    unset($pdo);
?>
    <div align="center">
    <form action="" method="post">
        <br>
        新規登録
        <br>
        <input type="text" name="name" placeholder="ユーザー名" style="text-align:center"><br>
        <input type="text" name="pass" placeholder="パスワード" style="text-align:center"><br>
        <input type="submit" name="submit" value="登録">
        <br>
    </form>
    </div>

    <br>
    <a href="login.php" align="left">ログイン画面へ戻る→</a>
  
    </body>
</html>
