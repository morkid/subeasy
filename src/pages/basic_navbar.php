
<nav class="navbar navbar-inverse navbar-static-top" role="navigation">
  <div class="container">
  	<div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="./"><i class="glyphicon glyphicon-film"></i> Sub.easy</a>
  </div>
  <div class="collapse navbar-collapse navbar-ex1-collapse">
    <ul class="nav navbar-nav">
      <li <?php if(CURRENT_FILE == 'collections')echo 'class="active"'?>><a href="?src=collections">Collections</a></li>
      <li class="dropdown <?php if(CURRENT_FILE == 'create')echo 'active'?>">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Create New <b class="caret"></b></a>
        <ul class="dropdown-menu">
          <li><a href="?src=create&amp;action=import"><i class="glyphicon glyphicon-hdd"></i> Import from .srt</a></li>
          <li><a href="?src=create&amp;action=manual"><i class="glyphicon glyphicon-pencil"></i> Write Manual</a></li>
        </ul>
      </li>
    </ul>
  </div>
  </div>
</nav>