<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $titulo }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 22px;
            margin: 0;
            color: #333;
        }

        .fecha {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            font-size: 11px;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .alerta {
            color: #dc3545;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            font-size: 10px;
            color: #666;
            text-align: center;
            position: fixed;
            bottom: 20px;
            left: 0;
            width: 100%;
        }

        .page-number:after {
            content: counter(page);
        }

        .resumen {
            margin-top: 20px;
            padding: 10px;
            background-color: #f8f9fa;
            border: 1px solid #ddd;
        }

        .resumen-titulo {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .resumen-item {
            margin-bottom: 3px;
        }

        .ubicacion-badge {
            display: inline-block;
            background: #e9ecef;
            padding: 2px 4px;
            border-radius: 3px;
            margin: 1px;
            font-size: 9px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>{{ $titulo }}</h1>
        <div class="fecha">Generado el: {{ $fecha }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Tipo</th>
                <th>Stock Actual</th>
                <th>Stock Mínimo</th>
                <th>Ubicación</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($productos as $producto)
                <tr>
                    <td>{{ $producto->codigo_producto }}</td>
                    <td>{{ $producto->nombre }}</td>
                    <td>{{ $producto->categoria->nombre ?? 'Sin categoría' }}</td>
                    <td>{{ $producto->tipo == 'materia_prima' ? 'Materia Prima' : 'Producto Terminado' }}</td>
                    <td>{{ $producto->stock_total ?? 0 }}</td>
                    <td>{{ $producto->stock_minimo_alerta }}</td>
                    <td>
                        @if ($producto->estanterias->count() > 0)
                            @foreach ($producto->estanterias as $estanteria)
                                <div class="ubicacion-badge">
                                    Zona: {{ $estanteria->zona->nombre ?? 'N/A' }} -
                                    Est: {{ $estanteria->nombre }}
                                    ({{ $estanteria->pivot->cantidad ?? 0 }})
                                </div>
                            @endforeach
                        @else
                            Sin ubicación
                        @endif
                    </td>
                    <td>
                        @if (($producto->stock_total ?? 0) <= $producto->stock_minimo_alerta)
                            <span class="alerta">BAJO STOCK</span>
                        @else
                            OK
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="resumen">
        <div class="resumen-titulo">Resumen del Inventario:</div>
        <div class="resumen-item">Total de productos: {{ $productos->count() }}</div>
        <div class="resumen-item">Productos con bajo stock:
            {{ $productos->filter(function ($producto) {return ($producto->stock_total ?? 0) <= $producto->stock_minimo_alerta;})->count() }}
        </div>
        <div class="resumen-item">Productos sin ubicación asignada:
            {{ $productos->filter(function ($producto) {return $producto->estanterias->count() === 0;})->count() }}
        </div>
    </div>

    <div class="footer">
        <p>Informe de Inventario - Página <span class="page-number"></span></p>
    </div>
</body>

</html>
