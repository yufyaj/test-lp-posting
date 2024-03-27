<?php
    $type = $_GET['type'];
    $state = $_GET['state'];
    $city = $_GET['city'];
    $busu = $_GET['busu'];
    $cost = $_GET['cost'];
    $company = $_GET['company'];
    $name = $_GET['name'];
    $mail = $_GET['mail'];
    $privacy = $_GET['privacy'];
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <label>type: </label><label><?= $type ?></label>
    <label>state: </label><label><?= $state ?></label>
    <label>city: </label><label><?= $city ?></label>
    <label>busu: </label><label><?= $busu ?></label>
    <label>company: </label><label><?= $company ?></label>
    <label>name: </label><label><?= $name ?></label>
    <label>mail: </label><label><?= $mail ?></label>
    <label>privacy: </label><label><?= $privacy ?></label>
    <from>
        <input type="hidden" name="type" value="<?= $type ?>" />
        <input type="hidden" name="state" value="<?= $state ?>" />
        <input type="hidden" name="city" value="<?= $city ?>" />
        <input type="hidden" name="busu" value="<?= $busu ?>" />
        <input type="hidden" name="cost" value="<?= $cost ?>" />
        <input type="hidden" name="company" value="<?= $company ?>" />
        <input type="hidden" name="name" value="<?= $name ?>" />
        <input type="hidden" name="mail" value="<?= $mail ?>" />
        <div>
            <a href="/?type=<?= $type ?>&state=<?= $state ?>&city=<?= $city ?>&busu=<?= $busu ?>&cost=<?= $cost ?>&company=<?= $company ?>&name=<?= $name ?>&mail=<?= $mail ?>&privacy=true">戻る</a>
        </div>
    </from>
</body>
</html>