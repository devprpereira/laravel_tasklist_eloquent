<head>

    {{-- Define dinamically the title depending on page --}}
    <title> @yield('title') </title>
    <meta lang="pt-BR" />
    <meta author="Paulo Roberto" />
</head>

<body>
    <header>
        <ul>
            <li><a href="{{ route('tasks.list') }}">List tasks</a> </li>
            <li><a href="{{ route('tasks.add')  }}">Add task</a> </li>
        </ul>
        {{-- Rotas acima usando o ->name('nome') depois da definição da rota no arquivo de rotas --}}

    </header>
    <h2>You are in @yield('title')</h2>
    <section>
        @yield('content')
    </section>

    <footer>

    </footer>

</body>
