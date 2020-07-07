<?php $title = 'Gestion éditorial'; ?>
        
        
        <section id="editorialSection">
            <h2>Editorial de la revue numéro <?=$data[0] ->numberMag?></h2>
            <span id="message" class="<?php if ($message === null) echo 'none' ?>"><?= $message ?></span>
            <a id="backLink" href="index.php?action=modifyMag&amp;idMag=<?= $data[0]->id_mag ?>">Retour au panneau de gestion du magazine</a>           
        </section>
        <section id="textEditor">
            <div id="contentContainer02">
                <h3>Contenu de l'éditorial</h3>
                <form id="formEditArticle" action="index.php?action=addEdito&amp;idMag=<?= $data[0]->id_mag ?>" method="POST">
                    <input type="submit" name="saveEdito" value="Enregistrer"> 
                    <h3><?= strlen($data[0]->editorial)?>/5400 </h3>
                    <textarea id="writtingSpace" name="contentEdito">
                        <?= $data[0]->editorial ?>
                    </textarea>
                    <input type="submit" name="saveEdito" value="Enregistrer">
                </form>
            </div>
        </section>