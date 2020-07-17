<?php $title = 'mon compte'; ?>
<?php $separator = '<div id="separator01"></div>'; ?>
<?php $script = '<script src="js/navbar.js"></script>'; ?>
<?php $preview = 0 ; ?>
<?php $active = 0 ; ?>


        <div id="sectionImg">
            <img class="rubricImg" src="images/<?= $magazine[0]->cover ?>" alt="graff">
        </div>
        <section id="article">
            <div id="titleArticle">
                <h2>MON COMPTE</h2>
                <div id="separator02"></div>
            </div>
            <div id="infos">
                <span>
                   Bienvenu sur votre compte Kilométrage. Vous êtes connecté en tant que <b><?= $user->pseudo ?></b>. Vous pouvez changer vos informations personnelles, 
                   nous envoyer une lettre ou vous abonner à notre newsletter en cliquant sur les liens ci-dessous.
                </span>
            </div>
            <?php if (isset($message)): ?>
            <span id="message"><?= $message ?></span>
            <?php endif; ?>
            <div id="infosCount">
                <div id="infosPerso">
                    <h2>Vos informations personnelles</h2>
                    <p class="infos"><b>Pseudo:</b>   <?= $user->pseudo ?></p>
                    <p class="infos"><b>Mail:</b>   <?= $user->email ?></p>
                    <p class="infos"><b>Membre depuis le:</b>   <?= $user->dateUser ?></p>
                    <p class="infos"><b>Newsletter:</b> 
                    <?php if ( $user->newsletter === '0'): ?> 
                    Non abonné
                    <?php else: ?>
                    Abonné
                    <?php endif; ?>
                    </p>
                    <p class="infos"><b>Courrier(s) en attente(s) de validation:</b>  Aucun</p>
                    <p class="infos"><b>Courrier(s) publié(s):</b>  Aucun</p>
                </div>
                <div id="blockLinks">
                    <a class="countLinks" href="index.php?action=modifInfosUser&amp;idMag=<?= $magazine[0]->idMag ?>">Modifier mes informations personnelles</a>
                    <a class="countLinks" href="index.php?action=nousEcrire&amp;idMag=<?= $magazine[0]->idMag ?>">Nous écrire un courrier</a>
                    <?php if ( $user->newsletter === '0'): ?> 
                    <a class="countLinks" href="index.php?action=newsLetterAbo&amp;idMag=<?= $magazine[0]->idMag ?>&amp;message=2">S'abonner à notre newsletter</a>
                    <?php else: ?>
                    <a class="countLinks" href="index.php?action=newsLetterAbo&amp;idMag=<?= $magazine[0]->idMag ?>&amp;message=3">Se désabonner de notre newsletter</a>
                    <?php endif; ?>
                    <a class="countLinks" href="index.php?action=userDeco">Se déconnecter de mon compte</a>
                </div>
            </div>
            
            
            

            
        </section>