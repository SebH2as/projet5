<?php $title = 'Gestion magazine'; ?>
<?php $script = '<script src="js/formSave.js"></script>
                <script src="js/infoBox.js"></script>
                <script src="js/mainPannelMag.js"></script>'; ?>

<section id="mag">
            <h2>magazine numéro <?= $data[0]->numberMag ?> créé le <?= $data[0]->dateMag ?></h2>
            <span id="message" class="<?php if ($message === null) echo 'none' ?>"><?= $message ?></span>

            <div class="buttonsPannel"> 
                <?php if($data[0]->statusPub === '0'): ?>
                <a class="buttonPannel" id="online" href="index.php?action=setOnlineMag&amp;idMag=<?= $data[0]->idMag ?>">Mettre en ligne</a>
                <?php endif; ?>
                <?php if($data[0]->statusPub === '1'): ?>
                <a class="buttonPannel" id="online" href="index.php?action=setSavedMag&amp;idMag=<?= $data[0]->idMag ?>">Sauvegarder</a>
                <?php endif; ?>
                <a class="buttonPannel" id="preview" href="index.php?action=previewMag&amp;idMag=<?= $data[0]->idMag ?>" target="_blank">Aperçu</a>
                <a class="buttonPannel" id="preview" href="#articlesMag">Articles associés</a>
                <a class="buttonPannel" id="delete" href="index.php?action=deleteMag&amp;idMag=<?= $data[0]->idMag ?>">Supprimer</a>
            </div>
            
            <div id="contentContainer">
                <form id="formMag" method="POST" enctype="multipart/form-data" action="index.php?action=modifyMag&amp;idMag=<?= $data[0]->idMag ?>">
                    <!--<h3>Revue numéro: <i><?= $data[0]->numberMag ?></i></h3>
                    <div class="formRow">
                        <div class="labelInput">
                            <label for="number">Modifier le numéro de la revue:</label>
                            <input type="number" id="number" name="number" min="1" max="50">    
                        </div>
                        <input type="submit" name="modifNumber" value="Modifier">
                    </div>-->
                    <input type="hidden" name="csrf" value="<?php echo $token ?>">

                    <h3><i class="fa fa-arrow-right"></i> Date de parution: <i><?php if(($data[0]->publication) === null) echo 'à définir'?>
                                        <?php if(($data[0]->publication) !== null) echo $data[0]->publication ?></i></h3>
                    <div class="formRow">
                        <div class="labelInput">
                            <label for="parution">Modifier la date de parution:<i class=" fa fa-circle"><i class=" fa fa-info"></i><div class="infoBox hidden"><span>30 signes maximum</span></div></i></label>
                            <input type="text" id="parution" name="parution" maxlength="30" size="30">    
                        </div>
                        <input type="submit" class="button01" name="modifPubli" value="Modifier" >
                    </div>
                    
                    <!--<h3>Thématique(s): <i><?php if(($data[0]->topics) === null) echo 'à définir'?>
                                        <?php if(($data[0]->topics) !== null) echo $data[0]->topics ?></i></h3>
                    <div class="formRow">
                        <div class="labelInput">
                            <label for="topics">Modifier la thématique:</label>
                            <input type="text" id="topics" name="topics" maxlength="30" size="30">    
                        </div>
                        <input type="submit" class="button01" name="modifTopics" value="Modifier">
                    </div>-->
                    
                    <h3><i class="fa fa-arrow-right"></i> Image de couverture: <i><?= $data[0]->cover ?></i></h3>
                    <div class="formRow">
                        <div class="labelInput">
                            <label for="cover">Changer la couverture:<i class=" fa fa-circle"><i class=" fa fa-info"></i><div class="infoBox hidden"><span>Uniquement .png ou .jpg</span></div></i></label>
                            <input type="file" id="cover" name="cover">    
                        </div>
                        <input type="submit" class="button01" name="modifCover" value="Changer">
                    </div>
                    
                    <h3><i class="fa fa-arrow-right"></i> Titre 1: <i><?php if(($data[0]->title01) === null) echo 'à définir'?>
                                        <?php if(($data[0]->title01) !== null) echo $data[0]->title01 ?></i></h3>
                    <div class="formRow">
                        <div class="labelInput">
                            <label for="title01">Modifier le titre 1:<i class=" fa fa-circle"><i class=" fa fa-info"></i><div class="infoBox hidden"><span>70 signes maximum</span></div></i></label>
                            <input type="text" id="title01" name="title01" maxlength="70" size="70">    
                        </div>
                        <input type="submit" class="modifTitle" name="modifTitle01" value="Modifier">
                        <input type="submit" class="deleteTitle" name="deleteTitle01" value="Supprimmer">
                    </div>
                    
                     <h3><i class="fa fa-arrow-right"></i> Titre 2: <i><?php if(($data[0]->title02) === null) echo 'à définir'?>
                                        <?php if(($data[0]->title02) !== null) echo $data[0]->title02 ?></i></h3>
                    <div class="formRow">
                        <div class="labelInput">
                            <label for="title02">Modifier le titre 2:<i class=" fa fa-circle"><i class=" fa fa-info"></i><div class="infoBox hidden"><span>70 signes maximum</span></div></i></label>
                            <input type="text" id="title02" name="title02" maxlength="70" size="70">    
                        </div>
                        <input type="submit" class="modifTitle" name="modifTitle02" value="Modifier">
                        <input type="submit" class="deleteTitle" name="deleteTitle02" value="Supprimmer">
                    </div>
                    
                    <h3><i class="fa fa-arrow-right"></i> Editorial: <i><?php if(($data[0]->editorial) === null || strlen($data[0]->editorial) === 0 ) echo 'non rédigé'?>
                                    <?php if(strlen($data[0]->editorial) > 0) echo 'rédigé'?></i></h3>
                    <div class="formRow">
                        <div class="labelInput">
                            <label for="title02">Modifier ou relire l'éditorial:</label>  
                        </div>
                        <input type="submit" class="button01" name="modifEdito" value="Modfier ou relire">
                    </div>
                </form>
            </div>
        </section>
        <section id="articlesMag">
            <?php if (($data[0]->articlesNb) == 0): ?>
            <h2>Aucun article associé</h2>
            <div class="buttonsPannel"> 
                <a class="buttonPannel" id="createArticle" href="index.php?action=createNewArticle&amp;idMag=<?= $data[0]->idMag ?>">Créer un nouvel article</a>
            </div>
            <?php else: ?>
            <h2>Articles associés</h2>

            <div class="buttonsPannel"> 
                <a class="buttonPannel" id="createArticle" href="index.php?action=createNewArticle&amp;idMag=<?= $data[0]->idMag ?>">Créer un nouvel article</a>
            </div>
            <div id="tableContainer">
                <div id="tableTitles">
                    <span class="columnTitle">Date de création</span>
                    <span class="columnTitle">Rubrique</span>
                    <span class="columnTitle">Titre</span>
                    <span class="columnTitle">Auteur</span>
                    <span class="columnTitle">Image associée</span>
                    <span class="columnTitle">Article à la une</span>
                    <span class="columnTitle">Teaser</span>
                </div>
                <?php foreach($data as $article): ?>
                <a class="tableRows" href="index.php?action=pannelArticle&amp;idMag=<?= $data[0]->idMag ?>&amp;idText=<?= $article->id_text ?>">
                    <span class="tableContent"><?= $article->dateArticle ?></span>
                    <span class="tableContent"><?= $article->textType ?></span>
                    <span class="tableContent"><?= $article->title ?></span>
                    <span class="tableContent"><?= $article->author ?></span>
                    <span class="tableContent"><?php if($article->articleCover === null) echo 'aucune' ?>
                                            <?php if($article->articleCover !== null) echo 'oui' ?></span>
                    <span class="tableContent"><?php if($article->main === '0') echo 'non'  ?>
                                            <?php if($article->main === '1') echo 'oui'  ?></span>
                    <span class="tableContent"><?php if($article->teaser === null) echo 'non rédigé'  ?>
                                            <?php if($article->teaser !== null) echo 'rédigé'  ?></span>
                </a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>    
        </section>