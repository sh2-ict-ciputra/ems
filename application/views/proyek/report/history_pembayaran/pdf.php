<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
    table {
        border-collapse: collapse;
        padding-right: 30px;
        /* margin-left:-30px; */
        max-width: 95vw;
        text-align: center;
    }
    tr{
        font-size: 20px;
        /* background-color: black; */
        /* color: white; */
        
    }
    .dtrg-level-0{
        font-size: 24px;
        background-color: #26B99A;
        color: white;
    }
    .dtrg-level-1{
        font-size: 22px;
        background-color: #204d74;
        color: white;
    }
    
    table,
    td,
    th {
        border: 1px solid black;
    }
    body{
        margin:0
    }
    td{
        widows: auto
    }
</style>

<body>
    <?php
    print_r($data['data']);
    ?>

</body>

</html>