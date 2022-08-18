[日本語版はこちらにあります](./README-jp.md)


# IMApp_Trial
INTER-Mediator Trial web application.
You can check the sample programs, also study how to write code for INTER-Mediator with the courseware.

https://inter-mediator.com/ja/courseware.html

Here is two options about the installation, they are docker and direct hosting.

# Installing with Docker

You have to run the Docker Desktop, and execute below.

```
git clone https://github.com/inter-mediator/IMApp_Trial
cd IMApp_Trial
docker-compose up -d
```

It might be over 10 minutes to build the containers. After finishing to do them, open ```http://localhost:9080/``` on your web browser. The top page of the INTER-Mediator demo app is going to show on.

The editable pages and definition files are stored in files out-side of the docker container, so they are persistent.
The MySQL launches as a docker container and the schema has already assigned, but the db isn't persistent, so the data added after booting container will disappear after stop them.

# Installing for direct hosting

## Preparation
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
## Setup
This web app based on the composer. So you clone this repository, following to execute the composer command on the root of the repository.
```
git clone https://github.com/inter-mediator/IMApp_Trial
cd IMApp_Trial
composer update
```

### For Windows

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
./trialsetup.sh
cd ..
```

## Getting Started App
The quick way to host the web app, the php command's server mode is convenient.
```
php -S localhost:9000
```
After that, you can access the application with url ```http://localhost:9000/``` from any browser that executing on the same host.
