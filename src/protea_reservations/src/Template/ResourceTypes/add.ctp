<!-- src/Template/Resources/agregar.ctp -->
<?php echo $this->Html->css('registro.css'); ?>
<div class="users form">
<?= $this->Form->create($resource) ?>
    
<div class="row">
    <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
        <legend>
            <div class='text-center'>
                <h1>Agregar Recurso</h1>
                <br>
            </div>
        </legend>
    </div>
</div>
    
<fieldset>
    <div class="row">
        <div class='col-lg-4 col-lg-offset-4 col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1'>
            <?=
            $this->Form->input('Resources.resource_type', ['label' => 'Tipo: ',
                                                 'options' => $resource_types,
                                                 'class' => 'form-control'
                                                ]
                              );
            ?>
            <br>
        </div>
    </div>

    <div class='row'>
        <div class='col-lg-4 col-lg-offset-4 col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1'>
            <?=
            $this->Form->input('Resources.resource_name', ['label' => 'Nombre: ',
                                                 'placeholder' => 'Sala SITEA',
                                                 'class' => 'form-control'
                                                ]
                              )
            ?>
            <br>
        </div>
    </div>
    
    <div class='row'>
        <div class='col-lg-4 col-lg-offset-4 col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1'>
            <?=
            $this->Form->input('Resources.resource_code', ['label' => 'Placa/Serie: ',
                                                 'placeholder' => 'ABC123XYZ',
                                                 'class' => 'form-control'
                                                ]
                              )
            ?>
            <br>
        </div>
    </div>

    <div class='row'>
        <div class='col-lg-4 col-lg-offset-4 col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1'>
            <?=
            $this->Form->input('Resources.description', ['label' => 'Descripción: ',
                                               'placeholder' => 'Breve descripción del recurso',
                                               'class' => 'form-control'
                                              ]
                              )
            ?>
            <br>
        </div>
    </div>
</fieldset>
    
<div class='row  text-center' id="btnRegistrar">
    <div class='col-lg-4 col-lg-offset-4 col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1'>
        <br>
        <?= $this->Form->button('Agregar', ['class' => 'btn btn-success']); ?>
        <?= $this->Html->link('Regresar', array('controller' => 'resources','action'=> 'index'), array( 'class' => 'btn btn-warning')) ?>
    </div>
</div>
      
<legend><br></legend>

<?= $this->Form->end() ?>
</div>
