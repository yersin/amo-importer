<!-- Navigation -->
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="/">SB Admin</a>
    </div>
    <!-- Top Menu Items -->
    <div class="nav navbar-right top-nav">
        {!! Form::select('project', ["mk"=> "Мир купонов", "han" => "Хан"], session("project") ? session("project") : null, ["class" => "project form-control"]) !!}
    </div>


    <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
    <div class="collapse navbar-collapse navbar-ex1-collapse">
        <ul class="nav navbar-nav side-nav">
            <li {{ Request::url() == url("/") ? "class=active" : "" }}>
                <a href="/"><i class="fa fa-fw fa-dashboard"></i> Главная</a>
            </li>
            <li {{ Request::url() == url("/admin") ? "class=active" : "" }}>
                <a href="/admin"><i class="fa fa-fw fa-dashboard"></i> Нужная рубрика</a>
            </li>
            <li {{ str_contains(Request::url(), url("/admin/activities")) ? "class=active" : "" }}>
                <a href="/admin/activities"><i class="fa fa-fw fa-dashboard"></i> Деятельность</a>
            </li>
            <li {{ str_contains(Request::url(), url("/admin/rubric-activities")) ? "class=active" : "" }}>
                <a href="/admin/rubric-activities"><i class="fa fa-fw fa-dashboard"></i> Рубрика </a>
            </li>
            <li {{ str_contains(Request::url(), url("/admin/amo-configs")) ? "class=active" : "" }}>
                <a href="/admin/amo-configs"><i class="fa fa-fw fa-cog"></i> Конфигурации amo </a>
            </li>
        </ul>
    </div>
    <!-- /.navbar-collapse -->
</nav>
