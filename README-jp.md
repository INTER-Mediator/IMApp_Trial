<!-- -*- coding: utf-8 -*- -->

[English README is here](./README.md)


# IMApp_Trial
INTER-Mediatorの開発環境を数ステップで構築し体験できるWebアプリケーションです。
サンプルプログラムの確認や、coursewareで解説しているINTER-Mediatorのコードの書き方を勉強することができます。

https://inter-mediator.com/ja/courseware.html

インストール方法として、Dockerを使う方法とダイレクトホスティングの2種類があります。

# Dockerでインストールする

まず、お使いのプラットフォーム上でDocker Desktopを起動しておく必要があります。Docker Desktopのインストール方法はこちら（ https://www.docker.com/get-started/ ）を参考にしてください。

Docker Desktopが稼働可能な状態になったら以下を実行します。

```
git clone https://github.com/inter-mediator/IMApp_Trial
cd IMApp_Trial
docker-compose up -d
```

コンテナのビルドに10分以上かかるかもしれません。しかし、これだけでINTER-Mediatorの開発環境が実行可能になりました。

```
Creating network "imapp_trial_default" with the default driver
Creating imapp_trial_db_1 ... done
Creating php-apache_im    ... done
```

上のようなメッセージが確認できたら、Webブラウザで ``http://localhost:9080/`` を開いてください。INTER-Mediatorのデモアプリのトップページが表示されます。

編集可能なページや定義ファイルはDockerコンテナの外側のファイルに格納されているので、永続的に表示されます。
MySQLはDockerコンテナとして起動し、スキーマも割り当て済みですが、DBは永続的ではないので、コンテナ起動後に追加したデータはIMコンテナを停止すると消えます。

---
# ダイレクトホスティングのためのインストール

## 準備
PHP、git、composer、MySQL、Node.jsをnpmでインストールする。

データベースはINTER-Mediatorのサンプルデータベース（データベース名'test_db'）を以下のスキーマで用意します。すでにINTER-Mediatorを使用している場合は、このサンプルDBをセットアップする必要があります。

https://raw.githubusercontent.com/INTER-Mediator/INTER-Mediator/master/dist-docs/sample_schema_mysql.txt

MySQLを用意し、rootパスワードを知っている場合、このリポジトリをクローンした後、以下のようにtest_dbを設定することができます。
```
cd IMApp_Trial # カレントディレクトリがこのリポジトリのルートであると仮定します。
mysql -u root -p < vendor/inter-mediator/inter-mediator/dist-docs/sample_schema_mysql.txt
# 自作ユーザーは root のパスワードを設定しないかもしれないので、 -p パラメータを削除してください。
# Windowsユーザは、ディレクトリの区切り文字を / から \ または ¥ に変更する必要があります。
```
## セットアップ
このWebアプリは、composerをベースにしています。このリポジトリをクローンして、リポジトリのルートで以下のようにcomposerコマンドを実行します。
```
git clone https://github.com/inter-mediator/IMApp_Trial
cd IMApp_Trial
composer update
```

### Windowsの場合

Windows Subsystem for Linux (WSL) があれば、macOS/Linux と同じように設定できます。
Windows PowerShellなどを使っていない場合、composer updateコマンドの最後でエラーになります。
IMApp_Trialのカレントディレクトリで、以下のコマンドを手動で実行する必要があります。
この2つのコマンドは、shを使ったシェルスクリプトですので、bashアプリケーションを立ち上げてください。

```
cd .¥vendor¥inter-mediator¥inter-mediator
npm install
cd .¥dist-docs
./generateminifyjshere.sh
cd ..¥...¥...¥...¥lib
./trialsetup.sh
cd ..
```

## スタートアップアプリ
Webアプリを手っ取り早くホストするには、phpコマンドのサーバーモードが便利です。
```
php -S localhost:9000
```
これで、同じホストで実行されているブラウザから、 ``http://localhost:9000/`` という url でアプリケーションにアクセスできるようになります。
