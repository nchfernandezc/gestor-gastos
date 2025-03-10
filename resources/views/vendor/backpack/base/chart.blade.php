<div class="card">
    <div class="card-header">{{ $chart->options['chart_title'] ?? 'Gráfico' }}</div>
    <div class="card-body">
        {!! $chart->container() !!}
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
{!! $chart->script() !!}
