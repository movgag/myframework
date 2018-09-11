<html>
<head>
    <title><?= (isset($title) && $title) ? $title : 'Home' ?></title>
    <link rel="stylesheet" href="<?= PUBLIC_PATH ?>css/temp.css">
</head>
<body>
<h1 class="temp_coming_soon">PHP framework</h1>
<?php  require($viewPath);  ?>
</body>
</html>
