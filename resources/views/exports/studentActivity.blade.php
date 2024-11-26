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
                <th>name</th>
                <th>group</th>
                <th>location</th>
                <th>start_date</th>
                <th>end_date</th>
                <th>type</th>
                <th>description</th>
                <th>decree_number</th>
                <th>decree_date</th>
                <th>is_mbkm</th>
                <th>study_program_id</th>
                <th>academic_period_id</th>
                <th>student_activity_category_id</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($activities as $item)  
            <tr>
                <td>{{$item->id}}</td>
                <td>{{$item->name}}</td>
                <td>{{$item->group}}</td>
                <td>{{$item->location}}</td>
                <td>{{Date('d/m/Y', strtotime($item->start_date))}}</td>
                <td>{{Date('d/m/Y', strtotime($item->end_date))}}</td>
                <td>{{$item->type}}</td>
                <td>{{$item->description}}</td>
                <td>{{$item->decree_number}}</td>
                <td>{{Date('d/m/Y', strtotime($item->decree_date))}}</td>
                <td>{{$item->is_mbkm}}</td>
                <td>{{$item->study_program_id}}</td>
                <td>{{$item->academic_period_id}}</td>
                <td>{{$item->student_activity_category_id}}</td>
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