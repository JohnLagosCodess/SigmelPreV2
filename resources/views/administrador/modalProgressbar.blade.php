<div class="row">
    <x-adminlte-modal class="progressbar" id="modalProgressBar" title="Progress Bar" theme="info" icon="fas fa-spinner" size='l' scrollable="no" disable-animations>
        <div class="progress" style="height: 30px">
            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%; height: 100%">0%</div>
        </div>
    </x-adminlte-modal>
</div>   
<style>
    .progress{
        background-color: rgba(0, 0, 0, 0.3);
    }
    .progressbar{
        background-color: rgba(0, 0, 0, 0.5)
    }
    .progressbar .modal-dialog {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
    }
    .progressbar .modal-footer{
        display: none;
    }
    .close{
        display: none;
    }
    .modal-header{
        
    }
</style>