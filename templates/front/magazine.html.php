<?php $title = 'Kilométrage'; ?>
<?php $separator = ''; ?>
<?php $script = '<script src="js/navbar.js"></script>
        <script src="js/infoBox.js"></script>'; ?>
<?php $preview = 0 ; ?>
<?php $active = 0 ; ?>

        <header>
            <span id="number">Magazine numéro <?= $magazine[0]->numberMag ?></span>
            <?php if($magazine[0]->publication !== null): ?>
            <span id="publication"><?= $magazine[0]->publication ?></span>
            <?php endif; ?>
            <img id="headerImg" src="images/<?= $magazine[0]->cover ?>" alt="<?= $magazine[0]->cover ?>">
            <a class="fa fa-arrow-circle-left<?php if (empty($previous)) echo 'hidden' ?>" href="index.php?action=previousMag&amp;idMag=<?= $magazine[0]->idMag ?>">
            <div class="infoBox hidden"><span>Magazine précédent</span></div></a>
            <a class="fa fa-arrow-circle-right<?php if (empty($next)) echo 'hidden' ?>" href="index.php?action=nextMag&amp;idMag=<?= $magazine[0]->idMag ?>">
            <div class="infoBox hidden"><span>Magazine suivant</span></div></a>
        </header>

        <section id="coverPart01">
            <?php foreach($magazine as $article): ?>
            <?php if($article->main === '1'): ?>
                <a id="mainArticle" href="index.php?action=article&amp;idText=<?= $article->id_text ?>&amp;idMag=<?= $article->idMag ?>">
                    <div class="imgContainer">
                        <img id="mainImg" src="images/<?= $article->articleCover ?>" >
                    </div>
                    <h3><?= $article->textType ?></h3>
                    <h2><?= $article->title ?></h2>
                    
                
                </a>
            <?php endif; ?>
            <?php endforeach; ?>

            <?php if($magazine[0]->editorial !== null): ?>
            <a id="edito" href="index.php?action=editorial&amp;idMag=<?= $magazine[0]->idMag ?>">
                
                <h2><i class="fa fa-bullhorn"></i> Editorial</h2>
                <hr>
                <span>
                <?php 
                    if (strlen($magazine[0]->editorial) > 1000)
                    {
                        $espace = strpos($magazine[0]->editorial,' ', 1000); 
                        $extr = substr($magazine[0]->editorial,0,$espace);
                        echo strip_tags(htmlspecialchars_decode($extr)).' (Lire la suite)';
                    }else{echo strip_tags(htmlspecialchars_decode($magazine[0]->editorial));}
                ?> 
                </span>
                 
                
                
            </a>
            <?php endif; ?>
        </section>

        <section id="coverPart02">
            <?php foreach($magazine as $article): ?>
                <?php if($article->main === '0'): ?>    
                <a class="articleCover" href="index.php?action=article&amp;idText=<?= $article->id_text ?>&amp;idMag=<?= $article->idMag ?>">
                    <div class="imgContainer">
                        <img class="articleImg" src="images/<?= $article->articleCover ?>" >
                    </div>
                    <h3><?= $article->textType ?></h3>
                    <h2><?= $article->title ?></h2>
                </a>
            <?php endif; ?>
            <?php endforeach; ?>

            <div id="readersLettersAndSocial" href="index.php?action=readersLetters&amp;idMag=<?= $magazine[0]->idMag ?>">
                <a id="readersLetters" href="index.php?action=readersLetters&amp;idMag=<?= $magazine[0]->idMag ?>">
                    <h2><i class="fa fa-envelope"></i> Courrier des lecteurs</h2>
                    <hr>
                    <span>Vous nous écrivez, nous vous répondons. N'hésitez pas à nous rejoindre en créant un compte sur le site. Vous pourrez alors entamer le dialogue!</span>
                </a>
                <div id="socialMedia">
                    <a class="fa fa-facebook-square" href=""></a>
                    <a class="fa fa-twitter-square" href=""></a>
                    <a class="fa fa-youtube-square" href=""></a>
                </div>
            </div>
        </section>