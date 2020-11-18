<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <?php
            date_default_timezone_set('Asia/Tokyo');
            try {
                $dbh = new PDO('sqlite:C:\sqlite\chatLog.db');
            } catch (PDOException $err){
                echo 'error'.$err->getMessage();
                die();
            }
        ?>
    <!-- チャットの投稿欄 -->
        <form method="get" action="/">
            <p>
                <h2>名前</h2>
                <input type="text" name="name" size="40">
            </p>
            <p>
                <h2>メッセージ</h2>
                <textarea id="message" name="message"></textarea>
            </p>
            <input class="submit" type="submit" value="送信">
        </form>
        <!-- データベースへの書き込み -->
        <?php
            /// クエリパラメータを取得 ///
            function gGET($key){ return isset($_GET[$key]) ? $_GET[$key]: ''; }

            $sth_w = $dbh->prepare('INSERT INTO chatlog (name, message) VALUES(?, ?)');
            $sth_w->execute(array(gGET('name'), gGET('message')));
        ?>

        <!-- チャットの履歴を表示する -->

        <!-- データベースの読み込み -->
        <?php
            /// 最終行を取得 ///
            $sql_cnt = 'SELECT count(*) FROM chatlog';
            $sth_cnt = $dbh->prepare($sql_cnt);
            $sth_cnt->execute();
            $cnt = $sth_cnt->fetch();
            /// チャット履歴を取得 ///
            $sql = 'SELECT * FROM chatlog';
            $sth_r = $dbh->prepare($sql);
            $sth_r->execute();
            $result = $sth_r->fetchAll();
        ?>

        <!-- 履歴の表示 -->
        <p class="log">
            【履歴】 <br>
            <?php
                /// 直近30投稿の、投稿者、メッセージ、時間（UTC）を表示 ///
                for($i = $cnt[0]-30; $i<$cnt[0]; $i++){
                    print_r($result[$i]['name'].':'.$result[$i]['message'].'('.$result[$i]['date'].')'."<br>");
                }
            ?>
        </p>
    </body>
</html>
