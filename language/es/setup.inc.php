<?php
$lv = !$langBully ?  "lang" : "bully";
${$lv}['setup'] = array (
'try_again_to_complete' => "Si usted edit&oacute; el archivo de forma manual o hizo que el archivo se pueda editar, por favor haga clic en el bot&oacute;n de �Intentar de nuevo� para completar su actualizaci&oacute;n.",
'global_unwritable_1' => "El script de actualizaci&oacute;n tiene que modificar el archivo `includes/global.inc.php` pero no lo ha conseguido ya que el mismo no se puede editar. Por favor aplique chmod a este archivo a 0777 si tiene una cuenta de alojamiento tipo linux y despu&eacute;s intente de nuevo. De forma alternativa, puede hacerlo de forma manual editando el archivo para que tenga el siguiente contenido:",
'global_file_updated' => "El archivo `include/global.inc.php` se actualiz&oacute; con &eacute;xito.",
'config_files_updated' => "Todos los archivos de configuraci&oacute;n se han actualizado.",
'enter_key' => "C&oacute;digo de Licencia del Software ImeiUnlock:",
'no_key_entered' => "No se ha ingresado ning&uacute;n c&oacute;digo de licencia del software.",
'upgrade_try_again' => "Intente de nuevo",
'upgrade_success' => "<strong>&iexcl;Felicidades!</strong> Su tienda se ha actualizado con &eacute;xito a %1\$s. Por favor, ahora borre la carpeta de configuraci&oacute;n.",
'database_upgraded' => "La base de datos se ha actualizado con &eacute;xito.",
'upgrade_proceed_to_step' => "Proceda al Paso %1\$s &raquo;",'critical_upgrade_error' => "<strong>Error Cr&iacute;tico:</strong> &iexcl;No hay un historial de la versi&oacute;n disponible! El script de actualizaci&oacute;n no sabe por d&oacute;nde comenzar.",
'upgrade_now' => "Ahora Actualice &raquo;",
'upgrade_precis' => "Este script de actualizaci&oacute;n actualizar&aacute; su tienda desde <strong>%1\$s</strong> hasta <strong>%2\$s</strong>. Si est&aacute; listo por favor proceda haciendo clic en el bot&oacute;n &quot;Upgrade Now&quot; de abajo.",
'alreadyUpgraded' => "Su tienda ya ha sido actualizada a la versi&oacute;n %1\$s. No es necesario realizar el proceso de actualizaci&oacute;n de nuevo.",
'global_missing' => "El archivo `global.inc.php` no existe. Por favor haga una copia de `global.inc.php-dist` que puede encontrarse en el mismo directorio y c&aacute;mbiele el nombre a `global.inc.php`.",
'upgrade_existing' => "Elija esta opci&oacute;n para actualizar su tienda ImeiUnlock a la &uacute;ltima versi&oacute;n. <br />
	  <strong style='color: red;'>IMPORTANTE:</strong> &iexcl;Por favor haga una copia de seguridad COMPLETA de su base de datos y archivos antes de continuar!",
'upgrade_cubecart' => "Actualice ImeiUnlock",
'fresh_install' => "Elija una opci&oacute;n si desea realizar una instalaci&oacute;n fresca de ImeiUnlock.<br />
	  <strong style='color: red;'>ADVERTENCIA</strong>: &iexcl;Al proceder con esta opci&oacute;n cualquier dato de la tienda existente puede sobrescribirse!",
'install_cubecart' => "Instalar ImeiUnlock",
'splash_title' => "Por favor indique si desea instalar o actualizar una tienda existente",
'read_only' => "S&oacute;lo Lectura",
'writable' => "Editable",
'license_key' => "C&oacute;digo de Licencia del Software:",
'fromUs' => "Se puede comprar un c&oacute;digo de licencia del software directamente desde nuestro sitio web. <a href='http://www.cubecart.com'>http://www.cubecart.com</a>. El script de instalaci&oacute;n continuar&aacute; pero el panel de control de admin de su tienda no funcionar&aacute; si el c&oacute;digo de licencia es no v&aacute;lido.",
'not_copy_key' => "&Eacute;ste no es el mismo que un c&oacute;digo de retiro de derechos de autor.",
'stage2Name' => "Comprobar Permisos del Archivo",
'stage1Error' => "Debe aceptar nuestro Contrato de Licencia",
'stage3Name' => "Construir Datos de Configuraci&oacute;n, Crear Cuenta de Administrador &amp; Instalar Tablas de Bases de Datos",
'enterDBHostname' => "Por favor ingrese el nombre de anfitri&oacute;n de la base de datos",
'enterDBName' => "Por favor ingrese el nombre de la base de datos",
'enterDBUsername' => "Por favor ingrese el nombre de usuario de la base de datos",
'enteradminUsername' => "Por favor ingrese el nombre de usuario de admin deseado",
'enteradminPassword' => "Por favor ingrese una contrase&ntilde;a de admin",
'passwordMatch' => "Por favor aseg&uacute;rese de que sus contrase&ntilde;as coincidan",
'enterValidEmail' => "Por favor ingrese una direcci&oacute;n de email v&aacute;lida",
'enterFullname' => "Por favor ingrese su nombre completo",
'storeOfflineText' => "La tienda est&aacute; actualmente desconectada. Por favor vis&iacute;tela de nuevo pronto.",
'configWriteError' => "El archivo config no pudo escribirse.",
'stage4Name' => "Por favor Revierta los Permisos de Archivo",
'stage5Name' => "Instalaci&oacute;n Completada",
'stage1Name' => "Contrato de Licencia",
'agreeToLicense' => "Por favor haga clic en la casilla de comprobaci&oacute;n para continuar.",
'installation' => "Configuraci&oacute;n ImeiUnlock v%1\$s",
'stepStatus' => "Paso %1\$s de %2\$s",
'step' => "Paso",
'iagreetoLic' => "Le&iacute;, entend&iacute; y acepto el contrato de licencia",
'checkFilePerms' => "Por favor aseg&uacute;rese de que los siguientes archivos y carpetas sean editables:",
'fileFolder' => "Archivo / Carpeta",
'currentPermission' => "Estado Actual",
'na' => "n/d",
'congratsFilePerms' => "Felicidades. Los permisos de archivo est&aacute;n establecidos correctamente.",
'filePermsNotCorrect' => "Por favor aseg&uacute;rese de que los permisos de archivo est&eacute;n establecidos de forma correcta para continuar.",
'dbSettings' => "Configuraciones de Bases de Datos",
'fromProvider' => "(Por lo general, &eacute;stos se pueden obtener o crear en su panel de control de alojamiento web)",
'dbhostname' => "Nombre de Anfitri&oacute;n de la Base de Datos:",
'eg' => "ej.",
'dbName' => "Nombre de Base de Datos:",
'dbUsername' => "Nombre de usuario de la Base de datos:",
'dbPassword' => "Contrase&ntilde;a de la Base de Datos:",
'dbPrefix' => "Prefijo de la Base de datos:",
'dbPrefixOptional' => "(Opcional � Utilizado para instalaciones m&uacute;ltiples en una base de datos)",
'dropifExist' => "Desplegar tablas si existen:",
'previousInstallLost' => "Con esto marcado, cualquier instalaci&oacute;n previa con el mismo prefijo de tabla en esta base de datos se perder&aacute;. (Se marca por defecto)",
'localeSettings' => "Configuraciones del Local",
'storeCountry' => "Pa&iacute;s de la Tienda:",'US' => "Estados Unidos de Norteam&eacute;rica",
'UK' => "Reino Unido",
'EU' => "Europa",
'currenciesAccord' => "Esto configurar&aacute; las monedas y otras configuraciones seg&uacute;n el caso. ",
'help' => "Ayuda",
'administratorSettings' => "Perfil de Administrador de Tienda Predeterminado",
'adminSetDesc' => "(Estos detalles se utilizan para acceder al panel de control de admin de su tienda)",
'username' => "Nombre de usuario:",
'password' => "Contrase&ntilde;a:",
'confPass' => "Confirmar Contrase&ntilde;a:",
'emailAddress' => "Direcci&oacute;n de Email:",
'fullName' => "Nombre Completo:",
'skin' => "Dise&ntilde;o",
'clickForLarger' => "Haga Clic para Agrandar la Vista",
'changedAnytime' => "(Esto puede cambiarse en cualquier momento)",
'classic' => "Cl&aacute;sico (Ancho Fijo)",
'legend' => "Leyenda (Ancho Fijo)",
'killer' => "Matador (ancho 100%)",
'advancedSettings' => "Configuraciones Avanzadas",
'leaveIfUnsure' => "(Si no est&aacute; seguro, d&eacute;jelos)",'storeURL' => "URL de la Tienda:",
'serverRoot' => "Directorio Principal del Servidor:",
'siteRootRel' => "Ruta del Sitio en Relaci&oacute;n al Directorio Principal:",
'none' => "Ninguna",
'clickLink' => "Haga clic en el v&iacute;nculo phpinfo() para comprobar la config del servidor.",
'filepermsBack' => "Por favor aseg&uacute;rese de que el siguientes archivo ya no sea editable: (Puede saltarse este paso si su servidor corre con un Sistema Operativo Windows)",
'congratulations' => "&iexcl;Felicidades! Su tienda se ha instalado con &eacute;xito.",
'congratulationsSub' => "Por favor elija un destino. Sugerimos que comience accediendo a su panel de control de admin para configurar su tienda. ",
'adminHomepage' => "P&aacute;gina Principal del Admin",
'storeHomepage' => "P&aacute;gina Principal de la Tienda",
'important' => "IMPORTANTE:",
'deleteInstall' => "Puede que su tienda est&eacute; en riesgo hasta que se hubiera borrado el directorio de configuraci&oacute;n.",
'tryAgain' => "Intente de nuevo",
'contToStep' => "Contin&uacute;e al Paso %1\$s",
'closeWindow' => "Cerrar Ventana",
'prevPage' => "P&aacute;gina Anterior",
'clicktoClose' => "Haga Clic para Cerrar",
'chooseLang' => "Elija Idioma:",
'adminConfSettings' => "Configuraciones de Administrador",
'adminConfSettingsDesc' => "<p>El panel de control del administrador es un &aacute;rea en donde usted tiene un control total sobre su tienda, desde los productos que vende a la administraci&oacute;n de los pedidos. Para acceder al panel de control de los administradores de su tienda debe establecer un usuario al instalar. Este usuario ser&aacute; un &quot;Super User&quot; lo que significa que tendr&aacute; un control total sobre la tienda y sus configuraciones principales. </p>
<p>Si desea a&ntilde;adir otros administradores despu&eacute;s de la instalaci&oacute;n, esto se puede lograr desde el panel de control de admin. Inclusive puede otorgarles permisos espec&iacute;ficos para acceder a ciertas &aacute;reas de la tienda.</p>",
'advancedSettings' => "Configuraciones Avanzadas",
'advancedSettingsDesc' => "<p>Estas configuraciones son para usuarios avanzados. Por favor c&aacute;mbielas s&oacute;lo si usted es un administrador o dise&ntilde;ador con experiencia. </p>
<p>Para determinar la versi&oacute;n de su GD haga clic en el v&iacute;nculo <a href='../info.php' target='_blank'>phpinfo()</a>. Encuentre la secci&oacute;n denominada &quot;GD&quot;, esto le dir&aacute; su versi&oacute;n de GD como 1.x.x o 2.x.x. Si no hay ninguna menci&oacute;n a GD por favor selecciones &quot;None&quot;. </p>
<p><strong>Por qu&eacute; obtengo el mensaje de error &quot;Call a una funci&oacute;n no definida: imagecreatefromjpg()&quot;?<br />
  </strong>Esto se debe a que su servidor web no est&aacute; configurado para tener a GD habilitado. Para correr ImeiUnlock, se requiere la versi&oacute;n GD 1 o superior. Por favor contacte a su anfitri&oacute;n y p&iacute;dale amablemente que se la habilite, pero recuerdo que no est&aacute;n obligados a hacerlo.  Si tampoco puede utilizar GD, busque una compa&ntilde;&iacute;a alternativa de alojamiento para establecer su versi&oacute;n GD como &quot;None&quot;.</p>",
'mysqlDBSettings' => "Configuraciones de la Base de datos MySQL",
'mysqlDBSettingsDesc' => "<p>Para correr ImeiUnlock en su servidor debe tener la base de datos MySQL para que &eacute;sta almacene informaci&oacute;n como los detalles de su producto y cliente. Si est&aacute; intentando instalar ImeiUnlock en un ambiente de alojamiento compartido (virtual), su proveedor de alojamiento deber&iacute;a poder proveerle la informaci&oacute;n requerida para esta etapa del proceso de instalaci&oacute;n. Esta informaci&oacute;n deber&iacute;a incluir el nombre de la base de datos, nombre de usuario, contrase&ntilde;a y el nombre del anfitri&oacute;n. A veces encontrar&aacute; que puede configurar y administrar su base(s) de tados MySQL desde su panel de control de alojamiento. Sin embargo, si est&aacute; instalando ImeiUnlock en una m&aacute;quina de prueba o un servidor dedicado, puede que necesite consultar el <a href='http://dev.mysql.com/doc/mysql/en/index.html' target='_blank'>manual de MySQL</a>. </p>
<p><strong>&iquest;Para qu&eacute; se utiliza el prefijo de la base de datos?</strong>
<br />
El script de instalaci&oacute;n de ImeiUnlock crear&aacute; tablas dentro de su base de datos MySQL que son esenciales para que funcione. Si desea instalar muchas tiendas diferentes, y s&oacute;lo tiene una base de datos, estas tablas tendr&aacute;n que distinguirse las unas de las otras de alguna forma. Esto se logra poniendo un prefijo delante de cada tabla de base de datos para cada tienda.</p>",
'selectOs' => "Por favor elija el sistema operativo de su servidor:",
'permsMac' => "Configuraci&oacute;n de Permisos de Archivo en un Servidor Macintosh",
'permsWin' => "Configuraci&oacute;n de Permisos de Archivo en un Servidor Windows",
'permsWinDesc' => "Los servidores Windows no requieren permisos de archivo para modificarse. Por favor avance al siguiente paso como si no se necesitara hacer nada.",
'permsLinux' => "Configuraci&oacute;n de Permisos de Archivo en un Servidor Linux/Unix",
'permsLinuxDesc' => "<p>Inicie su software <abbr title='File Transfer Protocol'>FTP</abbr> favorite o el que utiliz&oacute; para cargar ImeiUnlock a su servidor. Recomendamos <a href='http://www.smartftp.com' target='_blank'>SmartFTP</a> o <a href='http://filezilla.sourceforge.net/' target='_blank'>FileZilla</a>. (Las im&aacute;genes de pantalla se toman de SmartFTP)</p>
<p><strong>1. Encuentre el archivo que necesita un permiso para cambio y haga clic derecho sobre &eacute;l y seleccione 'CHMOD'.  </strong></p>
<p align='center'><img src='../images/smartFTPSel.gif' alt='' width='309' height='427' title='' /></p>
<p><strong>2. Introduzca el valor chmod requerido en la casilla marcada &quot;Permissions&quot; y haga clic en 'Ok'. De forma alternativa, puede hacer clic en las casillas de comprobaci&oacute;n hasta que obtenga el valor deseado y haga clic en &quot;Ok&quot;. </strong> N.B. Recomendamos 777 para hacer que el archivo/carpeta se pueda editar y 644 para hacer que el archivo/carpeta sea de s&oacute;lo lectura.</p>
<p align='center'><img src='../images/typeChmodVal.gif' alt='' width='382' height='494' title='' /></p>
<p><strong>3. &iexcl;Listo! </strong></p>
<p><strong>Hint:</strong> Si su software FTP discrepa con el valor que este script de instalaci&oacute;n dice que es, s&oacute;lo apriete el bot&oacute;n de actualizar en el software FTP e intente de nuevo.  </p>",
'installHelp' => "Ayuda de Instalaci&oacute;n:",
'helpAdminSettings' => "Configuraci&oacute;n de Administrador",
'helpAdvanced' => "Configuraciones Avanzadas",
'helpDatabase' => "Base de Datos",
'helpFilePerms' => "Permisos de Archivo",
'opDectResultTrue' => "Se ha detectado que el sistema operativo de su servidor es",
'opDectResultFalse' => "Disculpe, no se pudo detectar el sistema operativo de su servidor. Por favor elija abajo.",
'suggestedOS' => "Sistema Operativo Sugerido",
'skinPreview' => "Vista Previa del Dise&ntilde;o",
'ioncube_install' => "Debe instalar ionCube Loader. Para hacerlo, visite ioncube.com/loaders.php y descargue el paquete para %1\$s.<br />Despu&eacute;s extraiga y cargue los archivos al directorio ioncube de su tienda.",
'siteEncoder' => 'Decodificador PHP:',
'auto_detect' => 'Detecci&oacute;n Autom&aacute;tica',
'installed' => 'Instalado',
'not_installed' => 'Sin instalar',
'subscribe' => 'Subscribe to ImeiUnlock Mailing List',
'subscribe_desc' => 'Receive important news and updates concerning ImeiUnlock development and releases.'

);
?>
