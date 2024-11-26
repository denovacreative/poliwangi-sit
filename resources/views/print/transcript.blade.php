<html>

<head>
    <style type="text/css">
        .style1 {
            font-size: large
        }

        .style2 {
            font-size: medium
        }

    </style>
    <title>Cetak Transkrip Nilai Mahasiswa</title>
</head>

<body>
    <form>
        <table width="850" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
                <td width="15%">
                    <div align="left">
                        <h2 align="center"><img src="{{ asset('storage/images/app/poliwangi.png') }}" width="133" height="124"></h2>
                    </div>
                </td>
                <td width="85%">
                    <div align="center" class="style1"><strong  style="text-transform: uppercase;">KEMENTERIAN RISET, TEKNOLOGI DAN PENDIDIKAN
                            TINGGI<br>
                            {{ $nama_univ }} </strong><br>
                        <span class="style2">{{ $alamat_univ }}</span><br>
                        <span class="style2">Website : {{ $website_univ }} E-Mail :
                            {{ $email_univ }}</span>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="border: 1px solid #000;"></td>
            </tr>
            <tr>
                <td colspan="2">

                </td>
            </tr>
        </table>
        <br>
        <table width="850" border="1pt" align="center" cellpadding="5" cellspacing="0">
            <tr>
                <th colspan="8">
                    @if($program == 'D3')
                        Transkrip Sementara Akademik Program Diploma Tiga (D3) <br>
                        <i>(Temporary Academic Transcript of Three Years Diploma Program )</i> <br>
                    @endif
                    @if($program == 'D4')
                        Transkrip Sementara Akademik Program Diploma Empat (D4) <br>
                        <i>(Temporary Academic Transcript of Four Years Diploma Program )</i> <br>
                    @endif
                    @if($program == 'D2')
                        Transkrip Sementara Akademik Program Diploma Dua (D2) <br>
                        <i>(Temporary Academic Transcript of Two Years Diploma Program )</i> <br>
                    @endif
                    @if($program == 'D1')
                        Transkrip Sementara Akademik Program Diploma Satu (D1) <br>
                        <i>(Temporary Academic Transcript of One Years Diploma Program )</i> <br>
                    @endif
                    {{-- <small>No PIN : ........................../ No: ........./............./........./....../.......</small> --}}
                </th>
            </tr>
            <tr>
                <td colspan="8">
                    <table border="0" width="100%" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="50%">
                                <table border="0" width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td>
                                            Nama <br>
                                            <i>(Name)</i>
                                        </td>
                                        <td>: {{ $nama }}</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            NIM <br>
                                            <i>(Student Number)</i>
                                        </td>
                                        <td>: {{ $nim }}</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Tempat, Tanggal Lahir <br>
                                            <i>(Place/Date of Birth)</i>
                                        </td>

                                        <td>: {{ ucwords($tmplahir) }}, {{ date('d M Y', strtotime($tgllahir)) }}</td>
                                    </tr>
                                </table>
                            </td>
                            <td width="50%">
                                <table border="0" width="90%" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td>
                                            Program Studi <br>
                                            <i>(Study Program)</i>
                                        </td>
                                        <td>: {{ $program_studi }}</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <br>
                                            <i></i>
                                        </td>
                                        <td> </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <br>
                                            <i></i>
                                        </td>
                                        <td> </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <br>
                                            <i></i>
                                        </td>
                                        <td> </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <br>
                                            <i></i>
                                        </td>
                                        <td> </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td align="center">
                    KODE <br>
                    (Code)
                </td>
                <td align="center">
                    MATA KULIAH <br>
                    (Course)
                </td>
                <td align="center">
                    SKS <br>
                    (Credit)
                </td>
                <td align="center">
                    NILAI <br>
                    (Grade)
                </td>
                <td align="center">
                    KODE <br>
                    (Code)
                </td>
                <td align="center">
                    MATA KULIAH <br>
                    (Course)
                </td>
                <td align="center">
                    SKS <br>
                    (Credit)
                </td>
                <td align="center">
                    NILAI <br>
                    (Grade)
                </td>
            </tr>
            @php $data = [];$jumlah = 1;$mutu = 0;$kredit = 0; @endphp
            @foreach ($nilai as $item)
                @php
                    $row = [];
                    $row[] = $item['kode'];
                    $row[] = $item['matakuliah'];
                    $row[] = $item['matakuliah_inggris'] == null ? '' :( $item['matakuliah_inggris']);
                    $row[] = $item['sks'];
                    $row[] = $item['nhu'];
                    $data[] = $row;
                @endphp
                @if($jumlah % 2 == 0)

                    <tr>
                        <td align="center" style="font-size: 10px">{{ $data[0][0] }}</td>
                        <td align="left" style="font-size: 10px">
                            {{ $data[0][1] }} <br>
                            {{ $data[0][2] }}
                        </td>
                        <td align="center" style="font-size: 10px">{{ $data[0][3] }}</td>
                        <td align="center" style="font-size: 10px">{{ $data[0][4] }}</td>
                        <td align="center" style="font-size: 10px">{{ isset($data[1]) ? $data[1][0] : '' }}</td>
                        <td align="left" style="font-size: 10px">
                            {{ isset($data[1]) ? $data[1][1] : '' }} <br>
                            {{ isset($data[1]) ? $data[1][2] : '' }}
                        </td>
                        <td align="center" style="font-size: 10px">{{ isset($data[1]) ? $data[1][3] : '' }}</td>
                        <td align="center" style="font-size: 10px">{{ isset($data[1]) ? $data[1][4] : '' }}</td>
                    </tr>
                    @php $data = []; @endphp
                @endif
                @php
                    $jumlah++;
                    $mutu = (float)$mutu + (float)((float)$item['am'] * (float)$item['sks']);
                    $kredit = (float)$kredit + (float)$item['sks'];
                @endphp
            @endforeach
            @if(isset($data[0]))
                <tr>
                    <td align="center" style="font-size: 10px">{{ $data[0][0] }}</td>
                    <td align="left" style="font-size: 10px">
                        {{ $data[0][1] }} <br>
                        {{ $data[0][2] }}
                    </td>
                    <td align="center" style="font-size: 10px">{{ $data[0][3] }}</td>
                    <td align="center" style="font-size: 10px">{{ $data[0][4] }}</td>
                    <td align="left" style="font-size: 10px">{{ isset($data[1]) ? $data[1][0] : '' }}</td>
                    <td align="left" style="font-size: 10px">
                        {{ isset($data[1]) ? $data[1][1] : '' }} <br>
                        {{ isset($data[1]) ? $data[1][2] : '' }}
                    </td>
                    <td align="left" style="font-size: 10px">{{ isset($data[1]) ? $data[1][3] : '' }}</td>
                    <td align="left" style="font-size: 10px">{{ isset($data[1]) ? $data[1][4] : '' }}</td>
                </tr>
            @endif
            <tr>
                <td colspan="8">
                    <table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="30%">

                            </td>
                            <td>

                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="8">
                    <table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="45%">Jumlah SKS <i>(Total Credits)</i></td>
                            <td>: {{ $kredit }}</td>
                        </tr>
                        <tr>
                            <td>Indeks Prestasi Kumulatif <i>(Grade Point Average)</i></td>
                            <td>: {{ ($kredit > 0) ? bcdiv((float)$mutu / (int)$kredit, 1, 2) : bcdiv((float) 0, 1, 2) }}</td>
                        </tr>

                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="8">
                    <table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td>
                                Catatan <i>(Notes)</i>: <br>
                                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td>A</td>
                                        <td>= 4</td>
                                        <td>Dengan Pujian <i>(Cum Laude)</i></td>
                                        <td>= 3.51 - 4</td>
                                    </tr>
                                    <tr>
                                        <td>AB</td>
                                        <td>= 3.5</td>
                                        <td>Sangat Memuaskan <i>(Very Satisfactory)</i></td>
                                        <td>= 3.01 - 3.50</td>
                                    </tr>
                                    <tr>
                                        <td>B</td>
                                        <td>= 3</td>
                                        <td>Memuaskan <i>(Satisfactory)</i></td>
                                        <td>= 2.76 - 3.01</td>
                                    </tr>
                                    <tr>
                                        <td>BC</td>
                                        <td>= 2.5</td>
                                        <td>Cukup <i>(Sufficient)</i></td>
                                        <td>= 2 - 2.75</td>
                                    </tr>
                                    <tr>
                                        <td>C</td>
                                        <td>= 2</td>
                                        <td>Gagal <i>(Failed)</i></td>
                                        <td>= 0 - 1.99</td>
                                    </tr>
                                    <tr>
                                        <td>D</td>
                                        <td>= 1</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </table>
                            </td>
                            <td width="40%">
                                Wakil Direktur Bidang Akademik<br>
                                    <i> (Deputy Director For Academic Affairs)</i> <br>

                                <br>
                                <br>
                                <br>
                                <br>

                                  {{ $wakil_direktur_bidang_akademik_nama }}
                                <hr style="padding: 0;margin: 0;border: 1px solid #000">
                                NIP.  {{ $wakil_direktur_bidang_akademik_nip }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            @php
                function checkPredicate($ipk)
                {
                    if($ipk > 3.5){
                        $predikat = "Dengan Pujian <i>(Cum Laude)</i>";
                    } elseif($ipk > 3) {
                        $predikat = "Sangat Memuaskan <i>(Very Satisfactory)</i>";
                    } elseif($ipk > 2.75) {
                        $predikat = "Memuaskan <i>(Satisfactory)</i>";
                    } elseif($ipk > 1.99) {
                        $predikat = "Cukup <i>(Sufficient)</i>";
                    } else {
                        $predikat = "Gagal <i>(Failed)</i>";
                    }

                    return $predikat;
                }
            @endphp
        </table>
        <table width="910" border="0" align="center" cellpadding="5" cellspacing="0">
            <tr>
                <td>
                    <button type="button" name="cetak" id="cetak" class="print" onclick="printTranskrip()" style="visibility: visible;">Cetak</button>
                </td>
            </tr>
        </table>
    </form>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script>
        function printTranskrip(){
            var x = document.getElementById('cetak')
            x.style.visibility = "hidden"

            window.print()
            alert("Jangan di tekan tombol OK sebelum dokumen selesai dicetak")

            x.style.visibility = "visible"
        }

        var x = document.getElementById('cetak')
        x.style.visibility = "hidden"

        window.print()

        setTimeout(() => {
            alert("Jangan di tekan tombol OK sebelum dokumen selesai dicetak")
            x.style.visibility = "visible"
        }, 1000);
    </script>
</body>

</html>
