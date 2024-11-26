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
        <table width="1130" border="0" align="center" cellpadding="0" cellspacing="0">
            @if ($error == true)
            <br>
            <tr>
                <td width="80%">
                    <div align="center" class="style1"><strong  style="text-transform: uppercase;">
                           Data Tidak Di Temukan</strong><br>
                        <span class="style2">coba cek kembali data form yang anda masukan</span><br>
                        <span class="style2">Website : {{ $universitasProfile->website }} E-Mail :
                            {{ $universitasProfile->email }}</span>
                    </div>
                </td>
            </tr>
            <?php die; ?>
         @endif
            <tbody>
                <tr>
                    <td width="15%">
                        <div align="left">
                            <h2 align="center" style="margin-right: -12%;"><img src="{{ asset('storage/images/app/poliwangi.png') }}" width="133" height="124"></h2>
                        </div>
                    </td>
                    <td width="85%">
                        <div align="center"  style="margin-right: 12%;" class="style1"><strong  style="text-transform: uppercase;">KEMENTERIAN RISET, TEKNOLOGI DAN PENDIDIKAN
                                TINGGI<br>
                                 {{ $universitasProfile->name }}</strong><br>
                            <span class="style2">{{ $universitasProfile->street}}</span><br>
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
                                            <div align="center" class="style1" style=" margin-bottom: 1%;"><strong>LAPORAN PERWALIAN MAHASISWA</strong></div>
                                            {{-- <br> --}}
                                            @if ($judicialPeriod == null )
                                            <div align="center" class="style1" style="font-size: 17px; text-transform: uppercase;"><strong>SEMUA PERIODE YUDISIUM</strong></div>
                                            @else
                                            <div align="center" class="style1" style="font-size: 17px; text-transform: uppercase;"><strong>PERIODE YUDISIUM  {{ $judicialPeriod->name }}</strong></div>
                                            @endif
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

                                <br>
                                <tr>
                                    <tr>
                                        <td colspan="2">
                                            <table width="100%">
                                                <tr>
                                                    <td width="15%"> PROGRAM STUDI</td>
                                                    @if ( $StudyProgram  == null )
                                                    <td>: Semua Program Studi</td>
                                                    @else

                                                    <td >: {{ $StudyProgram->educationLevel->code }} - {{ $StudyProgram->name }}</td>
                                                    @endif

                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <td colspan="2">

                                        <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <table width="100%" border="1" cellspacing="0">
                                                            <tbody>
                                                                <tr bgcolor="#A8EEFF" id="render">
                                                                    <td width="5%">
                                                                        <div align="center"><strong>No</strong></div>
                                                                    </td>

                                                                    <td width="9%">
                                                                        <div align="center"><strong>Tanggal Yudisium</strong>
                                                                        </div>
                                                                    </td>
                                                                    <td width="7%">
                                                                        <div align="center"><strong>NIM</strong>
                                                                        </div>
                                                                    </td>
                                                                    <td width="8%">
                                                                        <div align="center"><strong>Nama</strong>
                                                                        </div>
                                                                    </td>

                                                                    <td width="7%">
                                                                        <div align="center"><strong>TTL</strong></div>
                                                                    </td>
                                                                    <td width="7%">
                                                                        <div align="center"><strong>No Hp</strong></div>
                                                                    </td>
                                                                    <td width="7%">
                                                                        <div align="center"><strong>Alamat</strong></div>
                                                                    </td>
                                                                    <td width="7%">
                                                                        <div align="center"><strong>Judul TA</strong></div>
                                                                    </td>
                                                                    <td width="7%">
                                                                        <div align="center"><strong>IPK</strong></div>
                                                                    </td>
                                                                    <td width="15%">
                                                                        <div align="center"><strong>Foto</strong></div>
                                                                    </td>
                                                                </tr>
                                                                @php
                                                                    $number = 1;
                                                                @endphp
                                                                @foreach ($data as $key => $report)
                                                                <tr>
                                                                    <td style="text-align: center;">{{ $number }}</td>
                                                                    <td style="text-align: center;">{{ $report->decree_date }}</td>
                                                                    <td style="text-align: center;">{{ $report->student->nim }}</td>
                                                                    <td style="text-align: center;">{{ $report->student->name }}</td>
                                                                    <td style="text-align: center;">{{ $report->student->birthplace }},{{ $report->student->birthdate }}</td>
                                                                    @if ($report->student->phone_number == null)
                                                                    <td style="text-align: center;">-</td>
                                                                    @else
                                                                    <td style="text-align: center;">{{ $report->student->phone_number }}</td>
                                                                    @endif
                                                                    @if ( $report->student->address == null)
                                                                    <td style="text-align: center;">-</td>
                                                                    @else
                                                                    <td style="text-align: center;">{{ $report->student->address }}</td>
                                                                    @endif
                                                                   @foreach ($report->student->thesis as $item)

                                                                   <td style="text-ali gn: center;">{{ $item->title }}</td>
                                                                   @endforeach
                                                                    <td style="text-align: center;">{{ $report->grade }}</td>
                                                                    <td style="text-align: center">
                                                                        @if ($report->student->picture == null)
                                                                        <img src="{{ asset('storage/images/students/mahasiswa-645c8b5e6eb46.jpeg') }}" alt="" srcset="" width="95%" style="padding-top: 3%;">
                                                                        @else
                                                                        <img src="{{ asset('storage/images/students/'.$report->student->picture) }}" alt="" srcset="" width="100%">
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                                @php
                                                                    $number++;
                                                                @endphp
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
                var x = document.getElementById('cetak');
                x.style.visibility = "hidden";

                var style = document.createElement('style');
                style.innerHTML = '@page { size: landscape; }';
                document.head.appendChild(style);

                window.print();
                alert("Jangan ditekan tombol OK sebelum dokumen selesai dicetak");

                x.style.visibility = "visible";
            }

            var x = document.getElementById('cetak');
            x.style.visibility = "hidden";

            var style = document.createElement('style');
            style.innerHTML = '@page { size: landscape; }';
            document.head.appendChild(style);



            setTimeout(() => {
                alert("Jangan ditekan tombol OK sebelum dokumen selesai dicetak");
                x.style.visibility = "visible";
            }, 1000);

        </script>
</body>

</html>
