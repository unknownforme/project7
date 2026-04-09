<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? "site" ?></title>
    <link rel="stylesheet" href="<?= $dir_backs ?>models/style.css">
</head>
<body>
    <header>
        <a class="button_link" href="<?= $dir_backs ?>home">home</a>
        <a class="button_link" href="<?= $dir_backs ?>overview">overview</a>
        <a class="button_link" href="<?= $dir_backs ?>prisoners">gevangenen</a>
        <a class="button_link" href="<?= $dir_backs ?>account">mijn account</a>
    </header>
    
    <div id="center">