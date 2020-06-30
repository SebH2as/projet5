<?php $title = 'Gestion magazine'; ?>

<section id="mag">
            <h2>magazine numéro <?= $magazine[0]->numberMag ?> créée le <?= $magazine[0]->dateMag ?></h2>
            <span id="message" class="<?php if ($message === null) echo 'none' ?>"><?= $message ?></span>

            <div class="buttonsPannel"> 
                <a class="buttonPannel" id="online">Mettre en ligne</a>
                <a class="buttonPannel" id="preview" href="index.php?action=previewMag&amp;idMag=<?= $magazine[0]->idMag ?>" target="_blank">Aperçu</a>
                <a class="buttonPannel" id="delete" href="index.php?action=deleteMag&amp;idMag=<?= $magazine[0]->idMag ?>">Supprimer</a>
            </div>
            
            <div id="contentContainer">
                <form id="formMag" method="POST" action="index.php?action=modifyMag&amp;idMag=<?= $magazine[0]->idMag ?>">
                    
                    <h3>Revue numéro: <i><?= $magazine[0]->numberMag ?></i></h3>
                    <div class="formRow">
                        <div class="labelInput">
                            <label for="number">Modifier le numéro de la revue:</label>
                            <input type="number" id="number" name="number" min="1" max="50">    
                        </div>
                        <input type="submit" name="modifNumber" value="Modifier">
                    </div>
                    
                    <h3>Date de parution: <i><?php if(($magazine[0]->publication) === null) echo 'à définir'?>
                                        <?php if(($magazine[0]->publication) !== null) echo $magazine[0]->publication ?></i></h3>
                    <div class="formRow">
                        <div class="labelInput">
                            <label for="parution">Modifier la date de parution:</label>
                            <input type="text" id="parution" name="parution" maxlength="20" size="20">    
                        </div>
                        <input type="submit" name="modifPubli" value="Modifier">
                    </div>
                    
                    <h3>Thématique(s): <i><?php if(($magazine[0]->topics) === null) echo 'à définir'?>
                                        <?php if(($magazine[0]->topics) !== null) echo $magazine[0]->topics ?></i></h3>
                    <div class="formRow">
                        <div class="labelInput">
                            <label for="topics">Modifier la thématique:</label>
                            <input type="text" id="topics" name="topics" maxlength="30" size="30">    
                        </div>
                        <input type="submit" name="modifTopics" value="Modifier">
                    </div>
                    
                    <h3>Couverture l'image: <i><?= $magazine[0]->cover ?></i></h3>
                    <div class="formRow">
                        <div class="labelInput">
                            <label for="cover">Changer la couverture:</label>
                            <input type="text" id="cover" name="cover" maxlength="30" size="30">    
                        </div>
                        <input type="submit" value="Changer">
                    </div>
                    
                    <h3>Titre principal: <i><?php if(($magazine[0]->title01) === null) echo 'à définir'?>
                                        <?php if(($magazine[0]->title01) !== null) echo $magazine[0]->title01 ?></i></h3>
                    <div class="formRow">
                        <div class="labelInput">
                            <label for="title01">Modifier le titre principal:</label>
                            <input type="text" id="title01" name="title01" maxlength="70" size="70">    
                        </div>
                        <input type="submit" name="modifTitle01" value="Modfier">
                    </div>
                    
                    <h3>Titre secondaire: <i><?php if(($magazine[0]->title02) === null) echo 'à définir'?>
                                        <?php if(($magazine[0]->title02) !== null) echo $magazine[0]->title02 ?></i></h3>
                    <div class="formRow">
                        <div class="labelInput">
                            <label for="title02">Modifier le titre secondaire:</label>
                            <input type="text" id="title02" name="title02" maxlength="70" size="70">    
                        </div>
                        <input type="submit" name="modifTitle02" value="Modfier">
                    </div>
                    
                    <h3>Editorial: <i><?php if(($magazine[0]->editorial) === null || strlen($magazine[0]->editorial) === 0 ) echo 'non rédigé'?>
                                    <?php if(strlen($magazine[0]->editorial) > 0) echo 'rédigé'?></i></h3>
                    <div class="formRow">
                        <div class="labelInput">
                            <label for="title02">Modifier ou relire l'éditorial:</label>  
                        </div>
                        <input type="submit" name="modifEdito" value="Modfier ou relire">
                    </div>
                </form>
            </div>
        </section>
        <section id="articlesMag">
            <?php if (($magazine[0]->articlesNb) == 0): ?>
            <h2>Aucun article associé</h2>
            <div class="buttonsPannel"> 
                <a class="buttonPannel" id="createArticle" href="index.php?action=createNewArticle&amp;idMag=<?= $magazine[0]->idMag ?>">Créer un nouvel article</a>
            </div>
            <?php else: ?>
            <h2>Articles associés</h2>

            <div class="buttonsPannel"> 
                <a class="buttonPannel" id="createArticle" href="index.php?action=createNewArticle&amp;idMag=<?= $magazine[0]->idMag ?>">Créer un nouvel article</a>
            </div>
            <div id="tableContainer">
                <div id="tableTitles">
                    <span class="columnTitle">Rubrique</span>
                    <span class="columnTitle">Titre</span>
                    <span class="columnTitle">Auteur</span>
                    <span class="columnTitle">Image associée</span>
                    <span class="columnTitle">Date de création</span>
                </div>
                <?php foreach($magazine as $article): ?>
                <a id="tableRows" href="index.php?action=modifyArticle&amp;idMag=<?= $magazine[0]->idMag ?>&amp;idText=<?= $article->id_text ?>">
                    <span class="tableContent"><?= $article->textType ?></span>
                    <span class="tableContent"><?= $article->title ?></span>
                    <span class="tableContent"><?= $article->author ?></span>
                    <span class="tableContent"><?= $article->articleCover ?></span>
                    <span class="tableContent"><?= $article->dateArticle ?></span>
                </a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>    
        </section>