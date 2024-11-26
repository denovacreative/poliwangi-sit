
<html xmlns="http://www.w3.org/1999/xhtml" lang="" xml:lang="">
<head>
<title>{{ $title}}</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
 <br/>
 <style type="text/css">

        /* Add margin-right and top to move the content to the left and up */
        #page1-div {
            margin-right: 20px;
            top: 40%;
        }
        p {margin: 0; padding: 0;}	.ft10{font-size:12px;font-family:Times;color:#000000;}
        .ft11{font-size:16px;font-family:Times;color:#000000;}
        .ft12{font-size:14px;font-family:Times;color:#000000;}
        .ft13{font-size:15px;font-family:Times;color:#000000;}
        .ft14{font-size:14px;font-family:Times;color:#000000;}
        .ft15{font-size:18px;font-family:Times;color:#000000;}
        .ft16{font-size:35px;font-family:Times;color:#000000;}
        .ft17{font-size:18px;font-family:Times;color:#000000;}
        .ft18{font-size:16px;font-family:Times;color:#000000;}
        .ft19{font-size:16px;font-family:Times;color:#000000;}
        .ft110{font-size:24px;font-family:Times;color:#000000;}

    </style>
</head>

<body bgcolor="" vlink="blue" link="blue">
@foreach ($datas as $key => $item)
@foreach ($item as $data)
<div id="page1-div">
<p style="position:absolute;top:63px;left:888px;white-space:nowrap" class="ft10"><b>&#160;&#160;NO :&#160;&#160;</b></p>
<p style="position:absolute;top:55px;left:933px;white-space:nowrap" class="ft10"><b>PIN. 933012023000093&#160;&#160;</b></p>
<p style="position:absolute;top:68px;left:306px;white-space:nowrap" class="ft12">&#160;</p>
<p style="position:absolute;top:75px;left:933px;white-space:nowrap" class="ft10"><b>3549/PWANGI/D4.MBP/586/2023</b></p>
<p style="position:absolute;top:156px;left:631px;white-space:nowrap" class="ft13"><b>&#160;</b></p>
<p style="position:absolute;top:100px;left:388px;white-space:nowrap" class="ft14"><i>(Ijin Pendirian: Permendikbud No. 14 Tahun 2013, tanggal 22 Pebruari 2013)</i></p>
<p style="position:absolute;top:120px;left:493px;white-space:nowrap" class="ft15"><b>Dengan ini menyatakan bahwa:</b></p>
<p style="position:absolute;top:150px;left:455px;white-space:nowrap" class="ft16"><i>{{ $key }}</i></p>
<p style="position:absolute;top:200px;left:532px;white-space:nowrap" class="ft17"><i><b>NIM : {{ $data['nim'] }}</b></i></p>
<p style="position:absolute;top:243px;left:350px;white-space:nowrap" class="ft11">Lahir di :  {{ $data['tempat_lahir'] }} &#160;&#160;&#160;Tanggal : {{ date('d-m-Y', strtotime($data['tanggal_lahir']));  }}  &#160;&#160;&#160;NIK : {{ $data['nik'] }}</p>
<br>
<p style="position:absolute;top:275px;left:170px;white-space:nowrap" class="ft11">telah menyelesaikan pendidikan dan memenuhi segala syarat&#160;Akademik Program Sarjana&#160;Terapan ( D4 ) pada tanggal 07 Maret 2023.</p>
<p style="position:absolute;top:310px;left:359px;white-space:nowrap" class="ft18"><b>Jurusan&#160;&#160;&#160;<i>&#160;{{ $data['jurusan'] }}</i></b><b>&#160;&#160;&#160;Program Studi&#160;&#160;&#160;<i>&#160;{{ $data['program_studi'] }}</i></b></p>
<p style="position:absolute;top:347px;left:369px;white-space:nowrap" class="ft12">(<i>Terakreditasi B sesuai BAN-PT&#160;No. 2119/SK/BAN-PT/Akred/Dipl-IV/VII/2019</i>)</p>
<p style="position:absolute;top:374px;left:436px;white-space:nowrap" class="ft11">Oleh sebab itu kepadanya diberikan Ijazah dan Gelar</p>
<p style="position:absolute;top:410px;left:357px;white-space:nowrap" class="ft110"><b>SARJANA&#160;TERAPAN <span style="text-transform: uppercase">{{  $data['title'] }}</span>&#160;({{ $data['title_as'] }})</b></p>
<p style="position:absolute;top:450px;left:380px;white-space:nowrap" class="ft11">beserta segala hak dan kewajiban yang melekat pada Gelar tersebut.</p>
<p style="position:absolute;top:477px;left:420px;white-space:nowrap" class="ft11">Diberikan di Banyuwangi pada tanggal  {{ date('d M Y', strtotime($data['tanggal_terbit']));  }} .</p>
<p style="position:absolute;top:581px;left:227px;white-space:nowrap" class="ft18"><b>KETUA&#160;JURUSAN,</b></p>
<p style="position:absolute;top:581px;left:954px;white-space:nowrap" class="ft18"><b>DIREKTUR</b></p>
<p style="position:absolute;top:708px;left:978px;white-space:nowrap" class="ft15"><b>&#160;</b></p>
<p style="position:absolute;top:700px;left:195px;white-space:nowrap" class="ft15"><b>{{ $data['ketua_jurusan']['nama'] }}</b></p>
<p style="position:absolute;top:723px;left:193px;white-space:nowrap" class="ft15"><b>NIP. {{ $data['ketua_jurusan']['nip'] }}</b></p>
<p style="position:absolute;top:700px;left:869px;white-space:nowrap" class="ft15"><b>{{ $data['direktur']['nama'] }}</b></p>
<p style="position:absolute;top:723px;left:889px;white-space:nowrap" class="ft15"><b>NIP. {{ $data['direktur']['nip'] }}</b></p>
</div>
@endforeach

@endforeach

</body>
</html>
