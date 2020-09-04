<?php $title = 'Création nouveau magazine'; ?>

<section id="newMag">
            <h2>Créer un nouveau numéro du magazine</h2>
            <div id="contentContainer">
                <form id="formNewMag" action="index.php?action=createNewMag" method="post">
                    <input type="hidden" name="csrf" value="<?php echo $token ?>">
                    <label for="number">Magazine numéro</label>
                    <input type="number" id="number" name="number" min="1" max="50" value="<?= $totalMag[0] + 1  ?>" readonly>
                    <input type="submit" value="Créer">
                </form>
            </div>
        </section>