<?php $title = 'admin Modif'; ?>


    <section id="admin">
            <h1>Vous pouvez changer ici votre pseudo, votre mail et votre mot de passe d'administrateur</h1>
            <span id="message" class="<?php if ($message === null) echo 'none' ?>"><?= $message ?></span>
            <span id="error" class="<?php if ($error === null) echo 'none' ?>"><?= $error ?></span>
            
            <form id="formProfil" action="index.php?action=reset" method="post">
                <div class="formSeparator">
                    <h3>Nouveau pseudo</h3>
                    <input title="pseudo" class="champ" type="text" name="pseudo" id="pseudo" placeholder="" size="25"/>
                    <label id="labelPseudo" for="pseudo"></label>
                    <h3>Mot de passe</h3>
                    <input title="pass01" type="password" name="pass01" class="password" placeholder="" size="50"/>
                    <label id="labelPassPseudo" for="pass01"></label>
                    <input type="submit" name="resetPseudo" class="buttonReset" value="Changer">
                </div>

                <div class="formSeparator">                      
                    <h3>Nouvel email</h3>
                    <input title="email" class="champ" type="text" name="email" id="email" placeholder="" size="25"/>
                    <label id="labelEmail" for="email"></label>
                    <h3>Ressaisissez le nouvel email</h3>
                    <input title="email02" class="champ" type="text" name="email02" id="email02" placeholder="" size="25"/>
                    <label id="labelEmail02" for="email02"></label>
                    <h3>Mot de passe</h3>
                    <input title="pass02" type="password" name="pass02" class="password" placeholder="" size="50"/>
                    <label id="labelPassEmail" for="pass02"></label>
                    <input type="submit" name="resetEmail" class="buttonReset" value="Changer">
                </div>    

                <div class="formSeparator">     
                    <h3>Nouveau de mot de passe</h3>
                    <input title="passwordNew" type="password" name="passwordNew" class="password" placeholder="" size="50"/>
                    <label id="labelPass" for="passwordNew"></label>
                    <h3>Ressaisissez le nouveau mot de passe</h3>
                    <input title="passwordNew2" type="password" name="passwordNew2" class="password" placeholder="" size="50"/>
                    <label id="labelPass2" for="passwordNew2"></label>
                    <h3>Ancien mot de passe</h3>
                    <input title="pass03" type="password" name="pass03" class="password" placeholder="" size="50"/>
                    <label id="labelPassPw" for="pass03"></label>
                    <input type="submit" name="resetPass" class="buttonReset" value="Changer">
                </div>
            </form>
    </section>