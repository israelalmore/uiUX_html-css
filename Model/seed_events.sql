-- Seed de eventos para testear filtros (categoría, fecha, búsqueda)
-- Requiere que exista al menos un usuario con id = 1 (el de db.sql ya sirve).
--
-- Ejecutar desde phpMyAdmin o por consola:
--   mysql -u root forever_events < Model/seed_events.sql
--
-- Si necesitas regenerar (borrar y volver a insertar), descomenta la siguiente línea:
-- DELETE FROM eventos WHERE organizador_id = 1 AND titulo <> 'Concierto Forever Demo';

USE forever_events;

INSERT INTO eventos
  (titulo, descripcion, categoria, fecha, hora,
   direccion, codigo_postal, ciudad, email,
   imagen_portada, organizador_id)
VALUES
  -- ===== Arte y Cultura =====
  ('Concierto Sinfónico de Primavera',
   'La Orquesta Nacional interpreta piezas de Beethoven y Mahler en una velada irrepetible.',
   'Arte y Cultura', '2026-05-20', '19:30:00',
   'Calle Príncipe de Vergara, 146', '28002', 'Madrid',
   'cultura@forever.com', '../Assets/img/images/events.jpg', 1),

  ('Festival de Cine al Aire Libre',
   'Tres noches de proyecciones bajo las estrellas con clásicos restaurados y estrenos independientes.',
   'Arte y Cultura', '2026-07-22', '21:30:00',
   'Parque de Doña Casilda, s/n', '48009', 'Bilbao',
   'cine@forever.com', '../Assets/img/images/events.jpg', 1),

  ('Exposición Goya y sus Tiempos',
   'Recorrido por la obra de Francisco de Goya con piezas cedidas por el Museo del Prado.',
   'Arte y Cultura', '2026-05-12', '10:00:00',
   'Plaza de los Sitios, 6', '50001', 'Zaragoza',
   'museos@forever.com', '../Assets/img/images/events.jpg', 1),

  ('Festival de Jazz al Atardecer',
   'Conciertos en directo de artistas internacionales junto al mar.',
   'Arte y Cultura', '2026-07-30', '20:00:00',
   'Paseo de la Concha, 12', '20007', 'San Sebastián',
   'jazz@forever.com', '../Assets/img/images/events.jpg', 1),

  -- ===== Deporte =====
  ('Maratón Internacional de Valencia',
   '42 km por las calles del centro histórico con miles de corredores de toda Europa.',
   'Deporte', '2026-06-07', '08:00:00',
   'Avenida del Puerto, 1', '46021', 'Valencia',
   'maraton@forever.com', '../Assets/img/images/events.jpg', 1),

  ('Torneo de Pádel Solidario',
   'Torneo benéfico cuyos ingresos se destinan a la asociación local de infancia.',
   'Deporte', '2026-06-14', '09:30:00',
   'Calle Cervantes, 23', '29016', 'Málaga',
   'padel@forever.com', '../Assets/img/images/events.jpg', 1),

  ('Carrera Popular 10K Sevilla',
   'Carrera urbana abierta a todos los niveles con recorrido por los principales monumentos.',
   'Deporte', '2026-04-19', '09:00:00',
   'Avenida de la Palmera, 25', '41013', 'Sevilla',
   'carrera@forever.com', '../Assets/img/images/events.jpg', 1),

  ('Liga Amateur de Fútbol Sala',
   'Jornada de la liga local con encuentros eliminatorios entre los ocho mejores equipos.',
   'Deporte', '2026-05-12', '18:00:00',
   'Calle Recogidas, 45', '18002', 'Granada',
   'futsal@forever.com', '../Assets/img/images/events.jpg', 1),

  -- ===== Tecnología =====
  ('DevConf Barcelona 2026',
   'Conferencia anual de desarrolladores con charlas sobre IA, cloud y arquitecturas modernas.',
   'Tecnología', '2026-09-12', '09:00:00',
   'Avinguda Diagonal, 661', '08028', 'Barcelona',
   'devconf@forever.com', '../Assets/img/images/events.jpg', 1),

  ('Hackathon AI Future',
   '48 horas de programación en equipo para construir prototipos con LLMs y visión por computador.',
   'Tecnología', '2026-10-05', '09:00:00',
   'Calle de Serrano, 117', '28006', 'Madrid',
   'hackathon@forever.com', '../Assets/img/images/events.jpg', 1),

  ('WebSummit España',
   'La mayor cumbre tecnológica del país con ponentes de Google, Microsoft y startups punteras.',
   'Tecnología', '2026-11-15', '09:00:00',
   'Paseo de la Castellana, 99', '28046', 'Madrid',
   'websummit@forever.com', '../Assets/img/images/events.jpg', 1),

  ('Bootcamp Cyber Security',
   'Formación intensiva de tres días en seguridad ofensiva, pentesting y respuesta a incidentes.',
   'Tecnología', '2026-08-22', '09:30:00',
   'Calle Alcalá, 240', '28027', 'Madrid',
   'cyber@forever.com', '../Assets/img/images/events.jpg', 1),

  -- ===== Político =====
  ('Debate Municipal Abierto',
   'Encuentro abierto a la ciudadanía con los principales candidatos al ayuntamiento.',
   'Político', '2026-05-25', '18:00:00',
   'Plaza Nueva, 1', '41001', 'Sevilla',
   'debate@forever.com', '../Assets/img/images/events.jpg', 1),

  ('Asamblea Vecinal Distrito Centro',
   'Reunión informativa sobre las obras de remodelación urbana previstas para 2027.',
   'Político', '2026-05-18', '19:00:00',
   'Calle Don Jaime I, 24', '50003', 'Zaragoza',
   'asamblea@forever.com', '../Assets/img/images/events.jpg', 1),

  ('Concentración por el Clima',
   'Manifestación pacífica con motivo de la cumbre climática europea.',
   'Político', '2026-09-21', '12:00:00',
   'Puerta del Sol, 1', '28013', 'Madrid',
   'clima@forever.com', '../Assets/img/images/events.jpg', 1),

  -- ===== Corporativo =====
  ('Cumbre Empresarial PYME',
   'Jornada de networking y mesas redondas para pequeñas y medianas empresas.',
   'Corporativo', '2026-07-10', '10:00:00',
   'Calle de Velázquez, 31', '28001', 'Madrid',
   'pyme@forever.com', '../Assets/img/images/events.jpg', 1),

  ('Networking Startups Forever',
   'Encuentro mensual entre fundadores, inversores y mentores del ecosistema emprendedor.',
   'Corporativo', '2026-06-25', '18:30:00',
   'Calle de Colón, 16', '46004', 'Valencia',
   'startups@forever.com', '../Assets/img/images/events.jpg', 1),

  ('Foro de Salud Digital',
   'Profesionales sanitarios y tecnólogos debaten sobre el futuro de la telemedicina.',
   'Corporativo', '2026-09-18', '10:00:00',
   'Carrer de Balmes, 215', '08006', 'Barcelona',
   'salud@forever.com', '../Assets/img/images/events.jpg', 1),

  ('Encuentro de Emprendedores Tech',
   'Pitch sessions, talleres de marca personal y comida-networking con perfiles del sector.',
   'Corporativo', '2026-05-29', '09:00:00',
   'Passeig de Gràcia, 90', '08008', 'Barcelona',
   'emprendedores@forever.com', '../Assets/img/images/events.jpg', 1),

  -- ===== Ocio =====
  ('Feria del Vino y la Gastronomía',
   'Más de 80 bodegas y restaurantes en un fin de semana dedicado al producto local.',
   'Ocio', '2026-09-26', '12:00:00',
   'Calle Portales, 14', '26001', 'Logroño',
   'feria@forever.com', '../Assets/img/images/events.jpg', 1),

  ('Mercadillo Vintage Forever',
   'Ropa, vinilos y objetos retro de los años 60 a los 90 en un ambiente único.',
   'Ocio', '2026-05-23', '11:00:00',
   'Plaza Mayor, 27', '28012', 'Madrid',
   'vintage@forever.com', '../Assets/img/images/events.jpg', 1),

  ('Cata de Cervezas Artesanas',
   'Recorrido guiado por seis cervezas locales con maridaje de tapas tradicionales.',
   'Ocio', '2026-06-28', '19:00:00',
   'Calle Somera, 5', '48005', 'Bilbao',
   'cervezas@forever.com', '../Assets/img/images/events.jpg', 1),

  -- ===== Social =====
  ('Gala Benéfica Cruz Roja',
   'Cena de gala cuyos ingresos se destinan a programas de inserción social en barrios desfavorecidos.',
   'Social', '2026-11-08', '20:00:00',
   'Avinguda Diagonal, 661', '08028', 'Barcelona',
   'gala@forever.com', '../Assets/img/images/events.jpg', 1),

  ('Voluntariado Limpieza de Playa',
   'Jornada de voluntariado abierta para retirar plásticos y residuos del litoral.',
   'Social', '2026-06-21', '09:00:00',
   'Paseo Marítimo Pablo Ruiz Picasso, 10', '29016', 'Málaga',
   'voluntariado@forever.com', '../Assets/img/images/events.jpg', 1),

  ('Recogida de Alimentos Solidaria',
   'Punto de recogida de alimentos no perecederos para el banco de alimentos local.',
   'Social', '2026-12-05', '10:00:00',
   'Calle de Russafa, 33', '46006', 'Valencia',
   'alimentos@forever.com', '../Assets/img/images/events.jpg', 1);
