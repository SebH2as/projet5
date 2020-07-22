<?php $title = 'Courrier'; ?>

<section id="courrier">
            <?php if (empty($letter[0])): ?>
            <h2>Aucun courrier ne correspond à cet identifiant</h2>
            <?php else: ?>
            <h2>Courrier de <?= $letter[0]->author ?></h2>
            <h3>Reçu le <?= $letter[0]->post_date ?></h3>

            <div class="buttonsPannel"> 
                <?php if($letter[0]->published === '0'): ?>
                <a class="buttonPannel" id="online" href="index.php?action=setOnlineMag">Mettre en ligne</a>
                <?php endif; ?>
                <?php if($letter[0]->published === '1'): ?>
                <a class="buttonPannel" id="online" href="index.php?action=setSavedMag">Sauvegarder</a>
                <?php endif; ?>
                <a class="buttonPannel" id="preview" href="index.php?action=previewMag" target="_blank">Aperçu</a>
                <a class="buttonPannel" id="delete" href="index.php?action=deleteMag">Supprimer</a>
            </div>

            <p><?= $letter[0]->content ?></p>
            
            <?php endif; ?>
        </section>