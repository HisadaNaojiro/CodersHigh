<nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?php echo $loader->SiteSetting->getUrl(); ?>/User/index.php">Demo Twitter</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li <?php echo ($_SERVER ["SCRIPT_NAME"]  === "/View/User/index.php")? 'class="active"' : ''; ?> >
              <a href="<?php echo $loader->SiteSetting->getUrl(); ?>/User/index.php">
                ホーム
              </a>
            </li>
            <li <?php echo ($_SERVER ["SCRIPT_NAME"]  === "/View/User/notice.php")? 'class="active"' : ''; ?>>
              <a href="<?php echo $loader->SiteSetting->getUrl(); ?>/User/notice.php">
                通知
              </a>
            </li>
          </ul>
          
          <p class="navbar-text navbar-right">
            <?php $currentUserName = $User->getNameById($loader->Session->get('user_id')); ?>
            <a href="<?php echo $loader->SiteSetting->getUrl() . '/User/detail.php?'. $currentUserName; ?>" class="navbar-link">
              ログアウト
            </a>
          </p>
          <ul class="nav navbar-nav navbar-right">
            <li class="dropdown ">
            <a href="#" class="dropdown-toggle " data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $currentUserName ; ?><span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li <?php echo ($_SERVER ["SCRIPT_NAME"]  === "/View/User/detail.php")? 'class="active"' : ''; ?> >
                  <a href="<?php echo $loader->SiteSetting->getUrl() . '/User/detail.php?name='. $currentUserName; ?>">
                    詳細
                  </a>
                </li>
                <li <?php echo ($_SERVER ["SCRIPT_NAME"]  === "/View/User/edit.php")? 'class="active"' : ''; ?> >
                  <a href="<?php echo $loader->SiteSetting->getUrl() . '/User/edit.php'?>">
                    プロフィール編集
                  </a>
                </li>
              </ul>
            </li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
