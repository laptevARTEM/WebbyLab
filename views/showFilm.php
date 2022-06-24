<!DOCTYPE html><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mini.css/3.0.1/mini-nord.css" integrity="sha512-hsKycmJmiBoB7d/g1dce3NLR1Zt9zH3nRNf/bi0XMc44pO4s6pEP6sVm7no3LtrMcXUj5yUON6iMWRXmH6VoUg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Document</title>
</head>
<body>
    <div class="container">
        <a class="btn" href="?controller=films&action=addForm">add new film</a>
        <a class="btn" href="?controller=index&action=logout">Logout</a>
        <a class="btn" href="?controller=films">Films List</a>
        <a class="btn" href="?controller=actors">Actors List</a>
        <div class="output">
                <p>Title: <a href="?controller=films&action=show&id=<?=$film['id']?>"><?=$film['name']?></a></p>
                <p>Year: <?=$film['year']?></p>
                <p>Format: <?=$film['format']?></p>
                <p>Actors: 
                    <?php 
                        $id = $film['id'];
                            if(isset($actors[$id])){
                                for($i=0;$i<count($actors[$id]);$i++){?>
                                    <span><a href="?controller=films&action=search_by_actor_name&name=<?= $actors[$id][$i]['name']?>&surname=<?=$actors[$id][$i]['surname']?>"><?= $actors[$id][$i]['name']?> <?= $actors[$id][$i]['surname']?><a>, </span>
                                <?php }?>
                            <?php }?>
                </p>
                <br>
        </div>
    </div>
</body>
</html>