<?php echo $this->Html->css('reservations.css');?>

<br>

<!-- TÍTULO -->
<div class="row text-center">
  <div class="col-xs-12" style="color:#000;">
      <h2>Calendario de Reservas</h2>
  </div>
</div>
<!-- FIN DE TÍTULO -->

<br><br>

<!-- SIMBOLOGÍA -->
<div>
    <div class="row"> 
        <div class="col-md-3 col-md-offset-9 col-sm-3 col-sm-offset-8 col-xs-3 col-xs-offset-9">
            <label>Estado de reservación</label>
        </div>
    </div>

    <div class="row" >
        <div class="col-md-3 col-md-offset-9 col-sm-3 col-sm-offset-8 col-xs-3 col-xs-offset-9 ">
            <div class="col-md-3 col-md-offset-1 col-sm-2 col-sm-offset-1 col-xs-12 col-xs-offset-1 ">
                Pendiente
            </div>
            <div class="poligp">    
            </div> 
        </div>
    </div>

    <div class="row">
        <div class="col-md-3 col-md-offset-9 col-sm-3 col-sm-offset-8 col-xs-3 col-xs-offset-9 ">
            <div class="col-md-3 col-md-offset-1 col-sm-6 col-sm-offset-1 col-xs-12 col-xs-offset-1 ">
                Aceptada
            </div>
            <div class="poliga">    
            </div> 
        </div>
    </div>
</div>
<!-- FIN DE SIMBOLOGÍA -->

<br><br>

<div id='calendar'>
</div>  

<br><br>

<!-- MENSAJES -->
<div id="callback" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- MODAL CONTENT -->
        <div class="modal-content">
            <!-- MODAL HEADER -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" role="button" aria-label="Cerrar">&times;</button>
                <h4 class="modal-title">Confirmación</h4>
            </div>
            
            <!-- MODAL BODY -->
            <div class="modal-body">
                <h4 id="callbackText">¡Su reservación está siendo procesada!</h4>
            </div>
            
            <!-- MODAL FOOTER -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" role="button" aria-label="Cerrar">Cerrar</button>
            </div>
        </div>
        <!-- FIN MODAL CONTENT -->
    </div>
</div>
<!-- FIN DE MENSAJES -->

<!-- MODAL -->
<div id="mdlReservaciones" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <!-- MODAL CONTENT -->
        <div class="modal-content">
            
            <!-- MODAL HEADER -->
            <div class="modal-header label-success" style="color:#fff;">
                <button type="button" class="close" data-dismiss="modal" role="button" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Reservación</h4>
            </div>
            
            <!-- MODAL BODY -->
            <div class="modal-body" style="color:#000;">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                        <h3 id="fecha">Fecha</h3>
                    </div>
                </div>
                
                <div class="row">
                    <!-- HORA INICIO -->
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="col-sm-12 col-sm-offset-0 col-xs-10 col-xs-offset-1">
                            <h4><font color="red">* </font>Hora de inicio</h4>
                        </div>
                        <div class="col-sm-12 col-sm-offset-0 col-xs-10 col-xs-offset-1">
                            <select name="horaInicio" class="form-control" role="listbox" aria-label="Hora de inicio" aria-required="true" id="start" onchange="changeEndHour(); getResources(document.getElementById('resource_type'));">                            
                                <?php
                                    $inicioBD = $configuration['reservation_start_hour'];
                                    $finBD = $configuration['reservation_end_hour'];

                                    for($i = $inicioBD; $i < $finBD; $i++)
                                    {
                                        if($i<10)
                                            echo "<option value=".$i."> 0".$i.":00:00</option>";
                                        else 
                                            echo "<option value=".$i."> ".$i.":00:00</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <!-- HORA FIN -->
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="col-sm-12 col-sm-offset-0 col-xs-10 col-xs-offset-1">
                            <h4><font color="red">* </font>Hora de fin</h4>
                        </div>
                        <div class="col-sm-12 col-sm-offset-0 col-xs-10 col-xs-offset-1">
                            <select name="horaFin" class="form-control" role="listbox" aria-label="Hora de fin" aria-required="true" id="end" onchange="getResources(document.getElementById('resource_type'))">
                                <?php
                                    $inicioBD = $configuration['reservation_start_hour'] + 1;
                                    $finBD = $configuration['reservation_end_hour'] + 1;

                                    for($i = $inicioBD; $i < $finBD; $i++)
                                    {
                                        if($i<10)
                                            echo "<option value=".$i."> 0".$i.":00:00</option>";
                                        else 
                                            echo "<option value=".$i."> ".$i.":00:00</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                
                <br>
                
                <div class="row">
                    <!-- TIPOS RECURSO -->
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="col-sm-12 col-sm-offset-0 col-xs-10 col-xs-offset-1">
                            <h4><font color="red">* </font>Tipo de recurso</h4>
                        </div>
                        <div class="col-sm-12 col-sm-offset-0 col-xs-10 col-xs-offset-1">
                            <select name="tipoRecurso" class="form-control" role="listbox" aria-label="Tipo de recurso" aria-required="true" onchange="getResources(this);activateButton(this, getElementById('check'));" id="resource_type">
                                <option value="Seleccionar" selected disabled>Seleccionar</option>
                                <?php
                                    foreach ($types as $value) 
                                    {
                                        echo "<option>".$value['description']."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <!-- RECURSOS -->
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="col-sm-12 col-sm-offset-0 col-xs-10 col-xs-offset-1">
                            <h4><font color="red">* </font>Recursos disponibles</h4>
                        </div>
                        <div class="col-sm-12 col-sm-offset-0 col-xs-10 col-xs-offset-1">
                            <select name="recursosDisponibles" class="form-control" role="listbox" aria-label="Recursos disponibles" aria-required="true" id="resource" onchange="showDescription(this)">
                                <option value="Seleccionar" selected disabled>Seleccionar</option>
                            </select>
                        </div>
                    </div>
                </div>              
                
                <br>
                
                <div class="row">
                    <!-- EVENTO/CURSO -->
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="col-sm-12 col-sm-offset-0 col-xs-10 col-xs-offset-1">
                            <h4><font color="red">* </font>Nombre del evento o curso</h4>
                        </div>
                        <div class="col-sm-12 col-sm-offset-0 col-xs-10 col-xs-offset-1">
                            <input class="form-control" type="text" id="event_name" role="textbox" aria-label="Nombre del evento" placeholder="FD-1312 Introducción a la enseñanza primaria" oninput="setEventName(this); activateButton(document.getElementById('resource_type'), document.getElementById('check')); ">
                        </div>
                    </div>
                    
                    <!-- COMENTARIOS -->
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="col-sm-12 col-sm-offset-0 col-xs-10 col-xs-offset-1">
                            <h4>Comentarios</h4>
                        </div>
                        <div class="col-sm-12 col-sm-offset-0 col-xs-10 col-xs-offset-1">
                            <textarea class="form-control" rows="2" id="comment"  role="textbox" aria-label="Comentarios (Opcional)" placeholder="Si necesita algo adicional a lo especificado en el recurso, por favor digítelo aquí."></textarea>
                        </div>
                    </div>
                </div>
                    
                <br>
                
                <!-- DESCRIPCIÓN DETALLADA -->
                <div class="row">
                    <div class="text-center">
                        <button data-toggle="collapse" class="btn btn-info" data-target="#resource_description">Información detallada</button>
                    </div>
                    <div id="resource_description" class="col-xs-10 col-xs-offset-1 collapse text-center"></div>
                </div>
                
                <br>
                
                <!-- TÉRMINOS Y CONDICIONES DE USO -->
                <div class="row">
                    <div class="text-center">
                        <div class="col-xs-12">
                            <label><input type="checkbox" value="" unchecked id="check" name="terms" onchange="activateButton(document.getElementById('resource_type'), this)"> He leído y acepto los </label>
                            <?php echo $this->Html->link('Términos y Condiciones de Uso',
                                         array('controller'=>'pages','action' => 'policy'),
                                         array('target' => '_blank', 'escape' => false, 'title'=>'Condiciones de Uso')) ?>
                        </div>
                    </div>
                </div>
                
                <!-- CAMPOS OBLIGATORIOS -->
                <div class="row">
                    <div class="col-xs-12">
                        <div class="col-xs-12">
                            <h6>(<font color="red">*</font>) Campos obligatorios</h6>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- MODAL FOOTER -->
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="getReservationData()" data-dismiss="modal" role="button" aria-label="Reservar" id="Reservar">Reservar</button>
                <button type="button" class="btn btn-danger" style="width:84px;" data-dismiss="modal" role="button" aria-label="Cerrar">Cerrar</button>
            </div>
        </div>
        <!-- FIN MODAL CONTENT -->
    </div>
</div>
<!-- FIN MODAL -->

<?= $this->Html->script('reservations.js'); ?>