<h2>Conversación</h2>
<div class="span9">

    <div id ="chat_vista">
        <a href="#" id="anteriores">Mostrar mensajes anteriores</a>
    </div>

    <div id="chat_input">
        <form method="post" action="<?=base_url();?>chat/mensaje">
            <?= form_input($formulario['input']); ?>
            <?= form_error($formulario['input']['name']); ?>
            <input type="submit" class="input-submit btn btn-info" value="Enviar" />
        </form>
        
    </div>
</div>

<div class="span2">
    <div id="online">
        <h5>Usuarios conectados (<span id="contador">0</span>)</h5>
        <div id="lista online">

           <div id="usuarios">

           </div>
        </div>
    </div>
</div>