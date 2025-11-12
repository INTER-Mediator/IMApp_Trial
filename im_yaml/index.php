<?php
require('vendor/autoload.php');

use Symfony\Component\Yaml\Yaml;

function devide4($a, $b, $c, $d)
{
    return [$a, $b, $c, $d];
}

$converted = '';
$converted2 = '';
$ar = '';
if (isset($_POST['php'])) {
    if (file_exists("doesnt_work")){
        $ar = $_POST['php'];
        $converted = "This Application doesn't work here. Please refer the repository (https://github.com/inter-mediator/IMApp_Trial) for more details.";
        $converted2 = $converted;
    }else {
        $data = trim(str_replace("IM_Entry", "devide4", $_POST['php']));
        $data = eval("return {$data};");

        $dataKeys = ['contexts', 'options', 'connection', 'debug'];
        $ar = var_export($data[0], true);
        $ar = $ar . "," . "\n" . var_export($data[1], true);
        $ar = $ar . "," . "\n" . var_export($data[2], true);
        $ar = $ar . "," . "\n" . var_export($data[3], true);
        $ar = "IMEntry(\n" . $ar . "\n);";

        $converted = [
                $dataKeys[0] => $data[0],
                $dataKeys[1] => $data[1],
                $dataKeys[2] => $data[2],
                $dataKeys[3] => $data[3],
        ];
        $converted2 = $converted;
        $converted = Yaml::dump($converted, 5, 2, Yaml::DUMP_COMPACT_NESTED_MAPPING);
        $converted2 = json_encode($converted2, JSON_PRETTY_PRINT);
    }
}
?>
<html>
<head>
    <style>
        textarea {
            width: 30vw;
            height: 60vh;
        }

        .topmessage {
            text-align: center;
            color: red;
            font-size: 160%;
        }
    </style>
</head>
<body>
<div class="topmessage">Don't Publish This Application to Open Area!!!!</div>
<form action="index.php" method="post">
    <div style="display: flex">
        <div>
            <h2>PHP Program just IM_Entry Call</h2>
            <textarea name="php"><?php echo $ar; ?></textarea>
        </div>
        <div style="margin-top: 200px">
            <button>Convert→
            </button>
        </div>
        <div>
            <h2>YAML</h2>
            <textarea name="yaml"><?php echo $converted; ?></textarea>
        </div>
        <div>
            <h2>JSON</h2>
            <textarea name="json"><?php echo $converted2; ?></textarea>
        </div>
    </div>
</form>
</body>
</html>