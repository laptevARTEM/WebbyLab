<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mini.css/3.0.1/mini-nord.css" integrity="sha512-hsKycmJmiBoB7d/g1dce3NLR1Zt9zH3nRNf/bi0XMc44pO4s6pEP6sVm7no3LtrMcXUj5yUON6iMWRXmH6VoUg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Document</title>
</head>
<body>
    <div class="container">
        <a class="btn" href="?controller=actors&action=addForm">add new actor</a>
        <a class="btn" href="?controller=films">list of films</a>
        <a class="btn" href="?controller=index&action=logout">Logout</a>
        <div class="output">
            <?php foreach ($actors as $actor):?>
                <p>Actor: <a href="?controller=films&action=search_by_actor_name&name=<?=$actor['name']?>&surname=<?=$actor['surname']?>"><?=$actor['name']?> <?=$actor['surname']?></a></p>
                <p>Films: 
                    <?php 
                        $id = $actor['id'];
                        if(isset($films[$id])){
                            for($i=0;$i<count($films[$id]);$i++){?>
                            <span><?=$films[$id][$i]['name']?>, </span>
                            <?php }?>
                        <?php }?>
                </p>
            <?php endforeach;?>
        </div>
    </div>
</body>
</html>