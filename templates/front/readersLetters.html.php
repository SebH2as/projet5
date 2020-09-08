<?php $title = 'Courrier des lecteurs'; ?>
<?php $separator = '<div id="separator01"></div>'; ?>
<?php $script = '<script src="js/navbar.js"></script>'; ?>
<?php $preview = 0 ; ?>
<?php $active = 0 ; ?>

<div id="sectionImg">
        <img class="rubricImg" src="images/<?= $magazine[0]->cover ?>" alt="graff">
</div>
        <section id="article">

                <div id="titleArticle">
                    <a href="index.php?action=magazine&amp;idMag=<?= $magazine[0]->idMag?>"><h3>MAGAZINE N°<?= $magazine[0]->numberMag ?></h3></a>
                    <h1>COURRIER</h1>
                    <a href="index.php?action=magazine&amp;idMag=<?= $magazine[0]->idMag?>"><h3>MAGAZINE N°<?= $magazine[0]->numberMag ?></h3></a>
                    <div id="separator02"></div>
                </div>

            <?php if (empty($letters[0])): ?>
                <h2 id="noLetters">Aucun courrier reçu pour ce numéro</h2>
            <?php else: ?>

                <div id="pageMovers<?php if($totalpages < 2) echo 'Hidden'?>">
                    <a class="fa fa-arrow-circle-o-left <?php if($currentpage === 1) echo 'hidden'?>" href="index.php?action=readersLetters&amp;currentpage=<?= $currentpage - 1?>&amp;idMag=<?= $magazine[0]->idMag ?>"></a>
                    <span><?='Page ' . $currentpage . '/' . $totalpages?></span> 
                    <a class="fa fa-arrow-circle-o-right <?php if($currentpage === (int) $totalpages ) echo 'hidden' ?>" href="index.php?action=readersLetters&amp;currentpage=<?= $currentpage + 1?>&amp;idMag=<?= $magazine[0]->idMag ?>"></a>
                </div> 
             
            <?php foreach($letters as $letter): ?>
                <div class="lettersAll">
                    <div class="readerLetter">
                        <h2>Courrier du <?=$letter->post_date ?> par <?=$letter->author ?> :</h2>
                        <span><?=$letter->content ?></span>
                    </div>
                    <div class="responseLetter">
                        <h2>Notre réponse :</h2>
                        <span><?=htmlspecialchars_decode($letter->response) ?></span>
                    </div> 
                </div>
            <?php endforeach; ?>

            <div id="pageMovers<?php if($totalpages < 2) echo 'Hidden'?>">
                    <a class="fa fa-arrow-circle-o-left <?php if($currentpage === 1) echo 'hidden'?>" href="index.php?action=readersLetters&amp;currentpage=<?= $currentpage - 1?>&amp;idMag=<?= $magazine[0]->idMag ?>"></a>
                    <span><?='Page ' . $currentpage . '/' . $totalpages?></span> 
                    <a class="fa fa-arrow-circle-o-right <?php if($currentpage === (int) $totalpages ) echo 'hidden' ?>" href="index.php?action=readersLetters&amp;currentpage=<?= $currentpage + 1?>&amp;idMag=<?= $magazine[0]->idMag ?>"></a>
            </div> 
            <?php endif; ?>
        </section>