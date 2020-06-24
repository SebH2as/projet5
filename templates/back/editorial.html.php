<?php $title = 'Gestion éditorial'; ?>
        
        
        <section id="articleInfos">
            <h2>Editorial de la revue numéro <?=$magazine[0] ->numberMag?></h2>
            <span id="message">Le numéro de la revue a été modifié</span>           
        </section>
        <section id="textEditor">
            <div id="contentContainer">
                <h3>Contenu de l'éditorial</h3>
                <form id="formEditArticle" action="index.php?action=addEdito&amp;idMag=<?= $magazine[0]->id ?>" method="POST">
                    <input type="submit" name="saveEdito" value="Enregistrer">  
                    <textarea id="writtingSpace" name="contentEdito">
                        
                    </textarea>
                    <input type="submit" name="saveEdito" value="Enregistrer">
                </form>
            </div>
        </section>