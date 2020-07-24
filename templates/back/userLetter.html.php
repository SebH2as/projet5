<?php $title = 'Courrier'; ?>

        <section id="courrier">
            <?php if (empty($letter[0])): ?>
            <h2>Aucun courrier ne correspond à cet identifiant</h2>
            <?php else: ?>
            <h2>Courrier de <?= $letter[0]->author ?></h2>
            <h3>Reçu le <?= $letter[0]->post_date ?></h3>

            <div class="buttonsPannel"> 
                <a class="buttonPannel" id="delete" href="index.php?action=deleteMag">Supprimer</a>
            </div>

            <div id="contentContainer">
                <form id="formMag" method="POST" enctype="multipart/form-data" action="index.php?action=modifyMag">
                    <h3>Magazine associé: <i><?php if ($letter[0]->magRelated === null): ?> aucun
                                            <?php else: ?> <?= $letter[0]->magRelated ?><?php endif; ?></i></h3>
                        <div class="formRow">
                            <div class="labelInput">
                                <label for="numberMag">Modifier le magazine associé:</label>
                                    <select list="numberMag" name="numberMag">
                                        <?php foreach($numberMags as $number): ?>  
                                        <option value="<?= $number->numberMag ?>">Magazine numéro <?= $number->numberMag ?></option>
                                        <?php endforeach; ?>
                                    </select>     
                            </div>
                            <input type="submit" class="button01" name="modifMag" value="Modifier">
                        </div>
                </form>
            </div>

            <div id="letterContent">
                <h3>Contenu du courrier</h3>
                <p><?= $letter[0]->content ?></p>
            </div>  
            
            <div id="response">
                <h3>Répondre</h3>
                <form id="formEditArticle" action="index.php?action=addResponse" method="POST">
                    <input type="submit" name="saveEdito" value="Enregistrer la réponse"> 
                    
                    <textarea id="writtingSpace" name="contentEdito">
                        
                    </textarea>
                    <input type="submit" name="saveEdito" value="Enregistrer la réponse">
                </form>
            </div>
            

            <?php endif; ?>
        </section>