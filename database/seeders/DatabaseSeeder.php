<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Lead;
use App\Models\Client;
use App\Models\Opportunity;
use App\Models\Budget;
use App\Models\Activity;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // =====================================================================
        // USERS (6)
        // =====================================================================
        $users = [];
        $usersData = [
            ['name' => 'Admin Klinea',      'email' => 'admin@klinea.eu',            'role' => 'admin'],
            ['name' => 'Carlos García',      'email' => 'carlos.garcia@klinea.eu',    'role' => 'commercial'],
            ['name' => 'María López',        'email' => 'maria.lopez@klinea.eu',      'role' => 'commercial'],
            ['name' => 'Ana Martínez',       'email' => 'ana.martinez@klinea.eu',     'role' => 'manager'],
            ['name' => 'Pedro Sánchez',      'email' => 'pedro.sanchez@klinea.eu',    'role' => 'commercial'],
            ['name' => 'Laura Fernández',    'email' => 'laura.fernandez@klinea.eu',  'role' => 'commercial'],
            ['name' => 'Sergio Ruiz',        'email' => 'sergio.ruiz@klinea.eu',      'role' => 'commercial'],
            ['name' => 'Elena Torres',        'email' => 'elena.torres@klinea.eu',     'role' => 'commercial'],
            ['name' => 'Javier Moreno',       'email' => 'javier.moreno@klinea.eu',    'role' => 'commercial'],
            ['name' => 'Raquel Díaz',         'email' => 'raquel.diaz@klinea.eu',      'role' => 'manager'],
            ['name' => 'David Romero',        'email' => 'david.romero@klinea.eu',     'role' => 'commercial'],
            ['name' => 'Patricia Navarro',    'email' => 'patricia.navarro@klinea.eu', 'role' => 'commercial'],
            ['name' => 'Alberto Gil',         'email' => 'alberto.gil@klinea.eu',      'role' => 'commercial'],
            ['name' => 'Marta Jiménez',       'email' => 'marta.jimenez@klinea.eu',    'role' => 'commercial'],
            ['name' => 'Roberto Castillo',    'email' => 'roberto.castillo@klinea.eu', 'role' => 'manager'],
            ['name' => 'Nuria Ortega',        'email' => 'nuria.ortega@klinea.eu',     'role' => 'commercial'],
        ];

        foreach ($usersData as $userData) {
            $users[] = User::create([
                'name'              => $userData['name'],
                'email'             => $userData['email'],
                'email_verified_at' => now(),
                'password'          => Hash::make('password'),
                'role'              => $userData['role'],
            ]);
        }

        // Commercial user IDs (2-16)
        $commercialIds = [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16];

        // =====================================================================
        // LEADS (35)
        // =====================================================================
        $leadsData = [
            ['name' => 'Javier Ruiz',          'email' => 'javier.ruiz@construccionesmartin.es',    'phone' => '612345678',  'company' => 'Construcciones Martín S.L.',        'source' => 'web',       'status' => 'new',       'assigned_to' => 2, 'notes' => 'Interesado en software de gestión de obras. Tiene 50 empleados.'],
            ['name' => 'Elena Torres',          'email' => 'elena@techsoluciones.com',               'phone' => '623456789',  'company' => 'TechSoluciones S.A.',               'source' => 'linkedin',  'status' => 'contacted', 'assigned_to' => 3, 'notes' => 'Contactada por LinkedIn, busca CRM para su equipo de ventas.'],
            ['name' => 'Manuel Herrero',        'email' => 'mherrero@grupoalimentario.es',           'phone' => '634567890',  'company' => 'Grupo Alimentario Ibérico',         'source' => 'referral',  'status' => 'qualified', 'assigned_to' => 2, 'notes' => 'Referido por cliente actual. Necesita digitalizar procesos de pedidos.'],
            ['name' => 'Carmen Vidal',          'email' => 'cvidal@inmobiliariasur.com',              'phone' => '645678901',  'company' => 'Inmobiliaria Sur S.L.',             'source' => 'event',     'status' => 'proposal',  'assigned_to' => 5, 'notes' => 'Conocida en la feria SIMA 2026. Muy interesada en módulo inmobiliario.'],
            ['name' => 'Francisco Navarro',     'email' => 'fnavarro@energiaverde.es',                'phone' => '656789012',  'company' => 'Energía Verde Renovables S.A.',     'source' => 'cold_call', 'status' => 'won',       'assigned_to' => 6, 'notes' => 'Cerrado tras presentación. Contrato firmado para implementación CRM.'],
            ['name' => 'Isabel Moreno',         'email' => 'imoreno@clinicasalud.com',                'phone' => '667890123',  'company' => 'Clínica Salud Integral',            'source' => 'web',       'status' => 'new',       'assigned_to' => 3, 'notes' => 'Formulario web. Busca gestión de pacientes y citas.'],
            ['name' => 'Roberto Jiménez',       'email' => 'rjimenez@logisticaexpress.es',            'phone' => '678901234',  'company' => 'Logística Express S.L.',            'source' => 'linkedin',  'status' => 'contacted', 'assigned_to' => 5, 'notes' => 'Contacto vía LinkedIn. Empresa de logística con 200 empleados.'],
            ['name' => 'Patricia Domínguez',    'email' => 'pdominguez@educanet.es',                  'phone' => '689012345',  'company' => 'EduCanet Formación',                'source' => 'referral',  'status' => 'qualified', 'assigned_to' => 2, 'notes' => 'Referido por Ana Martínez. Centro de formación profesional.'],
            ['name' => 'Alejandro Gil',         'email' => 'agil@consultoriaplus.com',                'phone' => '690123456',  'company' => 'Consultoría Plus S.A.',             'source' => 'event',     'status' => 'lost',      'assigned_to' => 6, 'notes' => 'Perdido. Eligieron otra solución por precio.'],
            ['name' => 'Lucía Romero',          'email' => 'lucia@modaiberia.es',                     'phone' => '601234567',  'company' => 'Moda Iberia S.L.',                  'source' => 'web',       'status' => 'new',       'assigned_to' => 3, 'notes' => 'Tienda online de moda. Quiere integrar CRM con su e-commerce.'],
            ['name' => 'Miguel Ángel Serrano',  'email' => 'maserrano@automotorsur.com',              'phone' => '612345679',  'company' => 'Automotor Sur S.A.',                'source' => 'cold_call', 'status' => 'contacted', 'assigned_to' => 2, 'notes' => 'Llamada en frío. Concesionario con 3 sedes. Interesado en seguimiento de ventas.'],
            ['name' => 'Beatriz Molina',        'email' => 'bmolina@farmaceuticaibera.es',            'phone' => '623456790',  'company' => 'Farmacéutica Ibera S.A.',           'source' => 'linkedin',  'status' => 'proposal',  'assigned_to' => 5, 'notes' => 'Empresa farmacéutica. Propuesta enviada para CRM + módulo regulatorio.'],
            ['name' => 'David Ortega',          'email' => 'dortega@hotelplaza.com',                  'phone' => '634567891',  'company' => 'Hotel Plaza Madrid',                'source' => 'referral',  'status' => 'won',       'assigned_to' => 6, 'notes' => 'Hotel 4 estrellas. Implementación completada. Cliente satisfecho.'],
            ['name' => 'Sara Delgado',          'email' => 'sdelgado@arquitecturaviva.es',            'phone' => '645678902',  'company' => 'Arquitectura Viva Estudio',         'source' => 'web',       'status' => 'qualified', 'assigned_to' => 3, 'notes' => 'Estudio de arquitectura. Busca gestión de proyectos y clientes.'],
            ['name' => 'Andrés Castro',         'email' => 'acastro@segurosiberia.com',               'phone' => '656789013',  'company' => 'Seguros Iberia S.A.',               'source' => 'event',     'status' => 'new',       'assigned_to' => 2, 'notes' => 'Contacto en evento InsurTech Madrid 2026. Aseguradora mediana.'],
            ['name' => 'Marta Ramos',           'email' => 'mramos@consultoradigital.es',             'phone' => '667890124',  'company' => 'Consultora Digital 360',            'source' => 'cold_call', 'status' => 'contacted', 'assigned_to' => 5, 'notes' => 'Consultora tecnológica. Puede ser también partner de implementación.'],
            ['name' => 'Pablo Guerrero',        'email' => 'pguerrero@viveroseljardin.com',           'phone' => '678901235',  'company' => 'Viveros El Jardín S.L.',            'source' => 'web',       'status' => 'new',       'assigned_to' => 6, 'notes' => 'Empresa de jardinería y viveros. Quiere gestionar presupuestos y clientes.'],
            ['name' => 'Teresa Peña',           'email' => 'tpena@abogadosasociados.es',              'phone' => '689012346',  'company' => 'Peña & Asociados Abogados',         'source' => 'referral',  'status' => 'qualified', 'assigned_to' => 3, 'notes' => 'Bufete de abogados. 15 profesionales. Necesitan CRM especializado en legal.'],
            ['name' => 'Raúl Flores',           'email' => 'rflores@metalurgicacentro.es',            'phone' => '690123457',  'company' => 'Metalúrgica Centro S.A.',           'source' => 'linkedin',  'status' => 'proposal',  'assigned_to' => 2, 'notes' => 'Empresa metalúrgica. Propuesta de CRM + ERP integrado.'],
            ['name' => 'Cristina Herrera',      'email' => 'cherrera@opticavisual.com',               'phone' => '601234568',  'company' => 'Óptica Visual S.L.',                'source' => 'cold_call', 'status' => 'lost',      'assigned_to' => 5, 'notes' => 'Perdido. No tiene presupuesto este año. Recontactar en 2027.'],
            ['name' => 'Fernando Reyes',        'email' => 'freyes@transportesrapidos.es',            'phone' => '612345680',  'company' => 'Transportes Rápidos S.A.',          'source' => 'web',       'status' => 'new',       'assigned_to' => 6, 'notes' => 'Flota de 80 vehículos. Interesado en gestión de flotas y clientes.'],
            ['name' => 'Nuria Cabrera',         'email' => 'ncabrera@eventosunicos.com',              'phone' => '623456791',  'company' => 'Eventos Únicos S.L.',               'source' => 'event',     'status' => 'contacted', 'assigned_to' => 2, 'notes' => 'Organizadora de eventos. Contacto en feria FITUR. Necesita CRM para gestión de clientes.'],
            ['name' => 'Óscar Medina',          'email' => 'omedina@imprentadigital.es',              'phone' => '634567892',  'company' => 'Imprenta Digital Madrid S.L.',      'source' => 'referral',  'status' => 'qualified', 'assigned_to' => 3, 'notes' => 'Referido por David Ortega. Imprenta con servicio online. 25 empleados.'],
            ['name' => 'Silvia Nieto',          'email' => 'snieto@centrodentalplus.es',              'phone' => '645678903',  'company' => 'Centro Dental Plus',                'source' => 'web',       'status' => 'won',       'assigned_to' => 5, 'notes' => 'Clínica dental con 3 sedes. Contrato firmado.'],
            ['name' => 'Jorge Pascual',         'email' => 'jpascual@softwareinnova.com',             'phone' => '656789014',  'company' => 'Software Innova S.A.',              'source' => 'linkedin',  'status' => 'proposal',  'assigned_to' => 6, 'notes' => 'Empresa de desarrollo de software. Quiere CRM para sus comerciales.'],
            ['name' => 'Pilar Santos',          'email' => 'psantos@residenciassol.es',               'phone' => '667890125',  'company' => 'Residencias Sol y Vida S.L.',       'source' => 'cold_call', 'status' => 'contacted', 'assigned_to' => 2, 'notes' => 'Grupo de residencias de mayores. 5 centros en Madrid y Castilla.'],
            ['name' => 'Alberto Vargas',        'email' => 'avargas@electrodomesticosya.com',         'phone' => '678901236',  'company' => 'Electrodomésticos Ya S.L.',         'source' => 'web',       'status' => 'new',       'assigned_to' => 3, 'notes' => 'Tienda de electrodomésticos online. Busca CRM con módulo e-commerce.'],
            ['name' => 'Rosa María Iglesias',   'email' => 'riglesias@academiaidiomas.es',            'phone' => '689012347',  'company' => 'Academia Idiomas Global',           'source' => 'referral',  'status' => 'qualified', 'assigned_to' => 5, 'notes' => 'Academia de idiomas con 8 sedes. Necesita gestión de alumnos y leads.'],
            ['name' => 'Héctor Cano',           'email' => 'hcano@talleresbcn.com',                   'phone' => '690123458',  'company' => 'Talleres Mecánicos BCN S.L.',       'source' => 'event',     'status' => 'new',       'assigned_to' => 6, 'notes' => 'Cadena de talleres mecánicos en Barcelona. Contacto en feria Motortec.'],
            ['name' => 'Verónica Cruz',         'email' => 'vcruz@disenovanguardia.es',               'phone' => '601234569',  'company' => 'Diseño Vanguardia Studio',          'source' => 'linkedin',  'status' => 'contacted', 'assigned_to' => 2, 'notes' => 'Estudio de diseño gráfico. 12 diseñadores. Quiere gestionar proyectos.'],
            ['name' => 'Antonio Prieto',        'email' => 'aprieto@alimentosdelnorte.es',            'phone' => '612345681',  'company' => 'Alimentos del Norte S.A.',          'source' => 'cold_call', 'status' => 'lost',      'assigned_to' => 3, 'notes' => 'Empresa de alimentación. No interesados en cambiar de sistema actual.'],
            ['name' => 'Inmaculada Lozano',     'email' => 'ilozano@clinicaveterinaria.es',           'phone' => '623456792',  'company' => 'Clínica Veterinaria Animalvida',   'source' => 'web',       'status' => 'new',       'assigned_to' => 5, 'notes' => 'Clínica veterinaria con servicio de urgencias 24h.'],
            ['name' => 'Rafael Marín',          'email' => 'rmarin@constructoralevante.com',          'phone' => '634567893',  'company' => 'Constructora Levante S.A.',         'source' => 'referral',  'status' => 'proposal',  'assigned_to' => 6, 'notes' => 'Constructora con proyectos en Levante. Presupuesto en fase de revisión.'],
            ['name' => 'Eva Campos',            'email' => 'ecampos@agenciaviajes.es',                'phone' => '645678904',  'company' => 'Viajes Soñados S.L.',               'source' => 'event',     'status' => 'qualified', 'assigned_to' => 2, 'notes' => 'Agencia de viajes con 10 oficinas. Interesada en CRM turístico.'],
            ['name' => 'Guillermo Soto',        'email' => 'gsoto@tecnologiasavanzadas.com',          'phone' => '656789015',  'company' => 'Tecnologías Avanzadas S.L.',        'source' => 'linkedin',  'status' => 'won',       'assigned_to' => 3, 'notes' => 'Empresa IT. Contrato cerrado para CRM Enterprise + soporte premium.'],
        ];

        $leads = [];
        foreach ($leadsData as $i => $leadData) {
            $createdAt = Carbon::create(2026, 1, 1)->addDays(rand(0, 47)); // Jan 1 - Feb 17
            $leads[] = Lead::create(array_merge($leadData, [
                'created_at' => $createdAt,
                'updated_at' => $createdAt->copy()->addDays(rand(0, 5)),
            ]));
        }

        // =====================================================================
        // CLIENTS (22)
        // =====================================================================
        $clientsData = [
            ['name' => 'Francisco Navarro',      'email' => 'fnavarro@energiaverde.es',         'phone' => '656789012',  'company' => 'Energía Verde Renovables S.A.',   'nif' => 'A28345678',  'address' => 'Calle de la Energía 15',        'city' => 'Madrid',      'postal_code' => '28001', 'sector' => 'energía',        'website' => 'https://energiaverde.es',       'assigned_to' => 6, 'lead_id' => 5],
            ['name' => 'David Ortega',            'email' => 'dortega@hotelplaza.com',           'phone' => '634567891',  'company' => 'Hotel Plaza Madrid',              'nif' => 'B28456789',  'address' => 'Gran Vía 42',                   'city' => 'Madrid',      'postal_code' => '28013', 'sector' => 'servicios',      'website' => 'https://hotelplazamadrid.com',  'assigned_to' => 6, 'lead_id' => 13],
            ['name' => 'Silvia Nieto',            'email' => 'snieto@centrodentalplus.es',       'phone' => '645678903',  'company' => 'Centro Dental Plus',              'nif' => 'B46123456',  'address' => 'Avenida de la Constitución 8',  'city' => 'Valencia',    'postal_code' => '46001', 'sector' => 'salud',          'website' => 'https://centrodentalplus.es',   'assigned_to' => 5, 'lead_id' => 24],
            ['name' => 'Guillermo Soto',          'email' => 'gsoto@tecnologiasavanzadas.com',   'phone' => '656789015',  'company' => 'Tecnologías Avanzadas S.L.',      'nif' => 'B08567890',  'address' => 'Carrer de la Tecnologia 22',    'city' => 'Barcelona',   'postal_code' => '08001', 'sector' => 'tecnología',     'website' => 'https://tecavanzadas.com',      'assigned_to' => 3, 'lead_id' => 35],
            ['name' => 'Marcos Álvarez',          'email' => 'malvarez@grupoalvarez.es',         'phone' => '611222333',  'company' => 'Grupo Álvarez Construcciones',    'nif' => 'A41234567',  'address' => 'Avenida de Andalucía 100',      'city' => 'Sevilla',     'postal_code' => '41001', 'sector' => 'construcción',   'website' => 'https://grupoalvarez.es',       'assigned_to' => 2, 'lead_id' => null],
            ['name' => 'Laura Blanco',            'email' => 'lblanco@blancoyasociados.com',     'phone' => '622333444',  'company' => 'Blanco & Asociados Consulting',   'nif' => 'B48345678',  'address' => 'Gran Vía de Don Diego López de Haro 30', 'city' => 'Bilbao', 'postal_code' => '48001', 'sector' => 'servicios',  'website' => 'https://blancoyasociados.com',  'assigned_to' => 3, 'lead_id' => null],
            ['name' => 'Pedro Montero',           'email' => 'pmontero@alimentosmontero.es',     'phone' => '633444555',  'company' => 'Alimentos Montero S.A.',          'nif' => 'A50456789',  'address' => 'Polígono Industrial Las Fuentes 12', 'city' => 'Zaragoza', 'postal_code' => '50001', 'sector' => 'alimentación', 'website' => 'https://alimentosmontero.es', 'assigned_to' => 5, 'lead_id' => null],
            ['name' => 'Carolina Vega',           'email' => 'cvega@eduvega.es',                 'phone' => '644555666',  'company' => 'EduVega Centro de Formación',     'nif' => 'B29567890',  'address' => 'Calle Larios 18',               'city' => 'Málaga',      'postal_code' => '29001', 'sector' => 'educación',      'website' => 'https://eduvega.es',            'assigned_to' => 2, 'lead_id' => null],
            ['name' => 'Ignacio Fuentes',         'email' => 'ifuentes@inmofuentes.com',         'phone' => '655666777',  'company' => 'InmoFuentes Real Estate',         'nif' => 'B28678901',  'address' => 'Paseo de la Castellana 89',     'city' => 'Madrid',      'postal_code' => '28046', 'sector' => 'inmobiliaria',   'website' => 'https://inmofuentes.com',       'assigned_to' => 6, 'lead_id' => null],
            ['name' => 'Diana Rojas',             'email' => 'drojas@saluddigital.es',           'phone' => '666777888',  'company' => 'Salud Digital Innovación S.L.',   'nif' => 'B08789012',  'address' => 'Carrer del Bruc 45',            'city' => 'Barcelona',   'postal_code' => '08009', 'sector' => 'salud',          'website' => 'https://saluddigital.es',       'assigned_to' => 3, 'lead_id' => null],
            ['name' => 'Emilio Castaño',          'email' => 'ecastano@constructoranorte.es',    'phone' => '677888999',  'company' => 'Constructora Norte S.A.',         'nif' => 'A33890123',  'address' => 'Calle Uria 25',                 'city' => 'Oviedo',      'postal_code' => '33001', 'sector' => 'construcción',   'website' => 'https://constructoranorte.es',  'assigned_to' => 5, 'lead_id' => null],
            ['name' => 'Gloria Peña',             'email' => 'gpena@tecnosoluciones.com',        'phone' => '688999000',  'company' => 'TecnoSoluciones Globales S.L.',   'nif' => 'B46901234',  'address' => 'Avenida del Puerto 150',        'city' => 'Valencia',    'postal_code' => '46023', 'sector' => 'tecnología',     'website' => 'https://tecnosoluciones.com',   'assigned_to' => 2, 'lead_id' => null],
            ['name' => 'Rubén Giménez',           'email' => 'rgimenez@energiasolar360.es',      'phone' => '699000111',  'company' => 'Energía Solar 360 S.L.',          'nif' => 'B41012345',  'address' => 'Calle Betis 88',                'city' => 'Sevilla',     'postal_code' => '41010', 'sector' => 'energía',        'website' => 'https://energiasolar360.es',    'assigned_to' => 6, 'lead_id' => null],
            ['name' => 'Natalia Espinosa',        'email' => 'nespinosa@modanatural.com',        'phone' => '600111222',  'company' => 'Moda Natural España S.L.',        'nif' => 'B28123987',  'address' => 'Calle Serrano 55',              'city' => 'Madrid',      'postal_code' => '28006', 'sector' => 'servicios',      'website' => 'https://modanatural.com',       'assigned_to' => 3, 'lead_id' => null],
            ['name' => 'Adrián Peláez',           'email' => 'apelaez@logisticapelaez.es',       'phone' => '611333444',  'company' => 'Logística Peláez S.A.',           'nif' => 'A15234567',  'address' => 'Polígono de Pocomaco 7',        'city' => 'A Coruña',    'postal_code' => '15008', 'sector' => 'servicios',      'website' => 'https://logisticapelaez.es',    'assigned_to' => 2, 'lead_id' => null],
            ['name' => 'Sandra Ríos',             'email' => 'srios@clinicarios.es',             'phone' => '622444555',  'company' => 'Clínica Ríos S.L.',               'nif' => 'B30345678',  'address' => 'Avenida de la Libertad 30',     'city' => 'Murcia',      'postal_code' => '30001', 'sector' => 'salud',          'website' => 'https://clinicarios.es',        'assigned_to' => 5, 'lead_id' => null],
            ['name' => 'Víctor Aguilar',          'email' => 'vaguilar@agroaguilar.com',         'phone' => '633555666',  'company' => 'AgroAguilar S.A.',                'nif' => 'A14456789',  'address' => 'Carretera de Córdoba km 5',     'city' => 'Córdoba',     'postal_code' => '14001', 'sector' => 'alimentación',   'website' => 'https://agroaguilar.com',       'assigned_to' => 6, 'lead_id' => null],
            ['name' => 'Olga Méndez',             'email' => 'omendez@formaciononline.es',       'phone' => '644666777',  'company' => 'Formación Online Plus S.L.',      'nif' => 'B28567321',  'address' => 'Calle Alcalá 200',              'city' => 'Madrid',      'postal_code' => '28028', 'sector' => 'educación',      'website' => 'https://formaciononlineplus.es','assigned_to' => 3, 'lead_id' => null],
            ['name' => 'Daniel Esteban',          'email' => 'desteban@inmoesteban.com',         'phone' => '655777888',  'company' => 'InmoEsteban Grupo Inmobiliario',  'nif' => 'B08678432',  'address' => 'Passeig de Gràcia 100',         'city' => 'Barcelona',   'postal_code' => '08008', 'sector' => 'inmobiliaria',   'website' => 'https://inmoesteban.com',       'assigned_to' => 2, 'lead_id' => null],
            ['name' => 'Alicia Montes',           'email' => 'amontes@techmontes.es',            'phone' => '666888999',  'company' => 'TechMontes Desarrollo S.L.',      'nif' => 'B48789543',  'address' => 'Alameda de Recalde 50',         'city' => 'Bilbao',      'postal_code' => '48008', 'sector' => 'tecnología',     'website' => 'https://techmontes.es',         'assigned_to' => 5, 'lead_id' => null],
            ['name' => 'Ramón Crespo',            'email' => 'rcrespo@cresposervicios.com',      'phone' => '677999000',  'company' => 'Crespo Servicios Integrales',     'nif' => 'B50890654',  'address' => 'Paseo Independencia 15',        'city' => 'Zaragoza',    'postal_code' => '50004', 'sector' => 'servicios',      'website' => 'https://cresposervicios.com',   'assigned_to' => 6, 'lead_id' => null],
            ['name' => 'Yolanda Benítez',         'email' => 'ybenitez@constructorabenitez.es',  'phone' => '688000111',  'company' => 'Constructora Benítez S.L.',       'nif' => 'B35901765',  'address' => 'Calle Triana 40',               'city' => 'Las Palmas',  'postal_code' => '35002', 'sector' => 'construcción',   'website' => 'https://constructorabenitez.es','assigned_to' => 2, 'lead_id' => null],
        ];

        $clients = [];
        foreach ($clientsData as $clientData) {
            $createdAt = Carbon::create(2026, 1, 1)->addDays(rand(0, 40));
            $clients[] = Client::create(array_merge($clientData, [
                'country'    => 'España',
                'created_at' => $createdAt,
                'updated_at' => $createdAt->copy()->addDays(rand(0, 7)),
            ]));
        }

        // =====================================================================
        // OPPORTUNITIES (18)
        // =====================================================================
        $opportunitiesData = [
            ['title' => 'Implementación CRM Enterprise',           'client_id' => 1,  'assigned_to' => 6, 'value' => 45000.00,  'probability' => 90, 'stage' => 'negotiation',   'expected_close_date' => '2026-03-15', 'description' => 'Implementación completa de CRM Enterprise para Energía Verde. Incluye migración de datos y formación.'],
            ['title' => 'Renovación Licencias Software',           'client_id' => 2,  'assigned_to' => 6, 'value' => 18500.00,  'probability' => 75, 'stage' => 'proposal',      'expected_close_date' => '2026-03-01', 'description' => 'Renovación anual de licencias de software de gestión hotelera.'],
            ['title' => 'Consultoría Digital Transformación',      'client_id' => 4,  'assigned_to' => 3, 'value' => 85000.00,  'probability' => 60, 'stage' => 'qualification', 'expected_close_date' => '2026-04-30', 'description' => 'Proyecto de transformación digital completa. Análisis, estrategia e implementación.'],
            ['title' => 'Sistema Gestión Obras',                   'client_id' => 5,  'assigned_to' => 2, 'value' => 120000.00, 'probability' => 40, 'stage' => 'prospecting',   'expected_close_date' => '2026-06-01', 'description' => 'ERP + CRM integrado para gestión de obras de construcción.'],
            ['title' => 'CRM Dental Multi-sede',                   'client_id' => 3,  'assigned_to' => 5, 'value' => 32000.00,  'probability' => 95, 'stage' => 'closed_won',    'expected_close_date' => '2026-02-15', 'description' => 'CRM especializado para clínica dental con 3 sedes. Ya cerrado.'],
            ['title' => 'Plataforma E-learning',                   'client_id' => 8,  'assigned_to' => 2, 'value' => 55000.00,  'probability' => 50, 'stage' => 'proposal',      'expected_close_date' => '2026-04-15', 'description' => 'Plataforma de formación online con gestión de alumnos y CRM integrado.'],
            ['title' => 'Módulo Inmobiliario CRM',                 'client_id' => 9,  'assigned_to' => 6, 'value' => 28000.00,  'probability' => 70, 'stage' => 'negotiation',   'expected_close_date' => '2026-03-20', 'description' => 'Módulo CRM especializado para gestión inmobiliaria. Portales y matching.'],
            ['title' => 'Digitalización Clínica',                  'client_id' => 10, 'assigned_to' => 3, 'value' => 67000.00,  'probability' => 55, 'stage' => 'qualification', 'expected_close_date' => '2026-05-01', 'description' => 'Digitalización completa de procesos clínicos. Historia digital + CRM pacientes.'],
            ['title' => 'ERP Construcción Norte',                  'client_id' => 11, 'assigned_to' => 5, 'value' => 95000.00,  'probability' => 30, 'stage' => 'prospecting',   'expected_close_date' => '2026-06-15', 'description' => 'Sistema ERP especializado en construcción con módulo CRM.'],
            ['title' => 'Automatización Marketing',                'client_id' => 12, 'assigned_to' => 2, 'value' => 22000.00,  'probability' => 80, 'stage' => 'proposal',      'expected_close_date' => '2026-03-10', 'description' => 'Módulo de automatización de marketing y email campaigns.'],
            ['title' => 'Consultoría Procesos Logística',          'client_id' => 15, 'assigned_to' => 2, 'value' => 38000.00,  'probability' => 45, 'stage' => 'qualification', 'expected_close_date' => '2026-04-20', 'description' => 'Optimización de procesos logísticos y CRM para gestión de flota.'],
            ['title' => 'Gestión Agrícola Integral',               'client_id' => 17, 'assigned_to' => 6, 'value' => 42000.00,  'probability' => 35, 'stage' => 'prospecting',   'expected_close_date' => '2026-05-30', 'description' => 'Sistema de gestión agrícola con CRM para clientes y proveedores.'],
            ['title' => 'Portal Inmobiliario Premium',             'client_id' => 19, 'assigned_to' => 2, 'value' => 150000.00, 'probability' => 25, 'stage' => 'prospecting',   'expected_close_date' => '2026-06-30', 'description' => 'Desarrollo de portal inmobiliario premium con CRM integrado.'],
            ['title' => 'CRM Formación Online',                   'client_id' => 18, 'assigned_to' => 3, 'value' => 19500.00,  'probability' => 85, 'stage' => 'negotiation',   'expected_close_date' => '2026-02-28', 'description' => 'CRM para gestión de leads y alumnos de formación online.'],
            ['title' => 'Sistema Gestión Clínica Veterinaria',    'client_id' => 16, 'assigned_to' => 5, 'value' => 15000.00,  'probability' => 10, 'stage' => 'closed_lost',   'expected_close_date' => '2026-02-10', 'description' => 'Propuesta para gestión de clínica veterinaria. Perdida por presupuesto.'],
            ['title' => 'Renovación Infraestructura TI',           'client_id' => 20, 'assigned_to' => 5, 'value' => 75000.00,  'probability' => 65, 'stage' => 'proposal',      'expected_close_date' => '2026-04-01', 'description' => 'Renovación de infraestructura TI y CRM para empresa tecnológica.'],
            ['title' => 'Migración Cloud Servicios',               'client_id' => 6,  'assigned_to' => 3, 'value' => 52000.00,  'probability' => 70, 'stage' => 'negotiation',   'expected_close_date' => '2026-03-25', 'description' => 'Migración de servicios a cloud con CRM SaaS incluido.'],
            ['title' => 'Soporte Premium Anual',                   'client_id' => 14, 'assigned_to' => 3, 'value' => 8500.00,   'probability' => 90, 'stage' => 'closed_won',    'expected_close_date' => '2026-02-05', 'description' => 'Contrato de soporte premium anual. Cliente recurrente.'],
        ];

        $opportunities = [];
        foreach ($opportunitiesData as $oppData) {
            $createdAt = Carbon::create(2026, 1, 1)->addDays(rand(0, 40));
            $opportunities[] = Opportunity::create(array_merge($oppData, [
                'created_at' => $createdAt,
                'updated_at' => $createdAt->copy()->addDays(rand(0, 10)),
            ]));
        }

        // =====================================================================
        // BUDGETS (12)
        // =====================================================================
        $budgetsData = [
            [
                'reference'      => 'KLN-2026-0001',
                'client_id'      => 1,
                'opportunity_id' => 1,
                'assigned_to'    => 6,
                'items'          => [
                    ['description' => 'Licencia CRM Enterprise (anual)', 'quantity' => 1, 'unit_price' => 15000, 'total' => 15000],
                    ['description' => 'Implementación y configuración', 'quantity' => 80, 'unit_price' => 85, 'total' => 6800],
                    ['description' => 'Migración de datos', 'quantity' => 40, 'unit_price' => 75, 'total' => 3000],
                    ['description' => 'Formación usuarios (jornadas)', 'quantity' => 5, 'unit_price' => 600, 'total' => 3000],
                ],
                'status'     => 'sent',
                'valid_until'=> '2026-04-15',
                'notes'      => 'Presupuesto para implementación completa de CRM Enterprise.',
            ],
            [
                'reference'      => 'KLN-2026-0002',
                'client_id'      => 2,
                'opportunity_id' => 2,
                'assigned_to'    => 6,
                'items'          => [
                    ['description' => 'Renovación licencias software gestión hotelera', 'quantity' => 1, 'unit_price' => 12000, 'total' => 12000],
                    ['description' => 'Soporte técnico anual', 'quantity' => 1, 'unit_price' => 4500, 'total' => 4500],
                    ['description' => 'Actualizaciones y parches', 'quantity' => 1, 'unit_price' => 2000, 'total' => 2000],
                ],
                'status'     => 'accepted',
                'valid_until'=> '2026-03-31',
                'notes'      => 'Renovación anual. Cliente desde 2024.',
            ],
            [
                'reference'      => 'KLN-2026-0003',
                'client_id'      => 3,
                'opportunity_id' => 5,
                'assigned_to'    => 5,
                'items'          => [
                    ['description' => 'CRM Dental Multi-sede (3 sedes)', 'quantity' => 1, 'unit_price' => 18000, 'total' => 18000],
                    ['description' => 'Módulo gestión citas', 'quantity' => 1, 'unit_price' => 5000, 'total' => 5000],
                    ['description' => 'Formación personal clínico', 'quantity' => 3, 'unit_price' => 800, 'total' => 2400],
                    ['description' => 'Integración con software dental', 'quantity' => 20, 'unit_price' => 90, 'total' => 1800],
                ],
                'status'     => 'accepted',
                'valid_until'=> '2026-03-15',
                'notes'      => 'Presupuesto aceptado. Inicio de implementación en marzo.',
            ],
            [
                'reference'      => 'KLN-2026-0004',
                'client_id'      => 4,
                'opportunity_id' => 3,
                'assigned_to'    => 3,
                'items'          => [
                    ['description' => 'Consultoría transformación digital (fase 1)', 'quantity' => 1, 'unit_price' => 25000, 'total' => 25000],
                    ['description' => 'Desarrollo plataforma a medida', 'quantity' => 200, 'unit_price' => 95, 'total' => 19000],
                    ['description' => 'Licencias CRM Professional', 'quantity' => 25, 'unit_price' => 480, 'total' => 12000],
                    ['description' => 'Formación y change management', 'quantity' => 10, 'unit_price' => 650, 'total' => 6500],
                ],
                'status'     => 'sent',
                'valid_until'=> '2026-05-30',
                'notes'      => 'Proyecto de transformación digital. Fase 1 de 3.',
            ],
            [
                'reference'      => 'KLN-2026-0005',
                'client_id'      => 5,
                'opportunity_id' => 4,
                'assigned_to'    => 2,
                'items'          => [
                    ['description' => 'ERP Construcción (módulo base)', 'quantity' => 1, 'unit_price' => 45000, 'total' => 45000],
                    ['description' => 'Módulo CRM integrado', 'quantity' => 1, 'unit_price' => 15000, 'total' => 15000],
                    ['description' => 'Módulo gestión de obras', 'quantity' => 1, 'unit_price' => 20000, 'total' => 20000],
                    ['description' => 'Implementación y personalización', 'quantity' => 150, 'unit_price' => 90, 'total' => 13500],
                    ['description' => 'Formación jefes de obra', 'quantity' => 8, 'unit_price' => 500, 'total' => 4000],
                ],
                'status'     => 'draft',
                'valid_until'=> '2026-06-30',
                'notes'      => 'Presupuesto en borrador. Pendiente de revisión interna.',
            ],
            [
                'reference'      => 'KLN-2026-0006',
                'client_id'      => 9,
                'opportunity_id' => 7,
                'assigned_to'    => 6,
                'items'          => [
                    ['description' => 'Módulo CRM Inmobiliario', 'quantity' => 1, 'unit_price' => 12000, 'total' => 12000],
                    ['description' => 'Integración portales inmobiliarios', 'quantity' => 1, 'unit_price' => 8000, 'total' => 8000],
                    ['description' => 'Matching automático propiedades', 'quantity' => 1, 'unit_price' => 5000, 'total' => 5000],
                    ['description' => 'Puesta en marcha', 'quantity' => 15, 'unit_price' => 85, 'total' => 1275],
                ],
                'status'     => 'sent',
                'valid_until'=> '2026-04-20',
                'notes'      => 'Módulo inmobiliario con integraciones de portales.',
            ],
            [
                'reference'      => 'KLN-2026-0007',
                'client_id'      => 12,
                'opportunity_id' => 10,
                'assigned_to'    => 2,
                'items'          => [
                    ['description' => 'Módulo automatización marketing', 'quantity' => 1, 'unit_price' => 9500, 'total' => 9500],
                    ['description' => 'Configuración email campaigns', 'quantity' => 1, 'unit_price' => 3500, 'total' => 3500],
                    ['description' => 'Integración Google Analytics + redes sociales', 'quantity' => 1, 'unit_price' => 4000, 'total' => 4000],
                    ['description' => 'Formación equipo marketing', 'quantity' => 2, 'unit_price' => 600, 'total' => 1200],
                ],
                'status'     => 'sent',
                'valid_until'=> '2026-04-10',
                'notes'      => 'Automatización marketing para TecnoSoluciones.',
            ],
            [
                'reference'      => 'KLN-2026-0008',
                'client_id'      => 14,
                'opportunity_id' => 18,
                'assigned_to'    => 3,
                'items'          => [
                    ['description' => 'Contrato soporte premium anual', 'quantity' => 1, 'unit_price' => 6000, 'total' => 6000],
                    ['description' => 'Horas de consultoría incluidas', 'quantity' => 20, 'unit_price' => 75, 'total' => 1500],
                    ['description' => 'Actualizaciones prioritarias', 'quantity' => 1, 'unit_price' => 1000, 'total' => 1000],
                ],
                'status'     => 'accepted',
                'valid_until'=> '2027-02-05',
                'notes'      => 'Soporte premium. Cliente satisfecho con servicio anterior.',
            ],
            [
                'reference'      => 'KLN-2026-0009',
                'client_id'      => 16,
                'opportunity_id' => 15,
                'assigned_to'    => 5,
                'items'          => [
                    ['description' => 'Sistema gestión clínica veterinaria', 'quantity' => 1, 'unit_price' => 8000, 'total' => 8000],
                    ['description' => 'Módulo historiales clínicos', 'quantity' => 1, 'unit_price' => 3500, 'total' => 3500],
                    ['description' => 'Implementación', 'quantity' => 20, 'unit_price' => 80, 'total' => 1600],
                ],
                'status'     => 'rejected',
                'valid_until'=> '2026-03-10',
                'notes'      => 'Rechazado por presupuesto insuficiente del cliente.',
            ],
            [
                'reference'      => 'KLN-2026-0010',
                'client_id'      => 6,
                'opportunity_id' => 17,
                'assigned_to'    => 3,
                'items'          => [
                    ['description' => 'Migración a infraestructura cloud', 'quantity' => 1, 'unit_price' => 20000, 'total' => 20000],
                    ['description' => 'CRM SaaS (licencia anual)', 'quantity' => 15, 'unit_price' => 600, 'total' => 9000],
                    ['description' => 'Migración de datos y configuración', 'quantity' => 60, 'unit_price' => 90, 'total' => 5400],
                    ['description' => 'Formación equipo', 'quantity' => 3, 'unit_price' => 700, 'total' => 2100],
                ],
                'status'     => 'sent',
                'valid_until'=> '2026-04-25',
                'notes'      => 'Migración cloud completa con CRM incluido.',
            ],
            [
                'reference'      => 'KLN-2026-0011',
                'client_id'      => 18,
                'opportunity_id' => 14,
                'assigned_to'    => 3,
                'items'          => [
                    ['description' => 'CRM Formación Online', 'quantity' => 1, 'unit_price' => 10000, 'total' => 10000],
                    ['description' => 'Módulo captación leads', 'quantity' => 1, 'unit_price' => 4500, 'total' => 4500],
                    ['description' => 'Automatización seguimiento', 'quantity' => 1, 'unit_price' => 3000, 'total' => 3000],
                    ['description' => 'Integración plataforma e-learning', 'quantity' => 1, 'unit_price' => 2000, 'total' => 2000],
                ],
                'status'     => 'sent',
                'valid_until'=> '2026-03-28',
                'notes'      => 'CRM para gestión de alumnos y leads de formación online.',
            ],
            [
                'reference'      => 'KLN-2026-0012',
                'client_id'      => 20,
                'opportunity_id' => 16,
                'assigned_to'    => 5,
                'items'          => [
                    ['description' => 'Renovación infraestructura servidores', 'quantity' => 1, 'unit_price' => 35000, 'total' => 35000],
                    ['description' => 'Licencias CRM Enterprise', 'quantity' => 20, 'unit_price' => 750, 'total' => 15000],
                    ['description' => 'Migración y configuración', 'quantity' => 80, 'unit_price' => 90, 'total' => 7200],
                    ['description' => 'Soporte post-implementación (3 meses)', 'quantity' => 1, 'unit_price' => 4500, 'total' => 4500],
                ],
                'status'     => 'draft',
                'valid_until'=> '2026-05-01',
                'notes'      => 'Borrador pendiente de aprobación del director técnico.',
            ],
        ];

        $budgets = [];
        foreach ($budgetsData as $budgetData) {
            $items    = $budgetData['items'];
            $subtotal = array_sum(array_column($items, 'total'));
            $taxRate  = 21.00;
            $taxAmount = round($subtotal * ($taxRate / 100), 2);
            $total     = round($subtotal + $taxAmount, 2);

            $createdAt = Carbon::create(2026, 1, 5)->addDays(rand(0, 38));

            $budgets[] = Budget::create([
                'reference'      => $budgetData['reference'],
                'client_id'      => $budgetData['client_id'],
                'opportunity_id' => $budgetData['opportunity_id'],
                'assigned_to'    => $budgetData['assigned_to'],
                'items'          => $items,
                'subtotal'       => $subtotal,
                'tax_rate'       => $taxRate,
                'tax_amount'     => $taxAmount,
                'total'          => $total,
                'status'         => $budgetData['status'],
                'valid_until'    => $budgetData['valid_until'],
                'notes'          => $budgetData['notes'],
                'created_at'     => $createdAt,
                'updated_at'     => $createdAt->copy()->addDays(rand(0, 5)),
            ]);
        }

        // =====================================================================
        // ACTIVITIES (120+)
        // =====================================================================
        $activitySubjects = [
            'call' => [
                'Llamada de seguimiento comercial',
                'Llamada de presentación de producto',
                'Llamada para confirmar reunión',
                'Llamada de cualificación de lead',
                'Llamada para resolver dudas',
                'Llamada de cierre comercial',
                'Llamada de bienvenida al cliente',
                'Llamada de feedback post-implementación',
            ],
            'email' => [
                'Envío de propuesta comercial',
                'Email de seguimiento post-reunión',
                'Envío de documentación técnica',
                'Email de presentación de servicios',
                'Confirmación de presupuesto',
                'Envío de caso de éxito',
                'Email de agradecimiento',
                'Recordatorio de vencimiento de presupuesto',
            ],
            'meeting' => [
                'Reunión de presentación inicial',
                'Demo del producto CRM',
                'Reunión de negociación',
                'Reunión de seguimiento de proyecto',
                'Presentación de propuesta técnica',
                'Kickoff de implementación',
                'Reunión de revisión trimestral',
                'Comité de dirección del proyecto',
            ],
            'note' => [
                'Nota sobre requerimientos del cliente',
                'Resumen de conversación telefónica',
                'Observaciones sobre competencia',
                'Nota sobre situación financiera del cliente',
                'Registro de necesidades detectadas',
                'Observaciones post-visita comercial',
                'Nota sobre cambio de interlocutor',
                'Registro de información relevante',
            ],
            'task' => [
                'Preparar propuesta comercial personalizada',
                'Enviar documentación técnica',
                'Agendar demo del producto',
                'Actualizar información del contacto',
                'Revisar presupuesto pendiente',
                'Preparar presentación para reunión',
                'Coordinar con equipo técnico',
                'Hacer seguimiento de factura',
            ],
            'status_change' => [
                'Cambio de estado del lead',
                'Actualización de fase de oportunidad',
                'Cambio de estado del presupuesto',
                'Lead cualificado para propuesta',
                'Oportunidad avanzada a negociación',
                'Presupuesto enviado al cliente',
                'Lead marcado como perdido',
                'Oportunidad cerrada ganada',
            ],
        ];

        $activityDescriptions = [
            'call' => [
                'Se contactó telefónicamente con el cliente para dar seguimiento a la propuesta enviada. Mostró interés pero necesita consultarlo con dirección.',
                'Llamada de 20 minutos presentando las funcionalidades del CRM. El cliente solicitó una demo presencial.',
                'Confirmada reunión para el próximo martes a las 10:00 en las oficinas del cliente.',
                'Se cualificó el lead. Tiene presupuesto aprobado y necesidad real. Pasar a fase de propuesta.',
                'El cliente tenía dudas sobre la integración con su sistema actual de facturación. Se le explicaron las opciones.',
                'Llamada final de negociación. Acuerdo en precio y condiciones. Pendiente firma de contrato.',
                'Llamada de bienvenida al nuevo cliente. Se presentó al equipo de soporte asignado.',
                'El cliente confirma que la implementación está funcionando correctamente. Muy satisfecho con el resultado.',
            ],
            'email' => [
                'Se envió la propuesta comercial detallada con tres opciones de plan: básico, profesional y enterprise.',
                'Resumen de la reunión de ayer con los puntos acordados y próximos pasos a seguir.',
                'Documentación técnica sobre integraciones API y requisitos de infraestructura.',
                'Email introductorio con presentación de la empresa y catálogo de servicios CRM.',
                'Se confirmó la recepción del presupuesto. El cliente lo revisará esta semana.',
                'Se compartió caso de éxito de empresa del mismo sector con resultados similares.',
                'Email de agradecimiento por la confianza depositada en nuestros servicios.',
                'Se recordó que el presupuesto vence en 15 días. Solicitar decisión.',
            ],
            'meeting' => [
                'Primera reunión con el equipo directivo. Se presentaron las soluciones CRM disponibles.',
                'Demostración en vivo del CRM con datos de ejemplo del sector del cliente. Muy buena recepción.',
                'Sesión de negociación sobre precios y condiciones del contrato. Se acordó un descuento del 10%.',
                'Revisión del avance del proyecto. Se van cumpliendo los hitos según el cronograma.',
                'Presentación técnica detallada con arquitectura de la solución propuesta.',
                'Reunión de inicio de proyecto con todos los stakeholders. Se definió el equipo y los plazos.',
                'Revisión trimestral de resultados. KPIs de uso del CRM muy positivos.',
                'Reunión con dirección para presentar informe de progreso y plan de siguientes fases.',
            ],
            'note' => [
                'El cliente requiere integración con SAP y sistema de nóminas propio.',
                'Resumen: el contacto principal está interesado pero el director financiero pone objeciones al precio.',
                'La competencia (SalesForce) está ofreciendo precios agresivos. Debemos ajustar la propuesta.',
                'La empresa factura 5M anuales y tiene planes de expansión. Buen potencial de crecimiento.',
                'Necesita: CRM, automatización de marketing, módulo de reporting avanzado.',
                'Visita comercial muy productiva. Las instalaciones son modernas y el equipo muy receptivo.',
                'El anterior contacto (Juan Pérez) ha dejado la empresa. Nuevo interlocutor: María García.',
                'La empresa está en proceso de certificación ISO 9001. El CRM les ayudaría con la trazabilidad.',
            ],
            'task' => [
                'Preparar propuesta personalizada incluyendo módulo de gestión documental solicitado.',
                'Recopilar y enviar fichas técnicas de los módulos solicitados por el cliente.',
                'Coordinar con el equipo de producto para agendar demo la semana que viene.',
                'Actualizar datos del contacto: nuevo teléfono móvil y dirección de email.',
                'Revisar presupuesto KLN-2026-003 y aplicar descuento acordado.',
                'Preparar presentación PowerPoint con caso de uso específico para el sector del cliente.',
                'Reunión interna con desarrollo para validar viabilidad técnica de la integración.',
                'Verificar estado de la factura pendiente y gestionar el cobro si es necesario.',
            ],
            'status_change' => [
                'Lead cambiado de "nuevo" a "contactado" tras primera llamada telefónica.',
                'Oportunidad movida de "cualificación" a "propuesta". Se enviará presupuesto esta semana.',
                'Presupuesto cambiado a "enviado". Se remitió por email al director general.',
                'Lead cualificado exitosamente. Tiene presupuesto, necesidad y timing adecuado.',
                'La oportunidad avanza a fase de negociación. El cliente ha aceptado la propuesta base.',
                'Presupuesto enviado al cliente por email y correo certificado.',
                'Lead marcado como perdido. El cliente eligió la solución de un competidor.',
                'Oportunidad cerrada como ganada. Contrato firmado por ambas partes.',
            ],
        ];

        $types = ['call', 'email', 'meeting', 'note', 'task', 'status_change'];

        // Collect all loggable entities
        $loggables = [];

        // Add leads as loggable
        foreach ($leads as $lead) {
            $loggables[] = ['type' => 'App\\Models\\Lead', 'id' => $lead->id];
        }
        // Add clients as loggable
        foreach ($clients as $client) {
            $loggables[] = ['type' => 'App\\Models\\Client', 'id' => $client->id];
        }
        // Add opportunities as loggable
        foreach ($opportunities as $opp) {
            $loggables[] = ['type' => 'App\\Models\\Opportunity', 'id' => $opp->id];
        }
        // Add budgets as loggable
        foreach ($budgets as $budget) {
            $loggables[] = ['type' => 'App\\Models\\Budget', 'id' => $budget->id];
        }

        // Create 120 activities
        for ($i = 0; $i < 120; $i++) {
            $type      = $types[array_rand($types)];
            $loggable  = $loggables[array_rand($loggables)];
            $userId    = $commercialIds[array_rand($commercialIds)];
            $subjects  = $activitySubjects[$type];
            $descs     = $activityDescriptions[$type];
            $createdAt = Carbon::create(2026, 1, 1)->addDays(rand(0, 47))->addHours(rand(8, 18))->addMinutes(rand(0, 59));

            Activity::create([
                'user_id'       => $userId,
                'type'          => $type,
                'subject'       => $subjects[array_rand($subjects)],
                'description'   => $descs[array_rand($descs)],
                'loggable_type' => $loggable['type'],
                'loggable_id'   => $loggable['id'],
                'created_at'    => $createdAt,
                'updated_at'    => $createdAt,
            ]);
        }

        // Seed login logs
        $this->call(LoginLogSeeder::class);
    }
}
