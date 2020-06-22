<?php $title = 'Mon Compte'; ?>
<?php $separator = '<div id="separator01"></div>'; ?>

<div id="sectionImg">
            <img class="rubricImg" src="images/testHeader08.jpg" alt="graff">
        </div>
        <section id="article">
            <div id="titleArticle">
                <h2>MON COMPTE</h2>
                <div id="separator02"></div>
            </div>
            <form action="index.php?action=connection" method="post">
                <label for="titleArticle">Pseudo:</label>
                <input type="text" id="titleArticle" name="titleArticle" maxlength="70" size="70">
                <label for="titleArticle">Email:</label>
                <input type="text" id="titleArticle" name="titleArticle" maxlength="70" size="70">
                <label for="titleArticle">Mdp:</label>
                <input type="text" id="titleArticle" name="titleArticle" maxlength="70" size="70">  
                <input type="submit" value="Se connecter">
        </section>