<?php $title = 'Mon Compte'; ?>
<?php $separator = '<div id="separator01"></div>'; ?>
<?php $script = '<script src="js/navbar.js"></script>'; ?>
<?php $preview = 0 ; ?>
<?php $active = 0 ; ?>

        <div id="sectionImg">
            <img class="rubricImg" src="images/<?= $magazine[0]->cover ?>" alt="graff">
        </div>
        <section id="article">
            <div id="titleArticle">
                <h2>MON COMPTE</h2>
                <div id="separator02"></div>
            </div>
            <form id="formSignIn" action="index.php?action=connection" method="post">
                <label for="titleArticle">Pseudo:</label>
                <input type="text" id="titleArticle" name="titleArticle" maxlength="70" size="70">
                <label for="titleArticle">Email:</label>
                <input type="text" id="titleArticle" name="titleArticle" maxlength="70" size="70">
                <label for="titleArticle">Mdp:</label>
                <input type="text" id="titleArticle" name="titleArticle" maxlength="70" size="70">  
                <input type="submit" id="register" value="Se connecter">
        </section>