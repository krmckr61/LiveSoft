<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: red;
        }

        iframe {
            position: absolute;
            right: 0px;
            bottom: 0px;
        }

        #LiveSupport {
            position: absolute;
            right: 0px;
            bottom: 0px;
            width: 400px;
            height: 500px;
        }

        .relative {
            width: 100%;
            height: 100%;
            position: relative;
        }

        #minus {
            position: absolute;
            right: 0px;
            top: 0px;
            background-color: #365899;
            color: #fff;
            padding: 10px;
            z-index: 100;
            font-size: 30px;
            cursor: pointer;
        }
    </style>
</head>
<body>
<div id="LiveSupport">
    <div class="relative">
        <span id="minus">-</span>
        <iframe src="http://192.168.1.24/UserPanel" frameborder="0" width="100%" height="100%"></iframe>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="http://livesoft.test/asset/plugins/bower_components/countdown/countdown.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $("#minus").on("click", function () {
            $("iframe").hide();
            $("#LiveSupport").height(0);
        });
    });
</script>
</body>
</html>