<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>MindEdu - Registro</title>
  <link rel="stylesheet" href="../css/registro.css">
</head>
<body>
  <div class="container">
    <!-- Panel izquierdo -->
    <div class="left-panel">
      <h1>MindEdu</h1>
      <h2>EMPIEZA A TU RITMO</h2>
      <p>Un pequeño paso para sentirte mejor.<br>
         No hay una forma correcta de sentir. Aquí puedes escucharte, aprender y encontrar apoyo.
      </p>
      <ul>
        <li>Reconoce cómo te sientes</li>
        <li>Encuentra tu momento de calma</li>
        <li>Aprende con recursos prácticos</li>
      </ul>
      <small>MindEdu acompaña tu bienestar. No reemplaza la atención de un profesional de salud.</small>
    </div>

    <!-- Panel derecho -->
    <div class="right-panel">
      <h2>Hagamos espacio para ti</h2>
      <form action="../config/registro.php" method="POST">

        <div class="login-link">
          <span>¿Ya tienes cuenta?</span>
          <a href="index.php">Inicia sesión →</a>
        </div>

        <!-- FILA 1 -->
        <div class="form-row">
          <div class="form-group">
            <label for="nombre">Nombre completo *</label>
            <input type="text" id="nombre" name="nombre" required>
          </div>

          <div class="form-group age-group">
            <label for="edad">Edad *</label>
            <input type="number" id="edad" name="edad" required>
            <small>A partir de 12 años.</small>
          </div>
        </div>

        <!-- CORREO -->
        <div class="form-group">
          <label for="email">Correo electrónico *</label>
          <input type="email" id="email" name="email" required>
          <small>Usaremos este correo para acceder a tu cuenta.</small>
        </div>

        <!-- FILA CONTRASEÑAS -->
        <div class="form-row">
          <div class="form-group">
            <label for="password">Contraseña *</label>
            <input type="password" id="password" name="password" required>
          </div>

          <div class="form-group">
            <label for="confirmar">Confirmar contraseña *</label>
            <input type="password" id="confirmar" name="confirmar" required>
          </div>
        </div>

        <div class="password-info">
          Mínimo 8 caracteres, una mayúscula, una minúscula, un número y un símbolo.
        </div>

        <!-- INTERESES -->
        <div class="interest-section">
          <h3>¿Qué te gustaría cuidar?</h3>
          <p>Elige lo que conecte contigo. Puedes cambiarlo después.</p>

          <div class="checkbox-group">
            <label>
              <input type="checkbox" name="cuidado[]" value="estrés">
              Manejo del estrés
            </label>
            <label>
              <input type="checkbox" name="cuidado[]" value="respiración">
              Respiración
            </label>
            <label>
              <input type="checkbox" name="cuidado[]" value="sueño">
              Sueño
            </label>
            <label>
              <input type="checkbox" name="cuidado[]" value="autoestima">
              Autoestima
            </label>
            <label>
              <input type="checkbox" name="cuidado[]" value="emociones">
              Emociones
            </label>
          </div>
        </div>

        <!-- CONSENTIMIENTO -->
        <div class="consent-box">
          <label>
            <input type="checkbox" id="checkConsentimiento" name="consentimiento" required>
            He leído y acepto el consentimiento informado y autorizo el tratamiento de mis datos personales.
          </label>

          <p>
            <a href="#" onclick="abrirModalLegal('consentimiento'); return false;">
              Leer consentimiento informado
            </a>
            ·
            <a href="#" onclick="abrirModalLegal('privacidad'); return false;">
              Ver política de privacidad
            </a>
          </p>
        </div>

        <!-- BOTON -->
        <button type="submit" class="register-btn">
          Crear mi cuenta
        </button>

      </form>

      <p class="chat-link">
        <a href="chat.php">Chat IA · ayuda sin iniciar sesión</a>
      </p>
    </div>
  </div>

  <!-- VENTANA MODAL LEGAL -->
  <div id="modalLegal" class="modal-legal-overlay" onclick="cerrarModalFondo(event)">
    <div class="modal-legal-caja">
      <div class="modal-legal-header">
        <h3 id="modalLegalTitulo">Información Legal</h3>
        <button type="button" class="modal-legal-cerrar" onclick="cerrarModalLegal()" aria-label="Cerrar">&times;</button>
      </div>
      <div id="modalLegalCuerpo" class="modal-legal-cuerpo"></div>
      <div class="modal-legal-footer">
        <button type="button" class="modal-legal-btn-sec" onclick="cerrarModalLegal()">Cerrar</button>
        <button type="button" class="modal-legal-btn" onclick="aceptarYCerrarModal()">Aceptar y continuar</button>
      </div>
    </div>
  </div>

  <script>
    const contenidosLegales = {
      consentimiento: {
        titulo: "Consentimiento Informado",
        html: `
          <div class="modal-legal-aviso">
            <strong>Aviso importante:</strong> MindEdu es una herramienta de apoyo educativo y preventivo. <strong>No emite diagnósticos clínicos ni sustituye la atención de un profesional de salud o psicología.</strong>
          </div>
          <h4>1. Finalidad del uso</h4>
          <p>MindEdu brinda recursos educativos, ejercicios de relajación y seguimiento personal de bienestar para acompañar el autocuidado cotidiano.</p>
          <h4>2. Voluntariedad</h4>
          <p>El registro y el uso de la plataforma son voluntarios. Puedes decidir qué información registrar y dejar de usar el sistema cuando lo desees.</p>
          <h4>3. Situaciones de crisis</h4>
          <p>Si atraviesas un malestar emocional intenso o una emergencia, busca atención inmediata con servicios de bienestar, centros de salud o líneas de apoyo correspondientes.</p>
        `
      },
      privacidad: {
        titulo: "Política de Privacidad y Tratamiento de Datos",
        html: `
          <p>En concordancia con los principios de protección de datos personales (Ley 1581 de 2012), se informa el manejo de tu información dentro de MindEdu:</p>
          <h4>1. Datos que se recolectan</h4>
          <ul>
            <li>Datos de cuenta y acceso (nombre, edad, correo y contraseña protegida).</li>
            <li>Preferencias elegidas y registros de bienestar o seguimiento dentro de la plataforma.</li>
          </ul>
          <h4>2. Uso y confidencialidad</h4>
          <p>Los datos se emplean únicamente para el funcionamiento del prototipo y la experiencia del usuario. No se venden ni se ceden con fines comerciales a terceros.</p>
          <h4>3. Control sobre tu cuenta</h4>
          <p>Puedes actualizar tus datos o eliminar tu cuenta y su información asociada desde las opciones de tu perfil.</p>
        `
      }
    };

    function abrirModalLegal(tipo) {
      const datos = contenidosLegales[tipo];
      if (!datos) return;
      document.getElementById("modalLegalTitulo").textContent = datos.titulo;
      document.getElementById("modalLegalCuerpo").innerHTML = datos.html;
      document.getElementById("modalLegal").classList.add("activo");
    }

    function cerrarModalLegal() {
      document.getElementById("modalLegal").classList.remove("activo");
    }

    function aceptarYCerrarModal() {
      const checkbox = document.getElementById("checkConsentimiento");
      if (checkbox) checkbox.checked = true;
      cerrarModalLegal();
    }

    function cerrarModalFondo(event) {
      if (event.target.id === "modalLegal") {
        cerrarModalLegal();
      }
    }

    document.addEventListener("keydown", function (event) {
      if (event.key === "Escape") {
        cerrarModalLegal();
      }
    });
  </script>
</body>
</html>