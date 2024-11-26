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
                <th>nim</th>
                <th>name</th>
                <th>study_program_id</th>
                <th>academic_period_id</th>
                <th>class_group_id</th>
                <th>gender</th>
                <th>birthplace</th>
                <th>birthdate</th>
                <th>religion_id</th>
                <th>ethnic_id</th>
                <th>no_kk</th>
                <th>nik</th>
                <th>email</th>
                <th>address</th>

            </tr>
        </thead>
        <tbody>
            @foreach ($students  as $item)
            <tr>
                <td>{{$item->nim}}</td>
                <td>{{$item->name}}</td>
                <td>{{$item->study_program_id}}</td>
                <td>{{$item->academic_period_id}}</td>
                <td>{{$item->class_group_id}}</td>
                <td>{{$item->gender}}</td>
                <td>{{$item->birthplace}}</td>
                <td>{{Date('d/m/Y', strtotime($item->birthdate))}}</td>
                <td>{{$item->birthdate}}</td>
                <td>{{$item->religion_id}}</td>
                <td>{{$item->ethnic_id}}</td>
                <td>{{$item->kk}}</td>
                <td>{{$item->email}}</td>
                <td>{{$item->nik}}</td>
                <td>{{$item->address}}</td>
            </tr>
            @endforeach
            {{-- <tr>
                <td>9ce68a84-deaa-4d4a-9c4b-270f73e4329e</td>
                <td>Study Kasus Student</td>
                <td></td>
                <td>Politeknik Banyuwangi</td>
                <td>12/09/2023</td>
                <td>12/09/2023</td>
                <td>0</td>
                <td></td>
                <td>7661/PL36/KP/2021</td>
                <td>16/10/2021</td>
                <td>FALSE</td>
                <td>c7a58db1-22f4-4d0d-bf71-2dd797a884f9</td>
                <td>20211</td>
                <td>2</td>
            </tr> --}}
        </tbody>
    </table>
</body>
</html>
