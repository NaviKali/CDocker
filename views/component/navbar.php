<style>
    .navbar {
        position:relative;
        top: 0;
        box-shadow: 0px 4px 10px 1px rgb(213, 213, 213);
    }

    .nav-link {
        color: black !important;
    }

    .nav-item {
        color: black !important;
    }
</style>
[Start]
<nav class="navbar navbar-dark bg-dark fixed-top bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">CDocker</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasDarkNavbar"
            aria-controls="offcanvasDarkNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="offcanvas offcanvas-end text-bg-dark" tabindex="-1" id="offcanvasDarkNavbar"
            aria-labelledby="offcanvasDarkNavbarLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasDarkNavbarLabel">菜单列表</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                    @foreach($TK['menus'] as $k => $v){
                    if(empty($v["children"])){
                    echo '
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="'.$v['name'].'">'.$k.'</a>
                    </li>
                    ';
                    }else{
                    echo '
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            '.$k.'
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            '.(function() use ($v):string{
                            $html = '';
                            foreach($v["children"] as $children_k => $children_v){
                            $html .= '<li><a class="dropdown-item" href="'.$children_v['name'].'">'.$children_k.'</a></li>
                            ';
                            }
                            return $html;
                            })().'
                        </ul>
                    </li>
                    ';
                    }
                    }for@
                </ul>
                <form class="d-flex mt-3" role="search">
                    <input class="form-control me-2" type="search" placeholder="请输入想要跳转的菜单" aria-label="Search"
                        name="search">
                    <button class="btn btn-success" type="submit">searchMenu</button>
                </form>
            </div>
        </div>
    </div>
</nav>
$(
    self::Start("component/fixed",[],[]);
)$

[/Start]