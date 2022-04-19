<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://laravel.com/img/notification-logo.png" class="logo" alt="Laravel Logo">
@else
{{-- {{ $slot }}  --}}
{{-- <img src="{{URL::asset('assets/images/brand/reformex-logo.png')}}" style="width:200px;max-width:200px;" alt="REFORMEX"> --}}
<h2 style="color: black;text-align:center;display:block;width:100%;
font-size: 40px;
text-transform: uppercase;">REFORM<span style="color:#1fc2b3;">EX</span></h2>
@endif
</a>
</td>
</tr>
