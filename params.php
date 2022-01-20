<?php
$dbUser = 'web';
$dbPassword = 'password';
$dbDSN = 'mysql:unix_socket=/var/run/mysqld/mysqld.sock;dbname=test_db;charset=utf8mb4';
$dbOption = [];
$browserCompatibility = array(
    'Chrome' => '71+',
    'FireFox' => '69+',
    'Safari' => '11+',
    'Trident' => '5+',
);
$dbServer = '192.168.56.1';
$dbPort = '80';
$dbDatabase = 'TestDB';
$dbProtocol = 'HTTP';
$passPhrase = '';
$generatedPrivateKey = <<<EOL
-----BEGIN RSA PRIVATE KEY-----
MIIEowIBAAKCAQEAyTFuj/i52z0pXsoa6HNTUFcmWBcaG5DodB5ac6WAKBxn4G/j
knKwIBjRluCjIcRdFk6m91ChSOoDvW3p3rk2UFMIfq9e6ojhsWO3AFrHXOVHt+P/
QWnI2KUtUmxO0jw9hbqK/Hl4IbWc8aGnxP/uGOmLJnLSP3wEtahXXaVSJrGTPZuk
pbzarqzS3waraUYP+b2aMGLL/BbPnc7xCF13TtkVdcVdjyQtofn7hM3Kd8fOFFOg
ix+JYOrf5jMG0aTs9NxwcHXb6DMwt/L30s+eIMI/81/sthT5TD7kEMUudz+yomnM
G6C+bFYgykcFlYwEeUD//5naitc3ZNYXEpyzWwIDAQABAoIBAQCWmpwqxYNKrBPl
0uAllP6Oq04WruRqMiTvlzEaVI8Ed48CoH73x0Y0IJ/zkyBKTJVp92Jgy0iQLiyy
hi6E/Ju9sQow2tHwOprHkN8SMuH9ldwDuXX/31HramnswwqVsWZUTnlv2PWmNi7P
abUOcI4os9nn5BeiUhGsceFERlaigwFJ1eWI7M+XfIh9YfLx8ERaZYi9g6MDNZ1k
TEirV/rGbts4K62IJ+UGiW5UYW4qfPvOdmsOKcr0IMmM4hu5/ZlGg/xDXrLRCQzj
Pt0+dJ1UZyb5PuhlUyjqF0vBBr/hLQhkAUPLE1CyXCgbDWrXEJkoT7DVILskZHo0
1+DmgbsxAoGBAPp8upw+vsY2yzxZep0GtXqqXQOVQ8f+XPeDK9kE5TgNPofCr8+3
cqerbwGPBRJueYnYNANNc0aVgNnX+rkUYEJMlrkeEqPPpNvEzOd/l067EprGgA5s
HZkMLJsxLTrJEuj5NczMtsJia6ufWD8l4XTvB6WKNSDCf4/sdZCFF0JVAoGBAM2e
+YU7AsC70q9BxPR4sc0vLk8kEY9eKuP7PCb991qpIxD/VFpRWy9znO7t9+EQKsJ1
U1HdU/YTSuSTmg9z+a7s4En1tI+ryUHmwv8run9r11lx7yuXgJhx6mg5Lc6BaFIN
QsbQIm/7HL0p5ugPfDiObPIxQUgR1s+Xl7HnkK7vAoGAaHzjMw4Rcomk2bXRqfME
fPjX+Aipz6FRkoYLImoiW/FaZjNWN2Wk1EB0+8d3LCsdU9z2RXJnZcgziavIkK/p
P37HWM0spVyWvn4no2Hb8iGjLyEiheGfrxoe+VXYMi9yTfC2+oliq0927o53t0/L
7oVPQUSXyOSZZaYTnIeIHkkCgYBYr+f5ohE25gwiUWDM/T3bPS1hLzJvvvMK8DLq
soG81dTtIOPWLN8CoYAfwf43UczPoOE2Hxt2uK2F13AMmD4qR7sZy2N80GB3Dzwt
6UOAcBgrWSwKhkcN+ZxcJcVvG3vOYC/cJquj1xB3OpqAnyU6E5xD/iClICSh10Wz
kyhhewKBgC1bAmPbOHoaNecuHTSO+pe5s29KagojaWMFsH1+Zs5HiVBmLmO9UdG9
UeplZBKmxW3+wQ5gVWIguqisfvi9/m07Z/3+uwCLSryHU6Kgg7Md9ezU9Obx+jxp
cmyuR8KhUNJ6zf23TUgQE6Dt1EAHB+uPIkWiH1Yv1BFghe4M4Ijk
-----END RSA PRIVATE KEY-----
EOL;
$activateClientService = true;  // Default is TRUE!!. (In case of debuging phase, it should be false.)
$preventSSAutoBoot = false;
$serviceServerPort = '11478';
$stopSSEveryQuit = false;
$notUseServiceServer = false;
$messages['default'][1022] = 'We don\'t support Internet Explorer. We\'d like you to access by Edge or any other major browsers.';
$messages['ja'][1022] = 'Internet Explorerは使用できません。Edgeあるいは他の一般的なブラウザをご利用ください。';
$activateClientService = true;
$webServerName = ['192.168.56.101'];
$serviceServerHost = '192.168.56.101';
$serviceServerConnect = 'http://192.168.56.101';
