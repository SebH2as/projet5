<?php $title = 'Création nouveau magazine'; ?>

<section id="newMag">
            <h2>Créer un nouveau numéro de la revue</h2>
            <div id="contentContainer">
                <form id="formNewMag" action="index.php?action=pannelMag" method="post">
                    <label for="number">Choisissez un numéro de revue:</label>
                    <input type="number" id="number" name="number" min="0" max="50">
                    <input type="submit" value="Créer">
                </form>
            </div>
        </section>