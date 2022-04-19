<!-- ROW-5 -->
<div class="row">
    <div class="col-12 col-sm-12">
        <div class="card">
            <div class="card-header ">
                <h3 class="card-title ">Tichetele mele</h3>
                <div class="card-options">
                    <a href="{{ route('tickets.create') }}" id="add__new__category" class="btn btn-md btn-primary "><i class="fa fa-plus"></i> Tichet nou</a>
                </div>
            </div>
            <div class="card-body">
                <div class="grid-margin">
                    <div class="">
                        <div class="table-responsive">

                            <table class="table card-table border table-vcenter text-nowrap align-items-center">
                                <thead class="thead-dark">
                                  <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Data</th>
                                    <th scope="col">Moderatori</th>
                                    <th scope="col">Departament</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">@</th>
                                  </tr>
                                </thead>
                                <tbody>
                                    @if($tickets && $tickets->count() > 0)
                                        @foreach($tickets as $ticket)
                                            <tr>
                                                <th scope="row">{{ $ticket->getDisponibleId() }}</th>
                                                <td data-toggle="tooltip" data-placement="top" title="{{ formatCarbonDate($ticket->created_at) }}">{{ formatShortCarbonDate($ticket->created_at) }}</td>
                                                {{-- <td>
                                                    @if($ticket->priority == 0)
                                                        <span class="tag tag-gray">Nesetat</span>
                                                    @elseif($ticket->priority == 1)
                                                        <span class="tag tag-red">Urgent</span>
                                                    @elseif($ticket->priority == 2)
                                                        <span class="tag tag-orange">Important</span>
                                                    @elseif($ticket->priority == 3)
                                                        <span class="tag tag-blue">Normal</span>
                                                    @endif
                                                </td> --}}
                                                <td>
                                                    @if($ticket->resolvers()->count() > 0)
                                                        @foreach($ticket->resolvers as $resolver) <p class="text-small">{{ $resolver->user->getName() }}</p> @endforeach
                                                    @else
                                                    <p class="text-small text-muted">Niciunul</p>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($ticket->department_id == 0)
                                                        <span>General</span>
                                                    @elseif($ticket->department_id == 1)
                                                        <span>Comercial</span>
                                                    @elseif($ticket->department_id == 2)
                                                        <span>Tehnic</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($ticket->status == 0)
                                                        <span class="tag tag-green">Deschis</span>
                                                    @else
                                                        <span class="tag tag-red">Inchis</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="row">
                                                        <div class="dropdown float-right">
                                                            <a class="btn btn-secondary btn-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                Actiuni
                                                            </a>
                                                            
                                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                                @if($ticket->hasUUID())
                                                                {{-- <a href="{{ route('tickets.show.uuid', $ticket->uuid) }}" class="dropdown-item"><i class="ti-eye"></i> Vezi</a> --}}
                                                                <a href="{{ route('tickets.show.vue.uuid', $ticket->uuid) }}" class="dropdown-item"><i class="ti-eye"></i> Vezi</a>
                                                                @endif

                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                            
                                            </tr>
                                        @endforeach
                                  @endif
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-center">
                                {{ $tickets->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- COL END -->
</div>
<!-- ROW-5 END -->