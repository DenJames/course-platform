<x-mail::message>
    #  Velkommen til {{ config('app.name') }}

    Hej {{ $user->name }},

    Du har modtaget denne e-mail, fordi du har oprettet en konto på {{ config('app.name') }}. Klik på knappen nedenfor for at oprette din adgangskode og få adgang til din konto.

    <x-mail::button :url="URL::signedRoute('password.create', ['user' => $user])">
        Opret adgangskode
    </x-mail::button>

    Linket er gyldigt i 24 timer. Hvis du ikke har oprettet en konto, behøver du ikke foretage dig yderligere.

    Mvh.<br>
    {{ config('app.name') }}
</x-mail::message>
