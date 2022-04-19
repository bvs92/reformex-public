@extends('volgh.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- PAGE-HEADER -->
        <div>
            <h1 class="page-title">Categorii</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Acasa</a></li>
                <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Categorii</a></li>
                <li class="breadcrumb-item active" aria-current="page">Categorie noua</li>
            </ol>
        </div>							
    <!-- PAGE-HEADER END -->
@endsection
@section('content')

            <!-- ROW-5 -->
            <div class="row">
                <div class="col-12 col-sm-12">
                    <div class="card">
                        <div class="card-header ">
                            <h3 class="card-title ">Detalii categorie</h3>
                        </div>
                        <div class="card-body">
                            <div class="grid-margin">
                                <div class="">
                                    <div>
                                       <form action="{{ route('categories.update', $category) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                          
                                            <div class="row">
                                              <div class="col-lg-6">
                                                  <div class="form-group">
                                                    <label for="name">Nume</label>
                                                    <input type="text" class="form-control @error('name') has-error @enderror" id="name" placeholder="Numele categoriei" name="name" value="{{ old('name') ?? $category->name }}">
                                                    @error('name')
                                                        <p class="small text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                              </div>
                                              <div class="col-lg-6">
                                                  <div class="form-group">
                                                    <label for="slug">Slug (URL)</label>
                                                    <input type="text" class="form-control @error('slug') has-error @enderror" id="slug" placeholder="Adresa url categorie" name="slug" value="{{ old('slug') ?? $category->slug }}">
                                                    @error('slug')
                                                        <p class="small text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                              </div>
                                            </div>
                                          
                                            <div class="form-group">
                                              <label for="price">Pret</label>
                                              <input type="text" 
                                              class="form-control @error('price') has-error @enderror" 
                                              id="price" 
                                              placeholder="Pretul cererilor din categorie" 
                                              name="price" value="{{ old('price') ?? $category->getPriceNormal() }}">
                                              @error('price')
                                                  <p class="small text-danger">{{ $message }}</p>
                                              @enderror
                                          </div>
                                          
                                            
                                            <div class="form-group">
                                                <label for="description">Descriere categorie</label>
                                                <textarea class="form-control 
                                                @error('description') has-error @enderror" 
                                                name="description" id="description" 
                                                cols="30" rows="6"
                                                >{{ old('description') ?? $category->description }}</textarea>
                                          
                                                @error('description')
                                                    <p class="small text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>
                                          
                                            <div class="form-group row">
                                                <div class="col-sm-10">
                                                  <button type="submit" class="btn btn-success">Salveaza categoria</button>
                                                </div>
                                            </div>
                                          
                                          
                                          </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- COL END -->
            </div><!-- ROW-5 END -->


             <!-- ROW-6 -->
             <div class="row">
                <div class="col-12 col-sm-12">
                    <div class="card">
                        <div class="card-header ">
                            <h3 class="card-title ">Alte Informatii</h3>
                        </div>
                        <div class="card-body">
                            <div class="grid-margin">
                                <div class="">
                                    <div>
                                        <div class="row">
                                            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-4">
                                                <div class="card overflow-hidden">
                                                    <div class="card-body pb-0">
                                                        <div class="">
                                                        <div class="d-flex">
                                                        <h6 class="mb-3">Ceva aici</h6>
                                                        <div class="ml-auto">
                                                        <a class="btn btn-light btn-sm btn-icon mr-2" href="#"><i class="fa fa-chevron-left"></i></a>
                                                        <a class="btn btn-light btn-sm btn-icon" href="#"><i class="fa fa-chevron-right"></i></a>
                                                        </div>
                                                        </div>
                                                        <h3 class="number-font mb-1">1234</h3>
                                                        <span class="text-success"><i class="zmdi zmdi-long-arrow-up zmdi-hc-lg mr-2"></i><span>+24%</span></span><span class="text-muted ml-2">From Last Month</span>
                                                        <p class="mb-0 mt-2 text-muted">Lorem ipsum dolor sit amet odio consectetur accusamus .</p>
                                                        </div>
                                                    </div>
                                                    <div class="chart-wrapper">
                                                        <canvas id="widgetChart1" class="chart-dropshadow-info"></canvas>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-4">
                                                <div class="card overflow-hidden">
                                                    <div class="card-body pb-0">
                                                        <div class="">
                                                        <div class="d-flex">
                                                        <h6 class="mb-3">Numar cereri</h6>
                                                        <div class="ml-auto">
                                                        <a class="btn btn-light btn-sm btn-icon mr-2" href="#"><i class="fa fa-chevron-left"></i></a>
                                                        <a class="btn btn-light btn-sm btn-icon" href="#"><i class="fa fa-chevron-right"></i></a>
                                                        </div>
                                                        </div>
                                                        <h3 class="number-font mb-1">{{ $category->demands->count() }}</h3>
                                                        <span class="text-success"><i class="zmdi zmdi-long-arrow-up zmdi-hc-lg mr-2"></i><span>90.5%</span></span><span class="text-muted ml-2">From Last Month</span>
                                                        <p class="mb-0 mt-2 text-muted">Lorem ipsum dolor sit amet odio consectetur accusamus .</p>
                                                        </div>
                                                    </div>
                                                    <div class="chart-wrapper">
                                                        <canvas id="widgetChart2" class="chart-dropshadow-secondary"></canvas>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-4">
                                                <div class="card overflow-hidden">
                                                    <div class="card-body pb-0">
                                                        <div class="">
                                                        <div class="d-flex">
                                                        <h6 class="mb-3">Cereri Inregistrate azi</h6>
                                                        <div class="ml-auto">
                                                        <a class="btn btn-light btn-sm btn-icon mr-2" href="#"><i class="fa fa-chevron-left"></i></a>
                                                        <a class="btn btn-light btn-sm btn-icon" href="#"><i class="fa fa-chevron-right"></i></a>
                                                        </div>
                                                        </div>
                                                        <h3 class="number-font mb-1">8963</h3>
                                                        <span class="text-success"><i class="zmdi zmdi-long-arrow-up zmdi-hc-lg mr-2"></i><span>20.8%</span></span><span class="text-muted ml-2">From Last Month</span>
                                                        <p class="mb-0 mt-2 text-muted">Lorem ipsum dolor sit amet odio consectetur accusamus .</p>
                                                        </div>
                                                    </div>
                                                    <div class="chart-wrapper">
                                                        <canvas id="widgetChart3" class="chart-dropshadow-success"></canvas>
                                                    </div>
                                                </div>
                                            </div>
                                            </div>
                                            <!-- ROW-4 END -->
                                            
                                            <!-- ROW-5 -->
                                            <div class="row">
                                            <div class="col-12 col-sm-12">
                                            <div class="card">
                                            <div class="card-header ">
                                                <h3 class="card-title ">Cereri inregistrate in categoria <strong>{{ $category->name }}</strong></h3>
                                                <div class="card-options">
                                                    <a href="{{ route('demands.create') }}" id="add__new__category" class="btn btn-md btn-primary "><i class="fa fa-plus"></i> Adauga cerere noua</a>
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
                                                            <th scope="col">Subiect cerere</th>
                                                            <th scope="col">Oras</th>
                                                            <th scope="col">Data</th>
                                                            <th scope="col">Actiuni</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    @if($category->demands && $category->demands->count() > 0)
                                                        @foreach($category->demands as $demand)
                                                        <tr>
                                                            <th scope="row">{{ $demand->id }}</th>
                                                            <td style="width:40%;">{{ $demand->subject }}</td>
                                                            <td style="width:40%;">{{ $demand->city }}</td>
                                                            <td style="width:40%;">{{ $demand->showPublishDate() }}</td>
                                                            <td style="width:40%;">
                                                                <div class="row">
                                                                    <div class="dropdown float-right">
                                                                        <a class="btn btn-secondary btn-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            Actiuni
                                                                        </a>
                                                                        
                                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                                            <a class="dropdown-item" href="{{ route('demands.show', $demand) }}">Vezi</a>
                                                                            <a class="dropdown-item" href="#">Editeaza</a>
                                                                            <a class="dropdown-item" href="#">Elimina</a>
                                                                        </div>
                                                                        </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td colspan="5">
                                                                <p class="text-center">
                                                                    Nu exista nicio cerere inregistrata.
                                                                </p>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                            </div>
                                            </div>
                                            </div>
                                            </div>
                                            </div><!-- COL END -->
                                            </div><!-- ROW-5 END -->

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- COL END -->
            </div><!-- ROW-6 END -->


        </div>
    </div>
    <!-- CONTAINER END -->
</div>
@endsection
@section('js')
@endsection
			
	
	

		