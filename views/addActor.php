<?php 
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mini.css/3.0.1/mini-nord.css" integrity="sha512-hsKycmJmiBoB7d/g1dce3NLR1Zt9zH3nRNf/bi0XMc44pO4s6pEP6sVm7no3LtrMcXUj5yUON6iMWRXmH6VoUg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body{
            padding-top: 3rem;
        }
        .container {
            width: 400px;
        }
    </style>
</head>
<body>
<div class="container">
    <!-- Form to add User -->
    <h3>Add New User</h3>
    <form action="?controller=actors&action=add" method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="field">
                <label>Name: <input type="text" name="name"></label>
            </div>
        </div>
        <div class="row">
            <div class="field">
                <label>Surname: <input type="text" name="surname"></label>
            </div>
        </div>
        <?php foreach($films as $film):?>
            <div class="row">
                <div class="field">
                    <label><?=$film['name']?>: <input type="checkbox" name='<?=$film['id']?>'></label>
                </div>
            </div>
        <?php endforeach;?>
        <input type="submit" class="btn" value="Add">
        <a href="/?controller=actors" class="btn">Table</a>
        <a class="btn" href="?controller=index&action=logout">Logout</a>
        <?php if (isset($_GET['error'])) : ?>
        <span style="color: #ca2525;"><?= $_GET['error']; ?></span>
        <?php endif; ?>
    </form>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>