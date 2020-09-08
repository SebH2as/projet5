<?php $title = 'connection'; ?>
<?php $separator = '<div id="separator01"></div>'; ?>
<?php $script = '<script src="js/navbar.js"></script>'; ?>
<?php $preview = 0 ; ?>
<?php $active = 0 ; ?>

        <div id="sectionImg">
            <img class="rubricImg" src="images/<?= $magazine[0]->cover ?>" alt="graff">
        </div>
        <section id="article">
            <div id="titleArticle">
                <h2></h2>
                <h1>SE CONNECTER</h1>
                <h2></h2>
                <div id="separator02"></div>
            </div>
            <div id="infos">
                <span>
                    Pour vous connecter à votre compte personnel, veuillez renseigner le formulaire ci-dessous.
                </span>
            </div>
            <span id="error" class="<?php if ($error === null) echo 'none' ?>"><?= $error ?></span>
            <form id="formSignIn" action="index.php?action=connection&amp;idMag=<?= $magazine[0]->idMag ?>" method="post">
                <input type="hidden" name="csrf" value="<?php echo $token ?>">
                
                <label for="pseudo">Pseudo:</label>
                <input type="text" id="pseudo" name="pseudo" maxlength="70" size="70">

                <label for="password">Mdp:</label>
                <input type="password" id="password" name="password" maxlength="70" size="70">  

                <input type="submit" id="saver" value="Se connecter">
        </section>