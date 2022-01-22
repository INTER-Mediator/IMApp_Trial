<!DOCTYPE html>
<!--
/*
 * INTER-Mediator Trial Application
 *
 *   Copyright (c) 2022-2022 INTER-Mediator Directive Committee
 */  -->
<?php
$appRoot = dirname(__FILE__);
$imRoot = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'vendor'
    . DIRECTORY_SEPARATOR . 'inter-mediator' . DIRECTORY_SEPARATOR . 'inter-mediator';
$currentDirParam = $appRoot . DIRECTORY_SEPARATOR . 'lib/params.php';
if (file_exists($currentDirParam)) {
    include($currentDirParam);
}
if (isset($defaultTimezone)) {
    date_default_timezone_set($defaultTimezone);
} else if (ini_get('date.timezone') == null) {
    date_default_timezone_set('UTC');
}

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
    <li>このアプリケーションよりサンプルプログラムを稼働するには、MySQLで、INTER-Mediatorに付属のサンプルデータベースが稼働していることを前提としています。</li>
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
    <li>データベースへの接続は、127.0.0.1への接続で設定していますが、データベースの稼働条件が異なる場合は、lib/params.phpを変更して、データベースに合わせてください。</li>
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
    <li>INTER-Mediatorに含まれているサンプルプログラムを即座に実行できます。レポジトリの/samplesにコードがあるので、そちらも併せてご覧ください。</li>
    <li>サンプルの中にある認証ユーザー用のデータベースには、user1〜user5の5つのユーザーが定義されており、パスワードはユーザー名と同一です。
        概ね、user1でログインができますが、アクセス権の設定のテストも行っており、すべてのユーザーでのログインができるとは限りません。
        設定を参照の上ログインの確認や、あるいはできないことの確認をしてください。
    </li>
</ul>

<h3>
    <a href="./vendor/inter-mediator/inter-mediator/samples/Auth_Support/MySQL_accountmanager.html"
       target="_blank">ユーザー管理ページサンプル</a></h3>
<ul>
    <li>ユーザー名、パスワード共に、user1でログインができますが、通常の利用は、利用者と別の管理者を作り、その管理者でのみログインできるようにします。</li>
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
    以下のリンクは、Webサーバのルートに配置したファイルで、ページファイルエディタと定義ファイルエディタで開いて内容を編集し、その結果を参照することができます。いずれのリンクも、別のウインドウないしはタブを開きます。ページ更新が必要なときには手作業で行ってください。初期状態では何も表示しないようになっています。もちろん、独自に変更を加えて、自由に使ってみてください。</p>

<div style="display: flex; flex-wrap: wrap">
    <table style="margin-right: 20px">
        <tr>
            <td><a href="./src/page01.html" target="_blank">page01.htmlを表示する</a></td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/pageedit.html?target=../../../../src/page01.html"
                   target="_blank">
                    page01.htmlを編集する</a></td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/defedit.html?target=../../../../src/def01.php"
                   target="_blank">
                    def01.phpを編集する</a></td>
        </tr>
        <tr>
            <td><a href="./src/page02.html" target="_blank">page02.htmlを表示する</a></td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/pageedit.html?target=../../../../src/page02.html"
                   target="_blank">
                    page02.htmlを編集する</a>
            </td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/defedit.html?target=../../../../src/def02.php"
                   target="_blank">
                    def02.phpを編集する</a>
            </td>
        </tr>
        <tr>
            <td><a href="./src/page03.html" target="_blank">page03.htmlを表示する</a></td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/pageedit.html?target=../../../../src/page03.html"
                   target="_blank">
                    page03.htmlを編集する</a>
            </td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/defedit.html?target=../../../../src/def03.php"
                   target="_blank">
                    def03.phpを編集する</a>
            </td>
        </tr>
        <tr>
            <td><a href="./src/page04.html" target="_blank">page04.htmlを表示する</a></td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/pageedit.html?target=../../../../src/page04.html"
                   target="_blank">
                    page04.htmlを編集する</a>
            </td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/defedit.html?target=../../../../src/def04.php"
                   target="_blank">
                    def04.phpを編集する</a>
            </td>
        </tr>
        <tr>
            <td><a href="./src/page05.html" target="_blank">page05.htmlを表示する</a></td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/pageedit.html?target=../../../../src/page05.html"
                   target="_blank">
                    page05.htmlを編集する</a>
            </td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/defedit.html?target=../../../../src/def05.php"
                   target="_blank">
                    def05.phpを編集する</a>
            </td>
        </tr>
        <tr>
            <td><a href="./src/page06.html" target="_blank">page06.htmlを表示する</a></td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/pageedit.html?target=../../../../src/page06.html"
                   target="_blank">
                    page06.htmlを編集する</a>
            </td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/defedit.html?target=../../../../src/def06.php"
                   target="_blank">
                    def06.phpを編集する</a>
            </td>
        </tr>
        <tr>
            <td><a href="./src/page07.html" target="_blank">page07.htmlを表示する</a></td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/pageedit.html?target=../../../../src/page07.html"
                   target="_blank">
                    page07.htmlを編集する</a>
            </td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/defedit.html?target=../../../../src/def07.php"
                   target="_blank">
                    def07.phpを編集する</a>
            </td>
        </tr>
        <tr>
            <td><a href="./src/page08.html" target="_blank">page08.htmlを表示する</a></td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/pageedit.html?target=../../../../src/page08.html"
                   target="_blank">
                    page08.htmlを編集する</a>
            </td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/defedit.html?target=../../../../src/def08.php"
                   target="_blank">
                    def08.phpを編集する</a>
            </td>
        </tr>
        <tr>
            <td><a href="./src/page09.html" target="_blank">page09.htmlを表示する</a></td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/pageedit.html?target=../../../../src/page09.html"
                   target="_blank">
                    page09.htmlを編集する</a>
            </td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/defedit.html?target=../../../../src/def09.php"
                   target="_blank">
                    def09.phpを編集する</a>
            </td>
        </tr>
        <tr>
            <td><a href="./src/page10.html" target="_blank">page10.htmlを表示する</a></td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/pageedit.html?target=../../../../src/page10.html"
                   target="_blank">
                    page10.htmlを編集する</a>
            </td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/defedit.html?target=../../../../src/def10.php"
                   target="_blank">
                    def10.phpを編集する</a>
            </td>
        </tr>
        <tr>
            <td><a href="./src/page11.html" target="_blank">page11.htmlを表示する</a></td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/pageedit.html?target=../../../../src/page11.html"
                   target="_blank">
                    page11.htmlを編集する</a>
            </td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/defedit.html?target=../../../../src/def11.php"
                   target="_blank">
                    def11.phpを編集する</a>
            </td>
        </tr>
        <tr>
            <td><a href="./src/page12.html" target="_blank">page12.htmlを表示する</a></td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/pageedit.html?target=../../../../src/page12.html"
                   target="_blank">
                    page12.htmlを編集する</a>
            </td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/defedit.html?target=../../../../src/def12.php"
                   target="_blank">
                    def12.phpを編集する</a>
            </td>
        </tr>
        <tr>
            <td><a href="./src/page13.html" target="_blank">page13.htmlを表示する</a></td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/pageedit.html?target=../../../../src/page13.html"
                   target="_blank">
                    page13.htmlを編集する</a>
            </td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/defedit.html?target=../../../../src/def13.php"
                   target="_blank">
                    def13.phpを編集する</a>
            </td>
        </tr>
        <tr>
            <td><a href="./src/page14.html" target="_blank">
                    page14.htmlを表示する</a></td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/pageedit.html?target=../../../../src/page14.html"
                   target="_blank">
                    page14.htmlを編集する</a>
            </td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/defedit.html?target=../../../../src/def14.php"
                   target="_blank">
                    def14.phpを編集する</a>
            </td>
        </tr>
        <tr>
            <td><a href="./src/page15.html" target="_blank">page15.htmlを表示する</a></td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/pageedit.html?target=../../../../src/page15.html"
                   target="_blank">
                    page15.htmlを編集する</a>
            </td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/defedit.html?target=../../../../src/def15.php"
                   target="_blank">
                    def15.phpを編集する</a>
            </td>
        </tr>
        <tr>
            <td><a href="./src/page16.html" target="_blank">page16.htmlを表示する</a></td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/pageedit.html?target=../../../../src/page16.html"
                   target="_blank">
                    page16.htmlを編集する</a>
            </td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/defedit.html?target=../../../../src/def16.php"
                   target="_blank">
                    def16.phpを編集する</a>
            </td>
        </tr>
        <tr>
            <td><a href="./src/page17.html" target="_blank">page17.htmlを表示する</a></td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/pageedit.html?target=../../../../src/page17.html"
                   target="_blank">
                    page17.htmlを編集する</a>
            </td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/defedit.html?target=../../../../src/def17.php"
                   target="_blank">
                    def17.phpを編集する</a>
            </td>
        </tr>
        <tr>
            <td><a href="./src/page18.html" target="_blank">page18.htmlを表示する</a></td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/pageedit.html?target=../../../../src/page18.html"
                   target="_blank">
                    page18.htmlを編集する</a>
            </td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/defedit.html?target=../../../../src/def18.php"
                   target="_blank">
                    def18.phpを編集する</a>
            </td>
        </tr>
        <tr>
            <td><a href="./src/page19.html" target="_blank">page19.htmlを表示する</a></td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/pageedit.html?target=../../../../src/page19.html"
                   target="_blank">
                    page19.htmlを編集する</a>
            </td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/defedit.html?target=../../../../src/def19.php"
                   target="_blank">
                    def19.phpを編集する</a>
            </td>
        </tr>
        <tr>
            <td><a href="./src/page20.html" target="_blank">page20.htmlを表示する</a></td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/pageedit.html?target=../../../../src/page20.html"
                   target="_blank">
                    page20.htmlを編集する</a>
            </td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/defedit.html?target=../../../../src/def20.php"
                   target="_blank">
                    def20.phpを編集する</a>
            </td>
        </tr>
    </table>
    <table>
        <tr>
            <td><a href="./src/page21.html" target="_blank">page21.htmlを表示する</a></td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/pageedit.html?target=../../../../src/page21.html"
                   target="_blank">
                    page21.htmlを編集する</a></td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/defedit.html?target=../../../../src/def21.php"
                   target="_blank">
                    def21.phpを編集する</a></td>
        </tr>
        <tr>
            <td><a href="./src/page22.html" target="_blank">page22.htmlを表示する</a></td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/pageedit.html?target=../../../../src/page22.html"
                   target="_blank">
                    page22.htmlを編集する</a>
            </td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/defedit.html?target=../../../../src/def22.php"
                   target="_blank">
                    def22.phpを編集する</a>
            </td>
        </tr>
        <tr>
            <td><a href="./src/page23.html" target="_blank">page23.htmlを表示する</a></td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/pageedit.html?target=../../../../src/page23.html"
                   target="_blank">
                    page23.htmlを編集する</a>
            </td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/defedit.html?target=../../../../src/def23.php"
                   target="_blank">
                    def23.phpを編集する</a>
            </td>
        </tr>
        <tr>
            <td><a href="./src/page24.html" target="_blank">page24.htmlを表示する</a></td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/pageedit.html?target=../../../../src/page24.html"
                   target="_blank">
                    page24.htmlを編集する</a>
            </td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/defedit.html?target=../../../../src/def24.php"
                   target="_blank">
                    def24.phpを編集する</a>
            </td>
        </tr>
        <tr>
            <td><a href="./src/page25.html" target="_blank">page25.htmlを表示する</a></td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/pageedit.html?target=../../../../src/page25.html"
                   target="_blank">
                    page25.htmlを編集する</a>
            </td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/defedit.html?target=../../../../src/def25.php"
                   target="_blank">
                    def25.phpを編集する</a>
            </td>
        </tr>
        <tr>
            <td><a href="./src/page26.html" target="_blank">page26.htmlを表示する</a></td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/pageedit.html?target=../../../../src/page26.html"
                   target="_blank">
                    page26.htmlを編集する</a>
            </td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/defedit.html?target=../../../../src/def26.php"
                   target="_blank">
                    def26.phpを編集する</a>
            </td>
        </tr>
        <tr>
            <td><a href="./src/page27.html" target="_blank">page27.htmlを表示する</a></td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/pageedit.html?target=../../../../src/page27.html"
                   target="_blank">
                    page27.htmlを編集する</a>
            </td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/defedit.html?target=../../../../src/def27.php"
                   target="_blank">
                    def21.phpを編集する</a>
            </td>
        </tr>
        <tr>
            <td><a href="./src/page28.html" target="_blank">page28.htmlを表示する</a></td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/pageedit.html?target=../../../../src/page28.html"
                   target="_blank">
                    page28.htmlを編集する</a>
            </td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/defedit.html?target=../../../../src/def28.php"
                   target="_blank">
                    def28.phpを編集する</a>
            </td>
        </tr>
        <tr>
            <td><a href="./src/page29.html" target="_blank">page29.htmlを表示する</a></td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/pageedit.html?target=../../../../src/page29.html"
                   target="_blank">
                    page29.htmlを編集する</a>
            </td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/defedit.html?target=../../../../src/def29.php"
                   target="_blank">
                    def29.phpを編集する</a>
            </td>
        </tr>
        <tr>
            <td><a href="./src/page30.html" target="_blank">page30.htmlを表示する</a></td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/pageedit.html?target=../../../../src/page30.html"
                   target="_blank">
                    page30.htmlを編集する</a>
            </td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/defedit.html?target=../../../../src/def30.php"
                   target="_blank">
                    def30.phpを編集する</a>
            </td>
        </tr>
        <tr>
            <td><a href="./src/page31.html" target="_blank">page31.htmlを表示する</a></td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/pageedit.html?target=../../../../src/page31.html"
                   target="_blank">
                    page31.htmlを編集する</a>
            </td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/defedit.html?target=../../../../src/def31.php"
                   target="_blank">
                    def31.phpを編集する</a>
            </td>
        </tr>
        <tr>
            <td><a href="./src/page32.html" target="_blank">page32.htmlを表示する</a></td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/pageedit.html?target=../../../../src/page32.html"
                   target="_blank">
                    page32.htmlを編集する</a>
            </td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/defedit.html?target=../../../../src/def32.php"
                   target="_blank">
                    def32.phpを編集する</a>
            </td>
        </tr>
        <tr>
            <td><a href="./src/page33.html" target="_blank">page33.htmlを表示する</a></td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/pageedit.html?target=../../../../src/page33.html"
                   target="_blank">
                    page33.htmlを編集する</a>
            </td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/defedit.html?target=../../../../src/def33.php"
                   target="_blank">
                    def33.phpを編集する</a>
            </td>
        </tr>
        <tr>
            <td><a href="./src/page34.html" target="_blank">
                    page34.htmlを表示する</a></td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/pageedit.html?target=../../../../src/page34.html"
                   target="_blank">
                    page34.htmlを編集する</a>
            </td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/defedit.html?target=../../../../src/def34.php"
                   target="_blank">
                    def34.phpを編集する</a>
            </td>
        </tr>
        <tr>
            <td><a href="./src/page35.html" target="_blank">page35.htmlを表示する</a></td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/pageedit.html?target=../../../../src/page35.html"
                   target="_blank">
                    page35.htmlを編集する</a>
            </td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/defedit.html?target=../../../../src/def35.php"
                   target="_blank">
                    def35.phpを編集する</a>
            </td>
        </tr>
        <tr>
            <td><a href="./src/page36.html" target="_blank">page36.htmlを表示する</a></td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/pageedit.html?target=../../../../src/page36.html"
                   target="_blank">
                    page36.htmlを編集する</a>
            </td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/defedit.html?target=../../../../src/def36.php"
                   target="_blank">
                    def36.phpを編集する</a>
            </td>
        </tr>
        <tr>
            <td><a href="./src/page37.html" target="_blank">page37.htmlを表示する</a></td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/pageedit.html?target=../../../../src/page37.html"
                   target="_blank">
                    page37.htmlを編集する</a>
            </td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/defedit.html?target=../../../../src/def37.php"
                   target="_blank">
                    def37.phpを編集する</a>
            </td>
        </tr>
        <tr>
            <td><a href="./src/page38.html" target="_blank">page38.htmlを表示する</a></td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/pageedit.html?target=../../../../src/page38.html"
                   target="_blank">
                    page38.htmlを編集する</a>
            </td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/defedit.html?target=../../../../src/def38.php"
                   target="_blank">
                    def38.phpを編集する</a>
            </td>
        </tr>
        <tr>
            <td><a href="./src/page39.html" target="_blank">page39.htmlを表示する</a></td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/pageedit.html?target=../../../../src/page39.html"
                   target="_blank">
                    page39.htmlを編集する</a>
            </td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/defedit.html?target=../../../../src/def39.php"
                   target="_blank">
                    def39.phpを編集する</a>
            </td>
        </tr>
        <tr>
            <td><a href="./src/page40.html" target="_blank">page40.htmlを表示する</a></td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/pageedit.html?target=../../../../src/page40.html"
                   target="_blank">
                    page40.htmlを編集する</a>
            </td>
            <td>
                <a href="./vendor/inter-mediator/inter-mediator/editors/defedit.html?target=../../../../src/def40.php"
                   target="_blank">
                    def40.phpを編集する</a>
            </td>
        </tr>
    </table>
</div>
</body>
</html>
