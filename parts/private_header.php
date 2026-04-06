<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? "site" ?></title>
    <link rel="stylesheet" href="models/style.css">
</head>
<body>
    <header>
        <a href="<?= $dir_backs ?>home">home</a>
        <a href="<?= $dir_backs ?>overview">overview</a>
        <a href="<?= $dir_backs ?>prisoners">gevangenen</a>
        <a href="<?= $dir_backs ?>my_account">mijn account</a>
    </header>