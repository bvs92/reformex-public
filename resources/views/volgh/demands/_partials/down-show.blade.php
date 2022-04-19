{{-- part from show.blade.php -- used for communication. --}}


<div class="row mt-4">
    @if($demand->quotes && $demand->quotes->count() > 0)
    <div class="col-lg-12 mt-4">
        <h2 class="my-4">Cotatii de pret</h2>
        <hr>
        @foreach($demand->quotes()->latest()->get() as $quote)
        
            <div class="card mt-2">
                <div class="card-header">
                    <h5 class="py-2">
                        De la: <strong> {{ $quote->showProName() }}</strong>
                    </h5>
                    
                    <div class="card-options">
                        <h5 class="py-2">
                            <h5>Pret estimativ: <strong>{{ $quote->price }} RON</strong></h5>
                        </h5>
                    </div>
                </div>
                <div class="card-body">
                    <div>
                        {{ $quote->message }}
                    </div>
                    @if($quote->files && $quote->files->count() > 0)
                        <div class="row mt-8">
                            @foreach($quote->files as $theFile)
                               
                                <div class="col-lg-3 col-md-6 col-6">
                                    @if($theFile->mime_type == 'image/jpeg' || $theFile->mime_type == 'image/png' || $theFile->mime_type == 'image/webp')
                                        <a href="javascript:void(0);">
                                            <img class="img-fluid img-thumbnail mt-4" src="{{asset('storage/quotes/' . $theFile->name)}}" alt="{{ $theFile->name }}">
                                        </a>
                                        @elseif($theFile->mime_type == 'application/pdf')
                                            <a href="{{URL::asset('storage/quotes/' . $theFile->name)}}" style="font-size:10px;">
                                                <i class="fa fa-file-pdf-o" style="color:darkred;font-size:40px;"></i> {{ $theFile->name }}
                                            </a>
                                        @elseif($theFile->mime_type == 'text/csv')
                                            <a href="{{URL::asset('storage/quotes/' . $theFile->name)}}" style="font-size:10px;">
                                                <i class="fa fa-file-word-o" style="color:blue;font-size:40px;"></i> {{ $theFile->name }}
                                            </a>
                                        @elseif($theFile->mime_type == 'application/msword')
                                            <a href="{{URL::asset('storage/quotes/' . $theFile->name)}}" style="font-size:10px;">
                                                <i class="fa fa-file-word-o" style="color:blue;font-size:40px;"></i> {{ $theFile->name }}
                                            </a>

                                        @elseif($theFile->mime_type == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')
                                            <a href="{{URL::asset('storage/quotes/' . $theFile->name)}}" style="font-size:10px;">
                                                <i class="fa fa-file-word-o" style="color:blue;font-size:40px;"></i> {{ $theFile->name }}
                                            </a>
                                        @elseif($theFile->mime_type == 'application/vnd.ms-excel')
                                            <a href="{{URL::asset('storage/quotes/' . $theFile->name)}}" style="font-size:10px;">
                                                <i class="fa fa-file-excel-o" style="color:green;font-size:40px;"></i> {{ $theFile->name }}
                                            </a>
                                        @elseif($theFile->mime_type == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
                                            <a href="{{URL::asset('storage/quotes/' . $theFile->name)}}" style="font-size:10px;">
                                                <i class="fa fa-file-excel-o" style="color:green;font-size:40px;"></i> {{ $theFile->name }}
                                            </a>
                                        @elseif($theFile->mime_type == "text/plain")
                                            <a href="{{URL::asset('storage/quotes/' . $theFile->name)}}" style="font-size:10px;">
                                                <i class="fa fa-file-o" style="color:gray;font-size:40px;"></i> {{ $theFile->name }}
                                            </a>
                                        @else
                                            <a href="{{URL::asset('storage/quotes/' . $theFile->name)}}" style="font-size:10px;">
                                                <i class="fa fa-file-o" style="color:gray;font-size:40px;"></i> {{ $theFile->name }}
                                            </a>
                                        @endif
                                </div>
                               
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="card-footer text-muted">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            Trimis: {{ $quote->showPublishDate() }}
                        </div>


                        @if($quote->professional_id == auth()->user()->professional->id)
                            <div class="col-lg-6 col-md-6 col-sm-12 d-flex justify-content-end">
                                <a href="{{ route('quotes.edit', $quote) }}" class="btn btn-sm btn-warning float-left"><i class="side-menu__icon ti-pencil-alt"></i> Editeaza</a>
                            <form action="{{ route('quotes.destroy', $quote) }}" method="POST" style="float:left;margin-left:10px;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="side-menu__icon ti-trash"></i> Sterge</a>
                            </form>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        @endforeach
    </div>
    @endif
</div> <!-- end here -->

@if($demand->hasProfessional(auth()->user()->professional))
    <br>
    <hr>
    <h3>Trimite o cotatie de pret si incepe o conversatie.</h3>
    <br>
    <form method="POST" action="{{ route('demand.quote.store', $demand) }}" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-8">
                <div class="form-group">
                    <label for="message">Descrieti cotatia de pret cat mai clar</label>
                    <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="8">{{ old('message') }}</textarea>
                    @error('message')
                        <p class="small text-danger">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="price">Estimare Pret total</label>
                    <input type="text" class="form-control @error('price') is-invalid @enderror" id="price" name="price" placeholder="20.000" value="{{ old('price') }}">
                    @error('price')
                        <p class="small text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="form-label">Atasati fisier (optional)</div>
                    <div class="custom-file">
                        <input type="file" class="form-control custom-file-input @error('files_quote') is-invalid @enderror" class="custom-file-input" name="files_quote">
                        <label class="custom-file-label"><i class="ti-link"></i> Selecteaza fisier</label>
                        {{-- <p class="text-small text-gray">Selectati pana la 5 fisiere. Fisiere acceptate: Imagini, PDF, Documente Word, Excel sau text.</p> --}}
                    </div>

                    @error('files_quote')
                        <p class="small text-danger">{{ $message }}</p>
                    @enderror
                </div>
                

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block mt-4"><i class="fa fa-send"></i> Trimite cotatia de pret </button>
                </div>
            </div>
        </div>
        
        
        

    </form>
@endif