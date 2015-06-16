<?php
require_once "../sqlkit.php";
if (!isset($_GET['s']))
{
    $stt = "0";
}else
    $stt = $_GET['s'];

if (!isset($_GET['n'])) {
    $nick = "";
}
else {
    $nick = $_GET['n'];
}
$giu="";
$opi="";
$sino="true";
$link = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];

if (!isset($_GET['c'])) {
    $res = query("SELECT * FROM `song` ORDER BY id desc LIMIT 15");
    $con=0;
    while (($r = mysql_fetch_assoc($res))) {
        $cosd=$r['code'];
        $nick2 = $r['nick'];
        $stt2 = $r['start'];
        $jk="";
        if(strlen($nick2)>0)
            $jk='<span style="color:yellow;font-weight:bold"> - by '.$r['nick'].'</span>';
        if($con==0) {
            $code = $r['code'];
            $nick = $r['nick'];
            $stt = $r['start'];
            $ovulo="window.open('$link?c=$cosd&n=$nick2&s=$stt2', '_self')";
            $opi.='<p style="display: inline-block" class="black-65" ><span onclick="'.$ovulo.'" style="font-weight:bold;color:mediumpurple;text-decoration:underline;cursor: pointer" id="titolo'.$con.'"></span>'.$jk.'<span id="stato" style="font-weight:bold;color:lightsalmon"></span></p><br>';
        }else{
            $ovulo="window.open('$link?c=$cosd&n=$nick2&s=$stt2', '_self')";
            $opi.='<p style="display: inline-block" class="black-65" ><span onclick="'.$ovulo.'" style="font-weight:bold;color:mediumpurple;text-decoration:underline;cursor: pointer" id="titolo'.$con.'"></span>'.$jk.'</p><br>';
            $giu.="getTit($con,'$cosd');";
        }
        $con++;
    }

}
else {
    $sino="false";
    $code = $_GET['c'];
    query("DELETE FROM song WHERE code LIKE '$code'");
    //$arr['id']=1;
    $arr['nick']=$nick;
    $arr['code']=$code;
    $arr['start']=$stt;
    repTV("song",$arr);
    $res = query("SELECT * FROM `song` ORDER BY id desc LIMIT 15");
    $con=0;
    while (($r = mysql_fetch_assoc($res))) {
        $cosd=$r['code'];
        $nick2 = $r['nick'];
        $stt2 = $r['start'];
        $jk="";
        if(strlen($nick2)>0)
            $jk='<span style="color:yellow;font-weight:bold"> - by '.$r['nick'].'</span>';
        if($con==0) {
            $ovulo="window.open('$link?c=$cosd&n=$nick2&s=$stt2', '_self')";
            $opi.='<p style="display: inline-block" class="black-65" ><span onclick="'.$ovulo.'" style="font-weight:bold;color:mediumpurple;text-decoration:underline;cursor: pointer" id="titolo'.$con.'"></span>'.$jk.'<span id="stato" style="font-weight:bold;color:lightsalmon"></span></p><br>';
        }else{
            $ovulo="window.open('$link?c=$cosd&n=$nick2&s=$stt2', '_self')";
            $opi.='<p style="display: inline-block" class="black-65" ><span onclick="'.$ovulo.'" style="font-weight:bold;color:mediumpurple;text-decoration:underline;cursor: pointer" id="titolo'.$con.'"></span>'.$jk.'</p><br>';
            $giu.="getTit($con,'$cosd');";
        }
        $con++;
    }
}
?>
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>Tube</title>
    <script src="media/js/jquery.min.js" type="text/javascript"></script>
    <script type="text/javascript" charset="utf-8" src="media/js/jquery.tubular.1.0.js"></script>
    <script src='media/js/nod.js'></script>
    <link href="media/css/screen.css" rel="stylesheet" type="text/css" />
<style>
    .bravo-message-class { background: forestgreen; color: white; padding:2px 4px;
        font-weight: bold; }
    .bummer-message-class { background: purple; color: yellow; padding:2px 4px;font-weight: bold;}
    p#datis{
        margin-top: 10px;
        margin-bottom: 1px;
    }
    #video-controls a.tubular-mute{
        color: red;
        font-weight: bold;
    }
    #video-controls{
        width: 363px;
        margin-top: 10px;
    }
    #video-controls2{
        width: 50px;
    }
    #video-controls2{
        width: 110px;
    }
    label{
        color:white;
        font-weight:bold;
        display: inline-block;
        width:110px
    }
    .submit-btn{
        margin-left:130px;
        margin-top:4px;
    }

    #video-controls a, #video-controls2 a { color: #ddd; text-decoration: none; }
</style>
    <script>
        var vis=1;
        $('iframe').attr('src', $('iframe').attr('src'));
        $().ready(function() {
            $('iframe').attr('src', $('iframe').attr('src'));
            var options = { videoId: '<?php echo $code ?>', start:<?php echo $stt ?> , ratio: 16/9, repeat: false, mute: <?php echo $sino ?> };
            $('#wrapper').tubular(options);
            videoId ="<?php echo $code ?>";
            ytApiKey ="AIzaSyBsMGK9hgQPW66KepTcw6rW6YTauYMvAfM";
            $.get("https://www.googleapis.com/youtube/v3/videos?part=snippet&id=" + videoId + "&key=" + ytApiKey, function(data) {
                titolo=data.items[0].snippet.title;
                $('#titolo0').text(titolo);
            });
            <?php echo $giu ?>
            $('iframe').attr('src', $('iframe').attr('src'));
        });
        function nasc(){
            $('#pri').css("display","none");
            $('#video-controls').css("display","none");
            $('#video-controls2').css("display","block");
            $('#datis').css("display","none");
            $('#playlist').css("display","none");
        }
        function nasc2(){
            $('#pri').css("display","block");
            $('#playlist').css("display","inline-block");
            $('#video-controls').css("display","block");
            $('#datis').css("display","block");
            $('#video-controls2').css("display","none");
        }
        function getTit(i,id){
            videoId =id;
            ytApiKey ="AIzaSyBsMGK9hgQPW66KepTcw6rW6YTauYMvAfM";
            $.get("https://www.googleapis.com/youtube/v3/videos?part=snippet&id=" + videoId + "&key=" + ytApiKey, function(data) {
                titol=data.items[0].snippet.title;
                $('#titolo'+i).text(titol);
            });
        }
        function vai2() {
            window.open('https://www.youtube.com/watch?v=<?php echo $code ?>','_blank');
        }
        function vai3() {
            window.open('<?php echo $link ?>?c=<?php echo $code ?>&n=<?php echo $nick ?>&s=<?php echo $stt ?>', '_self');
        }
    </script>
</head>
<body>
<div id="wrapper" class="clearfix" >
    <div id="pri" style="width:600px;display: block">
    <div>
        <label for="mio" class="black-65">Scelta da:</label>
        <input id="mio" onClick="this.select();" value="OminoRandom">
    </div>
    <div>
            <label  class="black-65" for="mio2"><abbr title="Quello tra le quadre: www.youtube.com/watch?v=[DDfER59Ni6c]" style='cursor:help'>Cod. Youtube:</abbr></label>
            <input id="mio2" class='bar' onClick="this.select();">
    </div>
    <div>
        <label for="mio3" class="black-65">Inizia da (sec):</label>
        <input id="mio3" onClick="this.select();" class='foo' value="0">
    </div>

    <button class='submit-btn'
            onclick="window.open('<?php echo $link ?>?c=' + document.getElementById('mio2').value +'&n='+ document.getElementById('mio').value+'&s='+ document.getElementById('mio3').value, '_self');">Ok</button>
    </div>
    <script>
        var myNod = nod();
        myNod.configure({
            // Let's remove the delay on showing error messages.
            delay: 0,
            // Adding a custom success message (will be shown for every
            // field).
            successMessage: 'ok',
            // Adding our own classes.
            successClass: 'bravo-class',
            successMessageClass: 'bravo-message-class',
            errorClass: 'bummer-class',
            errorMessageClass: 'bummer-message-class',
            // Let's make nod disable the submit button if there are errors
            submit: '.submit-btn',
            disableSubmit: true

        });



        myNod.add([{
            selector: '.foo',
            validate: 'integer',
            defaultStatus: 'valid',
            errorMessage: 'Devi inserire un numero'
        }, {
            selector: '.bar',
            validate: 'exact-length:11',
            errorMessage: 'Il codice deve avere 11 cifre'
        }]);
    </script>
        <p id="video-controls" class="black-65">Controlli: <a href="#" class="tubular-pause">Play/Pausa</a> | <a href="#" class="tubular-volume-up">Vol +</a> | <a href="#" class="tubular-volume-down">Vol -</a> | <a href="#" class="tubular-mute">Musica!</a> | <a href="#" class="nascondi" id="nascondi" onclick="nasc()" style="color:lightsteelblue;font-weight: bold">Nascondi</a></p>
        <p id="video-controls2" class="black-65" style="display: none">Controlli:<a href="#" class="nascondi2" id="nascondi2" onclick="nasc2()" style="color:lightsteelblue;font-weight: bold"> Mostra</a></p>
        <div id="playlist"><p class="black-65" style="font-variant: small-caps;display: inline-block;color:hotpink;font-weight: bold">Ultime Scelte</p><br><?php echo $opi ?></div>
</div>


</body>
</html>