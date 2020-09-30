<?php $title = 'gestion utilisateurs'; ?>
<?php $script = null; ?>

<section id="users">
<?php if (empty($users[0])): ?>
            <h2>Aucun utilisateurs enregistrés</h2>
            <div class="buttonsPannel"> 
                <a class="buttonPannel" id="createMag" href="index.php?action=newMag">Créer un nouveau magazine</a>
            </div>
            <?php else: ?>
            <h2>Gestion des utilisateurs</h2>
            <div id="tableContainer">
                <div id="tableTitles">
                    <span class="columnTitle">Pseudo</span>
                    <span class="columnTitle">Date d'inscription</span>
                    <span class="columnTitle">Email</span>
                    <span class="columnTitle">Statut</span>
                    <span class="columnTitle">Newsletter</span>
                    <span class="columnTitle">Role</span>
                    <span class="columnTitle">Clef</span>
                    <span class="columnTitle"></span>
                </div>
                <?php foreach($users as $user): ?>
                <div class="tableRows">
                    <span class="tableContent"><?= $user->pseudo ?></span>
                    <span class="tableContent"><?= $user->inscription_date ?></span>
                    <span class="tableContent"><?= $user->email ?></span>
                    <span class="tableContent"><?php if ($user->actived === '0') echo 'Non activé' ?> 
                                                <?php if ($user->actived === '1') echo 'Activé' ?></span>
                    <span class="tableContent"><?php if ($user->newsletter === '0') echo 'Non abonné' ?> 
                                                <?php if ($user->newsletter === '1') echo 'Abonné' ?></span>
                    <span class="tableContent"><?php if ($user->role === '0') echo 'Utilisateur' ?> 
                                                <?php if ($user->role === '1') echo 'Administrateur' ?></span>
                    <span class="tableContent"><?= $user->confirmkey ?></span>
                    <span class="tableContent delete">
                        <a class="buttonPannel" id="deleteUser" href="index.php?action=deleteUser&amp;iduser=<?= $user->id_user ?>">Supprimmer</a>
                    </span>
                    
                </div>
                <?php endforeach; ?>
            </div>
            <div id="pageMovers<?php if($totalpages < 2) echo 'Hidden'?>">
                <a class="fa fa-arrow-circle-o-left <?php if($currentpage === 1) echo 'hidden'?>" href="index.php?action=usersAdmin&amp;currentpage=<?= $currentpage - 1?>"></a>
                <span><?='Page ' . $currentpage . '/' . $totalpages?></span> 
                <a class="fa fa-arrow-circle-o-right <?php if($currentpage === (int) $totalpages ) echo 'hidden' ?>" href="index.php?action=usersAdmin&amp;currentpage=<?= $currentpage + 1?>"></a>
            </div>
           
            <?php endif; ?>
</section>