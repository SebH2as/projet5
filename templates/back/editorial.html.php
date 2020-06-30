<?php $title = 'Gestion éditorial'; ?>
        
        
        <section id="editorialSection">
            <h2>Editorial de la revue numéro <?=$magazine[0] ->numberMag?></h2>
            <span id="message"></span>
            <a id="backLink" href="index.php?action=modifyMag&amp;idMag=<?= $magazine[0]->id_mag ?>">Retour au panneau de gestion du magazine</a>           
        </section>
        <section id="textEditor">
            <div id="contentContainer02">
                <h3>Contenu de l'éditorial</h3>
                <form id="formEditArticle" action="index.php?action=addEdito&amp;idMag=<?= $magazine[0]->id_mag ?>" method="POST">
                    <input type="submit" name="saveEdito" value="Enregistrer"> 
                    <h3><?= strlen($magazine[0]->editorial)?>/5400 </h3>
                    <textarea id="writtingSpace" name="contentEdito">
                        <?= $magazine[0]->editorial ?>
                    </textarea>
                    <input type="submit" name="saveEdito" value="Enregistrer">
                </form>
            </div>
        </section>