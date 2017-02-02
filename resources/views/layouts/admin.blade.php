<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <link rel="icon" href="/img/fav.png"  type="image/x-icon">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>CRM</title>

    <!-- Bootstrap Core CSS -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="/css/sb-admin.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="/css/plugins/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- jQuery -->
    <script src="/js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="/js/bootstrap.min.js"></script>

    <!-- Morris Charts JavaScript -->
    <script src="/js/plugins/morris/raphael.min.js"></script>
    <script src="/js/plugins/morris/morris.min.js"></script>
    <script src="/js/plugins/morris/morris-data.js"></script>
    <script type="text/javascript" src="/js/jquery.noty.packaged.min.js"></script>
</head>

<body>

<div id="wrapper">

    @include("admin.inc.sidebar")

    <div id="page-wrapper">
        <h1 align="center">CRM: {{ session("project") == "han" ? "Хан" : "Мир купонов" }}</h1>
        <hr>
        @yield("content")

    </div>
    <!-- /#page-wrapper -->

</div>
<!-- /#wrapper -->
<script>
    $("body").on("change", ".project", function () {
        $.ajax({
            url: "/set-project/" + $(this).val(),
            method: "POST",
            data: {_token:"{{ csrf_token() }}"},
            success: function(){
                location.reload();
            }
        });
    });

    $("#search").on("change", function () {
        var search = $("#search").val();
        var type = $("#search").data("type");
        if(search || search == ""){
            $(".rubric-table").html("<div align='center'><img src='/img/loader.gif' height='50'></div>");
            $.ajax({
                url: "/admin/rubric-search/" + search,
                method: "get",
                data:{type:type},
                success: function (data) {
                    $(".rubric-table").html(data);
                }
            });
        }
    });
</script>


</body>

</html>
