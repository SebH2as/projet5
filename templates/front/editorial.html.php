<?php $title = 'Editorial'; ?>
<?php $separator = '<div id="separator01"></div>'; ?>
<?php $script = '<script src="js/navbar.js"></script>'; ?>
<?php $preview = 0 ; ?>
<?php $active = 0 ; ?>

        <div id="sectionImg">
            <img class="rubricImg" src="images/<?= $magazine[0]->cover ?>" alt="graff">
        </div>
        <section id="article">
            <div id="titleArticle">
                <a href="index.php?action=magazine&amp;idMag=<?= $magazine[0]->idMag?>"><h3>Magazine N°<?= $magazine[0]->numberMag ?></h3></a>
                <h1>Editorial</h1>
                <a href="index.php?action=magazine&amp;idMag=<?= $magazine[0]->idMag?>"><h3>Magazine N°<?= $magazine[0]->numberMag ?></h3></a>
                <div id="separator02"></div>
            </div>
            <div id="articleContent">
                
                <span><?= htmlspecialchars_decode($magazine[0]->editorial) ?> </span>
            </div>
        </section>