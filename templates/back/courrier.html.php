<?php $title = 'Liste courrier'; ?>

<section id="courrier">
            <?php if (empty($letters[0])): ?>
            <h2>Aucun courrier reçu</h2>
            <?php else: ?>
            <h2>Courrier des lecteurs</h2>
            <div id="tableContainer">
                <div id="tableTitles">
                    <span class="columnTitle">Membre</span>
                    <span class="columnTitle">Date de réception</span>
                    <span class="columnTitle">Réponse</span>
                    <span class="columnTitle">Magazine associé</span>
                    <span class="columnTitle">Statut</span>
                </div>
                <?php foreach($letters as $letter): ?>
                <a class="tableRows" href="index.php?action=userLetter&amp;idLetter=<?= $letter->id_letter ?>">
                    <span class="tableContent"><?= $letter->author ?></span>
                    <span class="tableContent"><?= $letter->post_date ?></span>
                    <span class="tableContent"><?php if ($letter->response === null): ?> aucune
                                                <?php else: ?> oui<?php endif; ?></span>
                    <span class="tableContent"><?php if ($letter->magRelated === null): ?> aucun
                                                <?php else: ?> <?= $letter->magRelated ?><?php endif; ?></span>
                    <span class="tableContent"><?php if (($letter ->published) === '0') echo 'En attente de validation'?>
                                            <?php if (($letter ->published) === '1') echo 'Publié'?></span>
                </a>
                <?php endforeach; ?>
            </div>
            <div id="pageMovers<?php if($totalpages < 2) echo 'Hidden'?>">
                <a class="fa fa-arrow-circle-o-left <?php if($currentpage === 1) echo 'hidden'?>" href="index.php?action=courrier&amp;currentpage=<?= $currentpage - 1?>"></a>
                <span><?='Page ' . $currentpage . '/' . $totalpages?></span> 
                <a class="fa fa-arrow-circle-o-right <?php if($currentpage === (int) $totalpages ) echo 'hidden' ?>" href="index.php?action=courrier&amp;currentpage=<?= $currentpage + 1?>"></a>
            </div>
            <?php endif; ?>
        </section>