<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <table border="1">
        <thead>
            <tr>
                <th>id</th>
                <th>code</th>
                <th>name</th>
            </tr>
        </thead>
        <tbody>
            {{-- <tr>
                <td>c7a58db1-22f4-4d0d-bf71-2dd797a884f9</td>
                <td>41333</td>
                <td>Teknologi Pengolahan Hasil Ternak</td>
            </tr> --}}
            @foreach($studyProgram as $sp)
            <tr>
                <td>{{$sp->id}}</td>
                <td>{{$sp->code}}</td>
                <td>{{$sp->name}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>