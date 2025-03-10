@extends(backpack_view('blank'))

@php
    $widgets['before_content'][] = [
        'type' => 'div',
        'class' => 'row',
        'content' => [
            [
                'type' => 'jumbotron',
                'heading' => 'Bienvenido a tu Gestor de Gastos',
                'content' => 'Aquí puedes ver un resumen de tus gastos por categoría y mensuales.',
                'wrapper' => [
                    'class' => 'text-color',
                    'style' => 'color: #7c69ef; background-color: transparent;',
                ],
            ],
        ],
    ];

    $startDate = request('start_date') ?: now()->startOfYear()->toDateString();
    $endDate = request('end_date') ?: now()->toDateString();
    $categoryId = request('category_id');

    $categories = \App\Models\Category::where('user_id', backpack_user()->id)->pluck('name', 'id');

    $expensesQuery = \App\Models\Expense::where('user_id', backpack_user()->id)
                                        ->whereBetween('date', [$startDate, $endDate]);

    if ($categoryId) {
        $expensesQuery->where('category_id', $categoryId);
    }

    $expenses = $expensesQuery->with('category')
                              ->get()
                              ->groupBy('category_id')
                              ->map->sum('amount');

    $expensesWithZero = [];
    foreach ($categories as $id => $name) {
        $expensesWithZero[$name] = $expenses->has($id) ? $expenses[$id] : 0;
    }

    $chart1 = new \ConsoleTVs\Charts\Classes\Chartjs\Chart;
    $chart1->labels(array_keys($expensesWithZero)); 
    $chart1->dataset('Gastos por Categoría', 'bar', array_values($expensesWithZero))
           ->color(['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0']);

    $monthlyExpensesQuery = \App\Models\Expense::where('user_id', backpack_user()->id)
                                              ->whereBetween('date', [$startDate, $endDate]);

    if ($categoryId) {
        $monthlyExpensesQuery->where('category_id', $categoryId);
    }

    $monthlyExpenses = $monthlyExpensesQuery->selectRaw('MONTH(date) as month, SUM(amount) as total')
                                           ->groupBy('month')
                                           ->pluck('total');

    $chart2 = new \ConsoleTVs\Charts\Classes\Chartjs\Chart;
    $chart2->labels(['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic']);
    $chart2->dataset('Gastos Mensuales', 'line', $monthlyExpenses)
           ->color('#36A2EB');

    $totalGastosMes = \App\Models\Expense::where('user_id', backpack_user()->id)
                                        ->whereBetween('date', [$startDate, $endDate])
                                        ->when($categoryId, function ($query) use ($categoryId) {
                                            return $query->where('category_id', $categoryId);
                                        })
                                        ->sum('amount');

    $totalGastosMesAnterior = \App\Models\Expense::where('user_id', backpack_user()->id)
                                                ->whereBetween('date', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])
                                                ->when($categoryId, function ($query) use ($categoryId) {
                                                    return $query->where('category_id', $categoryId);
                                                })
                                                ->sum('amount');

    $diferenciaMensual = $totalGastosMes - $totalGastosMesAnterior;
    $tendencia = $diferenciaMensual >= 0 ? 'alza' : 'baja';
@endphp

@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ backpack_url('dashboard') }}" method="GET" class="form-inline">
                        <div class="form-group mr-3">
                            <label for="start_date" class="mr-2">Fecha de inicio:</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $startDate }}">
                        </div>
                        <div class="form-group mr-3">
                            <label for="end_date" class="mr-2">Fecha de fin:</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $endDate }}">
                        </div>
                        <div class="form-group mr-3">
                            <label for="category_id" class="mr-2">Categoría:</label>
                            <select name="category_id" id="category_id" class="form-control">
                                <option value="">Todas</option>
                                @foreach ($categories as $id => $name)
                                    <option value="{{ $id }}" {{ $categoryId == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary mr-2">Filtrar</button>
                        <a href="{{ backpack_url('dashboard') }}" class="btn btn-secondary">Limpiar Filtros</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Total de gastos</h5>
                    <p class="card-text h4">{{ number_format($totalGastosMes, 2) }} €</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5 class="card-title">Total de gastos el mes anterior</h5>
                    <p class="card-text h4">{{ number_format($totalGastosMesAnterior, 2) }} €</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-{{ $tendencia === 'alza' ? 'danger' : 'success' }}">
                <div class="card-body">
                    <h5 class="card-title">Comparativa mensual</h5>
                    <p class="card-text h4">
                        {{ number_format(abs($diferenciaMensual), 2) }} €
                        <small>({{ $tendencia }})</small>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Gastos por Categoría</div>
                <div class="card-body">
                    {!! $chart1->container() !!}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Gastos Mensuales</div>
                <div class="card-body">
                    {!! $chart2->container() !!}
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    {!! $chart1->script() !!}
    {!! $chart2->script() !!}
@endsection