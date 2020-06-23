<?php $title = 'Gestion magazine'; ?>

<section id="mag">
            <h2>Revue numéro <?= $magazine[0]->numberMag ?></h2>
            <span id="message">Le numéro de la revue a été modifié</span>
            <button>Aperçu</button>
            <div id="contentContainer">
                <form id="formMag" method="POST" action="index.php?action=modifyMag&amp;idMag=<?= $magazine[0]->id ?>">
                    
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
                            <label for="thematic">Modifier la thématique:</label>
                            <input type="text" id="thematic" name="thematic" maxlength="30" size="30">    
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
                    
                    <h3>Editorial: <i><?php if(($magazine[0]->editorial) === null) echo 'non rédigé'?>
                                        <?php if(($magazine[0]->editorial) !== null) echo 'rédigé' ?></i></h3>
                    <div class="formRow">
                        <div class="labelInput">
                            <label for="title02">Modifier ou relire l'éditorial:</label>  
                        </div>
                        <input type="submit" value="Modfier ou relire">
                    </div>
                </form>
            </div>
        </section>
        <section id="articlesMag">
            <h2>Articles associés</h2>
            <div id="tableContainer">
                <div id="tableTitles">
                    <span class="columnTitle">Rubrique</span>
                    <span class="columnTitle">Titre</span>
                    <span class="columnTitle">Auteur</span>
                    <span class="columnTitle">Image associée</span>
                    <span class="columnTitle">Date de création</span>
                </div>
                <a id="tableRows" href="">
                    <span class="tableContent">Chronique</span>
                    <span class="tableContent">Un os dans la soupe aux chous</span>
                    <span class="tableContent">Fédétonn Jean</span>
                    <span class="tableContent">img.png</span>
                    <span class="tableContent">10/09/2020</span>
                </a>
                <a id="tableRows" href="">
                    <span class="tableContent">Chronique</span>
                    <span class="tableContent">Un os</span>
                    <span class="tableContent">Fédétonn Jean</span>
                    <span class="tableContent">img.png</span>
                    <span class="tableContent">10/09/2020</span>
                </a>
                <a id="tableRows" href="">
                    <span class="tableContent">Chronique</span>
                    <span class="tableContent">Un os dans la soupe aux chous</span>
                    <span class="tableContent">Fédétonn Jean</span>
                    <span class="tableContent">img.png</span>
                    <span class="tableContent">10/09/2020</span>
                </a>
                <a id="tableRows" href="">
                    <span class="tableContent">Essai</span>
                    <span class="tableContent">Un os dans la soupe aux chous</span>
                    <span class="tableContent">Fédétonn Jean</span>
                    <span class="tableContent">img.png</span>
                    <span class="tableContent">10/09/2020</span>
                </a>
                <a id="tableRows" href="">
                    <span class="tableContent">Essai</span>
                    <span class="tableContent">Un os dans la soupe aux chous et des clous dans la mayonnaise</span>
                    <span class="tableContent">Fédétonn Jean</span>
                    <span class="tableContent">img.png</span>
                    <span class="tableContent">10/09/2020</span>
                </a>
                <a id="tableRows" href="">
                    <span class="tableContent">Essai</span>
                    <span class="tableContent">Un os dans la soupe aux chous</span>
                    <span class="tableContent">Fédétonn Jean</span>
                    <span class="tableContent">img.png</span>
                    <span class="tableContent">10/09/2020</span>
                </a>
                <a id="tableRows" href="">
                    <span class="tableContent">Fiction</span>
                    <span class="tableContent">Un os dans la soupe aux chous</span>
                    <span class="tableContent">Fédétonn Jean</span>
                    <span class="tableContent">img.png</span>
                    <span class="tableContent">10/09/2020</span>
                </a>
                <a id="tableRows" href="">
                    <span class="tableContent">Fiction</span>
                    <span class="tableContent">Un os dans la soupe aux chous</span>
                    <span class="tableContent">Fédétonn Jean</span>
                    <span class="tableContent">img.png</span>
                    <span class="tableContent">10/09/2020</span>
                </a>
                <a id="tableRows" href="">
                    <span class="tableContent">Fiction</span>
                    <span class="tableContent">Un os dans la soupe aux chous</span>
                    <span class="tableContent">Fédétonn Jean</span>
                    <span class="tableContent">img.png</span>
                    <span class="tableContent">10/09/2020</span>
                </a>
            </div>
            <button>Créer un nouvel article</button>
        </section>