<?php $title = 'Gestion article'; ?>
<?php $script = '<script src="js/formSave.js"></script>
                <script src="js/infoBox.js"></script>
                <script src="js/mainPannelArticle.js"></script>'; ?>

        <section id="articleInfos">
            <h2>Article du magazine numéro <?= $data[0]->numberMag ?></h2>
            <span id="message" class="<?php if ($message === null) echo 'none' ?>"><?= $message ?></span>
            
            <div class="buttonsPannel"> 
                <a class="buttonPannel" id="backLink" href="index.php?action=modifyMag&amp;idMag=<?= $data[0]->ArtIdMag ?>">Retour au magazine</a>
                <a class="buttonPannel" id="preview" href="index.php?action=previewArticle&amp;idText=<?= $data[0]->id_text ?>" target="_blank">Aperçu</a>
                <?php if (($data[0]->main) === '0'): ?>
                <a class="buttonPannel" id="preview" href="index.php?action=setMain&amp;idText=<?= $data[0]->id_text ?>&amp;idMag=<?= $data[0]->ArtIdMag ?>">Mettre en une</a>
                <?php else: ?>
                <a class="buttonPannel" id="preview" href="index.php?action=unsetMain&amp;idText=<?= $data[0]->id_text ?>&amp;idMag=<?= $data[0]->ArtIdMag ?>">Retirer de la une</a>
                <?php endif; ?>
                <a class="buttonPannel" id="delete" href="index.php?action=deleteArticle&amp;idText=<?= $data[0]->id_text ?>&amp;idMag=<?= $data[0]->ArtIdMag ?>">Supprimer</a>
            </div>
            
            <div id="contentContainer">
                <form id="formMag" method="POST" enctype="multipart/form-data" action="index.php?action=modifyArticle&amp;idText=<?= $data[0]->id_text ?>&amp;idMag=<?= $data[0]->ArtIdMag ?>">
                    
                <input type="hidden" name="csrf" value="<?php echo $token ?>">
                <h3><i class="fa fa-arrow-right"></i> Rubrique de l'article: <i><?php if(($data[0]->textType) === null) echo 'à définir'?>
                                        <?php if(($data[0]->textType) !== null) echo $data[0]->textType ?></i></h3>
                    <div class="formRow">
                        <div class="labelInput">
                            <label for="rubric">Modifier la rubrique de l'article:</label>
                            <select list="rubric" name="rubric">
                                <option value="Chronique">Chronique</option>
                                <option value="Essai">Essai</option>
                                <option value="Fiction">Fiction</option>
                            </select>
                        </div>
                        <input type="submit" name="modifRubric" value="Modifier">
                    </div>
                    
                    <h3><i class="fa fa-arrow-right"></i> Titre de l'article: <i><?php if(($data[0]->title) === null) echo 'à définir'?>
                                        <?php if(($data[0]->title) !== null) echo $data[0]->title ?></i></h3>
                    <div class="formRow">
                        <div class="labelInput">
                            <label for="title">Modifier le titre de l'article:<i class=" fa fa-circle"><i class=" fa fa-info"></i><div class="infoBox hidden"><span>70 signes maximum</span></div></i></label>
                            <input type="text" id="title" name="title" maxlength="70" size="70">    
                        </div>
                        <input type="submit" name="modifTitle" value="Modifier">
                    </div>
                    
                    <h3><i class="fa fa-arrow-right"></i> Auteur: <i><?php if(($data[0]->author) === null) echo 'à définir'?>
                                        <?php if(($data[0]->author) !== null) echo $data[0]->author ?></i></h3>
                    <div class="formRow">
                        <div class="labelInput">
                            <label for="author">Modifier le nom de l'auteur:<i class=" fa fa-circle"><i class=" fa fa-info"></i><div class="infoBox hidden"><span>30 signes maximum</span></div></i></label>
                            <input type="text" id="author" name="author" maxlength="30" size="30">    
                        </div>
                        <input type="submit" name="modifAuthor" value="Modifier">
                    </div>

                    <h3><i class="fa fa-arrow-right"></i> Teaser: <i><?php if(($data[0]->teaser) === null) echo 'non rédigé'?>
                                        <?php if(($data[0]->teaser) !== null) echo 'rédigé' ?></i></h3>
                    <div class="formRow">
                        <div class="labelInput">
                            <label for="teaser">Rédiger le teaser:<i class=" fa fa-circle"><i class=" fa fa-info"></i><div class="infoBox hidden"><span>95 signes maximum</span></div></i></label>
                            <input type="text" id="teaser" name="teaser" maxlength="95" size="95" 
                            placeholder="<?php if(($data[0]->teaser) !== null) echo $data[0]->teaser ?>">    
                        </div>
                        <input type="submit" name="modifTeaser" value="Modifier">
                    </div>
                    
                    <h3><i class="fa fa-arrow-right"></i> Image associée à l'article: <i><?php if(($data[0]->articleCover) === null) echo 'à définir'?>
                                        <?php if(($data[0]->articleCover) !== null) echo $data[0]->articleCover ?></i></h3>
                    <div class="formRow">
                        <div class="labelInput">
                            <label for="articleCover">Changer l'image:<i class=" fa fa-circle"><i class=" fa fa-info"></i><div class="infoBox hidden"><span>Uniquement .png ou .jpg</span></div></i></label>
                            <input type="file" id="articleCover" name="articleCover">    
                        </div>
                        <input type="submit" name="modifCover" value="Changer">
                    </div>
                </form>
            </div>
        </section>
        
        <section id="textEditor">
            <div id="contentContainer02">
                <h3>Contenu de l'article</h3>
                <form id="formEditArticle" action="index.php?action=addContent&amp;idMag=<?= $data[0]->ArtIdMag ?>&amp;idText=<?= $data[0]->id_text ?>" method="POST">
                    <input type="hidden" name="csrf" value="<?php echo $token ?>">
                    
                    <input type="submit" name="saveContent" value="Enregistrer"> 

                    <textarea id="writtingSpace" name="content">
                        <?= $data[0]->content ?>
                    </textarea>
                    <input type="submit" name="saveContent" value="Enregistrer">
                </form>
            </div>
        </section>
