<div class="users form">
    <?= $this->Form->create($reservation) ?>
    
    <!-- TÍTULO -->
    <div class="row">
        <div class="col-xs-12">
            <legend>
                <div class="text-center">
                    <h1>Reservación</h1>
                    <br>
                </div>
            </legend>
        </div>
    </div>
    <!-- FIN TÍTULO -->
    
    <!-- CAMPOS A MOSTRAR -->
    <fieldset>
        <!-- RECURSO -->
        <div class="row">
            <div class="col-xs-12">
                <div class="col-xs-3 col-xs-offset-3">
                    <?= $this->Form->label('Reservations.resource_id', 'Recurso'); ?>
                </div>
                <div class="col-xs-3">
                    <?= $this->Form->label('Reservations.resource_id', $reservation->resource->resource_name, ['style' => 'font-weight: normal']); ?>
                </div>
            </div>
        </div>
        <!-- FIN RECURSO -->

        <br>

        <!-- FECHA DE RESERVACIÓN -->
        <div class="row">
            <div class="col-xs-12">
                <div class="col-xs-3 col-xs-offset-3">
                    <?= $this->Form->label('Reservations.start_date', 'Fecha de reservación'); ?>
                </div>
                <div class="col-xs-3">
                    <?php 
                        $mesIngles = strftime('%b', strtotime($reservation->start_date));
                        $mesEspanol = '';    

                        switch($mesIngles)
                        {
                            case 'Jan':
                                $mesEspanol = 'ene';
                                break;
                            case 'Feb':
                                $mesEspanol = 'feb';
                                break;
                            case 'Mar':
                                $mesEspanol = 'mar';
                                break;
                            case 'Apr':
                                $mesEspanol = 'abr';
                                break;
                            case 'May':
                                $mesEspanol = 'may';
                                break;
                            case 'Jun':
                                $mesEspanol = 'jun';
                                break;
                            case 'Jul':
                                $mesEspanol = 'jul';
                                break;
                            case 'Aug':
                                $mesEspanol = 'ago';
                                break;
                            case 'Sep':
                                $mesEspanol = 'set';
                                break;
                            case 'Oct':
                                $mesEspanol = 'oct';
                                break;
                            case 'Nov':
                                $mesEspanol = 'nov';
                                break;
                            case 'Dec':
                                $mesEspanol = 'dic';
                                break;
                        }

                        //$fechaEspanol = date_format($reservation->start_date, 'd').$mesEspanol.$date_format($reservation->start_date, 'Y');
                        $fechaEspanol = strftime('%d', strtotime($reservation->start_date)).'/'.$mesEspanol.'/'.strftime('%Y', strtotime($reservation->start_date));
                    ?>
                    <?= $this->Form->label('Reservations.start_date', $fechaEspanol, ['style' => 'font-weight: normal']); ?>
                </div>
            </div>
        </div>
        <!-- FIN FECHA DE RESERVACIÓN -->

        <br>

        <!-- HORA DE INICIO -->
        <div class="row">
            <div class="col-xs-12">
                <div class="col-xs-3 col-xs-offset-3">
                    <?= $this->Form->label('Reservations.start_date', 'Hora de inicio'); ?>
                </div>
                <div class="col-xs-3">
                    <?= $this->Form->label('Reservations.start_date', date_format($reservation->start_date, 'H:i:s'), ['style' => 'font-weight: normal']); ?>
                </div>
            </div>
        </div>
        <!-- FIN HORA DE INICIO -->

        <br>

        <!-- FECHA DE FIN -->
        <div class="row">
            <div class="col-xs-12">
                <div class="col-xs-3 col-xs-offset-3">
                    <?= $this->Form->label('Reservations.end_date', 'Hora de fin'); ?>
                </div>
                <div class="col-xs-3">
                    <?= $this->Form->label('Reservations.end_date', date_format($reservation->end_date, 'H:i:s'), ['style' => 'font-weight: normal']); ?>
                </div>
            </div>
        </div>
        <!-- FIN FECHA DE INICIO -->

        <br>

        <!-- USUARIO -->
        <div class="row">
            <div class="col-xs-12">
                <div class="col-xs-3 col-xs-offset-3">
                    <?= $this->Form->label('Reservations.user_id', 'Usuario'); ?>
                </div>
                <div class="col-xs-3">
                    <?= $this->Form->label('Reservations.user_id', $reservation->user->first_name.' '.$reservation->user->last_name, ['style' => 'font-weight: normal']); ?>
                </div>
            </div>
        </div>
        <!-- FIN USUARIO -->

        <br>

        <!-- COMENTARIO DE USUARIO -->
        <div class="row">
            <div class="col-xs-12">
                <div class="col-xs-3 col-xs-offset-3">
                    <?= $this->Form->label('Reservations.user_comment', 'Comentario de usuario'); ?>
                </div>
                <div class="col-xs-3">
                    <?php
                        $comentarioUsuario = '';

                        if($reservation->user_comment == '')
                            $comentarioUsuario = '-';
                        else
                            $comentarioUsuario = $reservation->user_comment;
                    ?>
                    <?= $this->Form->label('Reservations.user_comment', $comentarioUsuario, ['style' => 'font-weight: normal']); ?>
                </div>
            </div>
        </div>
        <!-- FIN COMENTARIO DE USUARIO -->

        <br>

        <!-- SIGLA DEL CURSO -->
        <div class="row">
            <div class="col-xs-12">
                <div class="col-xs-3 col-xs-offset-3">
                    <?= $this->Form->label('Reservations.course_id', 'Sigla del curso'); ?>
                </div>
                <div class="col-xs-3">
                    <?= $this->Form->label('Reservations.course_id', $reservation->course_id, ['style' => 'font-weight: normal']); ?>
                </div>
            </div>
        </div>
        <!-- FIN SIGLA DEL CURSO -->

        <br>

        <!-- NOMBRE DEL CURSO -->
        <div class="row">
            <div class="col-xs-12">
                <div class="col-xs-3 col-xs-offset-3">
                    <?= $this->Form->label('Reservations.course_name', 'Nombre del curso'); ?>
                </div>
                <div class="col-xs-3">
                    <?= $this->Form->label('Reservations.course_name', $reservation->course_name, ['style' => 'font-weight: normal']); ?>
                </div>
            </div>
        </div>
        <!-- FIN NOMBRE DEL CURSO -->

        <br>

        <!-- COMENTARIO DE ADMINISTRADOR -->
        <div class="row">
            <div class="col-xs-12">
                <div class="col-xs-3 col-xs-offset-3">
                    <?= $this->Form->label('Reservations.admin_comment', 'Comentario del administrador'); ?>
                </div>
                <div class="col-xs-3">
                    <?=
                    $this->Form->input('Reservations.admin_comment', [  'label' => false,
                                                                        'type' => 'textarea',
                                                                        'class' => 'form-control',
                                                                        'placeholder' => '(Opcional). Indique el motivo de la aceptación o rechazo de la reservación.']);
                ?>
                </div>
            </div>
        </div>
        <!-- FIN COMENTARIO DE ADMINISTRADOR -->

        <br>

        <!-- BOTONES -->
        <div class="row text-center">
            <div class='col-xs-8 col-xs-offset-2'>
                <?= $this->Form->submit('Aceptar', array('class' => 'btn btn-primary', 'div' => false, 'name' => 'accion')); ?>
                <?= $this->Form->submit('Rechazar', array('class' => 'btn btn-danger', 'div' => false, 'name' => 'accion')); ?>
            </div>
        </div> 
        <!-- FIN BOTONES -->
    </fieldset>
    <!-- FIN CAMPOS A MOSTRAR -->

    <?= $this->Form->end() ?>
    
    <?php //echo $this->fetch('postLink'); ?>
</div>