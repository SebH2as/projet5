<?php $title = 'Nous rejoindre'; ?>
<?php $separator = '<div id="separator01"></div>'; ?>
<?php $script = '<script src="js/navbar.js"></script>
                <script src="js/infoBox.js"></script>
                <script src="js/formSave.js"></script>
                <script src="js/mainJoin.js"></script>'; ?>
<?php $preview = 0 ; ?>
<?php $active = 0 ; ?>

<div id="sectionImg">
            <img class="rubricImg" src="images/<?= $magazine[0]->cover ?>" alt="graff">
        </div>
        <section id="article">
            <div id="titleArticle">
                <h2></h2>
                <h1>NOUS REJOINDRE</h1>
                <h2></h2>
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
            <h2>Formulaire d'inscription</h2>
            <form id="formSignIn" action="index.php?action=addUser&amp;idMag=<?= $magazine[0]->idMag?> " method="post">
                <input type="hidden" name="csrf" value="<?php echo $token ?>">
                
                <label for="pseudo">Choisissez un pseudo <i class=" fa fa-circle"><i class=" fa fa-info"></i><div class="infoBox hidden"><span>Votre pseudo doit avoir au moins 3 caractères et pas plus de 15. Pas de caractère spéciaux.</span></div></i></label>
                <input type="text" id="pseudo" name="pseudo" maxlength="15" size="15">
                
                <label for="mail">Entrez votre adresse mail <i class=" fa fa-circle"><i class=" fa fa-info"></i><div class="infoBox hidden"><span>Veuillez entrer une adresse mail valide, vous recevrez un mail de confirmation pour valider votre inscription.</span></div></i></label>
                <input type="text" id="mail" name="mail" maxlength="50" size="50">

                <label for="mail2">Confirmez votre adresse mail</label>
                <input type="text" id="mail2" name="mail2" maxlength="50" size="50">
                
                <label for="password">Choisissez un mot de passe <i class=" fa fa-circle"><i class=" fa fa-info"></i><div class="infoBox hidden"><span>Votre mot de passe doit avoir au moins 8 caractères, pas plus de 50 et doit comporter au moins une majuscule, un caractère spécial et un chiffre.</span></div></i></label>
                <input type="password" id="password" name="password" maxlength="50" size="50">  
                
                <label for="password2">Confirmez votre mot de passe</label>
                <input type="password" id="password2" name="password2" maxlength="50" size="50">  
                
                <input type="submit" id="saver" name="saver" value="Créer votre compte">
        </section>