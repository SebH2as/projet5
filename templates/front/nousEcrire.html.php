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
                <h2>NOUS ECRIRE</h2>
                <div id="separator02"></div>
            </div>
            <div id="infos">
                <span>
                   Vous pouvez nous écrire un courrier relatif à un magazine particulier à l'aide du champs ci-dessous.
                   Vous serez eventuellement publié dans la rubrique Courrier des lecteurs du numéro suivant et
                   nous vous répondrons à cette occasion.
                </span>
            </div>
            <span id="error" class="<?php if ($error === null) echo 'none' ?>"><?= $error ?></span>
            
            <form id="formLetter" action="index.php?action=postLetter&amp;idMag=<?= $magazine[0]->idMag ?>&amp;message=1" method="post">
            
                <label for="courrier">Courrier:</label>
                <textarea id="courrier" name="courrier" rows="15" cols="50"></textarea>
                
                <label for="numberMag">Choisissez un numéro de magazine auquel associer ce courrier:</label>
                            <select list="numberMag" name="numberMag">
                                <?php foreach($numberMags as $number): ?>  
                                <option value="<?= $number->numberMag ?>">Magazine numéro <?= $number->numberMag ?></option>
                                <?php endforeach; ?>
                            </select> 

                <input type="submit" id="saver" value="Poster votre courrier">
            </form>
        </section>