<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?= $title ?></title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="./styles/back/styleBack.css">
        <script src="https://cdn.tiny.cloud/1/megygorehyq07d08ikdzcz9cckf4kuxryrgvmc533dogxs8y/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    </head>
    <body>
        <nav id="navBack">
            <h2>Kilométrage - Interface Administrateur</h2>
            <div id="backLinks">
                <div class="dropDown">
                    <span href="">Revue <i class="fa fa-caret-down"></i></span>
                    <div class="dropdown-content"> 
                        <a href="index.php?action=listMag">gestion des magazines créés</a>
                        <a href="index.php?action=newMag">Créer un nouveau magazine</a>
                    </div>
                </div>
                <div class="dropDown">
                    <span href="">Courrier <i class="fa fa-caret-down"></i></span>
                    <div class="dropdown-content">
                        <a href="index.php?action=courrier">Gestion du courrier</a>
                        <a href="#">Gestion des newsletters</a>
                    </div>
                </div>
                <div class="dropDown">
                    <span href="">Utilisateurs <i class="fa fa-caret-down"></i></span>
                    <div class="dropdown-content">
                        <a href="#">Administrateur</a>
                        <a href="#">Membres</a>
                    </div>
                </div>
                <div class="dropDown">
                    <a href="index.php?action=userDeco">Déconnection</a>
                </div>
            </div>
        </nav>

        <?= $content ?>

        <script>
            tinymce.init({
              selector: 'textarea#writtingSpace',
              plugins: 'advlist link image lists',
              menubar: 'none',
              toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | outdent indent | backcolor | save'
            });
          </script>
    </body>
</html>