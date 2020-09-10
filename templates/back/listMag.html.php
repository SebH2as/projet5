<?php $title = 'Liste Magazines'; ?>

<section id="oldNumbers">
            <?php if (empty($allMag[0])): ?>
            <h2>Aucun magazine créé</h2>
            <div class="buttonsPannel"> 
                <a class="buttonPannel" id="createMag" href="index.php?action=newMag">Créer un nouveau magazine</a>
            </div>
            <?php else: ?>
            <h2>Magazines créés</h2>
            <div id="tableContainer">
                <div id="tableTitles">
                    <span class="columnTitle">Numéro</span>
                    <span class="columnTitle">Parution</span>
                    <span class="columnTitle">Couverture</span>
                    <span class="columnTitle">Editorial</span>
                    <span class="columnTitle">Articles</span>
                    <span class="columnTitle">Une</span>
                    <span class="columnTitle">Statut</span>
                </div>
                <?php foreach($allMag as $mag): ?>
                <a class="tableRows" href="index.php?action=pannelMag&amp;idMag=<?= $mag->id_mag ?>">
                    <span class="tableContent"><?= $mag->numberMag ?></span>
                    <span class="tableContent"><?= $mag->publication ?></span>
                    <span class="tableContent"><?php if (($mag ->cover) === null) echo 'non'?>
                                            <?php if (($mag ->cover) !== null) echo 'oui'?></span>
                    <span class="tableContent"><?php if(($mag->editorial) === null || strlen($mag->editorial) === 0 ) echo 'non rédigé'?>
                                                <?php if(strlen($mag->editorial) > 0) echo 'rédigé'?></span>
                    <span class="tableContent"><?= $mag->articlesNb ?></span>
                    <span class="tableContent"><?php if (($mag ->articleMain) === '0' || ($mag ->articleMain) === null) echo 'non'?>
                                            <?php if (($mag ->articleMain) === '1') echo 'oui'?></span>
                    <span class="tableContent"><?php if (($mag ->statusPub) === '0') echo 'Sauvegardé'?>
                                            <?php if (($mag ->statusPub) === '1') echo 'En ligne'?></span>
                </a>
                <?php endforeach; ?>
            </div>
            <div id="pageMovers<?php if($totalpages < 2) echo 'Hidden'?>">
                <a class="fa fa-arrow-circle-o-left <?php if($currentpage === 1) echo 'hidden'?>" href="index.php?action=listMag&amp;currentpage=<?= $currentpage - 1?>"></a>
                <span><?='Page ' . $currentpage . '/' . $totalpages?></span> 
                <a class="fa fa-arrow-circle-o-right <?php if($currentpage === (int) $totalpages ) echo 'hidden' ?>" href="index.php?action=listMag&amp;currentpage=<?= $currentpage + 1?>"></a>
            </div>
            <div class="buttonsPannel"> 
                <a class="buttonPannel" id="createMag" href="index.php?action=newMag">Créer un nouveau magazine</a>
            </div>
            <?php endif; ?>
        </section>