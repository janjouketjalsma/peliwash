<?php
if ($sock = @fsockopen('www.google.com', 80, $num, $error, 5)){
    echo "<html>
        <head>
        <style>body{margin:0px;} iframe{margin:0px;width:100%;height:100%;border:none;overflow:hidden}
div#offline{
    width:1920px;
    height:1080px;
    position:absolute;
    background-color:rgba(0,0,0,0.8);
    color:red;
    font-size:50px;
    text-align: center;
    font-family: verdana;
}
</style>
        </head>
        <body>
        <iframe src='http://www.pelikaanhof.nl/peliwash/?page=washok'/>
        </body>
        </html>";
}else{
    echo '<div id="offline"><br><BR><BR><BR><br><br><br><br>Deze monitor is offline wegens een internetstoring.<br><br>Ga naar Pelikaanhof.nl/PeliWash voor up to date bezetting.</div>';
}
?>
