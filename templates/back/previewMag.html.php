<?php $title = 'aperçu magazine'; ?>
<?php $separator = ''; ?>
<?php $script = '<script src="js/navbar.js"></script>
        <script src="js/animation.js"></script>
        <script src="js/infoBox.js"></script>
        <script src="js/sum.js"></script>'; ?>
<?php $preview = 1 ; ?>
<?php $active = 0 ; ?>

<header>
            <div id="border" class=" fade anim" data-rate="-1.025"></div>
            <div id="borderLeft" class=" fade anim" data-rate="-1.025"></div>
            <div id="borderRight" class=" fade anim" data-rate="-1.025"></div>
            <img id="headerImg" class=" fade02" data-rate="2" src="images/<?= $magazine[0]->cover ?>" alt="graff">
            <h3 id="numberDate" class=" fade" data-rate="-1.025">Numéro <?= $magazine[0]->numberMag ?> <?= $magazine[0]->publication ?></h3>
            <h3 id="sentence" class=" fade" data-rate="-1.025">Un magazine qui tient la route...</h3>
            <a id="anchorSum" class=" fade" data-rate="-1.025" href="#summary">Sommaire</a>
            <a id="anchorEd" class=" fade" data-rate="-1.025" href="#editorial">Edito</a>
            <h2 id="title01" class=" fade" data-rate="-1.025"><?= $magazine[0]->title01 ?></h2>
            <h2 id="title02" class=" fade" data-rate="-1.025"><?= $magazine[0]->title02 ?></h2>
            <a class="fa fa-arrow-circle-left" ></a>
            <a class="fa fa-arrow-circle-right" ></a>
         </header>
         <section id="editorial">
            <div class="columnBig" >
                <div id="edito" class="lefters">
                   <h3>Numéro <?= $magazine[0]->numberMag ?> <?= $magazine[0]->publication ?></h3>
                   <h2>EDITO</h2>
                   <div id="editoText">
                        <p><?= htmlspecialchars_decode($magazine[0]->editorial) ?></p>
                    </div>
                </div>
            </div>
            <div class="column">
               <div class="square topers"></div>
               <div class="square topers"></div>
               <div class="square topers"></div>
           </div>

        </section>
        <section id="summary">
            <h2>SOMMAIRE</h2><span>Numéro Zéro Octobre 2020</span>

            <div class="containerSum">
                <div id="chronicImgs" class="containImg lefters">
                    <?php foreach($magazine as $article): ?>
                    <?php if($article->textType === 'Chronique'): ?>
                    <img class="thumbImg" src="images/<?=$article->articleCover ?>" alt="graff">
                    <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <div id="chronicsText" class="containText righters" >
                    <div class="bigTitle">
                        
                        <h3 class="rubricTitle">CHRONIQUES</h3>
                    </div>
                    <?php foreach($magazine as $article): ?>
                    <?php if($article->textType === 'Chronique'): ?>
                    <a class="textInfo" href="index.php?action=previewArticle&amp;idText=<?= $article->id_text ?>">
                        <h3 class="theme">Chroniques</h3>
                        <h3 class="title"><?=$article->title ?></h3>
                        <h4 class="author">Par <?=$article->author ?></h4>
                        <p class="extract">
                        <?php 
                        if (strlen($article->content) > 800)
                        {
                            $espace = strpos($article->content,' ', 800); 
                            $extr = substr($article->content,0,$espace);
                            echo strip_tags(htmlspecialchars_decode($extr)).'(...)';
                        }else{echo strip_tags(htmlspecialchars_decode($article->content));}
                        ?>  
                        </p>
                        <div class="falseLink">(Lire la suite...)</div>
                    </a>
                    <?php endif; ?>
                    <?php endforeach; ?>
                    </div>
            </div>
            <div class="containerSum">
                <div id="essaisImgs" class="containImg lefters">
                    <?php foreach($magazine as $article): ?>
                    <?php if($article->textType === 'Essai'): ?>
                    <img class="thumbImg" src="images/<?=$article->articleCover ?>" alt="graff">
                    <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <div id="essaisText" class="containText righters">
                    <div class="bigTitle">
                        
                        <h3 class="rubricTitle">ESSAIS</h3>
                    </div>
                    <?php foreach($magazine as $article): ?>
                    <?php if($article->textType === 'Essai'): ?>
                    <a class="textInfo" href="index.php?action=previewArticle&amp;idText=<?= $article->id_text ?>">
                        <h3 class="theme">Essais</h3>
                        <h3 class="title"><?=$article->title ?></h3>
                        <p class="extract">
                        <?php 
                        if (strlen($article->content) > 800)
                        {
                            $espace = strpos($article->content,' ', 800); 
                            $extr = substr($article->content,0,$espace);
                            echo strip_tags(htmlspecialchars_decode($extr)).'(...)';
                        }else{echo strip_tags(htmlspecialchars_decode($article->content));}
                        ?>  
                        </p>
                        <div class="falseLink">(Lire la suite...)</div>
                    </a>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="containerSum">
                <div id="fictionsImgs" class="containImg lefters">
                    <?php foreach($magazine as $article): ?>
                    <?php if($article->textType === 'Fiction'): ?>
                    <img class="thumbImg" src="images/<?=$article->articleCover ?>" alt="graff">
                    <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <div id="fictionsText" class="containText righters">
                    <div class="bigTitle">
                        
                        <h3 class="rubricTitle">FICTIONS</h3>
                    </div>
                    <?php foreach($magazine as $article): ?>
                    <?php if($article->textType === 'Fiction'): ?>
                    <a class="textInfo" href="index.php?action=previewArticle&amp;idText=<?= $article->id_text ?>">
                        <h3 class="theme">Fictions</h3>
                        <h3 class="title"><?=$article->title ?></h3>
                        <p class="extract">
                        <?php 
                        if (strlen($article->content) > 800)
                        {
                            $espace = strpos($article->content,' ', 800); 
                            $extr = substr($article->content,0,$espace);
                            echo strip_tags(htmlspecialchars_decode($extr)).'(...)';
                        }else{echo strip_tags(htmlspecialchars_decode($article->content));}
                        ?>  
                        </p>
                        <div class="falseLink">(Lire la suite...)</div>
                    </a>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>