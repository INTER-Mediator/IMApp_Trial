<!DOCTYPE html>
<!--
/*
 * INTER-Mediator Trial Application
 *
 *   Copyright (c) 2022-2022 INTER-Mediator Directive Committee
 */  -->
<?php
// Initialize global variables.
$dbProtocol = "";
$dbServer = "";
$dbPort = "";
$dbUser = "";
$dbPassword = "";
$dbDatabase  = "";
// Including params.php file.
$appRoot = dirname(__FILE__);
$imRoot = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'vendor'
        . DIRECTORY_SEPARATOR . 'inter-mediator' . DIRECTORY_SEPARATOR . 'inter-mediator';
$currentDirParam = $appRoot . DIRECTORY_SEPARATOR . 'lib/params.php';
if (file_exists($currentDirParam)) {
    include($currentDirParam);
}
// Set default timezone.
if (isset($defaultTimezone)) {
    date_default_timezone_set($defaultTimezone);
} else if (ini_get('date.timezone') == null) {
    date_default_timezone_set('UTC');
}
// Reading from the composer.json file.
$version = '';
$content = json_decode(file_get_contents($imRoot . DIRECTORY_SEPARATOR . 'composer.json'));
if ($content) {
    $version = $content->version;
}
$filterForDate = "| grep Date: | awk '{print $2,$3,$4,$5,$6}'";
$modDate = exec("git --git-dir={$imRoot}/.git log -1 {$filterForDate}");
$imModDate = (new DateTime($modDate))->format('Y年m月d日');
$modDate = exec("git --git-dir={$imRoot}/.git log -1 -- -p dist-docs/sample_schema_mysql.txt {$filterForDate}");
$mysqlModDate = (new DateTime($modDate))->format('Y年m月d日');
$modDate = exec("git --git-dir={$imRoot}/.git log -1 -- -p dist-docs/TestDB.fmp12 {$filterForDate}");
$fmModDate = (new DateTime($modDate))->format('Y年m月d日');
?>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>INTER-Mediator <?php echo htmlspecialchars($version, ENT_QUOTES, 'UTF-8'); ?> - VM for Trial</title>
    <script type="text/javascript" src="include_MySQL.php"></script>
    <script type="text/javascript" src="index.js"></script>
</head>
<body style="margin: 8px">
<h1>INTER-Mediator <?php echo htmlspecialchars($version, ENT_QUOTES, 'UTF-8'); ?> - VM for Trial</h1>

<h2>現在アクセスしているwebアプリケーションについて</h2>

<p>このアプリケーションは、以下のような目的で構築しました。</p>
<ul>
    <li>INTER-Mediatorに含まれるサンプルプログラムを試してみる</li>
    <li><a href="https://inter-mediator.com/ja/courseware.html" target="_blank">INTER-Mediatorのコースウエア</a>
        などで、システム構築方法を学習する
    </li>
    <li>INTER-Mediatorの試用のため</li>
</ul>
<p>
    このアプリケーションが使用しているINTER-Mediatorの最終更新日は
    <strong><?php echo htmlspecialchars($imModDate, ENT_QUOTES, 'UTF-8'); ?></strong>
    です。
</p>

<h3>アプリケーションの動作条件</h3>
<ul>
    <li>
        このアプリケーションよりサンプルプログラムを稼働するには、MySQLで、INTER-Mediatorに付属のサンプルデータベースが稼働していることを前提としています。
        <span style="color:red">Dockerで起動した場合には、サンプルデータベースは稼働した状態になっています。</span>
        ダイレクトホスティング（レポジトリのコードを直接公開）の場合は、以下の方法で、MySQLデータベースが稼働する状態にしてください。
    </li>
    <li>
        <a href="https://raw.githubusercontent.com/INTER-Mediator/INTER-Mediator/master/dist-docs/sample_schema_mysql.txt"
           target="_blank">
            レポジトリ内のスキーマファイル</a>をMySQLに読み込めば、サンプルデータベースは稼働します。簡単な方法は次の通りです。
        <div style="margin-left: 14px; background-color: #DDDDDD; padding: 8px">
            <strong>cd IMApp_Trial</strong> # このレポジトリのルートをカレントディレクトリとする<br>
            <strong>mysql -u root -p <
                vendor/inter-mediator/inter-mediator/dist-docs/sample_schema_mysql.txt</strong><br>
            # MacでHomebrewのようなrootパスワードが設定されていない場合は、-pを省略する
        </div>
    </li>
    <li>
        データベースへの接続は、127.0.0.1への接続で設定していますが、データベースの稼働条件が異なる場合は、lib/params.phpを変更して、データベースに合わせてください。
    </li>
    <li>FileMaker向けのサンプルプログラムは、FileMaker Serverが稼働しているサーバへの設定を、lib/params.phpで記述してください。
    </li>
    <li><strong>サンプルデータベースの最終更新日</strong>：MySQL(or MariaDB)=
        <?php echo htmlspecialchars($mysqlModDate, ENT_QUOTES, 'UTF-8'); ?>、
        FileMaker=<?php echo htmlspecialchars($fmModDate, ENT_QUOTES, 'UTF-8'); ?>
        <br><strong>あなたがお使いのサンプルデータベース</strong>：
        <span data-im-control="enclosure"><span
                    data-im-control="noresult">MySQL(or MariaDB)=2015年7月10日以前</span>
        <span data-im-control="repeater"><span
                    data-im="information@lastupdated">MySQL(or MariaDB)=</span></span>
        </span><?php
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, strtolower($dbProtocol) . '://' . $dbServer . ':' . $dbPort . '/fmi/xml/fmresultset.xml');
            curl_setopt($ch, CURLOPT_USERPWD, $dbUser . ':' . $dbPassword);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, '-db=' . $dbDatabase . '&-lay=information&-findall&-max=1');
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 1);
            $xml = curl_exec($ch);
            curl_close($ch);
            libxml_use_internal_errors(true);
            $parsedData = simplexml_load_string($xml);
            $output = '';
            if ($parsedData !== false) {
                $output = '、FileMaker=2015年7月11日以前';
            }
            require "{$appRoot}" . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
            $converter = new \INTERMediator\Data_Converter\FMDateTime();
            error_reporting(0);
            foreach ($parsedData->resultset->record->field as $key => $field) {
                if ((string)$field->attributes()->name === 'lastupdated') {
                    $dateInfo = $converter->dateArrayFromFMDate($field->data);
                    $output = '、FileMaker=' . intval($dateInfo['year']) . '年' .
                            intval($dateInfo['month']) . '月' .
                            intval($dateInfo['day']) . '日';
                    break;
                }
            }
            echo htmlspecialchars($output, ENT_QUOTES, 'UTF-8');
        } catch (Exception $e) {
        }
        ?>
    </li>
</ul>

<h2>リンク</h2>

<h3>
    <a href="./vendor/inter-mediator/inter-mediator/samples/"
       target="_blank">サンプルプログラム</a>
</h3>
<ul data-im-control="ignore_enc_rep">
    <li>
        INTER-Mediatorに含まれているサンプルプログラムを即座に実行できます。レポジトリの/samplesにコードがあるので、そちらも併せてご覧ください。
    </li>
    <li>サンプルの中にある認証ユーザー用のデータベースには、user1〜user5の5つのユーザーが定義されており、パスワードはユーザー名と同一です。
        概ね、user1でログインができますが、アクセス権の設定のテストも行っており、すべてのユーザーでのログインができるとは限りません。
        設定を参照の上ログインの確認や、あるいはできないことの確認をしてください。
    </li>
</ul>

<h3>
    <a href="./vendor/inter-mediator/inter-mediator/samples/Auth_Support/MySQL_accountmanager.html"
       target="_blank">ユーザー管理ページサンプル</a></h3>
<ul>
    <li>
        ユーザー名、パスワード共に、user1でログインができますが、通常の利用は、利用者と別の管理者を作り、その管理者でのみログインできるようにします。
    </li>
</ul>

<h3>その他のリンク</h3>
<ul>
    <li><a href="info.php" target="_blank">phpinfo()関数の実行</a>
    </li>
    <li><a href="https://inter-mediator.com/" target="_blank">INTER-Mediator Web Site</a></li>
    <li><a href="http://inter-mediator.info/" target="_blank">INTER-Mediator Manual Site</a></li>
    <li><a href="http://inter-mediator.org/" target="_blank">INTER-Mediator Directive Committee</a></li>
</ul>


<h2>トライアル用のページファイルと定義ファイル</h2>

<p>
    以下のリンクは、Webサーバのルートに配置したファイルで、ページファイルエディタと定義ファイルエディタで開いて内容を編集し、その結果を参照することができます。
    いずれのリンクも、別のウインドウないしはタブを開きます。ページ更新が必要なときには手作業で行ってください。
    初期状態では何も表示しないようになっています。もちろん、独自に変更を加えて、自由に使ってみてください。
    .phpファイルと.yamlファイルの使い分けは、一連のリンクの下に記述しました。
</p>
<p>
    従来までの「def01.phpを編集する」のリンクに相当するのは、「def01.phpを編集する」の右にある「DefEditor」のリンクです。
    チュートリアルで紹介した手順で進めたい方は、DefEditorのリンクをクリックしてご利用ください。
</p>

<div style="display: flex; flex-wrap: wrap">
    <table style="margin-right: 20px">
        <?php
        for($pn = 1; $pn <= 40; $pn += 1) {
            $p = substr("0".$pn, -2);
        ?>
        <tr>
            <td><a href="./src/page<?php echo $p;?>.html" target="_blank">page<?php echo $p;?>.htmlを表示する</a></td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/pageedit.html?target=../../../../src/page<?php echo $p;?>.html"
                   target="_blank">
                    page<?php echo $p;?>.htmlを編集する</a>
            </td>
            <td>
                def<?php echo $p;?>.phpを編集する
                [<a href="./vendor/inter-mediator/inter-mediator/editors/defedit.html?target=../../../../src/def<?php echo $p;?>.php"
                    target="_blank">DefEditor</a>]
                [<a href="/vendor/inter-mediator/inter-mediator/editors/pageedit.html?target=../../../../src/def<?php echo $p;?>.php"
                    target="_blank">ソース</a>]
            </td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/pageedit.html?target=../../../../src/page<?php echo $p;?>.yaml"
                   target="_blank">
                    page<?php echo $p;?>.yamlを編集する</a>
            </td>
        </tr>
        <?php
        }
        ?>
    </table>
</div>
<h3>.phpファイルと.yamlファイルの使い分けについて</h3>
<p>
    上記の編集可能なファイルは、1つのページファイルと、2つの定義ファイル（.phpおよび.yaml）の編集ができます。
    定義ファイルはどちらか一方を利用します。
    ページファイル内のヘッダ部分の記述を以下のように書き分けて、利用したい方を参照するようにします。
    いずれも、page01.htmlがページファイルの場合に、同じ番号の定義ファイルを利用する場合です。
</p>
<div class="code"><pre><code># .phpファイルを利用する場合
&lt;script src="def01.php"&gt;&lt;/script&gt;

# .yamlファイルを利用する場合
&lt;script src="../vendor/inter-mediator/inter-mediator/index.php"&gt;&lt;/script&gt;
（リンクにファイル番号がありませんが、ページファイル名と同じ名前の.yamlファイルを参照します）
</code></pre>
</div>
</body>
</html>
