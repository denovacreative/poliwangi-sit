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
                <th>academic_year_id</th>
                <th>name</th>
                <th>semester</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($academicPeriod as $item)  
            <tr>
                <td>{{$item->id}}</td>
                <td>{{$item->academic_year_id}}</td>
                <td>{{$item->name}}</td>
                <td>{{$item->semester}}</td>
            </tr>
            @endforeach
            {{-- <tr>
                <td>20211</td>
                <td>2021</td>
                <td>2021/2022 Ganjil</td>
                <td>1</td>
            </tr> --}}
        </tbody>
    </table>
</body>
</html>