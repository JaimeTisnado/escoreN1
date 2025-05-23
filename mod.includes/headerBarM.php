<div class="headerBar headerBar_fixed">
    <div class="headerBar_content">
        <div class="container">
            <a class="brand" href="<?php echo JPATH_BASE_WEB . DSW . 'index.php'; ?>">Sistema eScore</a>
            
            <div id="menu_top">
                <ul class="list_top right">
                    <!--<li><a href="javascript:;"><span class="msg" id="msg">1</span> Mensajes</a></li>-->
                    <li id="msg"></li>
                    <li>
                        <a href="javascript:;">
                            <i class="icon-top fa-cog"></i> Opciones <i class="icon-right fa-caret-down"></i>
                        </a>
                        <ul class="list_top_sub">
                            <li><a href="<?php echo JPATH_BASE_WEB . DSW . 'mod.mensajes/'; ?>"><i class="icon-top fa-envelope"></i> Mensajes</a></li>
                            <li class="divider"></li>
                            <li><a href="javascript:;"><i class="icon-top fa-info-circle"></i> Ayuda</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:;">
                            <i class="icon-top fa-user"></i> <?php echo $_SESSION[_NameSession_nomUser]; ?> <i class="icon-right fa-caret-down"></i>
                        </a>
                        <ul class="list_top_sub">
                            <li><a href="<?php echo JPATH_BASE_WEB . DSW . 'mod.usuarios/perfil_Usuario.php'; ?>"><i class="icon-top fa-shield"></i> Mi Perfil</a></li>
                            <li><a href="<?php echo JPATH_BASE_WEB . DSW . 'mod.usuarios/cambiar_Passw.php'; ?>"><i class="icon-top fa-lock"></i> Cambiar Contraseña</a></li>
                            <li class="divider"></li>
                            <li><a href="<?php echo JPATH_BASE_WEB . DSW . 'logout.php'; ?>"><i class="icon-top fa-power-off"></i> Cerrar Sesión</a></li>
                        </ul>
                    </li>
                </ul>
            </div><!-- menu_top -->
        </div><!-- container -->
    </div><!-- headerBar_content -->   
    
    <div class="subheaderBar_content">
        <div class="container">
            <?php 
            if (isset($_SESSION[_NameSession_idUser])) {
                fndb_mostrarMenuPanel($_SESSION[_NameSession_idUser]);
            }
            ?>
        </div><!-- container -->
    </div><!-- subheaderBar -->
</div><!-- headerBar -->
