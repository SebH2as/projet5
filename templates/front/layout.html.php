<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?= $title ?></title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./styles/front/styleFront.css">
    </head>
    <body>
        <nav id="navBar">
        <?php if ($preview === 1): ?>
            <div id="navTop">
                <a class="navTopLink" >Qui sommes nous?</a>
                <a class="navTopLink" >Nous rejoindre</a>
                <a class="navTopLink" >Se connecter</a>
            </div>
            <div id="logo" ><?= $separator ?><span>KILOMETRAGE</span></div>
            <div id="navLinks">
                <a class="navBottomLink fa fa-home" ></a>
                <a class="navBottomLink" >CHRONIQUES</a>
                <a class="navBottomLink" >ESSAIS</a>
                <a class="navBottomLink" >FICTIONS</a>
                <!--<a class="navBottomLink fa fa-search"></a>-->
            </div>           
            <div id="popupInfos">
                <p>infos</p>
            </div>
            <?php else: ?>
                <div id="navTop">
                <a class="navTopLink" href="">Qui sommes nous?</a>
                <a class="navTopLink" href="index.php?action=nousRejoindre&amp;idMag=<?= $magazine[0]->idMag ?>">Nous rejoindre</a>
                <?php if (isset($user)): ?>
                <a class="navTopLink" href="index.php?action=monCompte&amp;idMag=<?= $magazine[0]->idMag ?>">Mon compte(<?=$user->pseudo?>)</a>
                <?php else: ?>
                <a class="navTopLink" href="index.php?action=connectionPage&amp;idMag=<?= $magazine[0]->idMag ?>">Se connecter</a>
                <?php endif; ?>
            </div>
            <div id="logo"><?= $separator ?><span>KILOMETRAGE</span></div>
            <div id="navLinks">
                <a class="navBottomLink fa fa-home<?php if ($active === 1) echo ' active' ?>" href="index.php"></a>
                <a class="navBottomLink<?php if ($active === 2) echo ' active' ?>" href="index.php?action=chroniques&amp;idMag=<?= $magazine[0]->idMag ?>">CHRONIQUES</a>
                <a class="navBottomLink<?php if ($active === 3) echo ' active' ?>" href="index.php?action=essais&amp;idMag=<?= $magazine[0]->idMag ?>">ESSAIS</a>
                <a class="navBottomLink<?php if ($active === 4) echo ' active' ?>" href="index.php?action=fictions&amp;idMag=<?= $magazine[0]->idMag ?>">FICTIONS</a>
                <!--<a class="navBottomLink fa fa-search"></a>-->
            </div>           
            <?php endif; ?>
        </nav>

        <?= $content ?>

        <footer>
            <div id="media"><a class="fa fa-facebook-square" href=""></a><a class="fa fa-twitter-square" href=""></a><a class="fa fa-youtube-square" href=""></a></div>
        </footer>
        <?= $script ?>
    </body>
</html>