<?php $title = 'Nous écrire'; ?>
<?php $separator = '<div id="separator01"></div>'; ?>
<?php $script = '<script src="js/navbar.js"></script>'; ?>
<?php $preview = 0 ; ?>
<?php $active = 0 ; ?>


<div id="sectionImg">
            <img class="rubricImg" src="images/<?= $magazine[0]->cover ?>" alt="graff">
        </div>
        <section id="article">
            <div id="titleArticle">
                <h2>MODIFIER MON PSEUDO</h2>
                <div id="separator02"></div>
            </div>
            <div id="infos">
                <span>
                   Vous pouvez modifier votre pseudo, votre mail et votre mot de passe à l'aide du formulaire ci-dessous.
                   Il est obligatoire de renseigner votre mot de passe actuel mais vous pouvez laisser vide les champs que vous
                   ne voulez pas modifier.
                </span>
            </div>
            <span id="error" class="<?php if ($error === null) echo 'none' ?>"><?= $error ?></span>
            <form id="formSignIn" action="index.php?action=modifPseudo&amp;idMag=<?= $magazine[0]->idMag ?>&amp;message=4" method="post">
                <input type="hidden" name="csrf" value="<?php echo $token ?>">
                
                <label for="passwordOld">Veuillez saisir votre Mdp actuel:</label>
                <input type="passwordOld" id="passwordOld" name="passwordOld" maxlength="70" size="70">

                <label for="pseudoNew">Veuillez entrer un nouveau Pseudo:</label>
                <input type="text" id="pseudoNew" name="pseudoNew" maxlength="70" size="70">

                <input type="submit" id="saver" value="Modifier">
            </form>
        </section>