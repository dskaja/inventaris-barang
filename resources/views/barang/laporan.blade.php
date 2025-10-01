<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    @include('barang.partials.style-laporan')

</head>
<body>
    <div class="header">
        <h1>{{ $title }} </h1>
        <p>Tanggal Cetak: {{ $date }}</p>
</div>

@include('barang.partials.list-laporan')

</body>
</html>