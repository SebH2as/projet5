<?php $title = 'Nous rejoindre'; ?>
<?php $separator = '<div id="separator01"></div>'; ?>
<?php $script = '<script src="js/navbar.js"></script>
                <script src="js/infoBox.js"></script>'; ?>
<?php $preview = 0 ; ?>
<?php $active = 0 ; ?>

<div id="sectionImg">
            <img class="rubricImg" src="images/<?= $magazine[0]->cover ?>" alt="graff">
        </div>
        <section id="article">
            <div id="titleArticle">
                <h2>NOUS REJOINDRE</h2>
                <div id="separator02"></div>
            </div>
            <div id="infos">
                <span>
                    Pour devenir un membre de la communauté Kilométrage rien de plus simple: il vous
                    suffit de remplir le formulaire ci-dessous. Vous pourrez alors nous écrire via la rubrique courrier des
                    lecteurs, pour être éventuellement publié dans le numéro suivant, et vous recevrez, si vous le désirez, notre newsletter.
                </span>
            </div>
            <span id="error" class="<?php if ($error === null) echo 'none' ?>"><?= $error ?></span>
            <h2>confirmation</h2>
            <form id="formSignIn" action="index.php?action=addUser&amp;idMag=<?= $magazine[0]->idMag?> " method="post">
                <label for="pseudo">Entrez le code de confirmation <i class=" fa fa-circle"><i class=" fa fa-info"></i><div class="infoBox hidden"><span>Entrez le code de confirmation que vous avez reçu par email.</span></div></i></label>
                <input type="text" id="pseudo" name="pseudo" maxlength="15" size="15">
                
                <input type="submit" id="saver" name="saver" value="Confirmation">
        </section>