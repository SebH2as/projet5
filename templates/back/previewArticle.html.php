<?php $title = 'aperçu article'; ?>
<?php $separator = '<div id="separator01"></div>'; ?>
<?php $script = '<script src="js/navbar.js"></script>'; ?>
<?php $preview = 1 ; ?>

<div id="sectionImg">
            <img class="rubricImg" src="images/<?= $article[0]->articleCover ?>" alt="graff">
        </div>
        <section id="article">
            <div id="titleArticle">
                <a href=""><h3><?= $article[0]->textType ?></h3></a>
                <h1>ARTICLE</h1>
                <a href=""><h3>Magazine N°<?= $article[0]->numberMag ?></h3></a>
                <div id="separator02"></div>
            </div>
            <div id="articleContent">
                <span>Le <?= $article[0]->date ?></span><h3>Par <?= $article[0]->author ?></h3>
                <h2><?= $article[0]->title ?></h2>
                <span><?= htmlspecialchars_decode($article[0]->content) ?> </span>
            </div>
        </section>

        