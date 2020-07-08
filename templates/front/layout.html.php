<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?= $title ?></title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="./styles/front/styleFront.css">
    </head>
    <body>
        <nav id="navBar">
            <div id="navTop">
                <a class="navTopLink" href="">Qui sommes nous?</a>
                <a class="navTopLink" href="index.php?action=nousRejoindre&amp;idMag=<?= $magazine[0]->idMag ?>">Nous rejoindre</a>
                <a class="navTopLink" href="index.php?action=monCompte&amp;idMag=<?= $magazine[0]->idMag ?>">Mon compte</a>
            </div>
            <div id="logo" href="index.php"><?= $separator ?><span>KILOMETRAGE</span></div>
            <div id="navLinks">
                <a class="navBottomLink fa fa-home" href="index.php"></a>
                <a id="link01" class="navBottomLink" href="index.php?action=chronics&amp;idMag=<?= $magazine[0]->idMag ?>"">CHRONIQUES</a>
                <a class="navBottomLink" href="index.php?action=essais&amp;idMag=<?= $magazine[0]->idMag ?>"">ESSAIS</a>
                <a class="navBottomLink" href="index.php?action=fictions&amp;idMag=<?= $magazine[0]->idMag ?>"">FICTIONS</a>
                <a class="navBottomLink fa fa-search" href=""></a>
            </div>           
            <div id="popupInfos">
                <p>infos</p>
            </div>
        </nav>

        <?= $content ?>

        <footer>
            <div id="media"><a class="fa fa-facebook-square" href=""></a><a class="fa fa-twitter-square" href=""></a><a class="fa fa-youtube-square" href=""></a></div>
        </footer>
        <?= $script ?>
    </body>
</html>