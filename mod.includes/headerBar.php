<div class="headerBar headerBar_fixed">
    <div class="headerBar_content">
        
        <div class="container">
            <a class="brand" href="<?php echo JPATH_BASE_WEB.DSW;?>index.php">Sistema eScore</a>
            
            <div id="menu_top">
            	<ul class="list_top right">
                    <!--<li><a href="javascript:;"><span class="msg" id="msg">1</span> Mensajes</a></li>-->
                    <li id="msg"></li>
                    <li><a href="javascript:;"><i class="icon-top fa-cog"></i>Opciones<i class="icon-right fa-caret-down"></i></a>
                    	<ul class="list_top_sub">
                        	<li><a href="<?php echo JPATH_BASE_WEB.DSW;?>mod.mensajes/"><i class="icon-top fa-envelope"></i>Mensajes</a></li>
                            <li class="divider"></li>
                            <li><a href="javascript:;"><i class="icon-top fa-info-circle"></i>Ayuda</a></li>
                        </ul>
                    </li>
                    <li><a href="javascript:;"><i class="icon-top fa-user"></i><?php echo $_SESSION[_NameSession_nomUser]; ?><i class="icon-right fa-caret-down"></i></a>
                    	<ul class="list_top_sub">
                        	<li><a href="<?php echo JPATH_BASE_WEB.DSW;?>mod.usuarios/perfil_Usuario.php"><i class="icon-top fa-shield"></i>Mi Perfil</a></li>
                            <li><a href="<?php echo JPATH_BASE_WEB.DSW;?>mod.usuarios/cambiar_Passw.php"><i class="icon-top fa-lock"></i>Cambiar Contraseña</a></li>
                            <li class="divider"></li>
                            <li><a href="<?php echo JPATH_BASE_WEB.DSW;?>logout.php"><i class="icon-top fa-power-off"></i>Cerrar Sesión</a></li>
                        </ul>
                    </li>
            	</ul>
            </div><!-- menu_top -->
        </div><!-- container -->
        
    </div><!-- headerBar_content -->   
    
    <div class="subheaderBar_content">
        <div class="container">
        
            <!--<ul class="list_main">
                <li class="active"><a href="dashboard.php"><i class="icon-menu fa-home"></i>Home</a></li>
                <li>
                	<a href="revisar.php">Revisar Pregunta</a>
				</li>
                <li><a href="javascript:;"><i class="icon-menu fa-"></i>Reportes<i class="icon-right caret fa-caret-down"></i></a>
                	<ul class="list_main_sub">
                    	<li><a href="javascript:;"><i class="icon-menu-sub fa-angle-right"></i>Preguntas Evaluadas</a></li>
                      	<li><a href="javascript:;"><i class="icon-menu-sub fa-angle-right"></i>Rendimiento Revisores</a></li>
                      	<li><a href="javascript:;"><i class="icon-menu-sub fa-angle-right"></i>Reporte Final</a></li>
                    </ul>
                </li>
                <li><a href="javascript:;"><i class="icon-menu fa-"></i>Seguridad<i class="icon-right caret fa-caret-down"></i></a>
                	<ul class="list_main_sub">
                    	<li><a href="mod.accesos/listado.php"><i class="icon-menu-sub fa-angle-right"></i>Accesos</a></li>
                      	<li><a href="mod.perfiles/listado.php"><i class="icon-menu-sub fa-angle-right"></i>Perfiles</a></li>
                      	<li><a href="javascript:;"><i class="icon-menu-sub fa-angle-right"></i>Usuarios</a></li>
                    </ul>
                </li>
                <li><a href="javascript:;"><i class="icon-menu fa-"></i>Administración<i class="icon-right caret fa-caret-down"></i></a>
                	<ul class="list_main_sub">
                    	<li><a href="javascript:;"><i class="icon-menu-sub fa-angle-right"></i>Configuraciones</a></li>
                        <li><a href="javascript:;"><i class="icon-menu-sub fa-angle-right"></i>Paises</a></li>
                      	<li><a href="javascript:;"><i class="icon-menu-sub fa-angle-right"></i>Departamentos</a></li>
                      	<li><a href="javascript:;"><i class="icon-menu-sub fa-angle-right"></i>Municipios</a></li>
                        <li><a href="javascript:;"><i class="icon-menu-sub fa-angle-right"></i>Centros Educativos</a></li>
                        <li><a href="javascript:;"><i class="icon-menu-sub fa-angle-right"></i>Grados</a></li>
                        <li><a href="javascript:;"><i class="icon-menu-sub fa-angle-right"></i>Categorias</a></li>
                        <li><a href="javascript:;"><i class="icon-menu-sub fa-angle-right"></i>Items</a></li>
                    </ul>
                </li>
            </ul>-->
            
            <?php fndb_mostrarMenuPanel($_SESSION[_NameSession_idUser]);?>
        </div><!-- container -->
	</div><!-- subheaderBar -->

</div><!-- headerBar -->