<!-- saved from url=(0100)https://sit.poliwangi.ac.id/admin/kuliah/tampilkan-khs-per-mahasiswa/27838/direktur/tampilkan/2020/2 -->
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <style type="text/css">
        .style1 {
            font-size: large
        }

        .style2 {
            font-size: medium
        }
    </style>
    <title>{{ $title }}</title>
    <link rel="shortcut icon" href="{{ asset('storage/images/app/poliwangi.png') }}" type="image/x-icon">
    <style type="text/css">
        @font-face {
            font-weight: 400;
            font-style: normal;
            font-family: circular;

            src: url('chrome-extension://liecbddmkiiihnedobmlmillhodjkdmb/fonts/CircularXXWeb-Book.woff2') format('woff2');
        }

        @font-face {
            font-weight: 700;
            font-style: normal;
            font-family: circular;

            src: url('chrome-extension://liecbddmkiiihnedobmlmillhodjkdmb/fonts/CircularXXWeb-Bold.woff2') format('woff2');
        }
    </style>
</head>

<body>
    <form>
        <table width="1300" border="0" align="center" cellpadding="0" cellspacing="0">
            <tbody>
                <tr>
                    <td width="15%">
                        <div align="left">
                            <h2 align="center"><img src="{{ asset('storage/images/app/poliwangi.png') }}" width="133" height="124"></h2>
                        </div>
                    </td>
                    <td width="85%">
                        <div align="center" class="style1" style="margin-left: -14%"><strong  style="text-transform: uppercase;">KEMENTERIAN RISET, TEKNOLOGI DAN PENDIDIKAN
                                TINGGI<br>
                                {{ $universitasProfile->name }} </strong><br>
                            <span class="style2">{{ $universitasProfile->street }}</span><br>
                            <span class="style2">Website : {{ $universitasProfile->website }} E-Mail :
                                {{ $universitasProfile->email }}</span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <hr noshade="">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tbody>
                                <tr>
                                    <td colspan="2">
                                        <div align="center" class="style1"><strong>DARTAR TUGAS AKHIR </strong></div>
                                    </td>
                                </tr>
                                <tr>
                                </tr>
                                <tr>
                                    <td><button type="button" name="cetak" id="cetak" class="print" onclick="Cetakan()"
                                        style="visibility: visible;">Cetak</button></td>
                                        <td>&nbsp;</td>
                                    </tr>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <table width="100%">
                                            <tr>
                                                <td width="17%">PROGRAM STUDI</td>
                                                {{-- <td  width="20%"> --}}
                                                    <td style="text-transform: capitalize">: {{ $header['studyProgram'] }}</td>
                                                </td>
                                                <td> </td>
                                            </tr>
                                            <tr>
                                                <td>ANGKATAN</td>
                                                <td style="text-transform: capitalize">: {{ $header['academicYear']  }}</td>
                                                <td > </td>
                                                <td > </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">

                                        <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <table width="100%" border="1" cellspacing="0">
                                                            <tbody>
                                                                <tr bgcolor="#A8EEFF" id="render">
                                                                    <td width="1%">
                                                                        <div align="center"><strong>No</strong></div>
                                                                    </td>
                                                                    <td width="5%">
                                                                        <div align="center"><strong>NIM</strong></div>
                                                                    </td>
                                                                    <td width="9%">
                                                                        <div align="center"><strong>NAMA</strong></div>
                                                                    </td>
                                                                    <td width="5%">
                                                                        <div align="center"><strong>Topik</strong></div>
                                                                    </td>
                                                                    <td width="14%">
                                                                        <div align="center"><strong>Judul TA</strong></div>
                                                                    </td>
                                                                    <td width="5%">
                                                                        <div align="center"><strong>Tanggal Mulai</strong></div>
                                                                    </td>
                                                                    <td width="5%">
                                                                        <div align="center"><strong>Tanggal Selesai</strong></div>
                                                                    </td>
                                                                    <td width="9%">
                                                                        <div align="center"><strong>Dosen Pembimbing</strong></div>
                                                                    </td>

                                                                </tr>
                                                                <?php
                                                                $i=1;
                                                                ?>
                                                                @foreach ($data as $thesis)
                                                                <tr>
                                                                    <td bgcolor="#E0E0E0">
                                                                        <div align="center" style="font-size: 10px;">{{ $i }}</div>
                                                                    </td>
                                                                    <td bgcolor="#E0E0E0">
                                                                        <div align="center" style="font-size: 10px;">{{ $thesis->student->nim }}</div>
                                                                    </td>
                                                                    <td bgcolor="#E0E0E0">
                                                                        <div align="center"  style="font-size: 10px;">{{ $thesis->student->name }}</div>
                                                                    </td>
                                                                    <td bgcolor="#E0E0E0">
                                                                        <div align="center"  style="font-size: 10px;">{{ $thesis->topic }}</div>
                                                                    </td>
                                                                    <td bgcolor="#E0E0E0">
                                                                        <div align="center"  style="font-size: 10px;">{{ $thesis->title }}</div>
                                                                    </td>
                                                                    <td bgcolor="#E0E0E0">
                                                                        <div align="center"  style="font-size: 10px;">{{  Carbon\Carbon::parse($thesis->start_date)->format('d M Y')  }}</div>
                                                                    </td>
                                                                    <td bgcolor="#E0E0E0">
                                                                        <div align="center"  style="font-size: 10px;">{{ Carbon\Carbon::parse($thesis->finish_date)->format('d M Y')}}</div>
                                                                    </td>
                                                                    <?php $thesis_guidances = $thesis->thesisGuidance; ?>

                                                                    <td bgcolor="#E0E0E0">
                                                                        <div   style="font-size: 10px;">
                                                                            <ul style="padding-top: 3%;">
                                                                            @foreach ($thesis_guidances as $item)
                                                                            <li>{{  str_replace(',', ', ', $item->employee->front_title) . ' '}}{{ $item->employee->name }}{{  str_replace(',', ', ', $item->employee->back_title)  }}</li>
                                                                            @endforeach
                                                                        </ul>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <?php $i++;  ?>
                                                                @endforeach
                                                            </tbody>
                                                        </table>

                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr bgcolor="#FFFFFF">
                    <td colspan="3">&nbsp;</td>
                </tr>
            </tbody>
        </table>
    </form>
    <script src="./KHS_files/jquery-3.6.0.min.js.download"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script>
        function Cetakan() {
            var x = document.getElementsByName("cetak");
            for (i = 0; i < x.length; i++) {
                x[i].style.visibility = "hidden";
            }
            alert("Jangan di tekan tombol OK sebelum dokumen selesai tercetak!");
            window.print();
            for (i = 0; i < x.length; i++) {
                x[i].style.visibility = "visible";
            }
        }
    </script>
</body>

</html>
