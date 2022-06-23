<!doctype html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport"
         content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
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
       <h3>Control Panel</h3>
       <form action="/?controller=index" method="post">
           <div class="row">
               <div class="field">
                   <label>Email: <input type="email" name="email"></label>
               </div>
           </div>
           <div class="row">
               <div class="field">
                   <label>Password: <input type="password" name="password"><br></label>
               </div>
           </div>
           <input type="submit" class="btn" value="Login">
           <input type="submit" class="btn" value="Sign Up" name="sign">
       </form>
       <div>
           <a href="?controller=films">List of Films</a>
       </div>
</div>
</body>
</html>
