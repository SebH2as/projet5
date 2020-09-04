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
                <h2>ACTIVATION</h2>
                <div id="separator02"></div>
            </div>
            <div id="infos">
                <span>
                    Pour activer votre compte, saisissez le code que vous venez de recevoir par email.
                </span>
            </div>
            <span id="error" class="<?php if ($error === null) echo 'none' ?>"><?= $error ?></span>
            <h2>confirmation</h2>
            <form id="formSignIn" action="index.php?action=activation&amp;idMag=<?= $magazine[0]->idMag?> " method="post">
                <input type="hidden" name="csrf" value="<?php echo $token ?>">

                <label for="pseudo">Entrez votre pseudo <i class=" fa fa-circle"><i class=" fa fa-info"></i><div class="infoBox hidden"><span>Entrez le pseudo que vous avez choisi précédemment.</span></div></i></label>
                <input type="text" id="pseudo" name="pseudo" maxlength="15" size="15">
                
                <label for="pseudo">Entrez le code de confirmation <i class=" fa fa-circle"><i class=" fa fa-info"></i><div class="infoBox hidden"><span>Entrez le code de confirmation que vous avez reçu par email.</span></div></i></label>
                <input type="text" id="code" name="code" maxlength="15" size="15">
                
                <input type="submit" id="saver" name="saver" value="Activation">
            </form>
        </section>