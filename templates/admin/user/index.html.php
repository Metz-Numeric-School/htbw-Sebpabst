<?php $layout = 'admin/base.html.php'; ?>

<div class="container py-5">

    <div class="row align-items-center">
        <div class="col">
            <h1>Utilisateurs</h1>
        </div>
        <div class="col-auto">
            <a href="/admin/user/new" class="btn btn-primary">Nouveau</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <table class="table">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user->getId()) ?></td>
                            <td><?php echo htmlspecialchars($user->getFirstname()) ?> <?php echo htmlspecialchars($user->getLastname()) ?></td>
                            <td><?php echo htmlspecialchars($user->getEmail()) ?></td>
                            <td><?= $user->getIsadmin() ? "admin" : "user" ?></td>
                            <?php 
                            /* <td>
                                <a href="/admin/user/edit/<?php echo $user->getId() ?>" class="btn btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                <a href="/admin/user/archive/<?php echo $user->getId() ?>" class="btn btn-outline-danger"><i class="bi bi-archive"></i></a>
                            </td> */
                            ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    
</div>