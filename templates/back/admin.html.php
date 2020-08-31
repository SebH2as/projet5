<?php $title = 'admin Modif'; ?>


    <section id="admin">
            <h1>Vous pouvez changer ici votre pseudo, votre mail et votre mot de passe d'administrateur</h1>
            <h3><em>Votre pseudo doit avoir un nombre de caractère compris entre 4 et 15.</em></h3>
            <h3><em>Votre pseudo ne doit pas comporter de caractères spéciaux.</em></h3>
            <h3><em>Vérifiez bien la validité de votre Email.</em></h3>
            <h3><em>Votre mot de passe doit avoir un nombre de caractères compris entre 8 et 50.</em></h3>
            <h3><em>Votre mot de passe doit comporter au moins une minuscule, une majuscule, un caractère spécial et un chiffre.</em></h3>
            
            <form id="formProfil" action="index.php?action=reset" method="post">
                
                <h3>Nouveau pseudo</h3>
                <input title="pseudo" class="champ" type="text" name="pseudo" id="pseudo" placeholder="" size="25"/>
                <label id="labelPseudo" for="pseudo"></label>
                <h3>Nouvel email</h3>
                <input title="email" class="champ" type="text" name="email" id="email" placeholder="" size="25"/>
                <label id="labelEmail" for="email"></label>
                <h3>Ancien mot de passe</h3>
                <input title="passOld" type="password" name="passOld" class="password" placeholder="" size="50"/>
                <label id="labelPass" for="pass"></label>
                <h3>Nouveau de mot de passe</h3>
                <input title="pass" type="password" name="pass" class="password" placeholder="" size="50"/>
                <label id="labelPass" for="pass"></label>
                <h3>Ressaisissez le nouveau mot de passe</h3>
                <input title="pass2" type="password" name="pass2" class="password" placeholder="" size="50"/>
                <label id="labelPass2" for="pass2"><?php if(isset($error)) echo $error ?></label>
                <input type="submit" name="reset" class="buttonReset" value="Valider">
            </form>
    </section>