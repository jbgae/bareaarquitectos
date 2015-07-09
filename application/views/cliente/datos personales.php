            <div class="datosUsuario">
                <ul id="columIzquierda">
                    <li><span class="titulo">Nombre:</span></li>
                    <li><span class="titulo">Apellidos:</span></li>
                    <li><span class="titulo">Dirección:</span></li>
                    <li><span class="titulo">Ciudad:</span></li>
                    <li><span class="titulo">Provincia:</span></li>
                    <li><span class="titulo">Fecha Nacimiento:</span></li>
                    <li><span class="titulo">Fecha alta sistema:</span></li>
                    <li><span class="titulo">Teléfono:</span></li>
                    <li><span class="titulo">Email:</span></li>
                </ul>
                <ul id="columDerecha">
                    <li><?= $cliente->Nombre;?></li>
                    <li><?= $cliente->ApellidoP .' '. $cliente->ApellidoM;?></li>
                    <li>
                        <?php if($cliente->Direccion != 'Desconocida'):?>
                            <?= $cliente->Direccion;?>
                        <?php else:?>
                            <?= '------------ ';?>
                        <?php endif;?>
                    </li>
                    <li>
                        <?php if($cliente->Ciudad != 'Desconocida'):?>
                            <?= $cliente->Ciudad;?>
                        <?php else:?>
                            <?= '------------ ';?>
                        <?php endif;?>
                    </li>
                    <li>
                        <?php if($cliente->Provincia != 'Desconocida'):?>
                            <?= $cliente->Provincia;?>
                        <?php else:?>
                            <?= '------------ ';?>
                        <?php endif;?>
                    </li>
                    <li>
                        <?php if($cliente->FechaNacimiento != 'Desconocida'):?>
                            <?= $cliente->FechaNacimiento;?>
                        <?php else:?>
                            <?= '------------ ';?>
                        <?php endif;?>
                    </li>
                    <li>
                        <?php if($cliente->FechaAltaSistema != 'Desconocida'):?>
                            <?= $cliente->FechaAltaSistema;?>
                        <?php else:?>
                            <?= '------------ ';?>
                        <?php endif;?>
                    </li>
                    <li> 
                        <?php if($cliente->Telefono != 'Desconocida'):?>
                            <?= $cliente->Telefono;?>
                        <?php else:?>
                            <?= '------------ ';?>
                        <?php endif;?>
                    </li>
                    <li><?= $cliente->Email;?></li>        
                </ul>
            </div>
        </div>    
    </div> 
</section>
