<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">
      <!--<li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-journal-text"></i><span>EJEMPLO DESPLEGABLE</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="forms-elements.html">
              <i class="bi bi-circle"></i><span>1</span>
            </a>
          </li>
          <li>
            <a href="forms-layouts.html">
              <i class="bi bi-circle"></i><span>2</span>
            </a>
          </li>
        </ul>
      </li> 
      
      EJEMPLO DE SEPARADOR
      <li class="nav-heading">SEPARADOR</li> 
      -->

      

      <?php if($_SESSION['cchl']['rol'] == 1 || $_SESSION['cchl']['rol'] == 2){ ?>
          <li class="nav-item">
            <a class="nav-link collapsed" href="imprimir-cchl.php" data-value="imprimircchl">
              <i class="bi bi-printer"></i>
              <span>Imprimir CCHL</span>
            </a>
          </li><!-- End Dashboard Nav -->
      <?php } ?>

        <?php if($_SESSION['cchl']['rol'] == 1){ ?>
        
          <li class="nav-item">
            <a class="nav-link collapsed" href="actualizar-bd.php" data-value="actualizarbd">
              <i class="bi bi-menu-button-wide"></i>
              <span>Actualizar BD</span>
            </a>
          </li><!-- End Profile Page Nav -->

          <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#charts-nav" data-bs-toggle="collapse" href="#" data-value="generarcchl">
              <i class="bi bi-pencil-square"></i><span>Generar CCHL</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="charts-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
              <li>
                <a href="generar-cchl.php">
                  <i class="bi bi-circle"></i><span>Generar CCHL regular</span>
                </a>
              </li>
              <li>
                <a href="generar-cchl-manual.php">
                  <i class="bi bi-circle"></i><span>Generar Constancia Manual</span>
                </a>
              </li>
            </ul>
          </li><!-- End Charts Nav -->

          <!--<li class="nav-item">
            <a class="nav-link collapsed" href="cargar-cchl.php" data-value="cargarcchl">
              <i class="bi bi-layout-text-sidebar-reverse"></i>
              <span>Cargar CCHL</span>
            </a>
          </li> 
          End Contact Page Nav -->
          <li class="nav-item">
            <a class="nav-link collapsed" href="Carga_Cer.php" data-value="cargarcer">
              <i class="bi bi-layout-text-sidebar-reverse"></i>
              <span>Carga de certificados</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link collapsed" href="configuracion.php" data-value="configuracion">
              <i class="bi bi-gear"></i>
              <span>Configuración</span>
            </a>
          </li><!-- End Register Page Nav -->
        <?php } ?>

        <?php if($_SESSION['cchl']['instructor'] == 1){ ?>
          <li class="nav-item">
            <a class="nav-link collapsed" href="instructor.php" data-value="aviso-privacidad">
              <i class="bi bi-person-fill-exclamation"></i>
              <span>Avisos de privacidad</span>
            </a>
          </li>
        <?php } ?>
    </ul>

  </aside>