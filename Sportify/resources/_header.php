<!-- PHP Resource File: Header -->
<style>
    #banner{
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        margin-bottom: 2%;
        padding: 0 50px 10px;
        background-color: var(--COLOR2);
        transition: margin-top 0.5s;
        border-bottom: 10px solid var(--COLOR1);
    }
    #banner p{
        display: inline-block;
    }

    a:visited{
        color: var(--FG-1);
    }
    #img_links{
        margin-left: 15%;
    }
    #img_links img{
        width: 3%;
        margin-left: 0;
        transition: transform 0.25s;
    }
    #img_links img:hover{
        transform: scale(1.25);
    }
    #banner img{
        padding-left: 20px;
        padding-right: 20px;
        text-decoration: none;
        margin-right: 1%;
        border-radius: 20px;
        opacity: 0.8;
        transition: opacity 0.25s;
    }
    #banner img:not(#logo):hover{
        opacity: 1;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
    }
    #banner > a img{
        margin-right:-3%;
        vertical-align: top;
        border-radius: 20px;
    }
    #banner > a{
        font-size: 25px;
        font-weight:bold;
        margin-right: 0%;
    }
    #logo{
        margin-left: -50px;
    }
    #box{
        width: 100px;
        height: 100px;
        position: absolute;
        margin-top: 3%;
        z-index: -1;
        top: 20px;
        left: 45%;
    }
    #box{
        text-align: center;
        opacity: 0;
        transition: opacity 0.5s;
    }
    #box:hover{
        opacity: 1;
    }
    #link-name{
        float: right;
        clear:both;
        text-align:right;
    }
</style>
<script> 
  function display(name){
      document.getElementById('link-name').innerHTML = name;
  }
</script>
<div id="banner" onmouseover="show(this, true)" onmouseout="show(this, false)">
    <br>
    <a href="index.php" style="text-decoration: none"
        onmouseover="display('<em>Home</em><br>Go to homepage');"
        onmouseout="display('')">
        <img src="resources/images/logo_small.png" alt="logo_small" id="logo">
        <p> UPVTC Sportsfest 2021<br> </p>
    </a>
    <span id='img_links'>
        <a href='house.php' onmouseover="display('<em>House</em><br>Check the houses status')"              onmouseout="display('')"><img src="resources/images/link-house.png" alt="house"></a>
        <a href='sports.php?sports_id=10' onmouseover="display('<em>Sports</em><br>Check available games')" onmouseout="display('')"><img src="resources/images/link-sports.png" alt="sports"></a>
        <a href='ranking.php' onmouseover="display('<em>Ranking</em><br>Check the standing')"               onmouseout="display('')"><img src="resources/images/link-rank.png" alt="ranking"></a>
    </span>

    <span id="link-name"> </span>

</div>