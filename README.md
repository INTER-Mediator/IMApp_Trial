# IMApp_Trial
INTER-Mediator Trial web application.
You can check the sample programs, also study how to write code for INTER-Mediator with the corseware.

https://inter-mediator.com/ja/courseware.html

# Preparation
Installing PHP, git, composer, MySQL and Node.js with npm.

The database has to be prepared with the following schema which is the sample db (the db name is 'test_db') of the INTER-Mediator. If you already try to use the INTER-Mediator, this sample db has to be setup.

https://raw.githubusercontent.com/INTER-Mediator/INTER-Mediator/master/dist-docs/sample_schema_mysql.txt

If you already prepare the MySQL, and you know the root password, after you clone this repository you can set up the test_db as following.
```
cd IMApp_Trial # assuming the current directory is the root of this repository.
mysql -u root -p < vendor/inter-mediator/inter-mediator/dist-docs/sample_schema_mysql.txt
# Homebrew user might not set the root password, so you can remove the -p parameter.
# Windows user have to change the directory separator / to ¥ or \.
```
# Setup
This web app based on the composer. So you clone this repository, following to execute the composer command on the root of the repository.
```
git clone https://github.com/inter-mediator/IMApp_Trial
cd IMApp_Trial
composer update
```

## For Windows

If you have the Windows Subsystem for Linux (WSL), you can set it up same as macOS/Linux.
If you don't (e.g. Windows PowerShell), you are going to get an error at the end of composer update command.
You have to execute following commands manually from the current directory is IMApp_Trial.
These are two shell scripts which uses sh, so you need to set up any kind of bash application.

```
cd .¥vendor¥inter-mediator¥inter-mediator
npm install
cd .¥dist-docs
./generateminifyjshere.sh
cd ..¥..¥..¥..¥lib
./trialsetup.sh"
cd ..
```

## Getting Started App
The quick way to host the web app, the php command's server mode is convenient.
```
php -S localhost:9000
```
After that, you can access the application with url http://localhost:9000/ from any browser that executing on the same host.
