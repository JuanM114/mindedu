-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-11-2026 a las 01:53:01
-- Versión del servidor: 10.11.16-MariaDB
-- Versión de PHP: 8.3.36

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `mindedu`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administradores`
--

CREATE TABLE `administradores` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(120) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONES PARA LA TABLA `administradores`:
--

--
-- Volcado de datos para la tabla `administradores`
--

INSERT INTO `administradores` (`id`, `nombre`, `email`, `password`) VALUES
(2, 'Administrador', 'admin@mindedu.com', '123'),
(3, 'Administrador', 'admin@admin.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion_usuario`
--

CREATE TABLE `configuracion_usuario` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `idioma` varchar(10) DEFAULT 'es',
  `recordatorio_activo` tinyint(1) DEFAULT 0,
  `hora_recordatorio` time DEFAULT NULL,
  `texto_grande` tinyint(1) DEFAULT 0,
  `alto_contraste` tinyint(1) DEFAULT 0,
  `reducir_movimiento` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONES PARA LA TABLA `configuracion_usuario`:
--   `usuario_id`
--       `usuarios` -> `id`
--

--
-- Volcado de datos para la tabla `configuracion_usuario`
--

INSERT INTO `configuracion_usuario` (`id`, `usuario_id`, `idioma`, `recordatorio_activo`, `hora_recordatorio`, `texto_grande`, `alto_contraste`, `reducir_movimiento`) VALUES
(1, 1, 'es', 1, '21:31:00', 1, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conversaciones`
--

CREATE TABLE `conversaciones` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONES PARA LA TABLA `conversaciones`:
--   `usuario_id`
--       `usuarios` -> `id`
--

--
-- Volcado de datos para la tabla `conversaciones`
--

INSERT INTO `conversaciones` (`id`, `usuario_id`, `titulo`, `fecha_creacion`) VALUES
(68, 1, '😴 Quiero dormir mejor', '2026-11-13 22:27:58'),
(74, 2, '😴 Quiero dormir mejor', '2026-11-14 15:45:45');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ejercicios`
--

CREATE TABLE `ejercicios` (
  `id` int(11) NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `descripcion` text NOT NULL,
  `categoria` varchar(100) DEFAULT NULL,
  `duracion` int(11) NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `audio` varchar(255) DEFAULT NULL,
  `instrucciones` text DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONES PARA LA TABLA `ejercicios`:
--

--
-- Volcado de datos para la tabla `ejercicios`
--

INSERT INTO `ejercicios` (`id`, `titulo`, `descripcion`, `categoria`, `duracion`, `imagen`, `audio`, `instrucciones`, `fecha_creacion`) VALUES
(1, 'Respiración profunda', 'Ejercicio guiado para respirar con calma', 'Respiración', 300, NULL, NULL, NULL, '2026-11-10 00:06:39'),
(2, 'Meditación de calma', 'Meditación para reducir ansiedad', 'Meditación', 480, NULL, NULL, NULL, '2026-11-10 00:06:39'),
(3, 'Escaneo corporal', 'Reconocer tensiones corporales', 'Relajación', 600, NULL, NULL, NULL, '2026-11-10 00:06:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `emociones`
--

CREATE TABLE `emociones` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `estado_animo` int(11) NOT NULL,
  `nota` text DEFAULT NULL,
  `fecha` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONES PARA LA TABLA `emociones`:
--   `usuario_id`
--       `usuarios` -> `id`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_chat`
--

CREATE TABLE `historial_chat` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `mensaje_usuario` text DEFAULT NULL,
  `respuesta_ia` text DEFAULT NULL,
  `fecha` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONES PARA LA TABLA `historial_chat`:
--   `usuario_id`
--       `usuarios` -> `id`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `intereses_usuario`
--

CREATE TABLE `intereses_usuario` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `interes` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONES PARA LA TABLA `intereses_usuario`:
--   `usuario_id`
--       `usuarios` -> `id`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes`
--

CREATE TABLE `mensajes` (
  `id` int(11) NOT NULL,
  `conversacion_id` int(11) NOT NULL,
  `remitente` enum('usuario','ia') NOT NULL,
  `mensaje` text NOT NULL,
  `fecha` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONES PARA LA TABLA `mensajes`:
--   `conversacion_id`
--       `conversaciones` -> `id`
--

--
-- Volcado de datos para la tabla `mensajes`
--

INSERT INTO `mensajes` (`id`, `conversacion_id`, `remitente`, `mensaje`, `fecha`) VALUES
(39, 68, 'usuario', '😴 Quiero dormir mejor', '2026-11-13 22:27:58'),
(40, 68, 'usuario', 'hola', '2026-11-13 22:30:18'),
(41, 68, 'usuario', 'hola', '2026-11-13 22:30:41'),
(53, 74, 'usuario', '😴 Quiero dormir mejor', '2026-11-14 15:45:45'),
(70, 74, 'usuario', 'hola', '2026-11-18 17:33:00'),
(71, 74, 'ia', '¡Hola! Soy **MindEdu**, tu asistente de bienestar emocional. Es un gusto saludarte.\n\nEstoy aquí para acompañarte en tu camino universitario. Si te sientes estresado por los exámenes, algo abrumado con tus tareas, o simplemente necesitas un espacio para hablar sobre cómo te sientes, puedes contar conmigo.\n\nPuedo ayudarte con:\n*   Manejo del estrés académico y la ansiedad.\n*   Organización de tu tiempo y hábitos de estudio.\n*   Fortalecer tu autoestima.\n*   Aprender a regular tus emociones.\n\n¿Cómo te sientes hoy? ¿Hay algo que te gustaría compartir o en lo que necesites apoyo en este momento?', '2026-11-18 17:33:08'),
(103, 68, 'usuario', 'como manejo el estres?', '2026-11-21 19:42:09'),
(104, 68, 'ia', '¡Hola! Entiendo perfectamente cómo te sientes. La vida universitaria puede ser muy demandante y es completamente normal sentirse abrumado por las entregas, los exámenes y las responsabilidades personales.\n\nManejar el estrés no se trata de eliminarlo por completo, sino de aprender a gestionarlo para que no te paralice. Aquí te comparto algunas estrategias prácticas que pueden ayudarte:\n\n### 1. Organiza y prioriza\nA veces el estrés viene de ver \"la montaña\" completa de tareas. Intenta desglosarlas:\n*   **Divide y vencerás:** Divide un trabajo grande en pequeñas tareas de 20 o 30 minutos.\n', '2026-11-21 19:42:22'),
(105, 68, 'usuario', 'manejo del estres', '2026-11-21 19:42:44'),
(106, 68, 'ia', '¡Hola! Entiendo perfectamente cómo te sientes. La vida universitaria puede ser muy exigente y es normal que en algunos momentos el estrés se sienta un poco abrumador. No estás solo en esto y el primer paso para sentirte mejor es, precisamente, reconocer que necesitas herramientas para gestionarlo.\n\nAquí te comparto algunas estrategias prácticas que pueden ayudarte a bajar el ritmo y recuperar el equilibrio:\n\n1.  **Respiración Consciente:** Cuando sientas que el estrés sube, detente un momento. Inhala profundamente por la nariz en 4 tiempos, mantén el aire 4 tiempos y exhala lentamente por la boca en 4 tiempos. Hacer esto un par de veces ayuda a calmar tu sistema nervioso.\n2.  **Divide tus Tareas:**', '2026-11-21 19:42:53'),
(107, 68, 'usuario', 'manejo del sueño', '2026-11-21 19:51:27'),
(108, 68, 'ia', 'Hola. Entiendo perfectamente lo importante que es este tema. Como estudiante universitario, el sueño suele ser lo primero que sacrificamos ante las entregas y exámenes, pero es precisamente el descanso lo que permite que tu cerebro procese la información y que tus emociones se mantengan en equilibrio.\n\nTener dificultades para dormir suele estar muy ligado al estrés académico y a la rumiación (darle vueltas a los mismos pensamientos). Aquí te comparto algunas pautas de higiene del sueño que pueden ayudarte:\n\n1.  **Establece una rutina:** Intenta acostarte y levantarte a la misma hora todos los días, incluso los fines de semana. Esto ayuda a regular tu reloj biológico.\n2.  **Desconexión digital:** La luz azul de las pantallas (móvil, laptop) inhibe la melatonina, la hormona del sueño. Intenta dejar las pantallas al menos 30-60 minutos antes de dormir.\n3.  **Crea un ambiente propicio:** Asegúrate de que tu habitación esté oscura, en silencio y a una temperatura agradable.\n4.  **Evita estudiar en la cama:** Es importante que tu cerebro asocie la cama únicamente con el descanso y no con el estrés de las tareas.\n5.  **Prácticas de relajación:** Realizar una actividad tranquila antes de dormir le avisa a tu cuerpo que es momento de desconectar.\n\n*Nota: Recuerda que mi apoyo no reemplaza la atención psicológica o médica profesional. Si sientes que la falta de sueño está afectando gravemente tu salud o tu integridad, por favor busca ayuda profesional de inmediato.*\n\nPara ayudarte a preparar tu cuerpo y mente para un descanso reparador, te sugiero explorar los siguientes recursos de nuestra plataforma:\n\n### Recomendaciones de MindEdu\n\n**Recursos:**\n*   **Respira consciente:** Te ayudará a bajar las revoluciones del día y centrarte en el presente antes de ir a dormir.\n*   **Entender lo que sientes:** Útil si el insomnio es causado por preocupaciones o ansiedad que no te dejan descansar.\n\n**Ejercicios:**\n*   **Escaneo corporal:** Es ideal para realizarlo ya acostado, ya que ayuda a soltar la tensión física acumulada y facilita el inicio del sueño.\n*   **Respiración profunda:** Una técnica sencilla para calmar el sistema nervioso en pocos minutos.', '2026-11-21 19:51:36');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recursos`
--

CREATE TABLE `recursos` (
  `id` int(11) NOT NULL,
  `titulo` varchar(150) DEFAULT NULL,
  `categoria` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `formato` varchar(50) DEFAULT NULL,
  `duracion` varchar(20) DEFAULT NULL,
  `archivo` varchar(255) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONES PARA LA TABLA `recursos`:
--

--
-- Volcado de datos para la tabla `recursos`
--

INSERT INTO `recursos` (`id`, `titulo`, `categoria`, `descripcion`, `formato`, `duracion`, `archivo`, `imagen`) VALUES
(2, 'Una pausa entre tareas', 'Manejo del estrés', 'Recupera tu atención', 'Video', '8 min', NULL, 'recurso2.jpg'),
(3, 'Respira consciente', 'Respiración', 'Una práctica sencilla para volver al presente.', 'Guía', '5 min', 'respira_consciente.pdf', 'recurso1.jpg'),
(4, 'Entender lo que sientes', 'Emociones', 'Ponle nombre a tus emociones sin juzgar lo que estás sintiendo.', 'Articulo', '5 min', 'Entender_lo_que_sientes.pdf', 'recurso3.jpg'),
(5, 'Prepara tu descanso', 'Sueño', 'Pequeños habiros para cerrar el día y crear una rutina de descanso.', 'Guía', '7 min', 'Prepara_tu_descanso.pdf', 'recurso4.jpg'),
(6, 'Hablarte con amabilidad', 'Autoestima', 'Descubre una forma más cercana y comprensiva de hablar contigo.', 'Articulo', '4 min', 'Hablarte_con_amabilidad.pdf', 'recurso5.jpg'),
(7, 'Conceta con tu cuerpo', 'Respiración', 'Explora sensaciones y respiración con una práctica suave y guiada.', 'Video', '10 min', 'Conceta_con_tu_cuerpo.pdf', 'recurso6.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seguimiento_animo`
--

CREATE TABLE `seguimiento_animo` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `nivel` int(11) NOT NULL,
  `estado` varchar(50) NOT NULL,
  `nota` text DEFAULT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONES PARA LA TABLA `seguimiento_animo`:
--   `usuario_id`
--       `usuarios` -> `id`
--

--
-- Volcado de datos para la tabla `seguimiento_animo`
--

INSERT INTO `seguimiento_animo` (`id`, `usuario_id`, `nivel`, `estado`, `nota`, `fecha_registro`) VALUES
(1, 1, 4, 'Bien', 'he estado bien', '2026-11-10 19:53:55'),
(2, 1, 3, 'En calma', 'el dia ha estado normal', '2026-11-10 19:54:11'),
(3, 1, 2, 'Bajo', 'mal el dia', '2026-11-10 19:54:39'),
(4, 1, 5, 'Muy bien', 'perfecto', '2026-11-10 20:03:13');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `edad` int(11) NOT NULL,
  `password` varchar(255) NOT NULL,
  `idioma` varchar(10) DEFAULT 'es',
  `texto_grande` tinyint(1) DEFAULT 0,
  `alto_contraste` tinyint(1) DEFAULT 0,
  `reducir_movimiento` tinyint(1) DEFAULT 0,
  `fecha_registro` timestamp NULL DEFAULT current_timestamp(),
  `visitas` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONES PARA LA TABLA `usuarios`:
--

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `edad`, `password`, `idioma`, `texto_grande`, `alto_contraste`, `reducir_movimiento`, `fecha_registro`, `visitas`) VALUES
(1, 'Juan Ruiz', 'luizmununez68@gmail.com', 24, '$2y$10$3wAIVeTYNNsB5qeLKZEmPOJmD/pgL4P27OUx5LnHCO..vfvcTbT1C', 'es', 0, 0, 0, '2026-11-08 16:49:26', 0),
(2, 'Usuario1', 'usuario1@gmail', 24, '$2y$10$dcvV4.CeomO5BXfVNo1B.ev7Vx04XyDRi/kgcM8E8ZTqJy2.gPBs6', 'es', 0, 0, 0, '2026-11-08 17:08:18', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administradores`
--
ALTER TABLE `administradores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `configuracion_usuario`
--
ALTER TABLE `configuracion_usuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `conversaciones`
--
ALTER TABLE `conversaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `ejercicios`
--
ALTER TABLE `ejercicios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `emociones`
--
ALTER TABLE `emociones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `historial_chat`
--
ALTER TABLE `historial_chat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `intereses_usuario`
--
ALTER TABLE `intereses_usuario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `conversacion_id` (`conversacion_id`);

--
-- Indices de la tabla `recursos`
--
ALTER TABLE `recursos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `seguimiento_animo`
--
ALTER TABLE `seguimiento_animo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `administradores`
--
ALTER TABLE `administradores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `configuracion_usuario`
--
ALTER TABLE `configuracion_usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `conversaciones`
--
ALTER TABLE `conversaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT de la tabla `ejercicios`
--
ALTER TABLE `ejercicios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `emociones`
--
ALTER TABLE `emociones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `historial_chat`
--
ALTER TABLE `historial_chat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `intereses_usuario`
--
ALTER TABLE `intereses_usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT de la tabla `recursos`
--
ALTER TABLE `recursos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `seguimiento_animo`
--
ALTER TABLE `seguimiento_animo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `configuracion_usuario`
--
ALTER TABLE `configuracion_usuario`
  ADD CONSTRAINT `configuracion_usuario_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `conversaciones`
--
ALTER TABLE `conversaciones`
  ADD CONSTRAINT `conversaciones_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `emociones`
--
ALTER TABLE `emociones`
  ADD CONSTRAINT `emociones_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `historial_chat`
--
ALTER TABLE `historial_chat`
  ADD CONSTRAINT `historial_chat_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `intereses_usuario`
--
ALTER TABLE `intereses_usuario`
  ADD CONSTRAINT `intereses_usuario_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD CONSTRAINT `mensajes_ibfk_1` FOREIGN KEY (`conversacion_id`) REFERENCES `conversaciones` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `seguimiento_animo`
--
ALTER TABLE `seguimiento_animo`
  ADD CONSTRAINT `seguimiento_animo_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
