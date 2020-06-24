<?php $title = 'aperçu magazine'; ?>
<?php $separator = ''; ?>

<header>
            <div id="border" class=" fade anim" data-rate="-1.025"></div>
            <div id="borderLeft" class=" fade anim" data-rate="-1.025"></div>
            <div id="borderRight" class=" fade anim" data-rate="-1.025"></div>
            <img id="headerImg" class=" fade02" data-rate="2" src="images/testHeader06.jpg" alt="graff">
            <h3 id="numberDate" class=" fade" data-rate="-1.025">Numéro <?= $magazine[0]->numberMag ?> Octobre 2020</h3>
            <h3 id="sentence" class=" fade" data-rate="-1.025">Un magazine qui tient la route...</h3>
            <a id="anchorSum" class=" fade" data-rate="-1.025" href="#summary">Sommaire</a>
            <a id="anchorEd" class=" fade" data-rate="-1.025" href="#editorial">Edito</a>
            <h2 id="title01" class=" fade" data-rate="-1.025"><?= $magazine[0]->title01 ?></h2>
            <h2 id="title02" class=" fade" data-rate="-1.025"><?= $magazine[0]->title02 ?></h2>
            <a class="fa fa-arrow-circle-left" href=""></a>
            <a class="fa fa-arrow-circle-right" href=""></a>
         </header>
         <section id="editorial">
            <div class="columnBig" >
                <div id="edito" class="lefters">
                   <h3>Numéro Zéro Octobre 2020</h3>
                   <h2>EDITO</h2>
                   <div id="editoText">
                        <p><?= $magazine[0]->editorial ?></p>
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
                    <img id="chronicImg01" class="thumbImg" src="images/testHeader.jpg" alt="graff">
                    <img id="chronicImg02" class="thumbImg" src="images/testHeader02.jpg" alt="graff">
                    <img id="chronicImg03" class="thumbImg" src="images/testHeader03.jpg" alt="graff">
                </div>
                <div id="chronicText" class="containText righters">
                    <div class="textInfo">
                        <h3 class="theme">Chronique</h3>
                        <h3 class="title">Un os dans la soupe aux chous</h3>
                        <p class="extract">Lorem ipsum dolor sit amet consectetur adipisicing elit. Fugiat eum facilis, inventore voluptas optio magnam facere hic maiores reiciendis deserunt, culpa dignissimos adipisci esse impedit ipsum velit libero, ducimus odio.Rerum quae iusto nihil omnis voluptas distinctio voluptatem optio perspiciatis autem qui accusamus totam suscipit dolorum recusandae natus expedita atque ut, dignissimos facilis cumque quaerat corporis eveniet saepe ab! Laudantium. <a href="index.php?action=article">(Lire la suite...)</a></p>
                        
                    </div>
                </div>
            </div>
            <div class="containerSum">
                <div id="chronicImgs" class="containImg lefters">
                    <img id="chronicImg01" class="thumbImg" src="images/testHeader.jpg" alt="graff">
                    <img id="chronicImg02" class="thumbImg" src="images/testHeader02.jpg" alt="graff">
                    <img id="chronicImg03" class="thumbImg" src="images/testHeader03.jpg" alt="graff">
                </div>
                <div id="chronicText" class="containText righters">
                    <div class="textInfo">
                        <h3 class="theme">Essai</h3>
                        <h3 class="title">Un os dans la soupe aux chous</h3>
                        <p class="extract">Lorem ipsum dolor sit amet consectetur adipisicing elit. Fugiat eum facilis, inventore voluptas optio magnam facere hic maiores reiciendis deserunt, culpa dignissimos adipisci esse impedit ipsum velit libero, ducimus odio.Rerum quae iusto nihil omnis voluptas distinctio voluptatem optio perspiciatis autem qui accusamus totam suscipit dolorum recusandae natus expedita atque ut, dignissimos facilis cumque quaerat corporis eveniet saepe ab! Laudantium. <a href="index.php?action=article">(Lire la suite...)</a></p>
                        
                    </div>
                </div>
            </div>
            <div class="containerSum">
                <div id="chronicImgs" class="containImg lefters">
                    <img id="chronicImg01" class="thumbImg" src="images/testHeader.jpg" alt="graff">
                    <img id="chronicImg02" class="thumbImg" src="images/testHeader02.jpg" alt="graff">
                    <img id="chronicImg03" class="thumbImg" src="images/testHeader03.jpg" alt="graff">
                </div>
                <div id="chronicText" class="containText righters">
                    <div class="textInfo">
                        <h3 class="theme">Fiction</h3>
                        <h3 class="title">Un os dans la soupe aux chous</h3>
                        <p class="extract">Lorem ipsum dolor sit amet consectetur adipisicing elit. Fugiat eum facilis, inventore voluptas optio magnam facere hic maiores reiciendis deserunt, culpa dignissimos adipisci esse impedit ipsum velit libero, ducimus odio.Rerum quae iusto nihil omnis voluptas distinctio voluptatem optio perspiciatis autem qui accusamus totam suscipit dolorum recusandae natus expedita atque ut, dignissimos facilis cumque quaerat corporis eveniet saepe ab! Laudantium. <a href="index.php?action=article">(Lire la suite...)</a></p>
                        
                    </div>
                </div>
            </div>
        </section>