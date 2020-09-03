<?php $title = 'Essais'; ?>
<?php $separator = '<div id="separator01"></div>'; ?>
<?php $script = '<script src="js/navbar.js"></script>'; ?>
<?php $preview = 0 ; ?>
<?php $active = 3 ; ?>

<div id="sectionImg">
    <img class="rubricImg" src="images/<?= $magazine[0]->cover ?>" alt="graff">
</div>
         
<section id="listRubric">
             
    <div id="titleRubric"><h1>TOUT LES ESSAIS</h1><div id="separator02"></div></div>

    <div id="pageMovers<?php if($totalpages < 2) echo 'Hidden'?>">
        <a class="fa fa-arrow-circle-o-left <?php if($currentpage === 1) echo 'hidden'?>" href="index.php?action=fictions&amp;currentpage=<?= $currentpage - 1?>&amp;idMag=<?= $magazine[0]->idMag ?>"></a>
        <span><?='Page ' . $currentpage . '/' . $totalpages?></span> 
        <a class="fa fa-arrow-circle-o-right <?php if($currentpage === (int) $totalpages ) echo 'hidden' ?>" href="index.php?action=fictions&amp;currentpage=<?= $currentpage + 1?>&amp;idMag=<?= $magazine[0]->idMag ?>"></a>
    </div>

    <?php foreach($articles as $article): ?>
    <?php if($article->textType === 'Essai'): ?>
        <a class="containerRubric" href="index.php?action=article&amp;idText=<?= $article->id_text ?>&amp;idMag=<?= $article->idMag ?>">
            <div id="chronicImgs" class="containImg lefters">
                <img class="thumbImg" src="images/<?=$article->articleCover ?>" alt="graff">
            </div>
            <div id="rubricText" class="containText righters">
                <div class="textInfo">
                    <h3 class="number">Revue N°<?=$article->numberMag ?></h3>
                    <h3 class="title"><?=$article->title ?></h3>
                    <p class="extract">
                        <?php 
                        if (strlen($article->content) > 800)
                        {
                            $espace = strpos($article->content,' ', 800); 
                            $extr = substr($article->content,0,$espace);
                            echo strip_tags(htmlspecialchars_decode($extr)).'...(lire la suite)';
                        }else{echo strip_tags(htmlspecialchars_decode($article->content));}
                        ?>  
                    </p>
                </div>
            </div>
        </a>
    <?php endif; ?>
    <?php endforeach; ?>

    <div id="pageMovers<?php if($totalpages < 2) echo 'Hidden'?>">
        <a class="fa fa-arrow-circle-o-left <?php if($currentpage === 1) echo 'hidden'?>" href="index.php?action=fictions&amp;currentpage=<?= $currentpage - 1?>&amp;idMag=<?= $magazine[0]->idMag ?>"></a>
        <span><?='Page ' . $currentpage . '/' . $totalpages?></span> 
        <a class="fa fa-arrow-circle-o-right <?php if($currentpage === (int) $totalpages ) echo 'hidden' ?>" href="index.php?action=fictions&amp;currentpage=<?= $currentpage + 1?>&amp;idMag=<?= $magazine[0]->idMag ?>"></a>
    </div>

</section>