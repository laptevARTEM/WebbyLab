<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mini.css/3.0.1/mini-nord.css" integrity="sha512-hsKycmJmiBoB7d/g1dce3NLR1Zt9zH3nRNf/bi0XMc44pO4s6pEP6sVm7no3LtrMcXUj5yUON6iMWRXmH6VoUg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
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
    <h3>Add New Film</h3>
    <form action="?controller=films&action=add" method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="field">
                <label>Name: <input type="text" name="name" reuqired="" id="name" onChange="checkParams()"></label>
            </div>
        </div>
        <div class="row">
            <div class="field">
                <label>Year: <input type="number" name="year" reuqired="" id="year" onChange="checkParams()"><br></label>
            </div>
        </div>
        <div class="row">
            <div class="field">
            <select name="format" id="format">
                <option value = "VHS">VHS</option>
                <option value = "DVD">DVD</option>
                <option value = "Blue-ray">Blue-ray</option>
            </select>
            </div>
        </div>
        <input type="submit" class="btn" value="Add" disabled id="submit">
        <a href="/?controller=films" class="btn">Table</a>
        <a class="btn" href="?controller=index&action=logout">Logout</a>
        <?php if (isset($_GET['error'])) : ?>
        <span style="color: #ca2525;"><?= $_GET['error']; ?></span>
        <?php endif; ?>
    </form>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
<script>
    function checkParams(){
        var name = $('#name').val();
        var year = $('#year').val();
        if(name.trim()!="" && year>=1850 && year<=2022){
            $('#submit').removeAttr('disabled');
        }else{
            if(name==""||year==""){
                $('#submit').attr('disabled', 'disabled');
            }
            if(name.trim()==""&&name!=""){
                alert("Name must not be empty");
                $('#name').val('');
                $('#submit').attr('disabled', 'disabled');
            }
            if((year<1850||year>2022)&&year!=''){
                alert("Incorrect year. Set year between 1850 and 2022");
                $('#year').val('');
                $('#submit').attr('disabled', 'disabled');
            }
        }
    }
</script>
</html>