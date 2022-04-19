<div style="background:white;">

    @auth
    <div class="pt-4">
        <p class="text-center">Plan actual: <strong>{{ auth()->user()->activeSubscription() }}</strong></p>
    </div>
    <hr>
    @endauth

    

    <div class="pt-4">
       <div class="text-center">
            <img src="{{ asset(auth()->user()->getFullProfilePhoto()) }}" width="100px" class="rounded" alt="...">
            <p class="py-3">Firma Mea SRL</p>
        </div>
    </div>


    <div class="pt-4 mt-2">
        <h5 class="px-2 mb-0">Meniu</h5>
        <hr>
        <ul class="nav flex-column">

            <li class="nav-item">
                <a class="nav-link active" href="{{ route('subscriptions.index') }}">Abonamente</a>
            </li>

            <li class="nav-item">
                <a class="nav-link active" href="{{ route('credits.index') }}">Balanta</a>
            </li>

            <li class="nav-item">
                <a class="nav-link active" href="{{ route('categories.index') }}">Categorii</a>
            </li>
    
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('demands.index') }}">Cereri</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('demands.personal') }}">Cereri personalizate</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('demands.unlocked') }}">Cereri deblocate</a>
            </li>

            <li class="nav-item">
                <a class="nav-link active" href="{{ route('quotes.personal') }}">Cotatii trimise</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('users.index') }}">Utilizatori</a>
            </li>

            <li class="nav-item">
                <a class="nav-link active" href="{{ route('roles.index') }}">Roluri</a>
            </li>

            <li class="nav-item">
                <a class="nav-link active" href="{{ route('permissions.index') }}">Permisiuni</a>
            </li>

            
            {{-- <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
            </li>
            <li class="nav-item">
                <a class="nav-link disabled" href="#">Disabled</a>
            </li> --}}
        </ul>
    </div>
</div>