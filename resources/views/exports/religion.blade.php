<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th>id</th>
                <th>name</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($religions as $item)
            <tr>
                <td>{{$item->id}}</td>
                <td>{{$item->name}}</td>
            </tr>
            @endforeach

        </tbody>
    </table>
</body>
</html>
