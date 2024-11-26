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
    @if (count($data) < 1)
        <table width="1000" border="0" align="center" cellpadding="0" cellspacing="0">
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
        </table>
        <table width="100%">
            <tr>
                <th>
                    <center>Tidak terdapat data!</center>
                </th>
            </tr>
        </table>
    @endif
    @foreach ($data as $item)
    @php
        $number_plus = sprintf("%03s", $loop->iteration);
    @endphp
    <form>
        <table width="1000" border="0" align="center" cellpadding="0" cellspacing="0">
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
                        <center>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tbody>
                                <tr>
                                    <td colspan="2">
                                        <div class="style1"><center><strong>KARTU HASIL STUDY</strong></center></div>
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
                                                <td width="10%">NOMOR</td>
                                                <td width="30%">: {{ str_replace("****",$number_plus++,$khs_number)}}</td>
                                                <td width="20%" >
                                                    PROGRAM STUDI
                                                </td>
                                                <td width="40%">: {{ $item->studyProgram->name }}</td>
                                            </tr>
                                            <tr>
                                                <td>NIM</td>
                                                <td>: {{ $item->nim }}</td>
                                                <td > THN / SEMESTER</td>
                                                <td >: {{ $item->academicPeriod->name }}</td>
                                            </tr>
                                            <tr>
                                                <td >
                                                   NAMA
                                                </td>
                                                <td>: {{ $item->name }}</td>
                                                <td>KELAS</td>
                                                <td>: {{ $item->classGroup->name }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        @php
                                            $datas = \Illuminate\Support\Facades\DB::table('scores')
                                                ->join('college_classes', 'college_classes.id', '=', 'scores.college_class_id')
                                                ->join('courses', 'courses.id', '=', 'college_classes.course_id')->where('scores.student_id', '=', $item->id)->where('college_classes.academic_period_id', $academicPeriod)->where('scores.is_publish', '=', true)
                                                ->select(['courses.name', 'courses.id as id_course  ', 'courses.code', 'scores.index_score', 'scores.final_grade', 'college_classes.credit_total', 'college_classes.academic_period_id'])
                                                ->get();

                                            $dataact = \Illuminate\Support\Facades\DB::table('activity_score_conversions')
                                                ->join('student_activities', 'student_activities.id', '=', 'activity_score_conversions.student_activity_id')
                                                ->join('courses', 'courses.id', '=', 'activity_score_conversions.course_id')
                                                ->join('student_activity_members', 'student_activity_members.id', '=', 'activity_score_conversions.student_activity_member_id')
                                                ->where('student_activities.academic_period_id', $academicPeriod)
                                                ->where('student_activity_members.student_id', $item->id)
                                                ->where('activity_score_conversions.is_transcript', true)
                                                ->select(['activity_score_conversions.credit', 'activity_score_conversions.score', 'activity_score_conversions.grade', 'activity_score_conversions.index_score', 'courses.name', 'courses.id as id_course  ', 'courses.code'])
                                                ->get();
                                        @endphp

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
                                                                    <td width="14%">
                                                                        <div align="center"><strong>Kode</strong></div>
                                                                    </td>
                                                                    <td width="40%">
                                                                        <div align="center"><strong>Mata Kuliah</strong>
                                                                        </div>
                                                                    </td>
                                                                    <td width="9%">
                                                                        <div align="center"><strong>HM</strong></div>
                                                                    </td>
                                                                    <td width="11%">
                                                                        <div align="center"><strong>AM</strong></div>
                                                                    </td>
                                                                    <td width="10%">
                                                                        <div align="center"><strong>K</strong></div>
                                                                    </td>
                                                                    <td width="11%">
                                                                        <div align="center"><strong>M</strong></div>
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                                $i=1;
                                                                $mutu = 0;
                                                                $total_mutu = 0;
                                                                $total_credit = 0;
                                                                $credit = 0;
                                                                ?>
                                                                @if (count($datas) < 1 && count($dataact < 1))
                                                                <tr>
                                                                    <td bgcolor="#E0E0E0" colspan="7">
                                                                        <div align="center"><center>Tidak terdapat data</center></div>
                                                                    </td>
                                                                </tr>
                                                                @else
                                                                @foreach ($datas as $khs)
                                                                <?php
                                                                $credit += ($khs->credit_total ?? 0);
                                                                $mutu += ($khs->index_score ?? 0) * ($khs->credit_total ?? 0);
                                                                $total_mutu += $mutu;
                                                                $total_credit += $credit;
                                                                ?>
                                                                <tr>
                                                                    <td bgcolor="#E0E0E0">
                                                                        <div align="center">{{ $i }}</div>
                                                                    </td>
                                                                    <td bgcolor="#E0E0E0">
                                                                        <div align="center">{{ $khs->code }}</div>
                                                                    </td>
                                                                    <td bgcolor="#E0E0E0">
                                                                        <div align="left">{{ $khs->name }}</div>
                                                                    </td>
                                                                    <td bgcolor="#E0E0E0">
                                                                        <div align="center">{{ $khs->final_grade }}</div>
                                                                    </td>
                                                                    <td bgcolor="#E0E0E0">
                                                                        <div align="center">{{ $khs->index_score }}</div>
                                                                    </td>
                                                                    <td bgcolor="#E0E0E0">
                                                                        <div align="center">{{ $khs->credit_total }}</div>
                                                                    </td>
                                                                    <td bgcolor="#E0E0E0">
                                                                        <div align="center">{{ $khs->index_score * $khs->credit_total }}</div>
                                                                    </td>
                                                                </tr>
                                                                <?php $i++; ?>
                                                                @endforeach
                                                                <?php
                                                                $mutu2 = 0;
                                                                $total_mutu2 = 0;
                                                                $total_credit2 = 0;
                                                                $credit2 = 0;
                                                                ?>
                                                                @if (count($dataact) > 0)
                                                                @foreach ($dataact as $acti)
                                                                <?php
                                                                $credit2 += ($acti->credit ?? 0);
                                                                $mutu2 += ($acti->index_score ?? 0) * ($khs->credit ?? 0);
                                                                $total_mutu2 += $mutu2;
                                                                $total_credit2 += $credit2;
                                                                ?>
                                                                <tr>
                                                                    <td bgcolor="#E0E0E0">
                                                                        <div align="center">{{ $i }}</div>
                                                                    </td>
                                                                    <td bgcolor="#E0E0E0">
                                                                        <div align="center">{{ $acti->code }}</div>
                                                                    </td>
                                                                    <td bgcolor="#E0E0E0">
                                                                        <div align="left">{{ $acti->name }}</div>
                                                                    </td>
                                                                    <td bgcolor="#E0E0E0">
                                                                        <div align="center">{{ $acti->grade }}</div>
                                                                    </td>
                                                                    <td bgcolor="#E0E0E0">
                                                                        <div align="center">{{ $acti->index_score }}</div>
                                                                    </td>
                                                                    <td bgcolor="#E0E0E0">
                                                                        <div align="center">{{ $acti->credit }}</div>
                                                                    </td>
                                                                    <td bgcolor="#E0E0E0">
                                                                        <div align="center">{{ $acti->index_score * $acti->credit }}</div>
                                                                    </td>
                                                                </tr>
                                                                @php
                                                                    $i++;
                                                                @endphp
                                                                @endforeach
                                                                @endif

                                                                <tr bgcolor="#0099FF">
                                                                    <td colspan="5">
                                                                        <div align="center"><strong>Jumlah</strong>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div align="center" id="jumlahK">{{ ($credit+$credit2) }}</div>
                                                                    </td>
                                                                    <td>
                                                                        <div align="center" id="jumlahM">{{ ($mutu+$mutu2) }}</div>
                                                                    </td>
                                                                </tr>

                                                                <tr bgcolor="#0099FF">
                                                                    <td colspan="5">
                                                                        <div align="center"><strong>Indeks Prestasi
                                                                                Semester
                                                                                (IPS)</strong> </div>
                                                                    </td>
                                                                    <td colspan="2">
                                                                        <div align="center" id="mk">{{ number_format(($mutu+$mutu2) / ($credit+$credit2), 2) }}</div>
                                                                    </td>
                                                                </tr>
                                                                <tr bgcolor="#0099FF">
                                                                    <td colspan="5">
                                                                        <div align="center"><strong>Indeks Prestasi
                                                                                Kumulatif
                                                                                (IPK)</strong> </div>
                                                                    </td>
                                                                    <td colspan="2">
                                                                        <div align="center" id="mk">{{ number_format((($total_mutu+$total_mutu2) ?? 0) / (($total_credit+$total_credit2) ?? 0), 2) }}</div>
                                                                    </td>
                                                                </tr>
                                                                <tr valign="top" bgcolor="#0099FF">
                                                                    <td height="100" colspan="7">
                                                                        <p>Catatan :</p>
                                                                    </td>
                                                                </tr>
                                                                @endif
                                                            </tbody>
                                                        </table>
                                                        <br>
                                                        @php
                                                                $direktur = isset($universitasProfile->employee->name) ? 1 : 0;
                                                                $wadir1 = isset($universitasProfile->viceChancellor->name) ? 1 : 0;
                                                                $wadir2 = isset($universitasProfile->viceChancellor2->name) ? 1 : 0;
                                                                $wadir3 = isset($universitasProfile->viceChancellor3->name) ? 1 : 0;
                                                                $kejur = isset($item->studyProgram->major->employee->name) ? 1 : 0;
                                                                $kepro = isset($item->studyProgram->employee->name) ? 1 : 0;
                                                        @endphp
                                                        <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                                            <tbody>
                                                                <tr>
                                                                    <td height="19" colspan="2">Keterangan : </td>
                                                                    <td width="456">
                                                                        <center>
                                                                            <p>Banyuwangi, {{ date('Y-m-d'); }}</p>
                                                                        </center>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td width="32">HM</td>
                                                                    <td width="430" height="19"> = Huruf Mutu </td>
                                                                    <td style="text-transform: uppercase;">
                                                                        <center>
                                                                        @if ($ttd == 'Direktur' AND $direktur == 1)
                                                                            DIREKTUR
                                                                        @endif
                                                                        @if ($ttd == 'Wadir1' AND $wadir1 == 1)
                                                                            WAKIL DIREKTUR I <br>
                                                                            BIDANG AKADEMIK
                                                                        @endif
                                                                        @if ($ttd == 'Wadir2' AND $wadir2 == 1)
                                                                            WAKIL DIREKTUR II <br>
                                                                            BIDANG UMUM DAN KEUANGAN
                                                                        @endif
                                                                        @if ($ttd == 'Wadir3' AND $wadir3 == 1)
                                                                            WAKIL DIREKTUR III <br>
                                                                            BIDANG KEMAHASISWAAN
                                                                        @endif
                                                                        @if ($ttd == 'Kejur' AND $kejur == 1)
                                                                            KETUA JURUSAN
                                                                        @endif
                                                                        @if ($ttd == 'Kepro' AND $kepro == 1)
                                                                            KETUA PROGRAM STUDY
                                                                        @endif
                                                                        </center>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>AM</td>
                                                                    <td height="19"> = Angka Mutu </td>
                                                                    <td>&nbsp;</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>K</td>
                                                                    <td height="19"> = Kredit </td>
                                                                    <td>&nbsp;</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>M</td>
                                                                    <td height="19"> = Mutu </td>
                                                                    <td>&nbsp;</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>&nbsp;</td>
                                                                    <td height="19">&nbsp;</td>
                                                                    <td>
                                                                        <br>
                                                                        <br>
                                                                        <br>
                                                                            @if ($ttd == 'Direktur')
                                                                            @if ($direktur == 1)
                                                                            <center>
                                                                                {{ $universitasProfile->employee->front_title == null ? '' : $universitasProfile->employee->front_title .' '}}{{ $universitasProfile->employee->name }}, {{  str_replace(',', '., ', $universitasProfile->employee->back_title); }}
                                                                                <p>NIP.  {{ $universitasProfile->employee->nip }}</p>
                                                                            </center>
                                                                            @else
                                                                            <center>(............................................)</center>
                                                                            <center>NIP.</center>
                                                                            @endif
                                                                            @endif
                                                                            @if ($ttd == 'Wadir1')
                                                                            @if ($wadir1 == 1)  
                                                                            <center>
                                                                                {{ $universitasProfile->viceChancellor->front_title == null ? '' : $universitasProfile->viceChancellor->front_title .' '}}{{ $universitasProfile->viceChancellor->name }}, {{  str_replace(',', '., ', $universitasProfile->viceChancellor->back_title); }}
                                                                                <p>NIP.  {{ $universitasProfile->viceChancellor->nip }}</p>
                                                                            </center>
                                                                            @else
                                                                            <center>(............................................)</center>
                                                                            <center>NIP.</center>
                                                                            @endif
                                                                            @endif
                                                                            @if ($ttd == 'Wadir2')
                                                                            @if ($wadir2 == 1)
                                                                            <center>
                                                                                {{ $universitasProfile->viceChancellor2->front_title == null ? '' : $universitasProfile->viceChancellor2->front_title .' '}}{{ $universitasProfile->viceChancellor2->name }}, {{  str_replace(',', '., ', $universitasProfile->viceChancellor2->back_title); }}
                                                                                <p>NIP.  {{ $universitasProfile->viceChancellor2->nip }}</p>
                                                                            </center>
                                                                            @else
                                                                            <center>(............................................)</center>
                                                                            <center>NIP.</center>
                                                                            @endif
                                                                            @endif
                                                                            @if ($ttd == 'Wadir3')
                                                                            @if ($wadir3 == 1)
                                                                            <center>
                                                                                {{ $universitasProfile->viceChancellor3->front_title == null ? '' : $universitasProfile->viceChancellor3->front_title .' '}}{{ $universitasProfile->viceChancellor3->name }}, {{  str_replace(',', '., ', $universitasProfile->viceChancellor3->back_title); }}
                                                                                <p>NIP.  {{ $universitasProfile->viceChancellor3->nip }}</p>
                                                                            </center>
                                                                            @else
                                                                            <center>(............................................)</center>
                                                                            <center>NIP.</center>
                                                                            @endif
                                                                            @endif
                                                                            @if ($ttd == 'Kejur')
                                                                            @if ($kejur == 1)
                                                                            <center>
                                                                                {{ $item->studyProgram->major->employee->front_title == null ? '' : $item->studyProgram->major->employee->front_title .' '}}{{ $item->studyProgram->major->employee->name }}, {{  str_replace(',', '., ', $item->studyProgram->major->employee->back_title); }}
                                                                            <p>NIP.  {{ $item->studyProgram->major->employee->nip }}</p>
                                                                            </center>
                                                                            @else
                                                                            <center>(............................................)</center>
                                                                            <center>NIP.</center>
                                                                            @endif
                                                                            @endif
                                                                            @if ($ttd == 'Kepro')
                                                                            @if ($kepro == 1)
                                                                            <center>
                                                                                {{ $item->studyProgram->employee->front_title == null ? '' : $item->studyProgram->employee->front_title .' '}}{{ $item->studyProgram->employee->name }}, {{  str_replace(',', '., ', $item->studyProgram->employee->back_title); }}
                                                                                <p>NIP.  {{ $item->studyProgram->employee->nip }}</p>
                                                                            </center>
                                                                            @else
                                                                            <center>(............................................)</center>
                                                                            <center>NIP.</center>
                                                                            @endif
                                                                            @endif
                                                                    </td>
                                                                </tr>
                                                                {{-- <tr>
                                                                    <td>&nbsp;</td>
                                                                    <td height="19">&nbsp;</td>

                                                                    <td>NIP.  {{ $ketua_jurusan->nip }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>&nbsp;</td>
                                                                    <td height="19">&nbsp;</td>
                                                                    <td>&nbsp;</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>&nbsp;</td>
                                                                    <td height="19">&nbsp;</td>
                                                                    <td>DOSEN WALI </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>&nbsp;</td>
                                                                    <td height="19">&nbsp;</td>
                                                                    <td>&nbsp;</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>&nbsp;</td>
                                                                    <td height="19">&nbsp;</td>
                                                                    <td>&nbsp;</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>&nbsp;</td>
                                                                    <td height="19">&nbsp;</td>
                                                                    <td>&nbsp;</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>&nbsp;</td>
                                                                    <td height="19">&nbsp;</td>
                                                                    <td>
                                                                        <br>
                                                                      {{  $employee }}.
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>&nbsp;</td>
                                                                    <td height="19">&nbsp;</td>
                                                                    <td>NIP. {{ $nip }}</td>
                                                                </tr> --}}
                                                            </tbody>
                                                        </table>
                                                        <br>
                                                        <br>
                                                        <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                                            <tr>
                                                                <td width="50%">
                                                                    
                                                                </td>
                                                                <td width="50%">
                                                                    @if ($student_lecture == 1)
                                                                    <center>WALI DOSEN</center>
                                                                        <br>
                                                                        <br>
                                                                        <br>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">
                                                                    &nbsp;
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">
                                                                    &nbsp;
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">
                                                                    &nbsp;
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">
                                                                    &nbsp;
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">
                                                                    &nbsp;
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td width="50%">
                                                                    
                                                                </td>
                                                                <td width="50%">
                                                                    @if ($student_lecture == 1)
                                                                        @if (isset($item->employee->name))
                                                                        <center>
                                                                            {{ $item->employee->front_title == null ? '' : $item->employee->front_title .' '}}{{ $item->employee->name }}, {{  str_replace(',', '., ', $item->employee->back_title); }}
                                                                            <p>{{$item->employee->nip}}</p>
                                                                        </center>
                                                                        @else
                                                                        <center>(............................................)</center>
                                                                        <center>NIP.</center>
                                                                        @endif
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        </center>
                    </td>
                </tr>
                <tr bgcolor="#FFFFFF">
                    <td colspan="3">&nbsp;</td>
                </tr>
            </tbody>
        </table>
    </form>
    <br>
    <br>
    <br>
    <br>
    <br>
    @if ($student_lecture == 0)
    <br>
    <br>
    <br>
    <br>
    <br>
    @endif
    @endforeach
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

        var x = document.getElementById('cetak');
            x.style.visibility = "hidden";

            var style = document.createElement('style');
            style.innerHTML = '@page { size: potrait; }';
            document.head.appendChild(style);



            setTimeout(() => {
                alert("Jangan ditekan tombol OK sebelum dokumen selesai dicetak");
                x.style.visibility = "visible";
            }, 1000);
    </script>
</body>

</html>
