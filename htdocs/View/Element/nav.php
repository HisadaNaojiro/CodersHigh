<nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Demo Twitter</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="active">
              <a href="<?php echo $loader->SiteSetting->getUrl(); ?>/User/index.php">
                ホーム
              </a>
            </li>
            <li>
              <a href="<?php echo $loader->SiteSetting->getUrl(); ?>/User/notice.php">
                通知
              </a>
            </li>
            <li class="dropdown ">
            <a href="#" class="dropdown-toggle navbar-right" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="#">Action</a></li>
                <li><a href="#">Another action</a></li>
                <li><a href="#">Something else here</a></li>
                <li role="separator" class="divider"></li>
                <li class="dropdown-header">Nav header</li>
                <li><a href="#">Separated link</a></li>
                <li><a href="#">One more separated link</a></li>
              </ul>
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
                <li>
                  <a href="<?php echo $loader->SiteSetting->getUrl() . '/User/detail.php?name='. $currentUserName; ?>">
                    詳細
                  </a>
                </li>
                <li>
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
