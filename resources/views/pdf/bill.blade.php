<h1>Facture de la location du {{ $rental->start->format('d-m-Y') }} au {{ $rental->end->format('d-m-Y') }}</h1>
<hr>
<ul>
    <li>{{$rental->car->price_day }} € * {{ $rental->nb_days }} jour(s) : {{ ($rental->car->price_day * $rental->nb_days) }} €</li>
    @if($rental->warranty)
        <li>{{ $rental->warranty->name }} : {{ $rental->warranty->price }} €</li>
    @endif
    @foreach($rental->options as $option)
        <li>{{ $option->name }} : {{ $option->price }} €</li>
    @endforeach
    @foreach($rental->amendments as $amendment)
        <li>{{ $amendment->name }} : {{ $amendment->price }} €</li>
    @endforeach
    <li>Prix total : {{ $rental->total_price }} €</li>
</ul>
