<?php $title = 'Gestion article'; ?>

        <section id="articleInfos">
            <h2>Article du magazine numéro <?= $magazine[0]->numberMag ?></h2>
            <span id="message" class="<?php if ($message === null) echo 'none' ?>"><?= $message ?></span>
            <button>Aperçu</button>
            
            <div id="contentContainer">
                <form id="formMag" method="POST" action="index.php?action=modifyArticle&amp;idText=<?= $article[0]->id_text ?>&amp;idMag=<?= $magazine[0]->id_mag ?>">
                    
                <h3>Rubrique de l'article: <i>Chronique</i></h3>
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
                    
                    <h3>Titre de l'article: <i>Un clou dans la soupe aux chous</i></h3>
                    <div class="formRow">
                        <div class="labelInput">
                            <label for="title">Modifier le titre de l'article:</label>
                            <input type="text" id="title" name="title" maxlength="70" size="70">    
                        </div>
                        <input type="submit" name="modifTitle" value="Modifier">
                    </div>
                    
                    <h3>Auteur: <i>Jean Fédétonn</i></h3>
                    <div class="formRow">
                        <div class="labelInput">
                            <label for="author">Modifier le nom de l'auteur:</label>
                            <input type="text" id="author" name="author" maxlength="30" size="30">    
                        </div>
                        <input type="submit" value="Modifier">
                    </div>
                    
                    <h3>Image associée à l'article: <i>img.png</i></h3>
                    <div class="formRow">
                        <div class="labelInput">
                            <label for="imgArticle">Changer l'image:</label>
                            <input type="text" id="imgArticle" name="imgArticle" maxlength="30" size="30">    
                        </div>
                        <input type="submit" value="Changer">
                    </div>
                </form>
            </div>
        </section>
        <section id="textEditor">
            <div id="contentContainer">
                <h3>Contenu de l'article</h3>
                <form id="formEditArticle" method="POST">
                    <textarea id="writtingSpace">
                        
                    </textarea>
                </form>
            </div>
        </section>