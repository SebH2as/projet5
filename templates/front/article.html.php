<?php $title = 'Article'; ?>
<?php $separator = '<div id="separator01"></div>'; ?>
<?php $script = '<script src="js/navbar.js"></script>'; ?>
<?php $preview = 0 ; ?>
<?php $active = 0 ; ?>

        <div id="sectionImg">
            <img class="rubricImg" src="images/<?= $article[0]->articleCover ?>" alt="graff">
        </div>
        <section id="article">
            <div id="titleArticle">
                <a href="index.php?action=<?= strtolower($article[0]->textType) ?>s&amp;idMag=<?= $magazine[0]->idMag ?>"><h3><?= $article[0]->textType ?></h3></a>
                <h1><?= $article[0]->title ?></h1>
                <a href="index.php?action=magazine&amp;idMag=<?= $magazine[0]->idMag?>"><h3>Magazine N°<?= $article[0]->numberMag ?></h3></a>
                <div id="separator02"></div>
            </div>
            <div id="articleContent">
                <span><?= $article[0]->date ?></span><h3><?= $article[0]->author ?></h3>
                <span><?= htmlspecialchars_decode($article[0]->content) ?> </span>
            </div>
        </section>