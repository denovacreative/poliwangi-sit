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
                <th>academic_period_id</th>
                <th>student_id</th>
                <th>filing_date</th>
                <th>start_date</th>
                <th>finish_date</th>
                <th>topic</th>
                <th>topic_en</th>
                <th>title</th>
                <th>title_en</th>
                <th>abstract</th>
                <th>decree_number</th>
                <th>decree_date</th>
                <th>thesis_type</th>
                <th>is_active</th>
                <th>is_acc</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($thesis as $item)
            <tr>
                <td>{{$item->academic_period_id}}</td>
                <td>{{$item->student_id}}</td>
                <td>{{Date('d/m/Y', strtotime($item->filing_date))}}</td>
                <td>{{Date('d/m/Y', strtotime($item->start_date))}}</td>
                <td>{{Date('d/m/Y', strtotime($item->finish_date))}}</td>
                <td>{{$item->topic}}</td>
                <td>{{$item->topic_en}}</td>
                <td>{{$item->title}}</td>
                <td>{{$item->title_en}}</td>
                <td>{{$item->abstract}}</td>
                <td>{{$item->decree_number}}</td>
                <td>{{Date('d/m/Y', strtotime($item->decree_date))}}</td>
                <td>{{$item->thesis_type}}</td>
                <td>{{$item->is_active}}</td>
                <td>{{$item->is_acc}}</td>
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
