<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
  </head>
  <body>

<?php
//mysql接続
    $dsn = 'databadename';
    $user = 'username';
    $password = 'password';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING)); 

//投稿機能
    if(isset($_POST['comment'])&&$_POST['comment'] != null){
            echo $_POST['comment']."を受け付けました"."<br>";

    //名前の判定
        if(isset($_POST["name"])){
            $name = $_POST["name"];
        }else{
            $name = "名無し";
        }
       

    //パスワードの判定
        if(isset($_POST['pass'])){
            $pass = $_POST["pass"];
        }else{
            $pass = null;
        }

        if(empty($_POST['edit'])) {
        //新規投稿
            //名前の判定
            if(isset($_POST["name"])){
                $name = $_POST["name"];
            }else{
                $name = "名無し";
            }
            $comment = $_POST['comment'];
            $date = date("Y-m-d G:i:s");
            //パスワードの判定
            if(isset($_POST['pass'])){
                $pass = $_POST["pass"];
            }else{
                $pass = null;
            }

            $sql = $pdo -> prepare("INSERT INTO mission_5 (name, comment, date, pass) VALUES (:name, :comment, :date, :pass)");
            $sql -> bindParam(':name', $name, PDO::PARAM_STR);
            $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
            $sql -> bindParam(':date',$date , PDO::PARAM_STR);
            $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
            $sql -> execute();

        }else{
        //編集機能
            $id = $_POST['edit'];; 
            $name = $_POST['name'];
            $comment = $_POST['comment']; 
            $sql = 'update mission_5 set name=:name,comment=:comment where id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        }
    }
 
    //削除機能
    if(isset($_POST["delete"]) && isset($_POST["delpass"]) && $_POST["delpass"] != null){
        $delete = $_POST["delete"];
        $delpass = $_POST["delpass"];

        $sql = 'select*from mission_5';
        $stmt = $pdo -> query($sql);
        $result = $stmt -> fetchAll();

        foreach($result as $row){
            if($row['id'] == $delete){
                if($row['pass'] == $delpass){
                    $sql = 'delete from mission_5 where id=:id';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':id', $delete, PDO::PARAM_INT);
                    $stmt->execute();
                }else{
                    echo "パスワードが違います"."<br>";
                }
            }
        }
    }
 
    //編集番号選択
    if(isset($_POST['edit']) && isset($_POST['editpass']) && $_POST["editpass"] != null) {
        $edit = $_POST['edit'];
        $editpass = $_POST['editpass'];

        $sql = 'select*from mission_5';
        $stmt = $pdo -> query($sql);
        $result = $stmt -> fetchAll();

        foreach($result as $row){
            if($row['id'] == $edit){
                if($row['pass'] == $editpass){
                    $editnumber = $row['id'];
                    $editname = $row['name'];
                    $editcomment = $row['comment'];
                }else{
                    echo "パスワードが違います"."<br>";
                }
            }
        }
    }
?>

    <form action="mission_5.php" method="post">
        <br>
        お名前：　   <input type="text" name="name" placeholder="名前" value="<?php if(isset($editname)) {echo $editname;} ?>"><br>
        コメント：   <input type="text" name="comment" placeholder="コメント" value ="<?php if(isset($editcomment)) {echo $editcomment;} ?>"><br>
        パスワード：<input type="text" name="pass" placeholder="パスワード" value = "<?php if(isset($editpass)){echo $editpass;}?>">
        <input type="hidden" name="edit" value="<?php if(isset($editnumber)) {echo $editnumber;} ?>">
        <input type="submit" name="submit" value="送信">
        <br>
    </form>

    <form action="mission_5.php" method="post">
        <br>
        削除番号：<input type="text" name="delete" value="">
        <input type="submit" value="削除" value=""><br>
        パスワード：<input type="text" name="delpass"><br>
        <br>
    </form>

    <form action="mission_5.php" method="post">
        編集番号：<input type = "text" name = "edit" value="">
        <input type = "submit" value = "編集" value=""><br>
        パスワード：<input type="text" name="editpass"><br>
        <br>
        <hr>
    </form>

    <?php
    //テキストの表示
        $sql = 'select*from mission_5';
        $stmt = $pdo -> query($sql);
        $result = $stmt -> fetchAll();
        foreach($result as $row){
            echo $row['id']." ";
            echo $row['name']." ";
            echo $row['date']."<br>";
            echo $row['comment']."<br>";
            echo "<hr>";
        }
    ?>
  </body>
</html>