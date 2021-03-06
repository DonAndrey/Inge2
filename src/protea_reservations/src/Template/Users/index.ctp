<?php echo $this->Html->css('resources.css'); ?>

<!-- MENSAJES -->
<div class="lead text-danger" style="text-align:center">
    <?= $this->Flash->render('deleteUserError') ?> 
    <?= $this->Flash->render('editUserError') ?> 
    <?= $this->Flash->render('rejectUserError') ?> 
    <?= $this->Flash->render('confirmUserError') ?> 
</div> 
<div class="lead text-info" style="text-align:center">
    <?= $this->Flash->render('deleteUserSuccess') ?>
    <?= $this->Flash->render('editUserSuccess') ?>
    <?= $this->Flash->render('rejectUserSuccess') ?>
    <?= $this->Flash->render('confirmUserSuccess') ?>
</div>
<!-- FIN DE MENSAJES -->

<!-- TÍTULO -->
<div class="row" style="color:#000;">
    <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
        <legend>
            <div class='text-center'>
                <h2>Administrar Cuentas de Usuarios</h2>
                <br>
            </div>
        </legend>
    </div>
</div>
<!-- FIN DE TÍTULO -->


<!-- BOTONES -->
<div class="row text-center">
    <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
        <?= $this->Html->link(' Agregar Usuario',
                              ['controller' => 'Users',
                               'action'=> 'add'
                              ],
                              ['class' => 'btn btn-info glyphicon glyphicon-plus',
                               'style' => 'font-family: Arial, Helvetica, sans-serif;'
                              ])
        ?>
    </div>
    <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
        <br>
    </div>
</div>
<!-- FIN DE BOTONES -->


<!-- TABLA -->
<div class="table-responsive" style="color:#000;">
    <table class="table table-striped table-hover table-sm">
        <!-- ENCABEZADO TABLA -->
        <tr> 
            <th>
                <?php
                    echo $this->Paginator->sort('username', 'E-mail ');
                    echo $this->Html->tag('span', null, array('class' => 'glyphicon glyphicon-sort-by-alphabet'));
                ?>
            </th>
            <th>
                <?php
                    echo $this->Paginator->sort('first_name', 'Nombre ');
                    echo $this->Html->tag('span', null, array('class' => 'glyphicon glyphicon-sort-by-alphabet'));
                ?>
            </th>
            <th>
                <?php
                    echo $this->Paginator->sort('Roles.role_name', 'Rol ');
                    echo $this->Html->tag('span', null, array('class' => 'glyphicon glyphicon-sort-by-alphabet'));
                ?>
            </th>
            <th>
                <?php
                    echo $this->Paginator->sort('state', 'Estado ');
                    echo $this->Html->tag('span', null, array('class' => 'glyphicon glyphicon-sort-by-alphabet'));
                ?>
            </th>
            <th>
                Actualizar
            </th>
            <th>
                Eliminar
            </th>
        </tr>
        <!-- FIN DE ENCABEZADO TABLA -->

        <?php 
        // Recorre todos los usuarios y los muestra en la tabla
        foreach ( $users as $user ): ?>
            <tr>
                <!-- NOMBRE -->
                <td>
                    <?php
                        echo $this->Html->link($user['username'],
                                               array('controller' => 'users','action' => 'view', $user->id));
                    ?>
                </td>
                <!-- NOMBRE -->
                <td>
                    <?php
                        echo $user['first_name'] . " " . $user['last_name'];
                    ?>
                </td>
                <!-- ROL -->
                <td>
                    <?php
                        echo $user->_matchingData['Roles']->role_name;
                    ?>
                </td>
                <!-- ESTADO -->
                <td>
                    <?php
                    if($user['state'] == true)
                    {
                        echo "Aceptado";
                    }
                    else
                    {
                        ?>
                        <b> <?php echo "Pendiente"; ?> </b>
                        <?php
                    }

                    ?>
                </td>
                <!-- EDITAR -->
                <td>
                    <?php
                        echo $this->Html->link('<i class="glyphicon glyphicon-pencil"></i>',
                                               array('controller' => 'Users','action' => 'edit', $user->id),
                                               array('escape' => false));
                    ?>
                </td>
                <!-- ELIMINAR -->
                <td>
                    <?php
                        echo $this->Form->postLink($this->Html->tag('span',null,array('class' => 'glyphicon glyphicon-trash')),
                                                   array('controller' => 'Users','action' => 'delete', $user->id),
                                                   array('escape' => false, 'confirm' => '¿Está seguro que desea eliminar el usuario?'));
                    ?>
                </td>
            </tr>
        <?php
        endforeach;
        unset($user);
        ?>
    </table>
</div>
<!-- FIN DE TABLA -->

<!-- PAGINADOR -->
<div class="row text-center">
  <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
      <div class="center_pagination" >
          <ul class="pagination">
                <li><?php echo $this->Paginator->numbers(array('separator' => '')); ?></li>
          </ul>
      </div>
   </div>
</div>
<!-- FIN DE PAGINADOR -->