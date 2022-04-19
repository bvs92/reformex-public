<div class="row">
    <div class="col-lg-6">
        <p class="mt-4">Status cerere: @if($demand->getStatus() == 1) <span class="badge badge-success">Verificata</span> @elseif($demand->getStatus() == 0) <span class="badge badge-default">Neverificata</span> @else <span class="badge badge-danger">Falsa</span> @endif</p>
    </div>
    <div class="col-lg-6">
        @if($demand->getStatus() == 0)
            <a onclick="event.preventDefault();document.getElementById('changeStatusToTrue').submit();" class="btn btn-sm btn-success mt-4 text-white d-flex justify-content-center"><i class="ti-check"></i> Marcare 'Verificata'</a>
            <a onclick="event.preventDefault();document.getElementById('changeStatusToFalse').submit();" class="btn btn-sm btn-danger mt-4 text-white d-flex justify-content-center"><i class="ti-na"></i> Marcare 'Falsa'</a>
           
            <form 
                action="{{ route('demands.change.status.verified.uuid', $demand->uuid) }}" 
                id="changeStatusToTrue" 
                method="POST" 
                style="display: none;">
                @csrf
                @method('PUT')
            </form>

            <form 
                action="{{ route('demands.change.status.false.uuid', $demand->uuid) }}" 
                id="changeStatusToFalse" 
                method="POST" 
                style="display: none;">
                @csrf
                @method('PUT')
            </form>
        @elseif($demand->getStatus() == 1)
        <a onclick="event.preventDefault();document.getElementById('changeStatusToFalse').submit();" class="btn btn-sm btn-danger mt-4 text-white d-flex justify-content-center"><i class="ti-na"></i> Marcare 'Falsa'</a>

        <form 
            action="{{ route('demands.change.status.false.uuid', $demand->uuid) }}" 
            id="changeStatusToFalse" 
            method="POST" 
            style="display: none;">
            @csrf
            @method('PUT')
        </form>
        @else
        <a onclick="event.preventDefault();document.getElementById('changeStatusToTrue').submit();" class="btn btn-sm btn-success mt-4 text-white d-flex justify-content-center"><i class="ti-check"></i> Marcare 'Verificata'</a>
       
        <form 
            action="{{ route('demands.change.status.verified.uuid', $demand->uuid) }}" 
            id="changeStatusToTrue" 
            method="POST" 
            style="display: none;">
            @csrf
            @method('PUT')
        </form>
        @endif
    </div>

    @if($demand->getStatus() == 2)
    <hr>
    <div class="col-lg-12">
        <p class="text-center">Cererea a fost marcata ca fiind falsa. Va recomandam eliminarea acesteia.</p>
        <a onclick="event.preventDefault();document.getElementById('deleteDemand').submit();" class="btn btn-sm btn-danger mt-4 text-white d-flex justify-content-center"><i class="ti-trash"></i> Elimina definitiv</a>
    </div>
    @endif

</div>