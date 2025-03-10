<div class="row">
    <x-adminlte-modal class="progressbar" id="modalAlerta"  size='l' scrollable="no" data-backdrop="static" data-keyboard="false" disable-animations>
        <div style="display: flex; flex-direction:column; justify-content: center;">
            <div class="attention">
                <?php 
                    $ruta_logo = "/images/atencion.png";
                    $imagenPath_header = public_path($ruta_logo);
                    $imagenData_header = file_get_contents($imagenPath_header);
                    $imagenBase64_header = base64_encode($imagenData_header);
                ?>
                <img src="data:image/png;base64,{{ $imagenBase64_header }}" class="image_attention">
            </div>
            <div class="container_message">
                <p id="mensaje_alerta" class="message"></p>
            </div>
        </div>
    </x-adminlte-modal>
</div>   
<style>
    #modalAlerta .modal-header{
        display: none;
    }
    .attention{
        display: flex;
        justify-content: center;
    }
    .image_attention{
        width: 20%;
        height: 20%;
    }
    .container_message{
        display: flex;
        justify-content: center;
    }
    .message{
        font-size: 22px;
        font-weight: bold;
        text-align: justify;
    }
    #modalAlerta .modal-body{
        border: 10px solid #29b3c9;
    }
</style>