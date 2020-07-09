<?php $title = 'Nous rejoindre'; ?>
<?php $separator = '<div id="separator01"></div>'; ?>
<?php $script = '<script src="js/navbar.js"></script>'; ?>
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

            <h2>Formulaire d'inscription</h2>
            <form id="formSignIn" action="index.php?action=connection" method="post">
                <label for="pseudo">Choisissez un pseudo <i class=" fa fa-circle"><i class=" fa fa-info"></i></i></label>
                <input type="text" id="pseudo" name="pseudo" maxlength="70" size="70">
                
                <label for="mail">Entrez votre adresse mail <i class=" fa fa-circle"><i class=" fa fa-info"></i></i></label>
                <input type="text" id="mail" name="mail" maxlength="70" size="70">

                <label for="mail2">Confirmez votre adresse mail</label>
                <input type="text" id="mail2" name="mail2" maxlength="70" size="70">
                
                <label for="password">Choisissez un mot de passe <i class=" fa fa-circle"><i class=" fa fa-info"></i></i></label>
                <input type="text" id="password" name="password" maxlength="70" size="70">  
                
                <label for="password2">Confirmez votre mot de passe</label>
                <input type="text" id="password2" name="password2" maxlength="70" size="70">  
                
                <input type="submit" id="register" value="Créer votre compte">
        </section>