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
                <h2>MODIFIER MES INFORMATIONS PERSONNELLES</h2>
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
            <form id="formSignIn" action="index.php?action=modifInfos&amp;idMag=<?= $magazine[0]->idMag ?>&amp;message=0" method="post">
            
                <label for="passwordOld">Veuillez saisir votre Mdp actuel:</label>
                <input type="passwordOld" id="passwordOld" name="passwordOld" maxlength="70" size="70">
                
                <label for="passwordNew">Veuillez saisir un nouveau Mdp :</label>
                <input type="passwordNew" id="passwordNew" name="passwordNew" maxlength="70" size="70">  

                <label for="passwordNew2">Veuillez confirmer votre nouveau Mdp :</label>
                <input type="passwordNew2" id="passwordNew2" name="passwordNew2" maxlength="70" size="70">

                <label for="pseudoNew">Veuillez entrer un nouveau Pseudo:</label>
                <input type="text" id="pseudoNew" name="pseudoNew" maxlength="70" size="70">

                <label for="mailNew">Veuillez entrer une nouvelle adresse mail:</label>
                <input type="text" id="mailNew" name="mailNew" maxlength="50" size="50">

                <label for="mailNew">Veuillez confirmer votre nouvelle adresse mail:</label>
                <input type="text" id="mailNew" name="mailNew" maxlength="50" size="50">

                <input type="submit" id="saver" value="Modifier">
            </form>
        </section>