<h1>Roulmapoul - Retour du {{ $handover->datetime->format('d-m-Y') }}</h1>
<hr>
<ul>
    <li>Kilométrage de la voiture : {{ $handover->mileage }}</li>
    <li>Niveau de carburant de la voiture : {{ $handover->fuel_level }}</li>
    @if ($handover->interior_condition)
        <li>État intérieur de la voiture : {{ $handover->interior_condition }}</li>
    @endif
    @if ($handover->exterior_condition)
        <li>État extérieur de la voiture : {{ $handover->exterior_condition }}</li>
    @endif
    @if($handover->comment)
        <li>Commentaire : {{ $handover->comment }}</li>
    @endif
</ul>
@if($handover->rental->amendments->isNotEmpty())
    <hr>
    <h2>Avenants</h2>
    <ul>
        @foreach($handover->rental->amendments as $amendment)
            <li>{{ $amendment->name }} : {{ $amendment->price }} €</li>
        @endforeach
        <li>Prix total : {{ $handover->rental->amendments->sum('price') }} €</li>
    </ul>
@endif
