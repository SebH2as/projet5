<?php $title = 'Liste Magazines'; ?>

<section id="oldNumbers">
            <h2>Magazines créés</h2>
            <div id="tableContainer">
                <div id="tableTitles">
                    <span class="columnTitle">Numéro</span>
                    <span class="columnTitle">Date de création</span>
                    <span class="columnTitle">Parution</span>
                    <span class="columnTitle">Thématique(s)</span>
                    <span class="columnTitle">Editorial</span>
                    <span class="columnTitle">Nombre d'articles</span>
                    <span class="columnTitle">Statut</span>
                </div>
                <?php foreach($allMag as $mag): ?>
                <a class="tableRows" href="index.php?action=modifyMag&amp;idMag=<?= $mag->id ?>">
                    <span class="tableContent"><?= $mag->numberMag ?></span>
                    <span class="tableContent"><?= $mag->date ?></span>
                    <span class="tableContent"><?= $mag->publication ?></span>
                    <span class="tableContent"><?= $mag->topics ?></span>
                    <span class="tableContent"><?php if(($mag->editorial) === null || strlen($mag->editorial) === 0 ) echo 'non rédigé'?>
                                                <?php if(strlen($mag->editorial) > 0) echo 'rédigé'?></span>
                    <span class="tableContent">9</span>
                    <span class="tableContent"><?php if (($mag ->statusPub) === '0') echo 'Archivé'?>
                                            <?php if (($mag ->statusPub) === '1') echo 'En ligne'?></span>
                </a>
                <?php endforeach; ?>
            </div>
            <div id="pageMovers">
                <a class="fa fa-arrow-circle-o-left" href=""></a><span>Page 1/1</span> <a class="fa fa-arrow-circle-o-right" href=""></a>
            </div>
        </section>