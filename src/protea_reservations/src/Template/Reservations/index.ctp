<?php    echo $this->Html->css('reservations.css');?>
<div class="row text-center">
  <div class="col-xs-12">
      <h1>Calendario de Reservas</h1>
  </div>
</div>

<br>
        <div id='calendar'></div>   
<br>
<br>

<div id="callback" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" role="button" aria-label="Cerrar">&times;</button>
        <h4 class="modal-title">Confirmación</h4>
      </div>
      <div class="modal-body">
        <h4 id="callbackText">¡Su reservación fue exitosa!</h4>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" role="button" aria-label="Cerrar">Close</button>
      </div>
    </div>

  </div>
</div>

<!-- Modal -->
<div id="mdlReservaciones" class="modal fade" role="dialog">
    <div class="modal-dialog" role="document">
        <!-- Modal content -->
        <div class="modal-content">
            <!-- Modal header -->
            <div class="modal-header label-success">
                <button type="button" class="close" data-dismiss="modal" role="button" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Reservación</h4>
            </div>
            <!-- Fin Modal header -->
            
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                        <h3 id="fecha">Fecha</h3>
                    </div>
                </div>
                
                <!-- Fila 1 (Fechas) -->
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="col-xs-12">
                            <h4>Hora de inicio</h4>
                        </div>
                        <div class="col-xs-12">
                            <select name="horaInicio" class="form-control" role="listbox" aria-label="Hora de inicio" aria-required="true" id="start" onchange="getResources(document.getElementById('resource_type')); changeEndHour();">                            
                                <?php
                                    $inicioBD = 7;
                                    $finBD = 21;

                                    for($i = $inicioBD; $i < 10; $i++)
                                    {
                                        echo "<option value=".$i."> 0".$i.":00:00</option>";
                                    }

                                    for($i = 10; $i < $finBD; $i++)
                                    {
                                        echo "<option value=".$i."> ".$i.":00:00</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="col-xs-12">
                            <h4>Hora de fin</h4>
                        </div>
                        <div class="col-xs-12">
                            <select name="horaFin" class="form-control" role="listbox" aria-label="Hora de fin" aria-required="true" id="end" onchange="getResources(document.getElementById('resource_type'))">
                                <?php
                                    $inicioBD = 8;
                                    $finBD = 22;

                                    for($i = $inicioBD; $i < 10; $i++)
                                    {
                                        echo "<option value=".$i."> 0".$i.":00:00"."</option>";
                                    }

                                    for($i = 10; $i < $finBD; $i++)
                                    {
                                        echo "<option value=".$i."> ".$i.":00:00"."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- Fin Fila 1 (Fechas) -->
                
                <br>
                
                <!-- Fila 2 (Recursos) -->
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="col-xs-12">
                            <h4>Tipo de recurso</h4>
                        </div>
                        <div class="col-xs-12">
                            <select name="tipoRecurso" class="form-control" role="listbox" aria-label="Tipo de recurso" aria-required="true" onchange="getResources(this)" id="resource_type">
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
                    
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="col-xs-12">
                            <h4>Recursos disponibles</h4>
                        </div>
                        <div class="col-xs-12">
                            <select name="recursosDisponibles" class="form-control" role="listbox" aria-label="Recursos disponibles" aria-required="true" id="resource" onchange="showDescription(this)">
                                <option value="Seleccionar" selected disabled>Seleccionar</option>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- Fin Fila 2 (Recursos) -->
                                      
                
                <br>
                
                <!-- Fila 3 (Sigla y nombre del curso) -->
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="col-xs-12">
                            <h4>Sigla del curso</h4>
                        </div>
                        <div class="col-xs-12">
                            <input class="form-control" type="text" id="course_id" role="textbox" aria-label="Sigla del curso (Opcional)" placeholder="Opcional">
                        </div>
                    </div>
                    
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="col-xs-12">
                            <h4>Nombre del curso</h4>
                        </div>
                        <div class="col-xs-12">
                            <input class="form-control" type="text" id="course_name" role="textbox" aria-label="Nombre del curso (Opcional)"  placeholder="Opcional">
                        </div>
                    </div>
                </div>
                <!-- Fin Fila 3 (Sigla y nombre del curso) -->
                
                <br>
                <br>
                

                <div class="row">
                    <div class="col-xs-12">
                        <button data-toggle="collapse" class="btn btn-info" data-target="#resource_description">Información Detallada</button>
                        <div id="resource_description" class="collapse"></div>
                    </div>
                </div>
                
                <br>
                <br>

                <!-- Fila 4 (Comentario) -->
                <div class="row">
                    <div class="col-xs-12">
                        <h4>Comentarios</h4>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <textarea class="form-control" rows="5" id="comment"  role="textbox" aria-label="Comentarios (Opcional)" placeholder="Si necesita algo adicional a lo especificado en el recurso, por favor digítelo aquí."></textarea>
                    </div>
                </div>
                <!-- Fin Fila 4 (Comentario) -->
            </div>
            <!-- Fin Modal body -->
            
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="getReservationData()" data-dismiss="modal" role="button" aria-label="Reservar">Reservar</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal" role="button" aria-label="Cerrar">Cerrar</button>
            </div>
            <!-- Fin Modal footer -->
        </div>
        <!-- Fin Modal content -->
    </div>
</div>
<!-- Fin Modal -->

