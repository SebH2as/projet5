<?php $title = 'Article'; ?>
<?php $separator = '<div id="separator01"></div>'; ?>

<div id="sectionImg">
            <img class="rubricImg" src="images/<?= $article[0]->articleCover ?>" alt="graff">
        </div>
        <section id="article">
            <div id="titleArticle">
                <h2><?= $article[0]->textType ?></h2>
                <h1><?= $article[0]->title ?></h1>
                <h2>Revue N°<?= $article[0]->numberMag ?></h2>
                <div id="separator02"></div>
            </div>
            <div id="articleContent">
                <span><?= $article[0]->date ?></span><h3><?= $article[0]->author ?></h3>
                <span><?= htmlspecialchars_decode($article[0]->content) ?> </span>
            </div>
        </section>