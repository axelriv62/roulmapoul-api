<h1>Roulmapoul - Retrait du {{ $withdrawal->datetime->format('d-m-Y') }}</h1>
<hr>
<ul>
    <li>Kilométrage de la voiture : {{ $withdrawal->mileage }}</li>
    <li>Niveau de carburant de la voiture : {{ $withdrawal->mileage }}</li>
    @if ($withdrawal->interior_condition)
        <li>État intérieur de la voiture : {{ $withdrawal->interior_condition }}</li>
    @endif
    @if ($withdrawal->exterior_condition)
        <li>État extérieur de la voiture : {{ $withdrawal->exterior_condition }}</li>
    @endif
    @if($withdrawal->comment)
        <li>Commentaire : {{ $withdrawal->comment }}</li>
    @endif
</ul>
